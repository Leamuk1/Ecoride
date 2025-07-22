<?php
/**
 * US6 - ReservationController
 * Gestion complète du système de réservation
 */

require_once '../app/config/config.php';

class ReservationController {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            error_log("Erreur connexion ReservationController: " . $e->getMessage());
            $this->pdo = null;
        }
    }
    
    /**
     * TÂCHE 1 & 2 : Vérification disponibilité places + crédit utilisateur
     * Méthode principale pour créer une réservation
     */
    public function createReservation() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonResponse(false, 'Vous devez être connecté pour réserver', [
                'redirect' => '/ecoride/public/auth/login'
            ]);
            return;
        }
        
        // Vérifier la méthode POST et le token CSRF
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendJsonResponse(false, 'Méthode non autorisée');
            return;
        }
        
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->sendJsonResponse(false, 'Token de sécurité invalide');
            return;
        }
        
        // Vérifier la connexion PDO
        if (!$this->pdo) {
            $this->sendJsonResponse(false, 'Erreur de connexion à la base de données');
            return;
        }
        
        try {
            // Récupérer et valider les données
            $id_covoiturage = (int)($_POST['id_covoiturage'] ?? 0);
            $id_passager = (int)$_SESSION['user_id'];
            $nb_places = (int)($_POST['nb_places'] ?? 1);
            
            if ($id_covoiturage <= 0 || $nb_places <= 0 || $nb_places > 4) {
                $this->sendJsonResponse(false, 'Données invalides');
                return;
            }
            
            // TRANSACTION SÉCURISÉE (Tâche 4 & 5)
            $this->pdo->beginTransaction();
            
            try {
                // 1. Vérifier disponibilité places (Tâche 1)
                $verification = $this->verifierDisponibilite($id_covoiturage, $id_passager, $nb_places);
                if (!$verification['disponible']) {
                    throw new Exception($verification['message']);
                }
                
                // 2. Récupérer infos covoiturage et calculer prix
                $covoiturage = $this->getCovoiturageInfo($id_covoiturage);
                if (!$covoiturage) {
                    throw new Exception('Covoiturage introuvable');
                }
                
                $prix_total = $covoiturage['prix_par_personne'] * $nb_places;
                $credits_requis = (int)ceil($prix_total); // Convertir en crédits
                
                // 3. Vérifier crédit utilisateur (Tâche 2)
                $verification_credit = $this->verifierCreditUtilisateur($id_passager, $credits_requis);
                if (!$verification_credit['suffisant']) {
                    throw new Exception($verification_credit['message']);
                }
                
                // 4. Créer la réservation (Tâche 5 - Mise à jour BDD atomique)
                $id_reservation = $this->insererReservation([
                    'id_covoiturage' => $id_covoiturage,
                    'id_passager' => $id_passager,
                    'nb_places_reservees' => $nb_places,
                    'prix_total' => $prix_total,
                    'credits_utilises' => $credits_requis
                ]);
                
                // 5. Débiter les crédits de l'utilisateur
                $this->debiterCredits($id_passager, $credits_requis);
                
                // 6. Mettre à jour les places disponibles manuellement (pas de trigger)
                $this->mettreAJourPlacesDisponibles($id_covoiturage, $nb_places);
                
                // 7. Confirmer la réservation
                $this->confirmerReservation($id_reservation);
                
                // COMMIT de la transaction
                $this->pdo->commit();
                
                // 8. Envoyer notifications email (Tâche 6)
                $this->envoyerNotificationReservation($id_reservation);
                
                // Réponse de succès
                $this->sendJsonResponse(true, 'Réservation confirmée avec succès !', [
                    'id_reservation' => $id_reservation,
                    'credits_debites' => $credits_requis,
                    'nouveau_solde' => $verification_credit['solde_actuel'] - $credits_requis,
                    'places_reservees' => $nb_places
                ]);
                
            } catch (Exception $e) {
                // ROLLBACK en cas d'erreur (Tâche 7 - Gestion erreurs)
                $this->pdo->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            error_log("Erreur createReservation: " . $e->getMessage());
            $this->sendJsonResponse(false, $e->getMessage());
        }
    }
    
    /**
     * TÂCHE 1 : Vérification disponibilité places
     */
    private function verifierDisponibilite($id_covoiturage, $id_passager, $nb_places) {
        try {
            if (!$this->pdo) {
                return [
                    'disponible' => false,
                    'message' => 'Erreur de connexion base de données'
                ];
            }
            
            // 1. Vérifier si déjà une réservation
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) as count 
                FROM reservation 
                WHERE id_covoiturage = ? AND id_passager = ? AND statut IN ('en_attente', 'confirmee')
            ");
            $stmt->execute([$id_covoiturage, $id_passager]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($existing['count'] > 0) {
                return [
                    'disponible' => false,
                    'message' => 'Vous avez déjà une réservation pour ce covoiturage'
                ];
            }
            
            // 2. Vérifier places disponibles
            $stmt = $this->pdo->prepare("
                SELECT nb_places_disponibles 
                FROM covoiturage 
                WHERE id_covoiturage = ?
            ");
            $stmt->execute([$id_covoiturage]);
            $covoiturage = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$covoiturage) {
                return [
                    'disponible' => false,
                    'message' => 'Covoiturage introuvable'
                ];
            }
            
            if ($covoiturage['nb_places_disponibles'] >= $nb_places) {
                return [
                    'disponible' => true,
                    'message' => 'Réservation possible'
                ];
            } else {
                return [
                    'disponible' => false,
                    'message' => 'Seulement ' . $covoiturage['nb_places_disponibles'] . ' place(s) disponible(s)'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Erreur verifierDisponibilite: " . $e->getMessage());
            return [
                'disponible' => false,
                'message' => 'Erreur lors de la vérification des places'
            ];
        }
    }
    
    /**
     * TÂCHE 2 : Vérification crédit utilisateur
     */
    private function verifierCreditUtilisateur($id_utilisateur, $credits_requis) {
        try {
            if (!$this->pdo) {
                return [
                    'suffisant' => false,
                    'message' => 'Erreur de connexion base de données',
                    'solde_actuel' => 0
                ];
            }
            
            $stmt = $this->pdo->prepare("SELECT credit FROM utilisateur WHERE id_utilisateur = ?");
            $stmt->execute([$id_utilisateur]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return [
                    'suffisant' => false,
                    'message' => 'Utilisateur introuvable',
                    'solde_actuel' => 0
                ];
            }
            
            $solde_actuel = (int)$user['credit'];
            
            if ($solde_actuel >= $credits_requis) {
                return [
                    'suffisant' => true,
                    'message' => 'Crédits suffisants',
                    'solde_actuel' => $solde_actuel
                ];
            } else {
                $credits_manquants = $credits_requis - $solde_actuel;
                return [
                    'suffisant' => false,
                    'message' => "Crédits insuffisants. Il vous manque {$credits_manquants} crédits.",
                    'solde_actuel' => $solde_actuel,
                    'credits_manquants' => $credits_manquants
                ];
            }
            
        } catch (Exception $e) {
            error_log("Erreur verifierCreditUtilisateur: " . $e->getMessage());
            return [
                'suffisant' => false,
                'message' => 'Erreur lors de la vérification des crédits'
            ];
        }
    }
    
    /**
     * Récupérer les informations du covoiturage
     */
    private function getCovoiturageInfo($id_covoiturage) {
        try {
            if (!$this->pdo) {
                return false;
            }
            
            $stmt = $this->pdo->prepare("
                SELECT c.*, u.prenom as conducteur_prenom, u.email as conducteur_email
                FROM covoiturage c
                JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
                WHERE c.id_covoiturage = ? AND c.statut = 'planifie'
            ");
            $stmt->execute([$id_covoiturage]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getCovoiturageInfo: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * TÂCHE 5 : Insérer la réservation (Mise à jour BDD atomique)
     */
    private function insererReservation($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO reservation (
                    id_covoiturage, id_passager, nb_places_reservees, 
                    prix_total, credits_utilises, statut
                ) VALUES (?, ?, ?, ?, ?, 'en_attente')
            ");
            
            $stmt->execute([
                $data['id_covoiturage'],
                $data['id_passager'],
                $data['nb_places_reservees'],
                $data['prix_total'],
                $data['credits_utilises']
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la création de la réservation: " . $e->getMessage());
        }
    }
    
    /**
     * Débiter les crédits de l'utilisateur
     */
    private function debiterCredits($id_utilisateur, $credits) {
        try {
            if (!$this->pdo) {
                throw new Exception("Pas de connexion à la base de données");
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE utilisateur 
                SET credit = credit - ? 
                WHERE id_utilisateur = ?
            ");
            return $stmt->execute([$credits, $id_utilisateur]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors du débit des crédits: " . $e->getMessage());
        }
    }
    
    /**
     * Mettre à jour les places disponibles manuellement
     */
    private function mettreAJourPlacesDisponibles($id_covoiturage, $nb_places_reservees) {
        try {
            if (!$this->pdo) {
                throw new Exception("Pas de connexion à la base de données");
            }
            
            $stmt = $this->pdo->prepare("
                UPDATE covoiturage 
                SET nb_places_disponibles = nb_places_disponibles - ? 
                WHERE id_covoiturage = ?
            ");
            return $stmt->execute([$nb_places_reservees, $id_covoiturage]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la mise à jour des places: " . $e->getMessage());
        }
    }
    
    /**
     * Confirmer la réservation (changer statut vers 'confirmee')
     */
    private function confirmerReservation($id_reservation) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE reservation 
                SET statut = 'confirmee', date_confirmation = NOW() 
                WHERE id_reservation = ?
            ");
            return $stmt->execute([$id_reservation]);
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la confirmation: " . $e->getMessage());
        }
    }
    
    /**
     * TÂCHE 6 : Envoyer notifications email
     */
    private function envoyerNotificationReservation($id_reservation) {
        try {
            if (!$this->pdo) {
                return; // Pas d'email si pas de BDD
            }
            
            // Récupérer les infos de la réservation
            $stmt = $this->pdo->prepare("
                SELECT r.*, c.ville_depart, c.ville_arrivee, c.date_depart,
                       u.prenom as passager_prenom, u.email as passager_email,
                       conducteur.prenom as conducteur_prenom, conducteur.email as conducteur_email
                FROM reservation r
                JOIN covoiturage c ON r.id_covoiturage = c.id_covoiturage
                JOIN utilisateur u ON r.id_passager = u.id_utilisateur
                JOIN utilisateur conducteur ON c.id_conducteur = conducteur.id_utilisateur
                WHERE r.id_reservation = ?
            ");
            $stmt->execute([$id_reservation]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($reservation) {
                // Email au passager
                $this->envoyerEmailPassager($reservation);
                
                // Email au conducteur
                $this->envoyerEmailConducteur($reservation);
            }
            
        } catch (Exception $e) {
            error_log("Erreur envoyerNotificationReservation: " . $e->getMessage());
            // Ne pas faire échouer la réservation si l'email ne marche pas
        }
    }
    
    /**
     * Email de confirmation au passager
     */
    private function envoyerEmailPassager($reservation) {
        $subject = "Réservation confirmée - " . $reservation['ville_depart'] . " → " . $reservation['ville_arrivee'];
        $message = "
        Bonjour {$reservation['passager_prenom']},
        
        Votre réservation a été confirmée !
        
        Détails du voyage :
        - Trajet : {$reservation['ville_depart']} → {$reservation['ville_arrivee']}
        - Date : " . date('d/m/Y à H:i', strtotime($reservation['date_depart'])) . "
        - Places réservées : {$reservation['nb_places_reservees']}
        - Prix total : {$reservation['prix_total']} crédits
        
        Conducteur : {$reservation['conducteur_prenom']}
        
        Bon voyage !
        L'équipe EcoRide
        ";
        
        // Simulation envoi email (remplacer par vraie logique mail)
        error_log("EMAIL PASSAGER: " . $reservation['passager_email'] . " - " . $subject);
    }
    
    /**
     * Email de notification au conducteur
     */
    private function envoyerEmailConducteur($reservation) {
        $subject = "Nouvelle réservation pour votre covoiturage";
        $message = "
        Bonjour {$reservation['conducteur_prenom']},
        
        {$reservation['passager_prenom']} a réservé {$reservation['nb_places_reservees']} place(s) pour votre covoiturage.
        
        Trajet : {$reservation['ville_depart']} → {$reservation['ville_arrivee']}
        Date : " . date('d/m/Y à H:i', strtotime($reservation['date_depart'])) . "
        
        Vous pouvez contacter votre passager via votre dashboard.
        
        L'équipe EcoRide
        ";
        
        // Simulation envoi email
        error_log("EMAIL CONDUCTEUR: " . $reservation['conducteur_email'] . " - " . $subject);
    }
    
    /**
     * Annuler une réservation
     */
    public function cancelReservation() {
        if (!isset($_SESSION['user_id'])) {
            $this->sendJsonResponse(false, 'Vous devez être connecté');
            return;
        }
        
        if (!$this->pdo) {
            $this->sendJsonResponse(false, 'Erreur de connexion à la base de données');
            return;
        }
        
        try {
            $id_reservation = (int)($_POST['id_reservation'] ?? 0);
            $id_utilisateur = (int)$_SESSION['user_id'];
            
            $this->pdo->beginTransaction();
            
            // Vérifier que la réservation appartient à l'utilisateur
            $stmt = $this->pdo->prepare("
                SELECT r.*, c.date_depart 
                FROM reservation r
                JOIN covoiturage c ON r.id_covoiturage = c.id_covoiturage
                WHERE r.id_reservation = ? AND r.id_passager = ? AND r.statut = 'confirmee'
            ");
            $stmt->execute([$id_reservation, $id_utilisateur]);
            $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$reservation) {
                throw new Exception('Réservation introuvable ou déjà annulée');
            }
            
            // Vérifier qu'on peut encore annuler (24h avant le départ)
            $time_limite = strtotime($reservation['date_depart']) - (24 * 3600);
            if (time() > $time_limite) {
                throw new Exception('Impossible d\'annuler moins de 24h avant le départ');
            }
            
            // Annuler la réservation
            $stmt = $this->pdo->prepare("
                UPDATE reservation 
                SET statut = 'annulee', date_annulation = NOW(), raison_annulation = 'Annulation passager'
                WHERE id_reservation = ?
            ");
            $stmt->execute([$id_reservation]);
            
            // Rembourser les crédits
            $stmt = $this->pdo->prepare("
                UPDATE utilisateur 
                SET credit = credit + ? 
                WHERE id_utilisateur = ?
            ");
            $stmt->execute([$reservation['credits_utilises'], $id_utilisateur]);
            
            // Remettre les places disponibles
            $stmt = $this->pdo->prepare("
                UPDATE covoiturage 
                SET nb_places_disponibles = nb_places_disponibles + ? 
                WHERE id_covoiturage = ?
            ");
            $stmt->execute([$reservation['nb_places_reservees'], $reservation['id_covoiturage']]);
            
            $this->pdo->commit();
            
            $this->sendJsonResponse(true, 'Réservation annulée et crédits remboursés', [
                'credits_rembourses' => $reservation['credits_utilises']
            ]);
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur cancelReservation: " . $e->getMessage());
            $this->sendJsonResponse(false, $e->getMessage());
        }
    }
    
    /**
     * Lister les réservations d'un utilisateur
     */
    public function getUserReservations($id_utilisateur) {
        try {
            if (!$this->pdo) {
                return [];
            }
            
            $stmt = $this->pdo->prepare("
                SELECT r.*, c.ville_depart, c.ville_arrivee, c.date_depart, c.heure_depart,
                       conducteur.prenom as conducteur_prenom
                FROM reservation r
                JOIN covoiturage c ON r.id_covoiturage = c.id_covoiturage
                JOIN utilisateur conducteur ON c.id_conducteur = conducteur.id_utilisateur
                WHERE r.id_passager = ?
                ORDER BY r.date_reservation DESC
            ");
            $stmt->execute([$id_utilisateur]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getUserReservations: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * TÂCHE 7 : Gestion erreurs - Réponse JSON standardisée
     */
    private function sendJsonResponse($success, $message, $data = []) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
        exit;
    }
}
?>
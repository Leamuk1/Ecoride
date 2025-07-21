<?php
/**
 * Classe pour gérer le système de crédits
 */
class CreditSystem {
    
    private $db;
    
    const CREDITS_INSCRIPTION = 20; 
    const COMMISSION_PLATEFORME = 2;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    /**
     * Vérifier si l'utilisateur a assez de crédits
     */
    public function hasEnoughCredits($userId, $amount) {
        $query = "SELECT credit FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        return $user && $user['credit'] >= $amount;
    }
    
    /**
     * Effectuer une transaction de crédits
     */
    public function processPayment($passagerId, $chauffeurId, $covoiturageId, $montant) {
        try {
            $this->db->beginTransaction();
            
            // 1. Vérifier le solde du passager
            if (!$this->hasEnoughCredits($passagerId, $montant)) {
                throw new Exception("Crédits insuffisants");
            }
            
            // 2. Débiter le passager
            $this->debiterCredits($passagerId, $montant);
            
            // 3. Calculer les montants
            $commissionPlateforme = self::COMMISSION_PLATEFORME;
            $montantChauffeur = $montant - $commissionPlateforme;
            
            // 4. Créditer le chauffeur (après commission)
            $this->crediterCredits($chauffeurId, $montantChauffeur);
            
            // 5. Enregistrer la transaction
            $this->enregistrerTransaction([
                'passager_id' => $passagerId,
                'chauffeur_id' => $chauffeurId,
                'covoiturage_id' => $covoiturageId,
                'montant_total' => $montant,
                'commission' => $commissionPlateforme,
                'montant_chauffeur' => $montantChauffeur
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Débiter des crédits d'un utilisateur
     */
    private function debiterCredits($userId, $montant) {
        $query = "UPDATE utilisateur SET credit = credit - ? WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$montant, $userId]);
    }
    
    /**
     * Créditer des crédits à un utilisateur
     */
    private function crediterCredits($userId, $montant) {
        $query = "UPDATE utilisateur SET credit = credit + ? WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$montant, $userId]);
    }
    
    /**
     * Obtenir le solde d'un utilisateur
     */
    public function getSolde($userId) {
        $query = "SELECT credit FROM utilisateur WHERE id_utilisateur = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        
        return $user ? $user['credit'] : 0;
    }
    
    /**
     * Calculer le prix suggéré basé sur la distance
     */
    public function calculateSuggestedPrice($distanceKm) {
        // Formule : 0.10 crédit par km + frais fixes
        $prixParKm = 0.10;
        $fraisFixes = 5;
        
        return round(($distanceKm * $prixParKm) + $fraisFixes, 2);
    }
    
    /**
     * Enregistrer une transaction pour l'historique
     */
    private function enregistrerTransaction($data) {
        $query = "INSERT INTO transaction_credit 
                  (passager_id, chauffeur_id, covoiturage_id, montant_total, commission, montant_chauffeur, date_transaction)
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            $data['passager_id'],
            $data['chauffeur_id'], 
            $data['covoiturage_id'],
            $data['montant_total'],
            $data['commission'],
            $data['montant_chauffeur']
        ]);
    }
    
    /**
     * Rembourser en cas d'annulation
     */
    public function refundCredits($participationId) {
        try {
            $this->db->beginTransaction();
            
            // Récupérer les infos de participation
            $query = "SELECT p.id_utilisateur, p.credit_utilise, c.id_conducteur 
                      FROM participation p 
                      JOIN covoiturage c ON p.id_covoiturage = c.id_covoiturage 
                      WHERE p.id_participation = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$participationId]);
            $participation = $stmt->fetch();
            
            if ($participation) {
                // Rembourser le passager
                $this->crediterCredits($participation['id_utilisateur'], $participation['credit_utilise']);
                
                // Débiter le chauffeur (enlever les gains)
                $montantChauffeur = $participation['credit_utilise'] - self::COMMISSION_PLATEFORME;
                $this->debiterCredits($participation['id_conducteur'], $montantChauffeur);
                
                // Marquer la participation comme annulée
                $updateQuery = "UPDATE participation SET statut = 'annule' WHERE id_participation = ?";
                $updateStmt = $this->db->prepare($updateQuery);
                $updateStmt->execute([$participationId]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    
    /**
     * Obtenir l'historique des transactions d'un utilisateur
     */
    public function getTransactionHistory($userId) {
        $query = "SELECT 
                    t.*,
                    c.ville_depart,
                    c.ville_arrivee,
                    c.date_depart,
                    u1.pseudo as passager_pseudo,
                    u2.pseudo as chauffeur_pseudo
                  FROM transaction_credit t
                  JOIN covoiturage c ON t.covoiturage_id = c.id_covoiturage
                  JOIN utilisateur u1 ON t.passager_id = u1.id_utilisateur
                  JOIN utilisateur u2 ON t.chauffeur_id = u2.id_utilisateur
                  WHERE t.passager_id = ? OR t.chauffeur_id = ?
                  ORDER BY t.date_transaction DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $userId]);
        
        return $stmt->fetchAll();
    }
}

/**
 * Exemple d'utilisation dans un contrôleur
 */
class RideController {
    
    private $creditSystem;
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
        $this->creditSystem = new CreditSystem($database);
    }
    
    /**
     * Récupérer les détails d'un covoiturage
     */
    private function getRideDetails($covoiturageId) {
        $query = "SELECT c.*, u.pseudo as conducteur_pseudo 
                  FROM covoiturage c 
                  JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur 
                  WHERE c.id_covoiturage = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$covoiturageId]);
        
        return $stmt->fetch();
    }
    
    /**
     * Participer à un covoiturage avec gestion des crédits
     */
    public function participateToRide($userId, $covoiturageId) {
        // 1. Récupérer les infos du covoiturage
        $ride = $this->getRideDetails($covoiturageId);
        
        if (!$ride) {
            return [
                'success' => false,
                'message' => 'Covoiturage introuvable'
            ];
        }
        
        // 2. Vérifier les places disponibles
        if ($ride['nb_places_disponibles'] <= 0) {
            return [
                'success' => false,
                'message' => 'Plus de places disponibles'
            ];
        }
        
        // 3. Vérifier les crédits
        if (!$this->creditSystem->hasEnoughCredits($userId, $ride['prix_par_personne'])) {
            return [
                'success' => false,
                'message' => 'Crédits insuffisants. Votre solde: ' . $this->creditSystem->getSolde($userId) . ' crédits'
            ];
        }
        
        // 4. Traiter le paiement
        $success = $this->creditSystem->processPayment(
            $userId, 
            $ride['id_conducteur'], 
            $covoiturageId, 
            $ride['prix_par_personne']
        );
        
        if ($success) {
            // 5. Créer la participation
            $this->createParticipation($userId, $covoiturageId, $ride['prix_par_personne']);
            
            return [
                'success' => true,
                'message' => 'Participation confirmée ! Nouveau solde: ' . $this->creditSystem->getSolde($userId) . ' crédits'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Erreur lors du traitement. Veuillez réessayer.'
            ];
        }
    }
    
    /**
     * Créer une participation
     */
    private function createParticipation($userId, $covoiturageId, $creditUtilise) {
        $query = "INSERT INTO participation (id_utilisateur, id_covoiturage, credit_utilise, statut, date_confirmation) 
                  VALUES (?, ?, ?, 'confirme', NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId, $covoiturageId, $creditUtilise]);
        
        // Mettre à jour les places disponibles
        $updateQuery = "UPDATE covoiturage SET nb_places_disponibles = nb_places_disponibles - 1 WHERE id_covoiturage = ?";
        $updateStmt = $this->db->prepare($updateQuery);
        $updateStmt->execute([$covoiturageId]);
    }
}
?>
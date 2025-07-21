<?php
// app/controllers/HomeController.php - Version corrigée US7
require_once '../app/models/UserModel.php';

class HomeController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Page d'accueil
     */
    public function index() {
        try {
            // Récupérer quelques covoiturages pour la page d'accueil
            $rides = $this->getRecentRides();
            
            // Récupérer les statistiques de base
            $stats = $this->getHomeStats();
            
            // Variables pour la vue
            $title = "Accueil - EcoRide";
            $page_title = "EcoRide - Covoiturage Écologique";
            
        } catch (Exception $e) {
            error_log("Erreur HomeController::index - " . $e->getMessage());
            
            // En cas d'erreur, utiliser des données par défaut
            $rides = [];
            $stats = $this->getDefaultStats();
            $title = "Accueil - EcoRide";
            $page_title = "EcoRide - Covoiturage Écologique";
        }
        
        // Charger la vue d'accueil
        include '../app/views/layouts/header.php';
        include '../app/views/home/index.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * Page contact
     */
    public function contact() {
        $title = "Contact - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/contact/index.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * Page covoiturages
     */
    public function rides() {
        try {
            // Récupérer tous les covoiturages disponibles
            $rides = $this->getAllRides();
            
        } catch (Exception $e) {
            error_log("Erreur HomeController::rides - " . $e->getMessage());
            $rides = [];
        }
        
        $title = "Covoiturages - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/rides/index.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * Récupérer les covoiturages récents (version simple)
     */
    private function getRecentRides() {
        try {
            // Connexion à la base (utilise la config existante)
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            // Requête simple adaptée à ta structure BDD
            $sql = "SELECT 
                        c.id_covoiturage,
                        c.ville_depart,
                        c.ville_arrivee,
                        c.date_depart,
                        c.heure_depart,
                        c.prix_par_personne,
                        c.nb_places_disponibles,
                        u.pseudo as conducteur_pseudo,
                        u.prenom as conducteur_prenom,
                        v.energie as type_vehicule,
                        m.libelle as marque,
                        v.modele
                    FROM covoiturage c
                    JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
                    LEFT JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                    LEFT JOIN marque m ON v.id_marque = m.id_marque
                    WHERE c.date_depart > NOW()
                    AND c.statut = 'planifie'
                    ORDER BY c.date_depart ASC
                    LIMIT 6";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Ajouter des infos simples à chaque trajet
            foreach ($rides as &$ride) {
                $ride['eco_badge'] = $this->getEcoBadge($ride['type_vehicule']);
                $ride['formatted_date'] = date('d/m/Y', strtotime($ride['date_depart']));
                $ride['formatted_time'] = $ride['heure_depart'];
            }
            
            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur getRecentRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer tous les covoiturages disponibles
     */
    private function getAllRides() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $sql = "SELECT 
                        c.id_covoiturage,
                        c.ville_depart,
                        c.ville_arrivee,
                        c.date_depart,
                        c.heure_depart,
                        c.prix_par_personne,
                        c.nb_places_disponibles,
                        c.commentaire,
                        u.pseudo as conducteur_pseudo,
                        u.prenom as conducteur_prenom,
                        u.note_moyenne as conducteur_note,
                        v.energie as type_vehicule,
                        m.libelle as marque,
                        v.modele,
                        v.couleur
                    FROM covoiturage c
                    JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
                    LEFT JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                    LEFT JOIN marque m ON v.id_marque = m.id_marque
                    WHERE c.date_depart > NOW()
                    AND c.statut = 'planifie'
                    ORDER BY c.date_depart ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formatage des données
            foreach ($rides as &$ride) {
                $ride['eco_badge'] = $this->getEcoBadge($ride['type_vehicule']);
                $ride['formatted_date'] = date('d/m/Y', strtotime($ride['date_depart']));
                $ride['formatted_time'] = $ride['heure_depart'];
                $ride['conducteur_note'] = $ride['conducteur_note'] ?: 'Nouveau';
            }
            
            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur getAllRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les statistiques pour la page d'accueil (version simple)
     */
    private function getHomeStats() {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            
            $stats = [];
            
            // Nombre d'utilisateurs actifs
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilisateur WHERE statut = 'actif'");
            $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de trajets disponibles
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM covoiturage WHERE statut = 'planifie' AND date_depart > NOW()");
            $stats['available_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de trajets terminés
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM covoiturage WHERE statut = 'termine'");
            $stats['completed_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Trajets écologiques
            $stmt = $pdo->query("
                SELECT COUNT(*) as eco_rides
                FROM covoiturage c
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                WHERE v.energie IN ('electrique', 'hybride')
                AND c.statut = 'planifie'
            ");
            $stats['eco_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['eco_rides'];
            
            // Estimations
            $stats['km_shared'] = $stats['completed_rides'] * 250; // 250km par trajet en moyenne
            $stats['co2_saved'] = round($stats['km_shared'] * 0.12); // 120g CO2/km économisé
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Erreur getHomeStats: " . $e->getMessage());
            return $this->getDefaultStats();
        }
    }
    
    /**
     * Statistiques par défaut en cas d'erreur
     */
    private function getDefaultStats() {
        return [
            'users' => 10,
            'available_rides' => 15,
            'completed_rides' => 2,
            'eco_rides' => 8,
            'km_shared' => 500,
            'co2_saved' => 60
        ];
    }
    
    /**
     * Badge écologique selon le type de véhicule
     */
    private function getEcoBadge($typeVehicule) {
        switch ($typeVehicule) {
            case 'electrique':
                return [
                    'class' => 'badge-electric',
                    'text' => 'Électrique',
                    'color' => '#28a745'
                ];
            case 'hybride':
                return [
                    'class' => 'badge-hybrid',
                    'text' => 'Hybride',
                    'color' => '#17a2b8'
                ];
            case 'essence':
                return [
                    'class' => 'badge-standard',
                    'text' => 'Essence',
                    'color' => '#6c757d'
                ];
            case 'diesel':
                return [
                    'class' => 'badge-standard',
                    'text' => 'Diesel',
                    'color' => '#6c757d'
                ];
            default:
                return [
                    'class' => 'badge-standard',
                    'text' => 'Standard',
                    'color' => '#6c757d'
                ];
        }
    }
}
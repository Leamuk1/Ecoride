<?php
// app/controllers/HomeController.php

require_once '../app/models/RideModel.php';
require_once '../app/models/UserModel.php';

class HomeController {
    private $rideModel;
    private $userModel;
    
    public function __construct() {
        $this->rideModel = new RideModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Page d'accueil
     */
    public function index() {
        try {
            // Récupérer les covoiturages populaires pour la page d'accueil
            $rides = $this->getPopularRides();
            
            // Récupérer les statistiques pour la page d'accueil
            $stats = $this->getHomeStats();
            
            // Charger la vue avec les données
            $this->loadView('home/index', [
                'rides' => $rides,
                'stats' => $stats,
                'page_title' => 'EcoRide - Covoiturage Écologique',
                'meta_description' => 'Plateforme de covoiturage écologique. Partagez vos trajets, réduisez votre empreinte carbone et voyagez économique.'
            ]);
            
        } catch (Exception $e) {
            error_log("Erreur HomeController::index - " . $e->getMessage());
            
            // En cas d'erreur, charger la page sans données
            $this->loadView('home/index', [
                'rides' => [],
                'stats' => $this->getDefaultStats(),
                'page_title' => 'EcoRide - Covoiturage Écologique',
                'meta_description' => 'Plateforme de covoiturage écologique.',
                'error_message' => 'Certaines données n\'ont pas pu être chargées.'
            ]);
        }
    }
    
    /**
     * Récupérer les covoiturages populaires pour la page d'accueil
     */
    private function getPopularRides() {
        try {
            // Requête pour récupérer les trajets les plus populaires
            $sql = "
                SELECT 
                    c.id,
                    c.ville_depart,
                    c.ville_arrivee,
                    c.date_depart,
                    c.prix_place,
                    c.places_disponibles,
                    u.prenom,
                    u.nom,
                    v.type_vehicule,
                    v.modele,
                    v.couleur,
                    AVG(a.note) as note_moyenne,
                    COUNT(a.id) as nb_avis,
                    COUNT(p.id) as nb_participations
                FROM covoiturage c
                INNER JOIN utilisateur u ON c.id_conducteur = u.id
                LEFT JOIN vehicule v ON c.id_vehicule = v.id
                LEFT JOIN avis a ON a.id_evalue = u.id
                LEFT JOIN participation p ON p.id_covoiturage = c.id AND p.statut IN ('confirme', 'termine')
                WHERE c.date_depart > NOW()
                    AND c.places_disponibles > 0
                    AND c.statut = 'actif'
                GROUP BY c.id, c.ville_depart, c.ville_arrivee, c.date_depart, 
                         c.prix_place, c.places_disponibles, u.prenom, u.nom,
                         v.type_vehicule, v.modele, v.couleur
                ORDER BY nb_participations DESC, note_moyenne DESC, c.date_depart ASC
                LIMIT 6
            ";
            
            $result = $this->rideModel->executeQuery($sql);
            $rides = [];
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // Ajouter des informations supplémentaires
                $row['eco_badge'] = $this->getEcoBadge($row['type_vehicule']);
                $row['route_duration'] = $this->calculateDuration($row['ville_depart'], $row['ville_arrivee']);
                $rides[] = $row;
            }
            
            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur getPopularRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les statistiques pour la page d'accueil
     */
    private function getHomeStats() {
        try {
            $stats = [];
            
            // Nombre d'utilisateurs actifs
            $sql = "SELECT COUNT(*) as total FROM utilisateur WHERE statut = 'actif'";
            $result = $this->userModel->executeQuery($sql);
            $stats['users'] = $result->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de trajets réalisés
            $sql = "SELECT COUNT(*) as total FROM covoiturage WHERE statut = 'termine'";
            $result = $this->rideModel->executeQuery($sql);
            $stats['completed_rides'] = $result->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Kilomètres partagés (estimation)
            $sql = "
                SELECT COUNT(p.id) as total_participations
                FROM participation p
                INNER JOIN covoiturage c ON p.id_covoiturage = c.id
                WHERE p.statut = 'termine'
            ";
            $result = $this->rideModel->executeQuery($sql);
            $participations = $result->fetch(PDO::FETCH_ASSOC)['total_participations'];
            $stats['km_shared'] = $participations * 250; // Estimation 250km par trajet
            
            // CO2 économisé (estimation)
            $stats['co2_saved'] = round($stats['km_shared'] * 0.12); // 120g CO2/km économisé
            
            // Trajets écologiques (électrique + hybride)
            $sql = "
                SELECT COUNT(*) as eco_rides
                FROM covoiturage c
                INNER JOIN vehicule v ON c.id_vehicule = v.id
                WHERE v.type_vehicule IN ('electrique', 'hybride')
                    AND c.statut IN ('actif', 'termine')
            ";
            $result = $this->rideModel->executeQuery($sql);
            $stats['eco_rides'] = $result->fetch(PDO::FETCH_ASSOC)['eco_rides'];
            
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
            'users' => 15000,
            'completed_rides' => 50000,
            'km_shared' => 2300000,
            'co2_saved' => 320,
            'eco_rides' => 35000
        ];
    }
    
    /**
     * Obtenir le badge écologique selon le type de véhicule
     */
    private function getEcoBadge($typeVehicule) {
        switch ($typeVehicule) {
            case 'electrique':
                return [
                    'class' => 'eco-electric',
                    'icon' => 'fas fa-bolt',
                    'text' => 'Électrique'
                ];
            case 'hybride':
                return [
                    'class' => 'eco-hybrid',
                    'icon' => 'fas fa-leaf',
                    'text' => 'Hybride'
                ];
            case 'essence':
                return [
                    'class' => 'eco-standard',
                    'icon' => 'fas fa-car',
                    'text' => 'Essence'
                ];
            default:
                return [
                    'class' => 'eco-standard',
                    'icon' => 'fas fa-car',
                    'text' => 'Standard'
                ];
        }
    }
    
    /**
     * Calculer la durée estimée du trajet
     */
    private function calculateDuration($depart, $arrivee) {
        // Estimation basique - à améliorer avec une vraie API
        $distances = [
            'Paris-Marseille' => '7h30',
            'Paris-Lyon' => '4h30',
            'Paris-Toulouse' => '6h30',
            'Lyon-Marseille' => '3h15',
            'Marseille-Nice' => '2h30',
            'Paris-Bordeaux' => '5h45'
        ];
        
        $route = $depart . '-' . $arrivee;
        return $distances[$route] ?? '6h00';
    }
    
    /**
     * Charger une vue
     */
    private function loadView($view, $data = []) {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);
        
        // Inclure le layout principal
        include '../app/views/layouts/main.php';
    }
    
}
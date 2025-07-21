<?php
// app/controllers/HomeController.php - Version US3 avec recherche intégrée
require_once '../app/models/UserModel.php';

class HomeController {
    private $userModel;
    private $pdo;
    
    public function __construct() {
        $this->userModel = new UserModel();
        
        // Connexion à la base de données (factorisation)
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            error_log("Erreur connexion HomeController: " . $e->getMessage());
            $this->pdo = null;
        }
    }
    
    /**
     * Page d'accueil
     */
    public function index() {
        try {
            // Récupérer les statistiques de base seulement
            $stats = $this->getHomeStats();
            
            // Variables pour la vue
            $title = "Accueil - EcoRide";
            $page_title = "EcoRide - Covoiturage Écologique";
            
            // Plus de covoiturages sur l'accueil
            $rides = [];
            
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
     * Page covoiturages avec recherche US3 et pagination
     */
    public function rides() {
        try {
            // Récupérer les critères de recherche depuis le formulaire
            $departure = trim($_GET['departure'] ?? '');
            $arrival = trim($_GET['arrival'] ?? '');
            $date = trim($_GET['date'] ?? '');
            $passengers = (int)($_GET['passengers'] ?? 1);
            
            // Pagination simple
            $page = (int)($_GET['page'] ?? 1);
            $limit = 6; // 6 covoiturages par page
            $offset = ($page - 1) * $limit;
            
            // Déterminer si c'est une recherche ou un affichage général
            $isSearch = !empty($departure) || !empty($arrival) || !empty($date);
            
            if ($isSearch) {
                // Recherche avec critères spécifiques
                $rides = $this->rechercherCovoiturages($departure, $arrival, $date, $passengers, $limit, $offset);
                $total_rides = $this->countSearchResults($departure, $arrival, $date, $passengers);
                
                // Si aucun résultat, chercher des alternatives
                if (empty($rides)) {
                    $alternatives = $this->getAlternatives($departure, $arrival, $date, $passengers);
                } else {
                    $alternatives = [];
                }
            } else {
                // Affichage de tous les covoiturages disponibles
                $rides = $this->getAllRides($limit, $offset);
                $total_rides = $this->countAllRides();
                $alternatives = [];
            }
            
            // Calcul pagination
            $total_pages = ceil($total_rides / $limit);
            $pagination = [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_rides' => $total_rides,
                'has_previous' => $page > 1,
                'has_next' => $page < $total_pages,
                'previous_page' => $page - 1,
                'next_page' => $page + 1
            ];
            
        } catch (Exception $e) {
            error_log("Erreur HomeController::rides - " . $e->getMessage());
            $rides = [];
            $alternatives = [];
            $pagination = ['current_page' => 1, 'total_pages' => 0, 'total_rides' => 0];
        }
        
        $title = "Covoiturages - EcoRide";
        include '../app/views/layouts/header.php';
        include '../app/views/rides/index.php';
        include '../app/views/layouts/footer.php';
    }
    
    /**
     * NOUVELLE MÉTHODE US3 - Recherche de covoiturages avec pagination
     */
    private function rechercherCovoiturages($departure, $arrival, $date, $passengers, $limit = 20, $offset = 0) {
        try {
            if (!$this->pdo) {
                return [];
            }
            
            // Construction de la requête SQL étape par étape
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
                    WHERE c.statut = 'planifie'
                    AND c.date_depart > NOW()
                    AND c.nb_places_disponibles >= :passengers";

            // Array pour stocker les paramètres
            $params = [':passengers' => $passengers];

            // Ajouter les filtres de recherche un par un
            
            // Filtre par ville de départ
            if (!empty($departure)) {
                $sql .= " AND c.ville_depart LIKE :departure";
                $params[':departure'] = '%' . $departure . '%';
            }

            // Filtre par ville d'arrivée
            if (!empty($arrival)) {
                $sql .= " AND c.ville_arrivee LIKE :arrival";
                $params[':arrival'] = '%' . $arrival . '%';
            }

            // Filtre par date de départ
            if (!empty($date)) {
                $sql .= " AND DATE(c.date_depart) = :date";
                $params[':date'] = $date;
            }

            // Trier par date (plus proche en premier)
            $sql .= " ORDER BY c.date_depart ASC, c.prix_par_personne ASC";

            // Ajouter la pagination
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;

            // Exécuter la requête
            $stmt = $this->pdo->prepare($sql);
            
            // Bind des paramètres avec types spécifiques pour LIMIT/OFFSET
            foreach ($params as $key => $value) {
                if ($key === ':limit' || $key === ':offset' || $key === ':passengers') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            
            $stmt->execute();
            $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Formater les données pour l'affichage
            foreach ($rides as &$ride) {
                $ride['eco_badge'] = $this->getEcoBadge($ride['type_vehicule']);
                $ride['formatted_date'] = date('d/m/Y', strtotime($ride['date_depart']));
                $ride['formatted_time'] = substr($ride['heure_depart'], 0, 5);
                $ride['conducteur_note'] = $ride['conducteur_note'] ?: 'Nouveau';
            }

            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur rechercherCovoiturages: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer les covoiturages récents pour la page d'accueil
     */
    private function getRecentRides() {
        try {
            if (!$this->pdo) {
                return [];
            }
            
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
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formater les données
            foreach ($rides as &$ride) {
                $ride['eco_badge'] = $this->getEcoBadge($ride['type_vehicule']);
                $ride['formatted_date'] = date('d/m/Y', strtotime($ride['date_depart']));
                $ride['formatted_time'] = substr($ride['heure_depart'], 0, 5);
            }
            
            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur getRecentRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupérer tous les covoiturages disponibles avec pagination
     */
    private function getAllRides($limit = 50, $offset = 0) {
        try {
            if (!$this->pdo) {
                return [];
            }
            
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
                    ORDER BY c.date_depart ASC
                    LIMIT :limit OFFSET :offset";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Formater les données
            foreach ($rides as &$ride) {
                $ride['eco_badge'] = $this->getEcoBadge($ride['type_vehicule']);
                $ride['formatted_date'] = date('d/m/Y', strtotime($ride['date_depart']));
                $ride['formatted_time'] = substr($ride['heure_depart'], 0, 5);
                $ride['conducteur_note'] = $ride['conducteur_note'] ?: 'Nouveau';
            }
            
            return $rides;
            
        } catch (Exception $e) {
            error_log("Erreur getAllRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Compter le nombre total de résultats de recherche
     */
    private function countSearchResults($departure, $arrival, $date, $passengers) {
        try {
            if (!$this->pdo) {
                return 0;
            }
            
            $sql = "SELECT COUNT(*) as total
                    FROM covoiturage c
                    WHERE c.statut = 'planifie'
                    AND c.date_depart > NOW()
                    AND c.nb_places_disponibles >= :passengers";

            $params = [':passengers' => $passengers];

            if (!empty($departure)) {
                $sql .= " AND c.ville_depart LIKE :departure";
                $params[':departure'] = '%' . $departure . '%';
            }

            if (!empty($arrival)) {
                $sql .= " AND c.ville_arrivee LIKE :arrival";
                $params[':arrival'] = '%' . $arrival . '%';
            }

            if (!empty($date)) {
                $sql .= " AND DATE(c.date_depart) = :date";
                $params[':date'] = $date;
            }

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
        } catch (Exception $e) {
            error_log("Erreur countSearchResults: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Compter le nombre total de covoiturages disponibles
     */
    private function countAllRides() {
        try {
            if (!$this->pdo) {
                return 0;
            }
            
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as total 
                FROM covoiturage 
                WHERE statut = 'planifie' 
                AND date_depart > NOW()
            ");
            
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
        } catch (Exception $e) {
            error_log("Erreur countAllRides: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Récupérer les statistiques pour la page d'accueil
     */
    private function getHomeStats() {
        try {
            if (!$this->pdo) {
                return $this->getDefaultStats();
            }
            
            $stats = [];
            
            // Nombre d'utilisateurs actifs
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM utilisateur WHERE statut = 'actif'");
            $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de trajets disponibles
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM covoiturage WHERE statut = 'planifie' AND date_depart > NOW()");
            $stats['available_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Nombre de trajets terminés
            $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM covoiturage WHERE statut = 'termine'");
            $stats['completed_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Trajets écologiques
            $stmt = $this->pdo->query("
                SELECT COUNT(*) as eco_rides
                FROM covoiturage c
                JOIN vehicule v ON c.id_vehicule = v.id_vehicule
                WHERE v.energie IN ('electrique', 'hybride')
                AND c.statut = 'planifie'
            ");
            $stats['eco_rides'] = $stmt->fetch(PDO::FETCH_ASSOC)['eco_rides'];
            
            // Estimations simples
            $stats['km_shared'] = $stats['completed_rides'] * 250;
            $stats['co2_saved'] = round($stats['km_shared'] * 0.12);
            
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
     * Badge écologique selon le type de véhicule (version simplifiée)
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

    /**
     * Estimer la durée du trajet
     */
    private function estimerDuree($depart, $arrivee) {
        // Distances et durées courantes
        $routes = [
            'Paris-Marseille' => '7h30', 'Marseille-Paris' => '7h30',
            'Paris-Lyon' => '4h30', 'Lyon-Paris' => '4h30',
            'Lyon-Marseille' => '3h15', 'Marseille-Lyon' => '3h15',
            'Marseille-Nice' => '2h30', 'Nice-Marseille' => '2h30',
            'Paris-Toulouse' => '6h30', 'Toulouse-Paris' => '6h30',
            'Lyon-Toulouse' => '5h15', 'Toulouse-Lyon' => '5h15',
            'Toulouse-Bordeaux' => '2h45', 'Bordeaux-Toulouse' => '2h45',
            'Paris-Bordeaux' => '5h45', 'Bordeaux-Paris' => '5h45',
            'Paris-Lille' => '2h15', 'Lille-Paris' => '2h15',
            'Paris-Strasbourg' => '4h00', 'Strasbourg-Paris' => '4h00',
            'Paris-Nantes' => '3h45', 'Nantes-Paris' => '3h45',
            'Versailles-Bordeaux' => '5h30', 'Bordeaux-Versailles' => '5h30',
            'Créteil-Marseille' => '7h15', 'Marseille-Créteil' => '7h15',
            'Meaux-Lyon' => '4h15', 'Lyon-Meaux' => '4h15',
            'Lyon-Saint-Étienne' => '1h00', 'Saint-Étienne-Lyon' => '1h00',
            'Paris-Tours' => '2h30', 'Tours-Paris' => '2h30'
        ];
        
        $route = $depart . '-' . $arrivee;
        return $routes[$route] ?? $this->calculerDureeEstimee($depart, $arrivee);
    }

    /**
     * Estimer la distance du trajet
     */
    private function estimerDistance($depart, $arrivee) {
        // Distances en kilomètres
        $distances = [
            'Paris-Marseille' => 775, 'Marseille-Paris' => 775,
            'Paris-Lyon' => 463, 'Lyon-Paris' => 463,
            'Lyon-Marseille' => 314, 'Marseille-Lyon' => 314,
            'Marseille-Nice' => 204, 'Nice-Marseille' => 204,
            'Paris-Toulouse' => 679, 'Toulouse-Paris' => 679,
            'Lyon-Toulouse' => 535, 'Toulouse-Lyon' => 535,
            'Toulouse-Bordeaux' => 244, 'Bordeaux-Toulouse' => 244,
            'Paris-Bordeaux' => 579, 'Bordeaux-Paris' => 579,
            'Paris-Lille' => 225, 'Lille-Paris' => 225,
            'Paris-Strasbourg' => 489, 'Strasbourg-Paris' => 489,
            'Paris-Nantes' => 385, 'Nantes-Paris' => 385,
            'Versailles-Bordeaux' => 565, 'Bordeaux-Versailles' => 565,
            'Créteil-Marseille' => 760, 'Marseille-Créteil' => 760,
            'Meaux-Lyon' => 445, 'Lyon-Meaux' => 445,
            'Lyon-Saint-Étienne' => 60, 'Saint-Étienne-Lyon' => 60,
            'Paris-Tours' => 239, 'Tours-Paris' => 239
        ];
        
        $route = $depart . '-' . $arrivee;
        return $distances[$route] ?? $this->calculerDistanceEstimee($depart, $arrivee);
    }

    /**
     * Calcul estimation durée basique
     */
    private function calculerDureeEstimee($depart, $arrivee) {
        $longueur = abs(strlen($depart) - strlen($arrivee)) + 3;
        $heures = min(max($longueur, 2), 8);
        $minutes = rand(0, 45);
        return $heures . 'h' . str_pad($minutes, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Calcul estimation distance basique
     */
    private function calculerDistanceEstimee($depart, $arrivee) {
        $longueur = abs(strlen($depart) - strlen($arrivee)) + 5;
        return min(max($longueur * 50, 100), 800);
    }

    /**
     * Rechercher des alternatives automatiquement
     */
    private function getAlternatives($departure, $arrival, $date, $passengers) {
        $alternatives = [
            'dates' => [],
            'villes' => []
        ];

        try {
            if (!$this->pdo) {
                return $alternatives;
            }

            // 1. Alternatives de dates (si une date était spécifiée)
            if (!empty($date)) {
                $dateAlternatives = [
                    date('Y-m-d', strtotime($date . ' -1 day')),
                    date('Y-m-d', strtotime($date . ' +1 day')),
                    date('Y-m-d', strtotime($date . ' +2 days')),
                    date('Y-m-d', strtotime($date . ' +7 days'))
                ];

                foreach ($dateAlternatives as $altDate) {
                    if ($altDate >= date('Y-m-d')) {
                        $count = $this->countSearchResults($departure, $arrival, $altDate, $passengers);
                        if ($count > 0) {
                            $alternatives['dates'][] = [
                                'date' => $altDate,
                                'count' => $count,
                                'formatted' => date('d/m/Y', strtotime($altDate)),
                                'label' => $this->getDateLabel($altDate, $date)
                            ];
                        }
                    }
                }
            }

            // 2. Alternatives de villes (villes proches ou populaires)
            if (!empty($departure) || !empty($arrival)) {
                $villesAlternatives = $this->getVillesAlternatives($departure, $arrival);
                
                foreach ($villesAlternatives as $ville) {
                    $altDeparture = !empty($departure) ? $ville : $departure;
                    $altArrival = !empty($arrival) ? $ville : $arrival;
                    
                    if ($altDeparture !== $departure || $altArrival !== $arrival) {
                        $count = $this->countSearchResults($altDeparture, $altArrival, $date, $passengers);
                        if ($count > 0) {
                            $alternatives['villes'][] = [
                                'departure' => $altDeparture,
                                'arrival' => $altArrival,
                                'count' => $count,
                                'label' => $altDeparture . ' → ' . $altArrival
                            ];
                        }
                    }
                }
            }

            return $alternatives;

        } catch (Exception $e) {
            error_log("Erreur getAlternatives: " . $e->getMessage());
            return $alternatives;
        }
    }

    /**
     * Obtenir des villes alternatives populaires
     */
    private function getVillesAlternatives($departure, $arrival) {
        try {
            if (!$this->pdo) {
                return [];
            }

            // Villes populaires dans la BDD
            $sql = "SELECT ville_depart as ville, COUNT(*) as count
                    FROM covoiturage 
                    WHERE date_depart > NOW()
                    GROUP BY ville_depart
                    UNION
                    SELECT ville_arrivee as ville, COUNT(*) as count
                    FROM covoiturage 
                    WHERE date_depart > NOW()
                    GROUP BY ville_arrivee
                    ORDER BY count DESC
                    LIMIT 8";

            $stmt = $this->pdo->query($sql);
            $villes = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // Filtrer pour exclure les villes déjà sélectionnées
            return array_filter($villes, function($ville) use ($departure, $arrival) {
                return $ville !== $departure && $ville !== $arrival;
            });

        } catch (Exception $e) {
            error_log("Erreur getVillesAlternatives: " . $e->getMessage());
            return ['Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice', 'Bordeaux'];
        }
    }

    /**
     * Obtenir le libellé d'une date alternative
     */
    private function getDateLabel($altDate, $originalDate) {
        $diff = (strtotime($altDate) - strtotime($originalDate)) / (24 * 3600);
        
        if ($diff == -1) return 'Veille';
        if ($diff == 1) return 'Lendemain';
        if ($diff == 2) return '+2 jours';
        if ($diff == 7) return '+1 semaine';
        if ($diff == -7) return '-1 semaine';
        
        return ($diff > 0 ? '+' : '') . $diff . ' jours';
    }
}
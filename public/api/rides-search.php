<?php
// public/api/rides-search.php - API pour recherche de trajets réels
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Configuration base de données
$host = 'localhost';
$dbname = 'ecoride_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion base de données']);
    exit();
}

// Récupérer les paramètres de recherche
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = intval($_GET['passengers'] ?? 1);

try {
    // Requête de base
    $sql = "
        SELECT 
            c.id_covoiturage,
            c.ville_depart,
            c.ville_arrivee,
            c.lieu_depart,
            c.lieu_arrivee,
            c.date_depart,
            c.heure_depart,
            c.date_arrivee,
            c.heure_arrivee,
            c.nb_places_disponibles,
            c.prix_par_personne,
            c.duree_estimee,
            c.distance_km,
            c.commentaire,
            c.statut,
            
            u.nom,
            u.prenom,
            u.pseudo,
            u.note_moyenne,
            u.nb_avis_recus,
            
            v.modele,
            v.energie,
            v.couleur,
            v.nb_places,
            
            m.libelle as marque
            
        FROM covoiturage c
        JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
        JOIN vehicule v ON c.id_vehicule = v.id_vehicule
        JOIN marque m ON v.id_marque = m.id_marque
        WHERE c.statut = 'planifie'
        AND c.date_depart >= CURDATE()
        AND c.nb_places_disponibles >= :passengers
    ";
    
    $params = [':passengers' => $passengers];
    
    // Filtres optionnels
    if (!empty($departure)) {
        $sql .= " AND c.ville_depart LIKE :departure";
        $params[':departure'] = '%' . $departure . '%';
    }
    
    if (!empty($arrival)) {
        $sql .= " AND c.ville_arrivee LIKE :arrival";
        $params[':arrival'] = '%' . $arrival . '%';
    }
    
    if (!empty($date)) {
        $sql .= " AND c.date_depart = :date";
        $params[':date'] = $date;
    }
    
    // Tri par date et prix
    $sql .= " ORDER BY c.date_depart ASC, c.prix_par_personne ASC LIMIT 20";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatage des données pour le frontend
    $formattedRides = [];
    foreach ($rides as $ride) {
        $formattedRides[] = [
            'id' => $ride['id_covoiturage'],
            'departure' => $ride['ville_depart'],
            'arrival' => $ride['ville_arrivee'],
            'departureAddress' => $ride['lieu_depart'],
            'arrivalAddress' => $ride['lieu_arrivee'],
            'date' => $ride['date_depart'],
            'time' => substr($ride['heure_depart'], 0, 5), // Format HH:MM
            'endDate' => $ride['date_arrivee'],
            'endTime' => substr($ride['heure_arrivee'], 0, 5),
            'price' => round(floatval($ride['prix_par_personne'])),
            'seats' => intval($ride['nb_places_disponibles']),
            'duration' => $ride['duree_estimee'] ? substr($ride['duree_estimee'], 0, 5) : 'Non précisée',
            'distance' => $ride['distance_km'],
            'description' => $ride['commentaire'],
            'status' => $ride['statut'],
            
            // Conducteur
            'driver' => [
                'name' => $ride['prenom'] . ' ' . substr($ride['nom'], 0, 1) . '.',
                'pseudo' => $ride['pseudo'],
                'rating' => floatval($ride['note_moyenne']),
                'reviews' => intval($ride['nb_avis_recus'])
            ],
            
            // Véhicule
            'vehicle' => [
                'brand' => $ride['marque'],
                'model' => $ride['modele'],
                'color' => $ride['couleur'],
                'energy' => $ride['energie'],
                'seats' => intval($ride['nb_places']),
                'eco' => in_array($ride['energie'], ['electrique'])
            ]
        ];
    }
    
    // Statistiques de recherche
    $totalStmt = $pdo->query("SELECT COUNT(*) FROM covoiturage WHERE statut = 'planifie' AND date_depart >= CURDATE()");
    $totalRides = $totalStmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'data' => $formattedRides,
        'count' => count($formattedRides),
        'total_available' => intval($totalRides),
        'search_params' => [
            'departure' => $departure,
            'arrival' => $arrival,
            'date' => $date,
            'passengers' => $passengers
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la recherche',
        'message' => $e->getMessage()
    ]);
}
?>
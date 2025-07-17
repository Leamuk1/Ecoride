<?php
// public/api/nearby-cities.php - API pour trouver les villes proches
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
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

$city = $_GET['city'] ?? '';

if (empty($city)) {
    echo json_encode(['success' => false, 'error' => 'Nom de ville requis']);
    exit();
}

try {
    // Méthode 1 : Chercher les villes dans la même région (basé sur les trajets existants)
    $stmt = $pdo->prepare("
        SELECT DISTINCT 
            CASE 
                WHEN ville_depart = :city THEN ville_arrivee 
                ELSE ville_depart 
            END as ville_proche,
            COUNT(*) as nb_trajets,
            AVG(distance_km) as distance_moyenne
        FROM covoiturage 
        WHERE (ville_depart = :city OR ville_arrivee = :city)
        AND statut IN ('planifie', 'en_cours', 'termine')
        GROUP BY ville_proche
        HAVING ville_proche != :city
        ORDER BY nb_trajets DESC, distance_moyenne ASC
        LIMIT 10
    ");
    
    $stmt->execute([':city' => $city]);
    $directConnections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Méthode 2 : Chercher les villes populaires dans les trajets similaires
    $stmt2 = $pdo->prepare("
        SELECT DISTINCT 
            c2.ville_depart as ville_proche,
            COUNT(*) as popularite,
            AVG(c2.distance_km) as distance_moyenne
        FROM covoiturage c1
        JOIN covoiturage c2 ON (
            (c1.ville_arrivee = c2.ville_arrivee AND c1.ville_depart != c2.ville_depart)
            OR (c1.ville_depart = c2.ville_depart AND c1.ville_arrivee != c2.ville_arrivee)
        )
        WHERE c1.ville_depart = :city OR c1.ville_arrivee = :city
        AND c2.ville_depart != :city
        AND c2.statut IN ('planifie', 'en_cours', 'termine')
        GROUP BY c2.ville_depart
        ORDER BY popularite DESC, distance_moyenne ASC
        LIMIT 8
    ");
    
    $stmt2->execute([':city' => $city]);
    $similarRoutes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    // Méthode 3 : Chercher les destinations populaires
    $stmt3 = $pdo->prepare("
        SELECT DISTINCT 
            c2.ville_arrivee as ville_proche,
            COUNT(*) as popularite,
            AVG(c2.distance_km) as distance_moyenne
        FROM covoiturage c1
        JOIN covoiturage c2 ON (
            c1.ville_depart = c2.ville_depart 
            AND c1.ville_arrivee != c2.ville_arrivee
        )
        WHERE c1.ville_arrivee = :city
        AND c2.ville_arrivee != :city
        AND c2.statut IN ('planifie', 'en_cours', 'termine')
        GROUP BY c2.ville_arrivee
        ORDER BY popularite DESC, distance_moyenne ASC
        LIMIT 6
    ");
    
    $stmt3->execute([':city' => $city]);
    $popularDestinations = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    // Combiner et prioriser les résultats
    $nearbyCities = [];
    $seen = [];
    
    // Priorité 1 : Connexions directes (même région)
    foreach ($directConnections as $city_data) {
        $cityName = ucfirst(strtolower($city_data['ville_proche']));
        if (!in_array($cityName, $seen)) {
            $nearbyCities[] = [
                'name' => $cityName,
                'type' => 'direct',
                'score' => (int)$city_data['nb_trajets'],
                'distance' => $city_data['distance_moyenne'] ? round($city_data['distance_moyenne']) : null,
                'description' => 'Trajets directs disponibles'
            ];
            $seen[] = $cityName;
        }
    }
    
    // Priorité 2 : Routes similaires
    foreach ($similarRoutes as $city_data) {
        $cityName = ucfirst(strtolower($city_data['ville_proche']));
        if (!in_array($cityName, $seen) && count($nearbyCities) < 12) {
            $nearbyCities[] = [
                'name' => $cityName,
                'type' => 'similar',
                'score' => (int)$city_data['popularite'],
                'distance' => $city_data['distance_moyenne'] ? round($city_data['distance_moyenne']) : null,
                'description' => 'Routes similaires'
            ];
            $seen[] = $cityName;
        }
    }
    
    // Priorité 3 : Destinations populaires
    foreach ($popularDestinations as $city_data) {
        $cityName = ucfirst(strtolower($city_data['ville_proche']));
        if (!in_array($cityName, $seen) && count($nearbyCities) < 15) {
            $nearbyCities[] = [
                'name' => $cityName,
                'type' => 'destination',
                'score' => (int)$city_data['popularite'],
                'distance' => $city_data['distance_moyenne'] ? round($city_data['distance_moyenne']) : null,
                'description' => 'Destination populaire'
            ];
            $seen[] = $cityName;
        }
    }
    
    // Statistiques additionnelles
    $totalStmt = $pdo->prepare("
        SELECT COUNT(*) as total_trajets 
        FROM covoiturage 
        WHERE (ville_depart = :city OR ville_arrivee = :city)
        AND statut IN ('planifie', 'en_cours', 'termine')
    ");
    $totalStmt->execute([':city' => $city]);
    $totalTrajets = $totalStmt->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'city' => $city,
        'nearby_cities' => $nearbyCities,
        'total_found' => count($nearbyCities),
        'city_stats' => [
            'total_trajets' => (int)$totalTrajets,
            'has_direct_connections' => count($directConnections) > 0
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur lors de la recherche des villes proches',
        'debug' => $e->getMessage()
    ]);
}
?>
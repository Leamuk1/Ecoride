<?php
/**
 * Point d'entrée principal de l'application EcoRide
 * Gère le routing et l'initialisation de l'application
 */

// Démarrer la session AVANT d'inclure config.php
session_start();

// Configuration des erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Inclure la configuration
require_once '../app/config/config.php';

// Récupérer l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Supprimer le dossier de base si nécessaire
$base_path = '/ecoride/public';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

// Supprimer les slashes en début et fin
$path = trim($path, '/');

// Si l'URL contient des paramètres, les extraire
$path_parts = explode('/', $path);
$route = $path_parts[0] ?? '';

// Debug mode (à désactiver en production)
if (isset($_GET['debug'])) {
    echo "<pre>";
    echo "Request URI: " . $request_uri . "\n";
    echo "Path: " . $path . "\n";
    echo "Route: " . $route . "\n";
    echo "Path parts: " . print_r($path_parts, true);
    echo "</pre>";
}

try {
    // Router principal
    switch ($route) {
        
        // ================================================
        // PAGE D'ACCUEIL
        // ================================================
        case '':
        case 'home':
            require_once '../app/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
            
        // ================================================
        // TESTS (uniquement en développement)
        // ================================================
        case 'test-db':
            if (defined('APP_ENV') && APP_ENV === 'development') {
                echo "<h2> Test de connexion base de données</h2>";
                
                try {
                    $db = new PDO(
                        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                        DB_USER,
                        DB_PASS,
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    
                    echo "<p style='color: green;'> Connexion MySQL réussie !</p>";
                    echo "<p>Base de données : " . DB_NAME . "</p>";
                    echo "<p>Utilisateur : " . DB_USER . "</p>";
                    
                    // Test simple
                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM utilisateur");
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<p>Nombre d'utilisateurs : " . $result['count'] . "</p>";
                    
                    // Test covoiturages
                    $stmt = $db->prepare("SELECT COUNT(*) as count FROM covoiturage");
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo "<p>Nombre de covoiturages : " . $result['count'] . "</p>";
                    
                } catch (Exception $e) {
                    echo "<p style='color: red;'> Erreur : " . $e->getMessage() . "</p>";
                }
            } else {
                throw new Exception("Page non trouvée");
            }
            break;
            
        case 'test-final':
            if (defined('APP_ENV') && APP_ENV === 'development') {
                include 'test-final.php';
            } else {
                throw new Exception("Page non trouvée");
            }
            break;
            
        // ================================================
        // ROUTES FUTURES (à implémenter plus tard)
        // ================================================
        case 'rides':
            // TODO: Créer RideController
            echo "<h1>Page Covoiturages</h1>";
            echo "<p>En cours de développement...</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
            
        case 'login':
            // TODO: Créer AuthController
            echo "<h1>Page Connexion</h1>";
            echo "<p>En cours de développement...</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
            
        case 'register':
            // TODO: Créer AuthController
            echo "<h1>Page Inscription</h1>";
            echo "<p>En cours de développement...</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
            
        case 'about':
            // TODO: Créer PageController
            echo "<h1>À propos d'EcoRide</h1>";
            echo "<p>En cours de développement...</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
            
        case 'contact':
            // TODO: Créer PageController
            echo "<h1>Contact</h1>";
            echo "<p>En cours de développement...</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
            
        // ================================================
        // GESTION DES ERREURS 404
        // ================================================
        default:
            // Page 404
            http_response_code(404);
            $error_code = '404';
            $error_message = "La page que vous recherchez n'existe pas ou a été déplacée.";
            include '../app/views/layouts/error.php';
            break;
    }
    
} catch (Exception $e) {
    // Gestion globale des erreurs
    http_response_code(500);
    
    if (defined('APP_ENV') && APP_ENV === 'development') {
        // Affichage détaillé en développement
        echo "<h1> Erreur de l'application</h1>";
        echo "<p><strong>Message :</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p><strong>Fichier :</strong> " . $e->getFile() . "</p>";
        echo "<p><strong>Ligne :</strong> " . $e->getLine() . "</p>";
        echo "<h3>Stack trace :</h3>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "<hr>";
        echo "<a href='/'>← Retour à l'accueil</a>";
    } else {
        // Message générique en production
        $error_code = '500';
        $error_message = "Nous rencontrons actuellement des difficultés techniques. Veuillez réessayer plus tard.";
        include '../app/views/layouts/error.php';
    }
    
    // Log l'erreur
    error_log("EcoRide Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
}
?>
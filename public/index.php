<?php
// Point d'entrée principal EcoRide
session_start();

// Configuration
require_once '../app/config/config.php';

// Autoloader simple pour charger les classes automatiquement
spl_autoload_register(function($class) {
    $paths = [
        '../app/controllers/',
        '../app/models/',
        '../app/config/'
    ];
    
    foreach($paths as $path) {
        $file = $path . $class . '.php';
        if(file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Router simple - analyse l'URL demandée
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Supprimer les paramètres GET de l'URL pour le routing
$path = strtok($path, '?');

// Routage des pages
switch($path) {
    case '/':
    case '/home':
        echo "<h1>🏠 Page d'accueil EcoRide</h1>";
        echo "<p>✅ Le routing fonctionne !</p>";
        echo "<nav>";
        echo "<a href='/rides'>🚗 Voir les covoiturages</a> | ";
        echo "<a href='/login'>🔑 Connexion</a> | ";
        echo "<a href='/register'>📝 Inscription</a>";
        echo "</nav>";
        break;
    
    case '/rides':
        echo "<h1>🚗 Liste des covoiturages</h1>";
        echo "<p>✅ Page covoiturages accessible !</p>";
        echo "<a href='/'>← Retour accueil</a>";
        break;
    
    case '/login':
        echo "<h1>🔑 Connexion</h1>";
        echo "<p>✅ Page connexion accessible !</p>";
        echo "<a href='/'>← Retour accueil</a>";
        break;
    
    case '/register':
        echo "<h1>📝 Inscription</h1>";
        echo "<p>✅ Page inscription accessible !</p>";
        echo "<a href='/'>← Retour accueil</a>";
        break;
    
    case '/test-db.php':
        // Rediriger vers notre test de base de données
        require_once 'test-db.php';
        break;
    
    default:
        // Page 404
        http_response_code(404);
        echo "<h1>❌ Page non trouvée</h1>";
        echo "<p>La page demandée n'existe pas.</p>";
        echo "<a href='/'>← Retour accueil</a>";
        break;
}

// Debug info (à supprimer en production)
if(isset($_GET['debug'])) {
    echo "<hr><h3>🐛 Debug Info :</h3>";
    echo "<p><strong>URL demandée :</strong> " . htmlspecialchars($request) . "</p>";
    echo "<p><strong>Path analysé :</strong> " . htmlspecialchars($path) . "</p>";
    echo "<p><strong>Method :</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
}
?>
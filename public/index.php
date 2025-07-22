<?php
/**
 * Point d'entrée principal de l'application EcoRide
 */

session_start();

// Configuration des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Charger la configuration
require_once '../app/config/config.php';
require_once '../app/controllers/AuthController.php';

// Générer token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Récupérer l'URL
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Supprimer le dossier de base
$base_path = '/ecoride/public';
if (strpos($path, $base_path) === 0) {
    $path = substr($path, strlen($base_path));
}

$cleanPath = trim($path, '/');

// ============================================
// TRAITEMENT DES REQUÊTES POST
// ============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    
    switch ($cleanPath) {
        case 'auth/register':
            $authController->register();
            break;
            
        case 'auth/login':
            $authController->login();
            break;
            
        // ============================================
        // ROUTES US6 - SYSTÈME DE RÉSERVATION
        // ============================================
        case 'reservation/create':
            require_once '../app/controllers/ReservationController.php';
            $reservationController = new ReservationController();
            $reservationController->createReservation();
            break;
            
        case 'reservation/cancel':
            require_once '../app/controllers/ReservationController.php';
            $reservationController = new ReservationController();
            $reservationController->cancelReservation();
            break;
            
        // ============================================
        // NOUVELLES ROUTES US8 - ESPACE UTILISATEUR
        // ============================================
        case 'profile/update-role':
            if (!AuthController::isLoggedIn()) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['success' => false, 'message' => 'Non connecté']);
                exit;
            }
            
            require_once '../app/controllers/ProfileController.php';
            $profileController = new ProfileController();
            $profileController->updateRole();
            break;

        case 'profile/add-vehicle':
            if (!AuthController::isLoggedIn()) {
                header('Location: /ecoride/public/auth/login');
                exit;
            }
            
            require_once '../app/controllers/ProfileController.php';
            $profileController = new ProfileController();
            $profileController->addVehicle();
            break;

        case 'profile/update-preferences':
            if (!AuthController::isLoggedIn()) {
                header('HTTP/1.1 403 Forbidden');
                echo json_encode(['success' => false, 'message' => 'Non connecté']);
                exit;
            }
            
            require_once '../app/controllers/ProfileController.php';
            $profileController = new ProfileController();
            $profileController->updatePreferences();
            break;
    }
}

// ============================================
// ROUTER PRINCIPAL (GET)
// ============================================
switch ($cleanPath) {
    
    // ================================================
    // PAGE D'ACCUEIL - AVEC HOMECONTROLLER
    // ================================================
    case '':
    case 'home':
        require_once '../app/controllers/HomeController.php';
        $homeController = new HomeController();
        $homeController->index();
        break;
        
    // ================================================
    // ROUTES AUTHENTIFICATION
    // ================================================
    case 'auth/register':
        $title = "Inscription - EcoRide";
        
        // Rediriger si déjà connecté
        if (AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/dashboard');
            exit;
        }
        
        include '../app/views/layouts/header.php';
        include '../app/views/auth/register.php';
        include '../app/views/layouts/footer.php';
        break;
        
    case 'auth/login':
    case 'login':
        $title = "Connexion - EcoRide";
        
        // Rediriger si déjà connecté
        if (AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/dashboard');
            exit;
        }
        
        include '../app/views/layouts/header.php';
        include '../app/views/auth/login.php';
        include '../app/views/layouts/footer.php';
        break;

    case 'auth/logout':
    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;
        
    case 'dashboard':
        $title = "Tableau de bord - EcoRide";
        
        // Vérifier si connecté
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        include '../app/views/layouts/header.php';
        include '../app/views/user/dashboard.php';
        include '../app/views/layouts/footer.php';
        break;

    // ============================================
    // ROUTES US6 - RÉSERVATIONS
    // ============================================
    case 'mes-reservations':
        $title = "Mes réservations - EcoRide";
        
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        require_once '../app/controllers/ReservationController.php';
        $reservationController = new ReservationController();
        $reservations = $reservationController->getUserReservations($_SESSION['user_id']);
        
        include '../app/views/layouts/header.php';
        echo "<h1>Mes réservations (page en cours de création)</h1>";
        include '../app/views/layouts/footer.php';
        break;

    // ============================================
    // NOUVELLES ROUTES US8 - ESPACE UTILISATEUR
    // ============================================
    case 'profile':
    case 'mon-profil':
        $title = "Mon profil - EcoRide";
        
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        require_once '../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        
        //  CORRECTION: Utiliser la méthode du controller qui gère tout
        $profileController->index();
        break;

    case 'profile/vehicle':
    case 'mon-vehicule':
        $title = "Mon véhicule - EcoRide";
        
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        require_once '../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        
        //  CORRECTION: Utiliser la méthode du controller qui gère tout
        $profileController->vehicle();
        break;

    case 'profile/preferences':
    case 'mes-preferences':
        $title = "Mes préférences - EcoRide";
        
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        require_once '../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        
        //  CORRECTION: Utiliser la méthode du controller qui gère tout
        $profileController->preferences();
        break;

    // ================================================
    // ROUTE UPLOAD PHOTO (existante)
    // ================================================
    case 'upload-photo':
        $title = "Changer ma photo - EcoRide";
        
        if (!AuthController::isLoggedIn()) {
            header('Location: /ecoride/public/auth/login');
            exit;
        }
        
        require_once '../app/controllers/ProfileController.php';
        $profileController = new ProfileController();
        $profileController->uploadPhoto();
        break;
        
    // ================================================
    // US3 + US5 - COVOITURAGES
    // ================================================
    case 'rides':
        require_once '../app/controllers/HomeController.php';
        $homeController = new HomeController();
        
        // Vérifier s'il y a un ID dans l'URL pour US5
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            // Route: /rides?id=123 pour voir le détail (US5)
            $ride_id = (int)$_GET['id'];
            $homeController->rideDetail($ride_id);
        } else {
            // Route normale: /rides pour la liste (US3)
            $homeController->rides();
        }
        break;

    case 'contact':
        require_once '../app/controllers/HomeController.php';
        $homeController = new HomeController();
        $homeController->contact();
        break;
        
    case 'test-db':
        if (defined('APP_ENV') && APP_ENV === 'development') {
            echo "<h2>Test de connexion base de données</h2>";
            
            try {
                $db = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER,
                    DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                
                echo "<p style='color: green;'> Connexion MySQL réussie !</p>";
                echo "<p> Base de données : " . DB_NAME . "</p>";
                
                // Test table utilisateur
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM utilisateur");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p>👥 Nombre d'utilisateurs : " . $result['count'] . "</p>";
                
                // Test table covoiturage
                $stmt = $db->prepare("SELECT COUNT(*) as count FROM covoiturage WHERE statut = 'planifie'");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p> Covoiturages disponibles : " . $result['count'] . "</p>";
                
                // Test dernière activité
                $stmt = $db->prepare("SELECT MAX(date_creation) as last_user FROM utilisateur");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<p>📅 Dernier utilisateur inscrit : " . ($result['last_user'] ?: 'Aucun') . "</p>";
                
            } catch (Exception $e) {
                echo "<p style='color: red;'> Erreur : " . $e->getMessage() . "</p>";
            }
            
            echo "<hr><a href='/ecoride/public/'>← Retour à l'accueil</a>";
        } else {
            http_response_code(404);
            $title = "Page non trouvée - EcoRide";
            include '../app/views/layouts/header.php';
            echo "<div class='container' style='text-align: center; padding: 4rem 0;'>";
            echo "<h1>Page non trouvée (404)</h1>";
            echo "<a href='/ecoride/public/'>← Retour à l'accueil</a>";
            echo "</div>";
            include '../app/views/layouts/footer.php';
        }
        break;
        
    case 'about':
        $title = "À propos - EcoRide";
        include '../app/views/layouts/header.php';
        echo "<div class='container' style='padding: 8rem 2rem 2rem;'>";
        echo "<h1>À propos d'EcoRide</h1>";
        echo "<p>EcoRide est une plateforme de covoiturage écologique.</p>";
        echo "<p>Fonctionnalités actuelles :</p>";
        echo "<ul style='text-align: left; max-width: 400px; margin: 0 auto;'>";
        echo "<li>✅ Inscription et connexion sécurisées</li>";
        echo "<li>✅ Dashboard utilisateur</li>";
        echo "<li>✅ Système de crédits</li>";
        echo "<li>✅ Recherche de covoiturages</li>";
        echo "<li>✅ Page détail des trajets</li>";
        echo "<li>✅ Système de réservation</li>";
        echo "<li>✅ Espace utilisateur (US8 - Étape 1)</li>";
        echo "</ul>";
        echo "<a href='/ecoride/public/'>← Retour à l'accueil</a>";
        echo "</div>";
        include '../app/views/layouts/footer.php';
        break;
        
    default:
        http_response_code(404);
        $title = "Page non trouvée - EcoRide";
        include '../app/views/layouts/header.php';
        echo "<div class='container' style='text-align: center; padding: 4rem 0;'>";
        echo "<h1>Page non trouvée (404)</h1>";
        echo "<p>La page que vous recherchez n'existe pas.</p>";
        echo "<p><strong>Chemin demandé :</strong> " . htmlspecialchars($cleanPath) . "</p>";
        echo "<a href='/ecoride/public/' style='display: inline-block; margin-top: 1rem; padding: 0.75rem 1.5rem; background: var(--eco-green); color: white; text-decoration: none; border-radius: 6px;'>← Retour à l'accueil</a>";
        echo "</div>";
        include '../app/views/layouts/footer.php';
        break;
}
?>
<?php
/**
 * Configuration générale de l'application EcoRide
 * Contient toutes les constantes et paramètres de l'application
 */

// ================================================
// ENVIRONNEMENT
// ================================================
define('APP_ENV', 'development'); // 'development' ou 'production'
define('APP_NAME', 'EcoRide');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://ecoride.local');

// ================================================
// BASE DE DONNÉES
// ================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecoride_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ================================================
// CHEMINS DE L'APPLICATION
// ================================================
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEWS_PATH', APP_PATH . '/views');
define('CONTROLLERS_PATH', APP_PATH . '/controllers');
define('MODELS_PATH', APP_PATH . '/models');

// ================================================
// URLS DE BASE
// ================================================
define('BASE_URL', 'http://ecoride.local');
define('ASSETS_URL', BASE_URL);
define('UPLOAD_URL', BASE_URL . '/uploads');

// ================================================
// SÉCURITÉ
// ================================================
define('SECRET_KEY', 'ecoride_secret_key_2025');
define('SESSION_LIFETIME', 3600 * 24); // 24 heures
define('CSRF_TOKEN_NAME', 'ecoride_token');

// Options de session sécurisées
if (session_status() === PHP_SESSION_NONE) {
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);
}

// ================================================
// PARAMÈTRES ECORIDE
// ================================================

// Système de crédits
define('CREDITS_INITIAL', 20);           // Crédits à l'inscription
define('CREDITS_COMMISSION', 2);         // Commission par transaction
define('CREDITS_MAX_TRANSACTION', 500);  // Maximum par transaction

// Paramètres des covoiturages
define('MAX_PASSENGERS_PER_RIDE', 8);    // Maximum 8 passagers
define('MIN_ADVANCE_BOOKING', 1);        // Minimum 1 heure à l'avance
define('MAX_ADVANCE_BOOKING', 90);       // Maximum 90 jours à l'avance

// Paramètres des fichiers
define('MAX_AVATAR_SIZE', 2 * 1024 * 1024);      // 2MB pour les avatars
define('MAX_VEHICLE_PHOTO_SIZE', 5 * 1024 * 1024); // 5MB pour les photos véhicules
define('ALLOWED_AVATAR_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_VEHICLE_PHOTO_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// ================================================
// PAGINATION
// ================================================
define('RIDES_PER_PAGE', 12);
define('USERS_PER_PAGE', 20);
define('REVIEWS_PER_PAGE', 10);
define('ADMIN_ITEMS_PER_PAGE', 25);

// ================================================
// NOTIFICATIONS
// ================================================
define('EMAIL_FROM', 'noreply@ecoride.fr');
define('EMAIL_FROM_NAME', 'EcoRide');
define('SUPPORT_EMAIL', 'support@ecoride.fr');

// Configuration email (à adapter selon votre service)
define('SMTP_HOST', 'localhost');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_ENCRYPTION', 'tls');

// ================================================
// API ET SERVICES EXTERNES
// ================================================

// Google Maps (optionnel pour plus tard)
define('GOOGLE_MAPS_API_KEY', '');

// Service de géolocalisation pour les distances
define('DISTANCE_API_ENABLED', false);

// Service de notification push (optionnel)
define('PUSH_NOTIFICATIONS_ENABLED', false);

// ================================================
// CACHE ET PERFORMANCE
// ================================================
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 3600); // 1 heure
define('CACHE_PATH', ROOT_PATH . '/cache');

// ================================================
// LOGGING
// ================================================
define('LOG_ENABLED', true);
define('LOG_LEVEL', APP_ENV === 'development' ? 'DEBUG' : 'ERROR');
define('LOG_PATH', ROOT_PATH . '/logs');
define('LOG_MAX_FILES', 30); // Garder 30 jours de logs

// ================================================
// FONCTIONS UTILITAIRES
// ================================================

/**
 * Fonction pour obtenir une URL complète
 */
function url($path = '') {
    $base = '/ecoride/public';
    return $base . '/' . ltrim($path, '/');
}
/**
 * Fonction pour obtenir une URL d'asset
 */
function asset($path) {
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Fonction pour rediriger
 */
function redirect($url, $code = 302) {
    if (strpos($url, 'http') !== 0) {
        $url = url($url);
    }
    header("Location: $url", true, $code);
    exit;
}

/**
 * Fonction pour échapper les données HTML
 */
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Fonction pour générer un token CSRF
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Fonction pour vérifier un token CSRF
 */
function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Fonction pour logger les erreurs
 */
function log_error($message, $context = []) {
    if (LOG_ENABLED) {
        $logFile = LOG_PATH . '/error_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
        $logMessage = "[$timestamp] ERROR: $message$contextStr" . PHP_EOL;
        
        // Créer le dossier de logs s'il n'existe pas
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0755, true);
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Fonction pour logger les infos (développement)
 */
function log_info($message, $context = []) {
    if (LOG_ENABLED && APP_ENV === 'development') {
        $logFile = LOG_PATH . '/info_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | ' . json_encode($context) : '';
        $logMessage = "[$timestamp] INFO: $message$contextStr" . PHP_EOL;
        
        if (!is_dir(LOG_PATH)) {
            mkdir(LOG_PATH, 0755, true);
        }
        
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

/**
 * Fonction pour vérifier si l'utilisateur est connecté
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Fonction pour vérifier le rôle utilisateur
 */
function has_role($role) {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * Fonction pour vérifier les permissions admin
 */
function is_admin() {
    return has_role('administrateur') || has_role('employe');
}

/**
 * Fonction pour formater les crédits
 */
function format_credits($amount) {
    return number_format($amount, 0, ',', ' ') . ' crédits';
}

/**
 * Fonction pour formater les dates
 */
function format_date($date, $format = 'd/m/Y H:i') {
    if (is_string($date)) {
        $date = new DateTime($date);
    }
    return $date->format($format);
}

/**
 * Fonction pour calculer la distance approximative entre deux villes
 */
function estimate_distance($city1, $city2) {
    // Table de distances approximatives (en km)
    $distances = [
        'Paris-Marseille' => 775,
        'Paris-Lyon' => 465,
        'Lyon-Marseille' => 315,
        'Paris-Toulouse' => 680,
        'Lyon-Toulouse' => 360,
        'Marseille-Nice' => 205,
        'Nice-Cannes' => 35,
        'Bordeaux-Toulouse' => 245,
        'Montpellier-Marseille' => 170,
        'Paris-Bordeaux' => 580,
        'Lyon-Montpellier' => 300,
        'Toulouse-Montpellier' => 240
    ];
    
    $route1 = "$city1-$city2";
    $route2 = "$city2-$city1";
    
    if (isset($distances[$route1])) {
        return $distances[$route1];
    } elseif (isset($distances[$route2])) {
        return $distances[$route2];
    }
    
    return null; // Distance inconnue
}

/**
 * Fonction pour générer un slug à partir d'un texte
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    
    return empty($text) ? 'n-a' : $text;
}

// ================================================
// INITIALISATION
// ================================================

// Créer les dossiers nécessaires s'ils n'existent pas
$required_dirs = [UPLOAD_PATH, LOG_PATH, CACHE_PATH];
foreach ($required_dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Configuration des erreurs selon l'environnement
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Configuration de la timezone
date_default_timezone_set('Europe/Paris');

// ================================================
// AUTOLOADER SIMPLE (optionnel)
// ================================================
spl_autoload_register(function ($class_name) {
    $possible_paths = [
        CONTROLLERS_PATH . "/$class_name.php",
        MODELS_PATH . "/$class_name.php",
        APP_PATH . "/lib/$class_name.php"
    ];
    
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            break;
        }
    }
});

// ================================================
// CONSTANTES DE DÉVELOPPEMENT
// ================================================
if (APP_ENV === 'development') {
    define('DEBUG_MODE', true);
    define('SHOW_SQL_ERRORS', true);
} else {
    define('DEBUG_MODE', false);
    define('SHOW_SQL_ERRORS', false);
}

// Log du démarrage de l'application
if (LOG_ENABLED && APP_ENV === 'development') {
    log_info('Application EcoRide démarrée', [
        'url' => $_SERVER['REQUEST_URI'] ?? 'unknown',
        'method' => $_SERVER['REQUEST_METHOD'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ]);
}
?>
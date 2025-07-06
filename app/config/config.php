<?php
// Configuration générale EcoRide

// URL de base
define('BASE_URL', 'http://ecoride.local');

// Informations de l'application
define('APP_NAME', 'EcoRide');
define('APP_VERSION', '1.0.0');
define('APP_DESCRIPTION', 'Plateforme de covoiturage écologique');

// Sécurité
define('CSRF_TOKEN_NAME', 'csrf_token');
define('SESSION_LIFETIME', 3600); // 1 heure en secondes

// Uploads
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Email (à configurer plus tard)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'contact@ecoride.fr');
define('SMTP_PASS', ''); // À définir plus tard

// Environnement
define('ENVIRONMENT', 'development'); // development, production
define('DEBUG', true);

// Crédits système
define('DEFAULT_CREDITS', 20); // Crédits donnés à l'inscription
define('PLATFORM_COMMISSION', 2); // Crédits pris par la plateforme

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Europe/Paris');

// Configuration des sessions sécurisées
if(session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Mettre à 1 en HTTPS
    ini_set('session.use_strict_mode', 1);
}

// Affichage des erreurs selon l'environnement
if(ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>
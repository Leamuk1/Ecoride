<?php
// app/controllers/AuthController.php 
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../app/models/UserModel.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    /**
     * Réponse JSON simple
     */
    private function jsonResponse($success, $message, $redirect = null) {
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json');
        
        $response = ['success' => $success, 'message' => $message];
        if ($redirect) $response['redirect'] = $redirect;
        
        echo json_encode($response);
        exit();
    }
    
    /**
     * Nettoyer les données
     */
    private function cleanInput($data) {
        return htmlspecialchars(trim(stripslashes($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Vérifier tentatives d'inscription
     */
    private function checkAttempts() {
        if (!isset($_SESSION['inscription_attempts'])) {
            $_SESSION['inscription_attempts'] = 0;
            $_SESSION['inscription_time'] = time();
        }
        
        // Reset après 10 minutes
        if (time() - $_SESSION['inscription_time'] > 600) {
            $_SESSION['inscription_attempts'] = 0;
            $_SESSION['inscription_time'] = time();
        }
        
        $_SESSION['inscription_attempts']++;
        
        if ($_SESSION['inscription_attempts'] > 5) {
            $this->jsonResponse(false, 'Trop de tentatives d\'inscription. Attendez 10 minutes.');
        }
    }
    
    /**
     * Valider mot de passe
     */
    private function validatePassword($password) {
        if (strlen($password) < 8) {
            return 'Le mot de passe doit contenir au moins 8 caractères';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return 'Le mot de passe doit contenir au moins une majuscule';
        }
        if (!preg_match('/[0-9]/', $password)) {
            return 'Le mot de passe doit contenir au moins un chiffre';
        }
        
        $faibles = ['password', 'password123', '123456', 'azerty', 'qwerty'];
        if (in_array(strtolower($password), $faibles)) {
            return 'Ce mot de passe est trop commun, choisissez-en un autre';
        }
        
        return null;
    }
    
    /**
     * Inscription
     */
    public function register() {
        // Vérifications de base
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Méthode non autorisée');
        }
        
        // Honeypot anti-bot
        if (!empty($_POST['robot_check'])) {
            $this->jsonResponse(true, 'Compte créé avec succès !', '/ecoride/public/dashboard');
        }
        
        // Limitation des tentatives
        $this->checkAttempts();
        
        // Récupérer et nettoyer les données
        $data = [
            'pseudo' => $this->cleanInput($_POST['pseudo'] ?? ''),
            'email' => $this->cleanInput($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirm' => $_POST['password_confirm'] ?? '',
            'nom' => $this->cleanInput($_POST['nom'] ?? ''),
            'prenom' => $this->cleanInput($_POST['prenom'] ?? ''),
            'telephone' => $this->cleanInput($_POST['telephone'] ?? ''),
            'terms' => isset($_POST['terms'])
        ];
        
        // Validations simples
        if (strlen($data['pseudo']) < 3) {
            $this->jsonResponse(false, 'Le pseudo doit contenir au moins 3 caractères');
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(false, 'Email invalide');
        }
        
        $passwordError = $this->validatePassword($data['password']);
        if ($passwordError) {
            $this->jsonResponse(false, $passwordError);
        }
        
        if ($data['password'] !== $data['password_confirm']) {
            $this->jsonResponse(false, 'Les mots de passe ne correspondent pas');
        }
        
        if (!$data['terms']) {
            $this->jsonResponse(false, 'Vous devez accepter les conditions d\'utilisation');
        }
        
        // Créer le compte
        $result = $this->userModel->register($data);
        
        if ($result['success']) {
            $user = $this->userModel->getUserById($result['user_id']);
            if ($user) {
                $this->createUserSession($user);
            }
            $this->jsonResponse(true, 'Compte créé avec succès !', '/ecoride/public/dashboard');
        }
        
        $this->jsonResponse(false, $result['message']);
    }
    
    /**
     * Connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Méthode non autorisée');
        }
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email)) {
            $this->jsonResponse(false, 'Email requis');
        }
        
        if (empty($password)) {
            $this->jsonResponse(false, 'Mot de passe requis');
        }
        
        $result = $this->userModel->authenticate($email, $password);
        
        if ($result['success']) {
            $this->createUserSession($result['user'], isset($_POST['remember_me']));
            $this->jsonResponse(true, 'Connexion réussie !', '/ecoride/public/dashboard');
        }
        
        $this->jsonResponse(false, $result['message']);
    }
    
    /**
     * Déconnexion
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /ecoride/public/?message=disconnected');
        exit();
    }
    
    /**
     * Créer session utilisateur
     */
    private function createUserSession($user, $rememberMe = false) {
        session_regenerate_id(true);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_pseudo'] = $user['pseudo'] ?? $user['prenom'] ?? 'Utilisateur';
        $_SESSION['user_nom'] = $user['nom'] ?? '';
        $_SESSION['user_prenom'] = $user['prenom'] ?? '';
        $_SESSION['user_credits'] = $user['credit'] ?? 20;
        $_SESSION['user_created'] = $user['date_inscription'] ?? date('Y-m-d');
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        if ($rememberMe) {
            ini_set('session.gc_maxlifetime', 30 * 24 * 3600);
            setcookie(session_name(), session_id(), time() + (30 * 24 * 3600), '/');
        }
    }
    
    /**
     * Méthodes statiques utiles
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
    }
    
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) return null;
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'email' => $_SESSION['user_email'] ?? '',
            'pseudo' => $_SESSION['user_pseudo'] ?? '',
            'nom' => $_SESSION['user_nom'] ?? '',
            'prenom' => $_SESSION['user_prenom'] ?? '',
            'credits' => $_SESSION['user_credits'] ?? 0,
            'created' => $_SESSION['user_created'] ?? '',
        ];
    }
    
    public static function requireLogin($redirectTo = '/ecoride/public/auth/login') {
        if (!self::isLoggedIn()) {
            $currentUrl = $_SERVER['REQUEST_URI'] ?? '';
            header("Location: $redirectTo?redirect=" . urlencode($currentUrl));
            exit();
        }
    }
}
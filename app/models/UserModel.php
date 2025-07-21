<?php
// app/models/UserModel.php - Version simplifiée
class UserModel {
    private $db;
    
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    /**
     * Exécuter une requête (helper simple)
     */
    private function query($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Inscription utilisateur
     */
    public function register($userData) {
        // Vérifier email unique
        if ($this->emailExists($userData['email'])) {
            return ['success' => false, 'message' => "Cet email est déjà utilisé"];
        }
        
        // Vérifier pseudo unique
        if (!empty($userData['pseudo']) && $this->pseudoExists($userData['pseudo'])) {
            return ['success' => false, 'message' => "Ce pseudo est déjà utilisé"];
        }
        
        try {
            $sql = "INSERT INTO utilisateur (pseudo, nom, prenom, email, password, telephone, credit) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $this->query($sql, [
                $userData['pseudo'] ?? null,
                $userData['nom'] ?? '',
                $userData['prenom'] ?? '',
                $userData['email'],
                password_hash($userData['password'], PASSWORD_DEFAULT),
                $userData['telephone'] ?? null,
                20 // 20 crédits par défaut
            ]);
            
            $userId = $this->db->lastInsertId();
            
            return [
                'success' => true,
                'user_id' => $userId,
                'message' => 'Compte créé avec succès'
            ];
            
        } catch (Exception $e) {
            error_log("Erreur inscription: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur lors de la création du compte'];
        }
    }
    
    /**
     * Authentification
     */
    public function authenticate($login, $password) {
        try {
            $sql = "SELECT * FROM utilisateur WHERE (email = ? OR pseudo = ?) AND statut = 'actif' LIMIT 1";
            $stmt = $this->query($sql, [$login, $login]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Mettre à jour dernière connexion
                $this->query("UPDATE utilisateur SET derniere_connexion = NOW() WHERE id_utilisateur = ?", [$user['id_utilisateur']]);
                
                return [
                    'success' => true,
                    'user' => [
                        'id' => $user['id_utilisateur'],
                        'pseudo' => $user['pseudo'],
                        'nom' => $user['nom'],
                        'prenom' => $user['prenom'],
                        'email' => $user['email'],
                        'credit' => $user['credit'],
                        'date_inscription' => $user['date_creation'] ?? date('Y-m-d H:i:s')
                    ]
                ];
            }
            
            return ['success' => false, 'message' => 'Email/pseudo ou mot de passe incorrect'];
            
        } catch (Exception $e) {
            error_log("Erreur authentification: " . $e->getMessage());
            return ['success' => false, 'message' => 'Erreur de connexion'];
        }
    }
    
    /**
     * Vérifier si email existe
     */
    public function emailExists($email) {
        try {
            $stmt = $this->query("SELECT id_utilisateur FROM utilisateur WHERE email = ?", [$email]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Vérifier si pseudo existe
     */
    public function pseudoExists($pseudo) {
        try {
            $stmt = $this->query("SELECT id_utilisateur FROM utilisateur WHERE pseudo = ?", [$pseudo]);
            return $stmt->fetch() !== false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Obtenir un utilisateur par ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->query("SELECT * FROM utilisateur WHERE id_utilisateur = ?", [$id]);
            $user = $stmt->fetch();
            
            if ($user) {
                return [
                    'id' => $user['id_utilisateur'],
                    'pseudo' => $user['pseudo'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'email' => $user['email'],
                    'credit' => $user['credit'],
                    'date_inscription' => $user['date_creation'] ?? date('Y-m-d H:i:s')
                ];
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Erreur getUserById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Mettre à jour les crédits
     */
    public function updateCredit($userId, $newCredit) {
        try {
            $stmt = $this->query("UPDATE utilisateur SET credit = ? WHERE id_utilisateur = ?", [$newCredit, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erreur updateCredit: " . $e->getMessage());
            return false;
        }
    }
}
<?php
// app/models/UserModel.php

class UserModel {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Erreur connexion BDD UserModel: " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }
    
    /**
     * Exécuter une requête SQL (helper method)
     */
    public function executeQuery($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur SQL UserModel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtenir un utilisateur par ID
     */
    public function getUserById($id) {
        try {
            $stmt = $this->executeQuery("SELECT * FROM utilisateur WHERE id = ?", [$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Erreur getUserById: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obtenir un utilisateur par email
     */
    public function getUserByEmail($email) {
        try {
            $stmt = $this->executeQuery("SELECT * FROM utilisateur WHERE email = ?", [$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Erreur getUserByEmail: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Créer un nouvel utilisateur
     */
    public function createUser($data) {
        try {
            $sql = "
                INSERT INTO utilisateur 
                (nom, prenom, email, mot_de_passe, telephone, date_naissance, credits, date_inscription) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
            ";
            
            $stmt = $this->executeQuery($sql, [
                $data['nom'],
                $data['prenom'], 
                $data['email'],
                $data['mot_de_passe'], // Déjà hashé
                $data['telephone'] ?? null,
                $data['date_naissance'] ?? null,
                $data['credits'] ?? 500
            ]);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Erreur createUser: " . $e->getMessage());
            return false;
        }
    }
}
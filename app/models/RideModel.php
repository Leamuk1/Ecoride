<?php
// app/models/RideModel.php

class RideModel {
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
            error_log("Erreur connexion BDD RideModel: " . $e->getMessage());
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
            error_log("Erreur SQL RideModel: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Obtenir tous les covoiturages
     */
    public function getAllRides() {
        try {
            $sql = "
                SELECT c.*, u.prenom, u.nom, v.modele, v.type_vehicule 
                FROM covoiturage c
                INNER JOIN utilisateur u ON c.id_conducteur = u.id
                LEFT JOIN vehicule v ON c.id_vehicule = v.id
                WHERE c.statut = 'actif'
                ORDER BY c.date_depart ASC
            ";
            
            $stmt = $this->executeQuery($sql);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Erreur getAllRides: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtenir un covoiturage par ID
     */
    public function getRideById($id) {
        try {
            $sql = "
                SELECT c.*, u.prenom, u.nom, v.modele, v.type_vehicule 
                FROM covoiturage c
                INNER JOIN utilisateur u ON c.id_conducteur = u.id
                LEFT JOIN vehicule v ON c.id_vehicule = v.id
                WHERE c.id = ?
            ";
            
            $stmt = $this->executeQuery($sql, [$id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Erreur getRideById: " . $e->getMessage());
            return null;
        }
    }
}
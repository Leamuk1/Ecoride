-- ================================================
-- SCRIPT DE CRÉATION DES TABLES ECORIDE
-- Base de données : ecoride_db
-- Version : 1.0 - Schema personnalisé avec système crédits
-- Date : Juillet 2025
-- ================================================

CREATE DATABASE IF NOT EXISTS ecoride_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecoride_db;

-- ================================================
-- NETTOYAGE DES TABLES EXISTANTES
-- ================================================
DROP TABLE IF EXISTS transaction_credit;
DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS participation;
DROP TABLE IF EXISTS preference;
DROP TABLE IF EXISTS covoiturage;
DROP TABLE IF EXISTS vehicule;
DROP TABLE IF EXISTS utilisateur_role;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS marque;

-- ================================================
-- TABLE utilisateur
-- ================================================
CREATE TABLE utilisateur(
   id_utilisateur INT AUTO_INCREMENT,
   nom VARCHAR(100) NOT NULL,
   prenom VARCHAR(100) NOT NULL,
   email VARCHAR(150) NOT NULL UNIQUE,
   password VARCHAR(255) NOT NULL, 
   telephone VARCHAR(20),
   adresse TEXT,
   date_naissance DATE NOT NULL,
   photo VARCHAR(255),
   pseudo VARCHAR(50) UNIQUE,
   credit INT DEFAULT 20,  -- 20 crédits à l'inscription
   statut ENUM('actif','suspendu','inactif') DEFAULT 'actif',  -- AJOUT
   note_moyenne DECIMAL(3,2) DEFAULT 0.00,  -- AJOUT
   nb_avis_recus INT DEFAULT 0,  -- AJOUT
   date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY(id_utilisateur),
   INDEX idx_email (email),
   INDEX idx_pseudo (pseudo),
   INDEX idx_statut (statut)  -- AJOUT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE RÔLES
-- ================================================
CREATE TABLE role(
   id_role INT AUTO_INCREMENT,
   nom_role ENUM('chauffeur', 'passager', 'employe', 'administrateur') NOT NULL,
   description VARCHAR(255),
   PRIMARY KEY(id_role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE LIAISON UTILISATEUR-RÔLE
-- ================================================
CREATE TABLE utilisateur_role(
   id_utilisateur INT,
   id_role INT,
   PRIMARY KEY(id_utilisateur, id_role),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_role) REFERENCES role(id_role) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE MARQUES
-- ================================================
CREATE TABLE marque(
   id_marque INT AUTO_INCREMENT,
   libelle VARCHAR(100) NOT NULL UNIQUE,
   PRIMARY KEY(id_marque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE VÉHICULES
-- ================================================
CREATE TABLE vehicule(
   id_vehicule INT AUTO_INCREMENT,
   modele VARCHAR(100) NOT NULL,
   immatriculation VARCHAR(15) NOT NULL UNIQUE,
   energie ENUM('essence', 'diesel', 'electrique', 'hybride') NOT NULL,
   couleur VARCHAR(50) NOT NULL,
   date_premiere_immatriculation DATE NOT NULL,
   nb_places INT NOT NULL CHECK (nb_places BETWEEN 1 AND 8),
   id_marque INT NOT NULL,
   id_utilisateur INT NOT NULL,
   PRIMARY KEY(id_vehicule),
   FOREIGN KEY(id_marque) REFERENCES marque(id_marque),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
   INDEX idx_immatriculation (immatriculation),
   INDEX idx_energie (energie),
   INDEX idx_utilisateur (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE PRÉFÉRENCES
-- ================================================
CREATE TABLE preference(
   id_preference INT AUTO_INCREMENT,
   id_utilisateur INT NOT NULL,
   fumeur BOOLEAN DEFAULT FALSE,
   animal BOOLEAN DEFAULT FALSE,
   musique BOOLEAN DEFAULT TRUE,
   discussion BOOLEAN DEFAULT TRUE,
   autres_preferences TEXT,
   PRIMARY KEY(id_preference),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE COVOITURAGES
-- ================================================
CREATE TABLE covoiturage(
   id_covoiturage INT AUTO_INCREMENT,
   date_depart DATE NOT NULL,
   heure_depart TIME NOT NULL,
   lieu_depart VARCHAR(255) NOT NULL,
   ville_depart VARCHAR(100) NOT NULL,
   date_arrivee DATE NOT NULL,
   heure_arrivee TIME NOT NULL,
   lieu_arrivee VARCHAR(255) NOT NULL,
   ville_arrivee VARCHAR(100) NOT NULL,
   statut ENUM('planifie', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
   nb_places_total INT NOT NULL,
   nb_places_disponibles INT NOT NULL,
   prix_par_personne DECIMAL(10,2) NOT NULL,
   duree_estimee TIME,
   distance_km INT,
   commentaire TEXT,
   id_vehicule INT NOT NULL,
   id_conducteur INT NOT NULL,
   date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   PRIMARY KEY(id_covoiturage),
   FOREIGN KEY(id_vehicule) REFERENCES vehicule(id_vehicule),
   FOREIGN KEY(id_conducteur) REFERENCES utilisateur(id_utilisateur),
   INDEX idx_date_depart (date_depart),
   INDEX idx_ville_depart (ville_depart),
   INDEX idx_ville_arrivee (ville_arrivee),
   INDEX idx_statut (statut),
   INDEX idx_conducteur (id_conducteur),
   INDEX idx_places_dispo (nb_places_disponibles),
   CHECK (nb_places_disponibles <= nb_places_total),
   CHECK (nb_places_disponibles >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE PARTICIPATIONS
-- ================================================
CREATE TABLE participation(
   id_participation INT AUTO_INCREMENT,
   id_utilisateur INT NOT NULL,
   id_covoiturage INT NOT NULL,
   nb_places_reservees INT DEFAULT 1,
   statut ENUM('en_attente', 'confirme', 'annule', 'termine') DEFAULT 'en_attente',
   credit_utilise INT NOT NULL,
   date_participation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   date_confirmation TIMESTAMP NULL,
   commentaire_participant TEXT,
   PRIMARY KEY(id_participation),
   FOREIGN KEY(id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
   FOREIGN KEY(id_covoiturage) REFERENCES covoiturage(id_covoiturage) ON DELETE CASCADE,
   UNIQUE KEY unique_participation (id_utilisateur, id_covoiturage),
   INDEX idx_statut_participation (statut),
   INDEX idx_utilisateur (id_utilisateur),
   INDEX idx_covoiturage (id_covoiturage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE AVIS
-- ================================================
CREATE TABLE avis(
   id_avis INT AUTO_INCREMENT,
   commentaire TEXT,
   note TINYINT NOT NULL CHECK (note BETWEEN 1 AND 5),
   statut ENUM('en_attente', 'valide', 'rejete') DEFAULT 'en_attente',
   date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   date_validation TIMESTAMP NULL,
   id_evaluateur INT NOT NULL,  -- CORRECTION: Virgule ajoutée
   id_evalue INT NOT NULL, 
   id_covoiturage INT NOT NULL, 
   id_validateur INT NULL,
   PRIMARY KEY(id_avis),
   FOREIGN KEY(id_evaluateur) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(id_evalue) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(id_covoiturage) REFERENCES covoiturage(id_covoiturage),
   FOREIGN KEY(id_validateur) REFERENCES utilisateur(id_utilisateur),
   UNIQUE KEY unique_avis (id_evaluateur, id_evalue, id_covoiturage),
   INDEX idx_evalue (id_evalue),
   INDEX idx_statut_avis (statut),
   INDEX idx_evaluateur (id_evaluateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABLE TRANSACTIONS CRÉDITS
-- ================================================
CREATE TABLE transaction_credit(
   id_transaction INT AUTO_INCREMENT,
   passager_id INT NOT NULL,
   chauffeur_id INT NOT NULL,
   covoiturage_id INT NOT NULL,
   montant_total DECIMAL(10,2) NOT NULL,
   commission DECIMAL(10,2) NOT NULL DEFAULT 2.00,
   montant_chauffeur DECIMAL(10,2) NOT NULL,
   type_transaction ENUM('paiement', 'remboursement', 'bonus') DEFAULT 'paiement',
   date_transaction TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   commentaire VARCHAR(255) DEFAULT NULL,
   PRIMARY KEY(id_transaction),
   FOREIGN KEY(passager_id) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(chauffeur_id) REFERENCES utilisateur(id_utilisateur),
   FOREIGN KEY(covoiturage_id) REFERENCES covoiturage(id_covoiturage),
   INDEX idx_passager (passager_id),
   INDEX idx_chauffeur (chauffeur_id),
   INDEX idx_date (date_transaction)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TRIGGERS POUR AUTOMATISATION
-- ================================================

-- Trigger : Mise à jour automatique de la note moyenne
DELIMITER $$
CREATE TRIGGER update_note_moyenne_after_avis
AFTER INSERT ON avis
FOR EACH ROW
BEGIN
    UPDATE utilisateur 
    SET 
        note_moyenne = (
            SELECT AVG(note) 
            FROM avis 
            WHERE id_evalue = NEW.id_evalue 
            AND statut = 'valide'
        ),
        nb_avis_recus = (
            SELECT COUNT(*) 
            FROM avis 
            WHERE id_evalue = NEW.id_evalue 
            AND statut = 'valide'
        )
    WHERE id_utilisateur = NEW.id_evalue;
END$$

-- Trigger : Vérification des places disponibles avant participation
CREATE TRIGGER check_places_before_participation
BEFORE INSERT ON participation
FOR EACH ROW
BEGIN
    DECLARE places_dispo INT;
    
    SELECT nb_places_disponibles INTO places_dispo
    FROM covoiturage 
    WHERE id_covoiturage = NEW.id_covoiturage;
    
    IF places_dispo < NEW.nb_places_reservees THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Pas assez de places disponibles pour ce covoiturage';
    END IF;
END$$

DELIMITER ;

-- ================================================
-- INDEX OPTIMISÉS POUR LES RECHERCHES
-- ================================================
CREATE INDEX idx_recherche_covoiturage ON covoiturage 
(ville_depart, ville_arrivee, date_depart, nb_places_disponibles);

CREATE INDEX idx_historique_utilisateur ON covoiturage 
(id_conducteur, date_depart DESC);

CREATE INDEX idx_vehicule_ecologique ON vehicule (energie);

-- ================================================
-- COMMENTAIRES DES TABLES
-- ================================================
ALTER TABLE utilisateur COMMENT = 'Table des utilisateur avec système de crédits';
ALTER TABLE role COMMENT = 'Table des rôles utilisateur';
ALTER TABLE utilisateur_role COMMENT = 'Table de liaison utilisateur-rôle';
ALTER TABLE marque COMMENT = 'Table des marques de véhicules';
ALTER TABLE vehicule COMMENT = 'Table des véhicules avec type énergie';
ALTER TABLE preference COMMENT = 'Table des préférences utilisateur';
ALTER TABLE covoiturage COMMENT = 'Table des trajets avec prix en crédits';
ALTER TABLE participation COMMENT = 'Table des participations avec paiement crédits';
ALTER TABLE avis COMMENT = 'Table des avis et évaluations';
ALTER TABLE transaction_credit COMMENT = 'Historique des transactions en crédits';

-- ================================================
-- FINALISATION
-- ================================================
COMMIT;

-- Message de confirmation
SELECT 'TABLES CRÉÉES AVEC SUCCÈS !' as 'Statut';
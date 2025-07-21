-- ================================================
-- TABLES AUTHENTIFICATION ECORIDE
-- Fichier : database/auth_tables.sql
-- Version : 1.0 - Système d'authentification complet
-- Date : Juillet 2025
-- ================================================

-- Utiliser la base de données EcoRide
USE ecoride_db;

-- ================================================
-- TABLE : roles
-- Gestion des rôles utilisateur
-- ================================================

CREATE TABLE IF NOT EXISTS roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    nom_role VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion des rôles par défaut
INSERT INTO roles (nom_role, description, permissions) VALUES
('utilisateur', 'Utilisateur standard de la plateforme', '["rechercher", "reserver", "proposer"]'),
('conducteur', 'Utilisateur pouvant proposer des trajets', '["rechercher", "reserver", "proposer", "gerer_trajets"]'),
('moderateur', 'Modérateur de la plateforme', '["rechercher", "reserver", "proposer", "gerer_trajets", "moderer"]'),
('admin', 'Administrateur de la plateforme', '["all"]');

-- ================================================
-- TABLE : utilisateur (mise à jour/création)
-- Table principale des utilisateur
-- ================================================

CREATE TABLE IF NOT EXISTS utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    telephone VARCHAR(20),
    date_naissance DATE,
    genre ENUM('homme', 'femme', 'autre'),
    bio TEXT,
    photo_profil VARCHAR(255),
    id_role INT DEFAULT 1,
    credits DECIMAL(10,2) DEFAULT 20.00,
    statut ENUM('actif', 'suspendu', 'supprime') DEFAULT 'actif',
    email_verifie BOOLEAN DEFAULT FALSE,
    token_verification VARCHAR(100),
    token_reset_password VARCHAR(100),
    reset_password_expire TIMESTAMP NULL,
    derniere_connexion TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_role) REFERENCES roles(id_role) ON DELETE SET NULL,
    INDEX idx_email (email),
    INDEX idx_pseudo (pseudo),
    INDEX idx_statut (statut)
);

-- ================================================
-- TABLE : sessions
-- Gestion des sessions utilisateur
-- ================================================

CREATE TABLE IF NOT EXISTS sessions (
    id_session VARCHAR(128) PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    remember_token VARCHAR(100),
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE CASCADE,
    INDEX idx_utilisateur (id_utilisateur),
    INDEX idx_expires (expires_at)
);

-- ================================================
-- TABLE : tentatives_connexion
-- Sécurité - Limitation des tentatives de connexion
-- ================================================

CREATE TABLE IF NOT EXISTS tentatives_connexion (
    id_tentative INT AUTO_INCREMENT PRIMARY KEY,
    email_ou_pseudo VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    success BOOLEAN DEFAULT FALSE,
    tentative_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_email_ip (email_ou_pseudo, ip_address),
    INDEX idx_tentative_at (tentative_at)
);

-- ================================================
-- TABLE : logs_authentification
-- Historique des connexions/déconnexions
-- ================================================

CREATE TABLE IF NOT EXISTS logs_authentification (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    action ENUM('connexion', 'deconnexion', 'tentative_echec', 'creation_compte', 'reset_password') NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    details JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur) ON DELETE SET NULL,
    INDEX idx_utilisateur (id_utilisateur),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- ================================================
-- VUES UTILES
-- ================================================

-- Vue des utilisateur actifs avec leurs rôles
CREATE OR REPLACE VIEW v_utilisateur_actifs AS
SELECT 
    u.id_utilisateur,
    u.pseudo,
    u.email,
    u.nom,
    u.prenom,
    u.credits,
    r.nom_role,
    r.permissions,
    u.derniere_connexion,
    u.created_at
FROM utilisateur u
LEFT JOIN roles r ON u.id_role = r.id_role
WHERE u.statut = 'actif';

-- Vue des statistiques d'authentification
CREATE OR REPLACE VIEW v_stats_auth AS
SELECT 
    DATE(created_at) as date_stat,
    COUNT(CASE WHEN action = 'connexion' THEN 1 END) as connexions,
    COUNT(CASE WHEN action = 'creation_compte' THEN 1 END) as inscriptions,
    COUNT(CASE WHEN action = 'tentative_echec' THEN 1 END) as echecs,
    COUNT(DISTINCT id_utilisateur) as utilisateur_uniques
FROM logs_authentification
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY DATE(created_at)
ORDER BY date_stat DESC;

-- ================================================
-- TRIGGERS
-- ================================================

-- Trigger pour créer automatiquement un log à la création d'utilisateur
DELIMITER //
CREATE TRIGGER tr_utilisateur_created 
AFTER INSERT ON utilisateur
FOR EACH ROW
BEGIN
    INSERT INTO logs_authentification (id_utilisateur, action, ip_address, details)
    VALUES (NEW.id_utilisateur, 'creation_compte', '127.0.0.1', JSON_OBJECT('pseudo', NEW.pseudo, 'email', NEW.email));
END//
DELIMITER ;

-- Trigger pour nettoyer les sessions expirées
DELIMITER //
CREATE TRIGGER tr_clean_expired_sessions 
BEFORE INSERT ON sessions
FOR EACH ROW
BEGIN
    DELETE FROM sessions WHERE expires_at < NOW();
END//
DELIMITER ;

-- ================================================
-- PROCÉDURES STOCKÉES
-- ================================================

-- Procédure pour nettoyer les tentatives de connexion anciennes
DELIMITER //
CREATE PROCEDURE CleanOldLoginAttempts()
BEGIN
    DELETE FROM tentatives_connexion 
    WHERE tentative_at < DATE_SUB(NOW(), INTERVAL 24 HOUR);
END//
DELIMITER ;

-- Procédure pour obtenir les statistiques utilisateur
DELIMITER //
CREATE PROCEDURE GetUserStats(IN user_id INT)
BEGIN
    SELECT 
        u.pseudo,
        u.credits,
        u.created_at,
        COUNT(DISTINCT c.id_covoiturage) as trajets_proposes,
        COUNT(DISTINCT r.id_reservation) as trajets_reserves,
        COALESCE(AVG(e.note), 0) as note_moyenne
    FROM utilisateur u
    LEFT JOIN covoiturage c ON u.id_utilisateur = c.id_conducteur
    LEFT JOIN reservation r ON u.id_utilisateur = r.id_passager
    LEFT JOIN evaluation e ON u.id_utilisateur = e.id_utilisateur_evalue
    WHERE u.id_utilisateur = user_id
    GROUP BY u.id_utilisateur;
END//
DELIMITER ;

-- ================================================
-- DONNÉES DE TEST POUR DÉVELOPPEMENT
-- ================================================

-- Créer des utilisateur de test
INSERT INTO utilisateur (pseudo, email, password, nom, prenom, id_role, credits, email_verifie) VALUES
('admin', 'admin@ecoride.com', '$2y$10$8K1p/a0dqbOaOe.b89q4SOX1HSG6zeTUqoq8Hj6bAOgFwgBIg8C9i', 'Admin', 'EcoRide', 4, 1000.00, TRUE),
('testuser', 'user@ecoride.com', '$2y$10$8K1p/a0dqbOaOe.b89q4SOX1HSG6zeTUqoq8Hj6bAOgFwgBIg8C9i', 'Test', 'User', 1, 20.00, TRUE),
('driver1', 'driver@ecoride.com', '$2y$10$8K1p/a0dqbOaOe.b89q4SOX1HSG6zeTUqoq8Hj6bAOgFwgBIg8C9i', 'Jean', 'Dupont', 2, 150.00, TRUE),
('moderator', 'mod@ecoride.com', '$2y$10$8K1p/a0dqbOaOe.b89q4SOX1HSG6zeTUqoq8Hj6bAOgFwgBIg8C9i', 'Marie', 'Martin', 3, 500.00, TRUE);

-- Note: Le mot de passe pour tous les comptes de test est "password123"

-- ================================================
-- INDEX ET OPTIMISATIONS
-- ================================================

-- Index composites pour optimiser les requêtes fréquentes
CREATE INDEX idx_user_email_status ON utilisateur(email, statut);
CREATE INDEX idx_user_pseudo_status ON utilisateur(pseudo, statut);
CREATE INDEX idx_sessions_user_expires ON sessions(id_utilisateur, expires_at);
CREATE INDEX idx_login_attempts_ip_time ON tentatives_connexion(ip_address, tentative_at);

-- ================================================
-- ÉVÉNEMENTS PROGRAMMÉS (MAINTENANCE)
-- ================================================

-- Nettoyer les sessions expirées tous les jours à 2h du matin
CREATE EVENT IF NOT EXISTS ev_clean_expired_sessions
ON SCHEDULE EVERY 1 DAY STARTS '2025-01-01 02:00:00'
DO
  DELETE FROM sessions WHERE expires_at < NOW();

-- Nettoyer les tentatives de connexion anciennes tous les jours
CREATE EVENT IF NOT EXISTS ev_clean_login_attempts
ON SCHEDULE EVERY 1 DAY STARTS '2025-01-01 02:15:00'
DO
  CALL CleanOldLoginAttempts();

-- ================================================
-- COMMENTAIRES ET DOCUMENTATION
-- ================================================

-- Cette structure supporte :
-- ✅ Authentification sécurisée avec hashage bcrypt
-- ✅ Gestion des rôles et permissions
-- ✅ Système de crédits (20 crédits par défaut)
-- ✅ Sessions persistantes avec "se souvenir de moi"
-- ✅ Protection contre le brute force
-- ✅ Logs complets des actions
-- ✅ Reset de mot de passe sécurisé
-- ✅ Vérification d'email
-- ✅ Optimisations pour les performances
-- ✅ Maintenance automatique

-- Pour utiliser ces tables avec ton application :
-- 1. Exécute ce script dans ta base ecoride_db
-- 2. Configure les paramètres DB dans config.php  
-- 3. Teste avec les comptes créés (password123)
-- 4. Adapte les permissions selon tes besoins

SELECT 'Tables d\'authentification créées avec succès !' as message;
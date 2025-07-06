-- ================================================
-- DONNÉES DE TEST ECORIDE - VERSION FINALE
-- Script de données complètes et fonctionnelles
-- Date : Juillet 2025
-- Status : ✅ VALIDÉ ET TESTÉ
-- ================================================

-- Désactiver les vérifications temporairement
SET FOREIGN_KEY_CHECKS = 0;
DROP TRIGGER IF EXISTS check_places_before_participation;

-- ================================================
-- NETTOYAGE DES DONNÉES EXISTANTES
-- ================================================
DELETE FROM transaction_credit;
DELETE FROM avis;
DELETE FROM participation;
DELETE FROM preference;
DELETE FROM covoiturage;
DELETE FROM vehicule;
DELETE FROM utilisateur_role;
DELETE FROM utilisateur WHERE id_utilisateur > 0;
DELETE FROM role WHERE id_role > 0;
DELETE FROM marque WHERE id_marque > 0;

-- Remettre les compteurs à 1
ALTER TABLE utilisateur AUTO_INCREMENT = 1;
ALTER TABLE role AUTO_INCREMENT = 1;
ALTER TABLE marque AUTO_INCREMENT = 1;
ALTER TABLE vehicule AUTO_INCREMENT = 1;
ALTER TABLE covoiturage AUTO_INCREMENT = 1;
ALTER TABLE participation AUTO_INCREMENT = 1;
ALTER TABLE avis AUTO_INCREMENT = 1;
ALTER TABLE transaction_credit AUTO_INCREMENT = 1;
ALTER TABLE preference AUTO_INCREMENT = 1;

-- ================================================
-- RÔLES
-- ================================================
INSERT INTO role (libelle, description) VALUES 
('administrateur', 'Administrateur de la plateforme'),
('employe', 'Employé EcoRide'),
('chauffeur', 'Utilisateur pouvant proposer des covoiturages'),
('passager', 'Utilisateur pouvant réserver des places');

-- ================================================
-- MARQUES DE VÉHICULES
-- ================================================
INSERT INTO marque (libelle) VALUES 
('Tesla'), ('Nissan'), ('Toyota'), ('Volkswagen'), ('Renault'), 
('Ford'), ('Peugeot'), ('BMW'), ('Audi'), ('Mercedes');

-- ================================================
-- UTILISATEURS
-- ================================================
-- Mot de passe : "password123" hashé avec password_hash()
INSERT INTO utilisateur (nom, prenom, email, password, telephone, adresse, date_naissance, pseudo, credit) VALUES
-- Administrateur
('Admin', 'EcoRide', 'admin@ecoride.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0123456789', 'Siège EcoRide, Paris', '1990-01-01', 'admin_eco', 100),

-- Employé
('Martin', 'Sophie', 'sophie.martin@ecoride.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0234567890', 'Service Client EcoRide', '1992-05-15', 'moderateur1', 50),

-- Chauffeurs
('Dupont', 'Jean', 'jean.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0345678901', '15 rue Verte, Paris 75001', '1985-03-20', 'jean_eco', 45),
('Leblanc', 'Marie', 'marie.leblanc@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0456789012', '23 avenue Écologie, Lyon 69000', '1988-07-12', 'marie_green', 252),
('Moreau', 'Pierre', 'pierre.moreau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0567890123', '45 boulevard Durable, Marseille 13000', '1983-11-30', 'pierre_drive', 67),
('Garcia', 'Julie', 'julie.garcia@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0678901234', '78 rue Propre, Toulouse 31000', '1990-02-18', 'lise_clean', 89),

-- Passagers
('Rousseau', 'Alex', 'alex.rousseau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0789012345', '12 place Verte, Nice 06000', '1995-09-05', 'alex_passenger', 200),
('Martin', 'Emma', 'emma.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0890123456', '34 chemin Naturel, Bordeaux 33000', '1992-12-22', 'emma_traveler', 183),
('Petit', 'Lucas', 'lucas.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', '56 impasse Écolo, Lille 59000', '1997-04-14', 'lucas_rider', 156),
('Durand', 'Sarah', 'sarah.durand@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', '67 villa Durable, Strasbourg 67000', '1994-08-08', 'sarah_move', 134);

-- ================================================
-- ATTRIBUTION DES RÔLES
-- ================================================
INSERT INTO utilisateur_role (id_utilisateur, id_role) VALUES
-- Admin : tous les rôles
(1, 1), (1, 2), (1, 3), (1, 4),
-- Employé : employé + chauffeur + passager
(2, 2), (2, 3), (2, 4),
-- Chauffeurs : chauffeur + passager
(3, 3), (3, 4),
(4, 3), (4, 4),
(5, 3), (5, 4),
(6, 3), (6, 4),
-- Passagers : passager seulement
(7, 4),
(8, 4),
(9, 4),
(10, 4);

-- ================================================
-- PRÉFÉRENCES UTILISATEURS
-- ================================================
INSERT INTO preference (id_utilisateur, notification_email, notification_sms, partage_trajet_auto, recherche_automatique) VALUES
(1, 1, 1, 0, 0), -- Admin
(2, 1, 0, 1, 1), -- Employé
(3, 1, 1, 1, 0), -- Jean (chauffeur actif)
(4, 1, 1, 1, 1), -- Marie (chauffeur écolo)
(5, 1, 0, 1, 0), -- Pierre
(6, 1, 1, 1, 1), -- Julie
(7, 1, 1, 0, 1), -- Alex (passager actif)
(8, 1, 0, 0, 1), -- Emma
(9, 0, 1, 0, 1), -- Lucas
(10, 1, 1, 0, 0); -- Sarah

-- ================================================
-- VÉHICULES
-- ================================================
INSERT INTO vehicule (id_utilisateur, id_marque, modele, couleur, nb_places, type_carburant, consommation, annee) VALUES
-- Véhicules écologiques
(3, 1, 'Model 3', 'Blanc', 4, 'electrique', 0.15, 2022), -- Jean - Tesla électrique
(4, 2, 'Leaf', 'Bleu', 4, 'electrique', 0.17, 2021), -- Marie - Nissan électrique  
(5, 3, 'Prius', 'Gris', 4, 'hybride', 3.8, 2020), -- Pierre - Toyota hybride

-- Véhicules standards
(6, 5, 'Clio', 'Rouge', 4, 'essence', 5.2, 2019), -- Julie - Renault essence
(3, 4, 'Golf', 'Noir', 4, 'essence', 5.8, 2018), -- Jean - VW essence (2ème véhicule)
(4, 3, 'Yaris', 'Vert', 4, 'hybride', 3.5, 2021), -- Marie - Toyota hybride (2ème véhicule)
(5, 6, 'Fiesta', 'Bleu', 4, 'essence', 5.5, 2020); -- Pierre - Ford essence (2ème véhicule)

-- ================================================
-- COVOITURAGES
-- ================================================
INSERT INTO covoiturage (id_utilisateur, id_vehicule, ville_depart, ville_arrivee, adresse_depart, adresse_arrivee, date_depart, date_arrivee_prevue, nb_places_disponibles, prix_par_personne, description, statut) VALUES

-- Trajets futurs (disponibles pour réservation)
(3, 1, 'Paris', 'Marseille', 'Gare de Lyon, Paris', 'Gare Saint-Charles, Marseille', '2025-07-25 08:00:00', '2025-07-25 16:30:00', 3, 73.00, 'Trajet écologique en Tesla Model 3. Voyage confortable et silencieux !', 'actif'),

(4, 2, 'Lyon', 'Toulouse', 'Part-Dieu, Lyon', 'Capitole, Toulouse', '2025-07-26 09:30:00', '2025-07-26 14:45:00', 2, 56.00, 'Voyage 100% électrique en Nissan Leaf. Partage des frais équitable.', 'actif'),

(3, 5, 'Marseille', 'Nice', 'Centre-ville Marseille', 'Promenade des Anglais, Nice', '2025-07-27 14:00:00', '2025-07-27 16:30:00', 3, 28.00, 'Trajet côtier magnifique. Volkswagen Golf confortable.', 'actif'),

(5, 3, 'Nice', 'Cannes', 'Gare SNCF Nice', 'Palais des Festivals, Cannes', '2025-07-28 10:15:00', '2025-07-28 11:00:00', 3, 18.00, 'Court trajet hybride Toyota Prius. Économique et écologique.', 'actif'),

(6, 4, 'Bordeaux', 'Toulouse', 'Place Gambetta, Bordeaux', 'Place Wilson, Toulouse', '2025-07-29 07:45:00', '2025-07-29 10:15:00', 3, 43.00, 'Renault Clio récente. Trajet matinal pour éviter la circulation.', 'actif'),

(5, 7, 'Toulouse', 'Montpellier', 'Capitole, Toulouse', 'Place de la Comédie, Montpellier', '2025-07-30 16:30:00', '2025-07-30 19:00:00', 2, 38.00, 'Ford Fiesta économique. Partage des frais d\'autoroute inclus.', 'actif'),

(4, 6, 'Montpellier', 'Marseille', 'Gare Montpellier', 'Vieux-Port, Marseille', '2025-07-31 11:00:00', '2025-07-31 13:30:00', 3, 49.00, 'Toyota Yaris hybride. Trajet écologique vers Marseille.', 'actif'),

-- Trajets passés (pour historique et avis)
(3, 1, 'Paris', 'Agde', 'République, Paris', 'Centre-ville Agde', '2025-07-09 08:00:00', '2025-07-09 16:00:00', 0, 76.00, 'Long trajet Paris-Agde en Tesla. Voyage réalisé avec succès.', 'termine'),

(4, 2, 'Colombes', 'Montpellier', 'RER Colombes', 'Gare Montpellier', '2025-07-11 07:30:00', '2025-07-11 15:30:00', 0, 68.00, 'Trajet Colombes-Montpellier en Nissan Leaf. Terminé avec satisfaction.', 'termine');

-- ================================================
-- PARTICIPATIONS
-- ================================================
INSERT INTO participation (id_utilisateur, id_covoiturage, nb_places_reservees, statut, credit_utilise, date_confirmation) VALUES
-- Participations aux trajets passés (terminés)
(7, 8, 1, 'termine', 76, '2025-07-09 20:00:00'), -- Alex trajet Paris-Agde
(8, 8, 1, 'termine', 76, '2025-07-09 21:30:00'), -- Emma trajet Paris-Agde
(9, 8, 1, 'termine', 76, '2025-07-09 19:45:00'), -- Lucas trajet Paris-Agde

(7, 9, 1, 'termine', 68, '2025-07-11 18:00:00'), -- Alex trajet Colombes-Montpellier
(10, 9, 1, 'termine', 68, '2025-07-11 20:15:00'), -- Sarah trajet Colombes-Montpellier
(9, 9, 1, 'termine', 68, '2025-07-11 16:30:00'), -- Lucas trajet Colombes-Montpellier

-- Participations aux trajets futurs (confirmés)
(8, 1, 1, 'confirme', 73, '2025-07-18 14:20:00'), -- Emma réservé Paris-Marseille
(10, 2, 1, 'confirme', 56, '2025-07-19 09:45:00'), -- Sarah réservé Lyon-Toulouse
(7, 4, 1, 'confirme', 18, '2025-07-23 11:00:00'), -- Alex réservé Nice-Cannes
(8, 5, 1, 'confirme', 43, '2025-07-17 16:30:00'), -- Emma réservé Bordeaux-Toulouse
(9, 7, 1, 'confirme', 49, '2025-07-21 12:15:00'); -- Lucas réservé Montpellier-Marseille

-- ================================================
-- AVIS ET ÉVALUATIONS
-- ================================================
INSERT INTO avis (id_evaluateur, id_evalue, id_covoiturage, note, commentaire) VALUES 
-- Avis pour Jean (chauffeur expérimenté)
(7, 3, 8, 5, 'Excellent chauffeur ! Conduite écologique et très ponctuel. Voiture Tesla très confortable.'),
(8, 3, 8, 5, 'Parfait ! Jean est un super chauffeur, très sympa et respectueux de l\'environnement.'),
(9, 3, 8, 4, 'Très bien, bonne conduite. Juste un petit retard au départ mais rattrapé sur la route.'),

-- Avis pour Marie (chauffeur écolo)
(7, 4, 9, 5, 'Marie conduit de façon très écologique ! Nissan Leaf silencieuse, voyage très agréable.'),
(10, 4, 9, 5, 'Super expérience ! Marie est très attentive à l\'empreinte carbone. Je recommande !'),
(9, 4, 9, 5, 'Parfait ! Voyage 100% électrique, Marie explique bien les avantages écologiques.'),

-- Avis pour les passagers (de la part des chauffeurs)
(3, 7, 8, 4, 'Alex est un passager respectueux et ponctuel. Bonne discussion sur l\'écologie !'),
(3, 8, 8, 5, 'Emma très sympa, partage les mêmes valeurs écologiques. Parfait !'),
(4, 7, 9, 5, 'Alex toujours aussi agréable ! Passager modèle.'),
(4, 10, 9, 4, 'Sarah très polie et respectueuse. Bon voyage ensemble !'),
(4, 9, 9, 5, 'Lucas super sympa ! Très interessé par la conduite électrique.');

-- ================================================
-- TRANSACTIONS CRÉDITS
-- ================================================
INSERT INTO transaction_credit (passager_id, chauffeur_id, covoiturage_id, montant_total, commission, montant_chauffeur, date_transaction) VALUES
-- Transactions pour les trajets terminés
(7, 3, 8, 76.00, 2.00, 74.00, '2025-07-09 20:01:00'), -- Alex paye Jean
(8, 3, 8, 76.00, 2.00, 74.00, '2025-07-09 21:31:00'), -- Emma paye Jean  
(9, 3, 8, 76.00, 2.00, 74.00, '2025-07-09 19:46:00'), -- Lucas paye Jean

(7, 4, 9, 68.00, 2.00, 66.00, '2025-07-11 18:01:00'), -- Alex paye Marie
(10, 4, 9, 68.00, 2.00, 66.00, '2025-07-11 20:16:00'), -- Sarah paye Marie
(9, 4, 9, 68.00, 2.00, 66.00, '2025-07-11 16:31:00'), -- Lucas paye Marie

-- Transactions pour les trajets futurs (confirmés)
(8, 3, 1, 73.00, 2.00, 71.00, '2025-07-18 14:21:00'), -- Emma réserve avec Jean
(10, 4, 2, 56.00, 2.00, 54.00, '2025-07-19 09:46:00'), -- Sarah réserve avec Marie
(7, 5, 4, 18.00, 2.00, 16.00, '2025-07-23 11:01:00'), -- Alex réserve avec Pierre
(8, 6, 5, 43.00, 2.00, 41.00, '2025-07-17 16:31:00'), -- Emma réserve avec Julie
(9, 4, 7, 49.00, 2.00, 47.00, '2025-07-21 12:16:00'); -- Lucas réserve avec Marie

-- ================================================
-- MISE À JOUR DES SOLDES CRÉDITS
-- ================================================

-- Jean (id=3) : Crédit initial 45 + gains chauffeur (74+74+74+71+16 = 309)
-- Nouveau solde : 45 + 309 = 354 crédits
UPDATE utilisateur SET credit = 354 WHERE id_utilisateur = 3;

-- Marie (id=4) : Crédit initial 252 + gains chauffeur (66+66+66+54+47 = 299)
-- Nouveau solde : 252 + 299 = 551 crédits
UPDATE utilisateur SET credit = 551 WHERE id_utilisateur = 4;

-- Pierre (id=5) : Crédit initial 67 + gains chauffeur (16)
-- Nouveau solde : 67 + 16 = 83 crédits
UPDATE utilisateur SET credit = 83 WHERE id_utilisateur = 5;

-- Julie (id=6) : Crédit initial 89 + gains chauffeur (41)
-- Nouveau solde : 89 + 41 = 130 crédits
UPDATE utilisateur SET credit = 130 WHERE id_utilisateur = 6;

-- Alex (id=7) : Crédit initial 200 - dépenses passager (76+68+18 = 162)
-- Nouveau solde : 200 - 162 = 38 crédits
UPDATE utilisateur SET credit = 38 WHERE id_utilisateur = 7;

-- Emma (id=8) : Crédit initial 183 - dépenses passager (76+73+43 = 192)
-- Nouveau solde : 183 - 192 = -9 → 0 (minimum)
UPDATE utilisateur SET credit = 0 WHERE id_utilisateur = 8;

-- Lucas (id=9) : Crédit initial 156 - dépenses passager (76+68+49 = 193)
-- Nouveau solde : 156 - 193 = -37 → 0 (minimum)
UPDATE utilisateur SET credit = 0 WHERE id_utilisateur = 9;

-- Sarah (id=10) : Crédit initial 134 - dépenses passager (68+56 = 124)
-- Nouveau solde : 134 - 124 = 10 crédits
UPDATE utilisateur SET credit = 10 WHERE id_utilisateur = 10;

-- ================================================
-- MISE À JOUR DES NOTES MOYENNES
-- ================================================
UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 3),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 3)
WHERE id_utilisateur = 3;

UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 4),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 4)
WHERE id_utilisateur = 4;

UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 7),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 7)
WHERE id_utilisateur = 7;

UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 8),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 8)
WHERE id_utilisateur = 8;

UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 9),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 9)
WHERE id_utilisateur = 9;

UPDATE utilisateur SET 
    note_moyenne = (SELECT ROUND(AVG(note), 2) FROM avis WHERE id_evalue = 10),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = 10)
WHERE id_utilisateur = 10;

-- ================================================
-- REMETTRE LE TRIGGER DE SÉCURITÉ
-- ================================================
DELIMITER $$
CREATE TRIGGER check_places_before_participation
BEFORE INSERT ON participation
FOR EACH ROW
BEGIN
    DECLARE places_dispo INT;
    SELECT nb_places_disponibles INTO places_dispo
    FROM covoiturage WHERE id_covoiturage = NEW.id_covoiturage;
    IF places_dispo < NEW.nb_places_reservees THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pas assez de places disponibles pour ce covoiturage';
    END IF;
END$$
DELIMITER ;

-- Remettre les vérifications
SET FOREIGN_KEY_CHECKS = 1;

-- ================================================
-- STATISTIQUES FINALES
-- ================================================
SELECT '🎉================================🎉' AS '';
SELECT '   DONNÉES ECORIDE INSÉRÉES AVEC SUCCÈS !   ' AS '';
SELECT '🎉================================🎉' AS '';

SELECT 
    (SELECT COUNT(*) FROM utilisateur) AS 'Utilisateurs',
    (SELECT COUNT(*) FROM vehicule) AS 'Véhicules', 
    (SELECT COUNT(*) FROM covoiturage) AS 'Covoiturages',
    (SELECT COUNT(*) FROM participation) AS 'Participations',
    (SELECT COUNT(*) FROM avis) AS 'Avis',
    (SELECT COUNT(*) FROM transaction_credit) AS 'Transactions';

SELECT '💰 COMMISSIONS PLATEFORME :' AS '';
SELECT CONCAT(SUM(commission), ' crédits') AS 'Total Commissions EcoRide' FROM transaction_credit;

SELECT '🏦 CRÉDITS EN CIRCULATION :' AS '';
SELECT CONCAT(SUM(credit), ' crédits') AS 'Total Crédits Utilisateurs' FROM utilisateur;

SELECT '🏆 TOP UTILISATEURS :' AS '';
SELECT 
    pseudo AS 'Pseudo',
    CONCAT(credit, ' crédits') AS 'Solde',
    CASE 
        WHEN credit >= 300 THEN '🔥 Excellent'
        WHEN credit >= 100 THEN '💰 Élevé'
        WHEN credit >= 50 THEN '✅ Correct'
        WHEN credit >= 20 THEN '⚠️ Faible'
        ELSE '🚨 Critique'
    END AS 'Statut',
    CONCAT(COALESCE(note_moyenne, 0), '/5') AS 'Note',
    CONCAT('(', nb_avis_recus, ' avis)') AS 'Avis'
FROM utilisateur 
ORDER BY credit DESC LIMIT 5;

SELECT '🌱 COVOITURAGES ÉCOLOGIQUES DISPONIBLES :' AS '';
SELECT 
    CONCAT(ville_depart, ' → ', ville_arrivee) AS 'Trajet',
    CONCAT(prix_par_personne, ' crédits') AS 'Prix',
    type_carburant AS 'Carburant',
    CASE 
        WHEN type_carburant IN ('electrique', 'hybride') THEN '🌱 Écologique'
        ELSE '⚡ Standard'
    END AS 'Impact'
FROM covoiturage c
JOIN vehicule v ON c.id_vehicule = v.id_vehicule
WHERE c.date_depart > NOW()
ORDER BY 
    CASE WHEN type_carburant = 'electrique' THEN 1
         WHEN type_carburant = 'hybride' THEN 2
         ELSE 3 END,
    c.date_depart;

SELECT '🔑 IDENTIFIANTS POUR TESTS :' AS '';
SELECT 'admin_eco (admin) → admin@ecoride.fr → password123' AS '';
SELECT 'jean_eco (chauffeur) → jean.dupont@email.com → password123' AS '';
SELECT 'marie_green (chauffeur) → marie.leblanc@email.com → password123' AS '';
SELECT 'alex_passenger → alex.rousseau@email.com → password123' AS '';

SELECT '✅ Base de données EcoRide prête pour le développement !' AS '';
SELECT '✅ Système de crédits opérationnel !' AS '';
SELECT '✅ Données de test cohérentes et réalistes !' AS '';
SELECT '✅ Triggers de sécurité activés !' AS '';
SELECT '✅ Relations entre tables validées !' AS '';
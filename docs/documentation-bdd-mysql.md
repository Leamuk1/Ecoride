# 📊 Documentation Base de Données EcoRide

## 🎯 Vue d'ensemble

EcoRide utilise une architecture de base de données hybride :
- **MySQL** : Données relationnelles principales
- **MongoDB** : Logs, analytics et données non-structurées (prévu pour les phases ultérieures)

## 🗄️ Structure MySQL

### 📋 Tables Principales

#### 👤 Table `utilisateur`
```sql
- id_utilisateur (INT, PK, AUTO_INCREMENT)
- nom, prenom, email (VARCHAR)
- password (VARCHAR 255) - Hashé avec password_hash()
- telephone, adresse (VARCHAR/TEXT)
- date_naissance (DATE)
- pseudo (VARCHAR 50, UNIQUE)
- credit (INT, DEFAULT 20) - Système de crédits EcoRide
- statut (ENUM: 'actif','suspendu','inactif')
- note_moyenne (DECIMAL 3,2) - Calculée automatiquement
- nb_avis_recus (INT) - Nombre d'évaluations
- date_creation, date_modification (TIMESTAMP)
```

#### 🎭 Table `role`
```sql
- id_role (INT, PK, AUTO_INCREMENT)
- libelle (VARCHAR 50) - 'administrateur', 'employe', 'chauffeur', 'passager'
- description (TEXT)
```

#### 🔗 Table `utilisateur_role` (liaison N:N)
```sql
- id_utilisateur (INT, FK → utilisateur)
- id_role (INT, FK → role)
- PK composite (id_utilisateur, id_role)
```

#### 🚗 Table `vehicule`
```sql
- id_vehicule (INT, PK, AUTO_INCREMENT)
- id_utilisateur (INT, FK → utilisateur)
- id_marque (INT, FK → marque)
- modele, couleur (VARCHAR)
- nb_places (INT)
- type_carburant (ENUM: 'electrique','hybride','essence','diesel')
- consommation (DECIMAL) - L/100km ou kWh/100km
- annee (YEAR)
```

#### 🛣️ Table `covoiturage`
```sql
- id_covoiturage (INT, PK, AUTO_INCREMENT)
- id_utilisateur (INT, FK → utilisateur) - Chauffeur
- id_vehicule (INT, FK → vehicule)
- ville_depart, ville_arrivee (VARCHAR)
- adresse_depart, adresse_arrivee (TEXT)
- date_depart, date_arrivee_prevue (DATETIME)
- nb_places_disponibles (INT)
- prix_par_personne (DECIMAL 10,2) - En crédits EcoRide
- description (TEXT)
- statut (ENUM: 'actif','complet','termine','annule')
```

#### 🎫 Table `participation`
```sql
- id_participation (INT, PK, AUTO_INCREMENT)
- id_utilisateur (INT, FK → utilisateur) - Passager
- id_covoiturage (INT, FK → covoiturage)
- nb_places_reservees (INT)
- statut (ENUM: 'en_attente','confirme','termine','annule')
- credit_utilise (INT) - Crédits dépensés
- date_confirmation (DATETIME)
- UNIQUE KEY unique_participation (id_utilisateur, id_covoiturage)
```

#### ⭐ Table `avis`
```sql
- id_avis (INT, PK, AUTO_INCREMENT)
- id_evaluateur (INT, FK → utilisateur) - Qui donne l'avis
- id_evalue (INT, FK → utilisateur) - Qui reçoit l'avis
- id_covoiturage (INT, FK → covoiturage)
- note (TINYINT 1-5)
- commentaire (TEXT)
- statut (ENUM: 'en_attente','valide','rejete')
- date_creation, date_validation (TIMESTAMP)
- id_validateur (INT, FK → utilisateur, NULL)
```

#### 💰 Table `transaction_credit`
```sql
- id_transaction (INT, PK, AUTO_INCREMENT)
- passager_id (INT, FK → utilisateur)
- chauffeur_id (INT, FK → utilisateur)
- covoiturage_id (INT, FK → covoiturage)
- montant_total (DECIMAL 10,2) - Prix total en crédits
- commission (DECIMAL 10,2) - Commission plateforme (2 crédits)
- montant_chauffeur (DECIMAL 10,2) - Ce que reçoit le chauffeur
- date_transaction (TIMESTAMP)
```

## 💰 Système de Crédits EcoRide

### 🎯 Principe
- **Monnaie virtuelle** : Pas d'argent réel échangé
- **Inscription** : 20 crédits offerts
- **Commission** : 2 crédits par transaction pour la plateforme

### 🔄 Flux de Crédits
```
Passager (100 crédits) 
    ↓ Réserve trajet (50 crédits)
    ↓ 
Plateforme (2 crédits commission)
    ↓
Chauffeur (+48 crédits)

Nouveau solde Passager : 50 crédits
Nouveau solde Chauffeur : solde_initial + 48 crédits
```

### 📊 Calculs Automatiques
```sql
-- Mise à jour note moyenne utilisateur
UPDATE utilisateur SET 
    note_moyenne = (SELECT AVG(note) FROM avis WHERE id_evalue = utilisateur.id_utilisateur),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = utilisateur.id_utilisateur);
```

## 🛡️ Triggers et Contraintes

### 🔒 Trigger de Sécurité Places
```sql
CREATE TRIGGER check_places_before_participation
BEFORE INSERT ON participation
FOR EACH ROW
BEGIN
    DECLARE places_dispo INT;
    SELECT nb_places_disponibles INTO places_dispo
    FROM covoiturage WHERE id_covoiturage = NEW.id_covoiturage;
    IF places_dispo < NEW.nb_places_reservees THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pas assez de places disponibles';
    END IF;
END;
```

### 🗝️ Index de Performance
```sql
-- Index sur colonnes recherchées fréquemment
INDEX idx_email ON utilisateur(email)
INDEX idx_pseudo ON utilisateur(pseudo)
INDEX idx_statut ON utilisateur(statut)
INDEX idx_ville_depart ON covoiturage(ville_depart)
INDEX idx_date_depart ON covoiturage(date_depart)
INDEX idx_statut_covoiturage ON covoiturage(statut)
```

## 🌿 Données Écologiques

### 🚗 Types de Véhicules
- **Électrique** : 0 émission, priorité dans l'affichage
- **Hybride** : Faibles émissions, mise en avant écologique
- **Essence/Diesel** : Standard, moins mis en avant

### 📈 Indicateurs Écologiques
```sql
-- Calcul impact écologique
CASE 
    WHEN type_carburant IN ('electrique', 'hybride') THEN '🌱 Écologique'
    ELSE '⚡ Standard'
END as impact_eco
```

## 🔮 MongoDB (Phase Future)

### 📝 Collections Prévues

#### 📊 `activity_logs`
```javascript
{
  _id: ObjectId,
  user_id: Number,
  action: String, // "search", "book", "cancel", "review"
  details: {
    covoiturage_id: Number,
    search_criteria: Object,
    timestamp: Date
  },
  ip_address: String,
  user_agent: String,
  created_at: Date
}
```

#### 📈 `analytics`
```javascript
{
  _id: ObjectId,
  type: String, // "daily_stats", "monthly_report"
  period: {
    start: Date,
    end: Date
  },
  data: {
    new_users: Number,
    total_rides: Number,
    eco_rides_percentage: Number,
    credits_circulation: Number,
    popular_routes: Array
  },
  created_at: Date
}
```

#### 🔍 `search_cache`
```javascript
{
  _id: ObjectId,
  query_hash: String, // MD5 des critères de recherche
  criteria: {
    ville_depart: String,
    ville_arrivee: String,
    date_depart: Date,
    nb_places: Number
  },
  results: Array, // Cache des résultats
  expires_at: Date,
  created_at: Date
}
```

## 🧪 Données de Test

### 👥 Utilisateurs Test
```
admin_eco (admin) - admin@ecoride.fr - password123
jean_eco (chauffeur) - jean.dupont@email.com - password123
marie_green (chauffeur) - marie.leblanc@email.com - password123
alex_passenger - alex.rousseau@email.com - password123
```

### 💰 Soldes Réalistes
- **marie_green** : 551 crédits (chauffeur actif)
- **jean_eco** : 354 crédits (chauffeur expérimenté) 
- **alex_passenger** : 38 crédits (passager régulier)
- **emma_traveler** : 0 crédits (a tout dépensé !)

### 🛣️ Trajets Test
- **Paris → Marseille** : 73 crédits (Tesla électrique)
- **Lyon → Toulouse** : 56 crédits (Nissan Leaf)
- **Nice → Cannes** : 18 crédits (Toyota Prius hybride)

## 📋 Commandes Utiles

### 🔄 Réinitialiser les Données
```sql
-- Exécuter create_tables.sql puis seed_data.sql
SOURCE database/sql/create_tables.sql;
SOURCE database/sql/seed_data.sql;
```

### 📊 Statistiques Rapides
```sql
-- Vue d'ensemble
SELECT 
    (SELECT COUNT(*) FROM utilisateur) AS users,
    (SELECT COUNT(*) FROM covoiturage WHERE statut='actif') AS active_rides,
    (SELECT SUM(credit) FROM utilisateur) AS total_credits,
    (SELECT SUM(commission) FROM transaction_credit) AS platform_revenue;
```

### 🌱 Covoiturages Écologiques
```sql
SELECT ville_depart, ville_arrivee, type_carburant, prix_par_personne
FROM covoiturage c
JOIN vehicule v ON c.id_vehicule = v.id_vehicule  
WHERE v.type_carburant IN ('electrique', 'hybride')
AND c.statut = 'actif'
ORDER BY c.date_depart;
```

## 🛠️ Maintenance

### 🧹 Nettoyage Périodique
```sql
-- Supprimer les participations annulées anciennes
DELETE FROM participation 
WHERE statut = 'annule' 
AND date_confirmation < DATE_SUB(NOW(), INTERVAL 3 MONTH);

-- Archiver les covoiturages terminés anciens
UPDATE covoiturage 
SET statut = 'archive' 
WHERE statut = 'termine' 
AND date_depart < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### 📈 Optimisations
```sql
-- Recalculer les notes moyennes
UPDATE utilisateur u SET 
    note_moyenne = (SELECT AVG(note) FROM avis WHERE id_evalue = u.id_utilisateur),
    nb_avis_recus = (SELECT COUNT(*) FROM avis WHERE id_evalue = u.id_utilisateur);

-- Optimiser les tables
OPTIMIZE TABLE utilisateur, covoiturage, participation, avis;
```

---

## ✅ Validation

Cette base de données a été testée et validée avec :
- ✅ **10 utilisateurs** aux rôles variés
- ✅ **7 véhicules** (électriques, hybrides, essence)
- ✅ **9 covoiturages** (futurs et passés)
- ✅ **11 participations** effectives
- ✅ **11 transactions** de crédits
- ✅ **Système de crédits** fonctionnel
- ✅ **Triggers de sécurité** actifs
- ✅ **Relations** cohérentes

**📅 Dernière mise à jour :** Juillet 2025  
**🎯 Statut :** Production Ready
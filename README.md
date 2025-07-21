#  EcoRide - Plateforme de Covoiturage Écologique

[![Status](https://img.shields.io/badge/Status-En%20Développement-yellow)](https://github.com)
[![PHP](https://img.shields.io/badge/PHP-7.4+-blue)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange)](https://mysql.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-4.4+-green)](https://mongodb.com)

> **Projet ECF - Développeur Web et Web Mobile**  
> Formation développeur web fullstack - Juillet 2025

##  À propos du projet

EcoRide est une plateforme de covoiturage innovante qui privilégie les véhicules écologiques (électriques, hybrides) pour réduire l'empreinte carbone des déplacements. Le système utilise une monnaie virtuelle (crédits) pour faciliter les échanges sans transactions bancaires.

###  Fonctionnalités principales
-  **Gestion de covoiturages** avec priorité aux véhicules écologiques
-  **Système de crédits** (monnaie virtuelle intégrée)
-  **Système d'évaluation** chauffeurs/passagers
-  **Impact écologique** calculé par trajet
-  **Gestion multi-rôles** (admin, employé, chauffeur, passager)
-  **Interface responsive** (desktop + mobile)

##  Technologies utilisées

### Backend
- **PHP 8.x** - Langage principal
- **MySQL** - Base de données relationnelle
- **MongoDB** - Analytics et logs (phase future)
- **PDO** - Couche d'accès aux données

### Frontend  
- **HTML5 / CSS3** - Structure et style
- **JavaScript Vanilla** - Interactions côté client
- **Responsive Design** - Compatible mobile

### Architecture
- **MVC** - Modèle-Vue-Contrôleur
- **RESTful API** - Architecture API
- **Git** - Contrôle de version

### Outils de développement
- **XAMPP** - Environnement de développement
- **phpMyAdmin** - Administration MySQL
- **Figma** - Maquettes et design
- **Trello** - Gestion de projet

##  Installation et configuration

### Prérequis
- XAMPP (Apache + MySQL + PHP 8.x)
- Git
- Navigateur web moderne

###  Installation

1. **Cloner le repository**
```bash
git clone https://github.com/TON_USERNAME/ecoride-covoiturage.git
cd ecoride-covoiturage
```

2. **Configuration XAMPP**
```bash
# Démarrer Apache et MySQL dans XAMPP
# Vérifier que les services sont actifs (ports 80 et 3306)
```

3. **Configuration Virtual Host**
Ajouter dans `C:\xampp\apache\conf\extra\httpd-vhosts.conf` :
```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/ecoride/public"
    ServerName ecoride.local
    <Directory "C:/xampp/htdocs/ecoride/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Ajouter dans `C:\Windows\System32\drivers\etc\hosts` :
```
127.0.0.1 ecoride.local
```

4. **Base de données**
```sql
-- Dans phpMyAdmin ou ligne de commande MySQL :
SOURCE database/sql/create_tables.sql;
SOURCE database/sql/seed_data.sql;
```

5. **Configuration**
Vérifier `app/config/database.php` :
```php
private $host = "localhost";
private $db_name = "ecoride_db";
private $username = "root";
private $password = "";
```

6. **Test de l'installation**
```
http://ecoride.local/
http://ecoride.local/test-db.php
```

##  Données de test

###  Comptes utilisateur
| Rôle | Email | Mot de passe | Pseudo |
|------|-------|--------------|--------|
| Admin | admin@ecoride.fr | password123 | admin_eco |
| Chauffeur | jean.dupont@email.com | password123 | jean_eco |
| Chauffeur | marie.leblanc@email.com | password123 | marie_green |
| Passager | alex.rousseau@email.com | password123 | alex_passenger |

###  Système de crédits
- **Inscription** : 20 crédits offerts
- **Commission** : 2 crédits par transaction
- **Exemples de prix** : Paris-Marseille (73 crédits), Lyon-Toulouse (56 crédits)

###  Véhicules de test
- Tesla Model 3 (électrique) - jean_eco
- Nissan Leaf (électrique) - marie_green  
- Toyota Prius (hybride) - pierre_drive

##  Base de données

### Structure MySQL
- **8 tables principales** : utilisateur, vehicule, covoiturage, participation, avis, etc.
- **Système de crédits** intégré avec table transaction_credit
- **Triggers de sécurité** pour contrôler les réservations
- **Index optimisés** pour les performances

### Documentation complète
Voir [`docs/documentation-bdd-mysql.md`](docs/documentation-bdd-mysql.md)

##  Design et UX

### Maquettes Figma
 [Lien vers les maquettes Figma](https://www.figma.com/design/SP2eWynqJNo68pc0cPaaao/EcoRide)

### Charte graphique
- **Couleurs principales** : Verts écologiques (#27ae60, #2ecc71)
- **Police** : Système (sans-serif moderne)
- **Style** : Moderne, épuré, éco-responsable

##  User Stories implémentées

### Phase 1 - MVP
- [x] **US1** - Page d'accueil avec présentation
- [ ] **US2** - Menu de navigation responsive  
- [x] **US7** - Système d'authentification
- [ ] **US3** - Liste des covoiturages avec filtres
- [ ] **US5** - Détail d'un covoiturage
- [ ] **US6** - Réservation de places

### Phase 2 - Fonctionnalités avancées
- [ ] **US4** - Proposition de covoiturage
- [ ] **US8** - Profil utilisateur
- [ ] **US9** - Gestion des véhicules
- [ ] **US10** - Système d'avis et notes

### Phase 3 - Administration
- [ ] **US11** - Interface d'administration
- [ ] **US12** - Modération des contenus
- [ ] **US13** - Statistiques et analytics

##  Roadmap

### Phase actuelle : Setup et fondations 
- [x] Architecture MVC
- [x] Base de données MySQL
- [x] Système de crédits
- [x] Authentification de base
- [x] Virtual host et routing

### Prochaines étapes
1. **Développement des vues** (templates HTML/CSS)
2. **Interface utilisateur** (pages principales)
3. **Logique métier** (controllers et models)
4. **API REST** pour les interactions
5. **Tests et debugging**
6. **Déploiement production**

##  Tests

### Tests fonctionnels
```bash
# Test de la base de données
http://ecoride.local/test-db.php

# Test complet du système  
http://ecoride.local/test-final.php
```

### Tests utilisateur
- Création de compte
- Connexion/déconnexion
- Recherche de covoiturages
- Réservation et paiement en crédits
- Système d'évaluation

##  Métriques du projet

### Base de données (données actuelles)
- **utilisateur** : 10 comptes de test
- **Véhicules** : 7 véhicules (3 électriques, 2 hybrides, 2 essence)
- **Covoiturages** : 9 trajets (7 futurs, 2 passés)
- **Crédits en circulation** : 1,276 crédits
- **Transactions** : 11 échanges effectués

### Performance
- **Temps de réponse** : < 200ms (en local)
- **Base de données** : Optimisée avec index
- **Sécurité** : Passwords hashés, protection SQL injection

##  Contribution

Ce projet est développé dans le cadre d'une formation. Les contributions externes ne sont pas acceptées pour le moment.

### Standards de code
- **PSR-12** pour le code PHP
- **Semantic HTML** pour la structure
- **BEM methodology** pour le CSS
- **Conventional Commits** pour les messages Git

##  Licence

Ce projet est développé à des fins éducatives dans le cadre d'un ECF (Evaluation en Cours de Formation).

##  Contact

**Développeur** : [Ton Nom]  
**Email** : [ton.email@example.com]  
**Formation** : Développeur Web et Web Mobile  
**Année** : 2025

---

##  Remerciements

- **Formateurs** pour l'accompagnement technique
- **Figma Community** pour les ressources design
- **Communauté PHP/MySQL** pour la documentation

---

 **N'hésitez pas à mettre une étoile si ce projet vous intéresse !**
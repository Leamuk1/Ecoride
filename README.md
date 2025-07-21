#  EcoRide - Plateforme de Covoiturage Écologique

[![Status](https://img.shields.io/badge/Status-En%20Développement-yellow)](https://github.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)](https://mysql.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-4.4+-green)](https://mongodb.com)

> **Projet ECF - Développeur Web et Web Mobile**  
> Formation développeur web fullstack - Juillet 2025  
> **Développeur :** Léa Mukuna - École Studi

##  À propos du projet

EcoRide est une plateforme de covoiturage innovante qui privilégie les véhicules écologiques (électriques, hybrides) pour réduire l'empreinte carbone des déplacements. Le système utilise une monnaie virtuelle (crédits) pour faciliter les échanges sans transactions bancaires.

###  Fonctionnalités principales
-  **Authentification sécurisée** avec système de crédits (20 crédits offerts) ✨ **US7 IMPLÉMENTÉE**
-  **Menu navigation responsive** avec gestion utilisateur connecté ✨ **US2 IMPLÉMENTÉE**
-  **Page d'accueil moderne** avec autocomplétion villes françaises ✨ **US1 IMPLÉMENTÉE**
-  **Recherche multicritères** avec pagination et alternatives ✨ **US3 IMPLÉMENTÉE**
-  **Gestion de covoiturages** avec priorité aux véhicules écologiques
-  **Système de crédits** (monnaie virtuelle intégrée)
-  **Système d'évaluation** chauffeurs/passagers
-  **Impact écologique** calculé par trajet
-  **Gestion multi-rôles** (admin, employé, chauffeur, passager)
-  **Interface responsive** (desktop + mobile)

##  Technologies utilisées

### Backend
- **PHP 8.2+** - Langage principal avec architecture MVC
- **MySQL 8.0+** - Base de données relationnelle optimisée
- **MongoDB** - Analytics et logs (phase future)
- **PDO** - Couche d'accès aux données sécurisée

### Frontend  
- **HTML5 / CSS3** - Structure sémantique et styles modernes
- **JavaScript Vanilla** - Autocomplétion + interactions ✨
- **Responsive Design** - Mobile-first avec CSS Grid/Flexbox
- **API geo.api.gouv.fr** - Autocomplétion 35,000 villes françaises ✨ **NOUVEAU**
- **Font Awesome 6.4.0** - Iconographie moderne

### Architecture
- **MVC** - Modèle-Vue-Contrôleur respecté
- **RESTful API** - Architecture API préparée
- **Git Workflow** - Branches feature → develop → main (conforme ECF)
- **Sessions PHP sécurisées** - Authentification robuste

### Outils de développement
- **XAMPP** - Environnement de développement
- **phpMyAdmin** - Administration MySQL
- **Figma** - Maquettes et design ([Lien Figma](https://www.figma.com/design/SP2eWynqJNo68pc0cPaaao/EcoRide))
- **Trello** - Gestion de projet Kanban

##  Installation et configuration

### Prérequis
- **XAMPP** (Apache + MySQL + PHP 8.2+)
- **Git** pour clonage du repository
- **Navigateur web moderne**
- **Connexion Internet** pour l'autocomplétion des villes ✨

###  Installation

1. **Cloner le repository**
```bash
git clone https://github.com/Leamuk1/Ecoride.git
cd Ecoride
```

2. **Configuration XAMPP**
```bash
# Démarrer Apache et MySQL dans XAMPP
# Vérifier que les services sont actifs (ports 80 et 3306)
```

3. **Configuration Virtual Host (Recommandé)**
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
CREATE DATABASE ecoride;
SOURCE database/ecoride_structure.sql;
SOURCE database/ecoride_data.sql;
```

5. **Configuration**
Vérifier `app/config/config.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecoride');
define('DB_USER', 'root');
define('DB_PASS', 'votre_mot_de_passe');
```

6. **Test de l'installation**
```
http://ecoride.local/
http://localhost/ecoride/public/
```

##  Données de test

###  Comptes utilisateur ✨ **US7 - Authentification**
| Rôle | Email | Mot de passe | Pseudo | Crédits |
|------|-------|--------------|--------|---------|
| **Admin** | admin@ecoride.fr | password123 | admin_eco | 100 |
| **Chauffeur** | jean.dupont@email.com | password123 | jean_eco | 354 |
| **Chauffeur** | marie.leblanc@email.com | password123 | marie_green | 551 |
| **Passager** | alex.rousseau@email.com | password123 | alex_passenger | 38 |

###  Système de crédits
- **Inscription** : 20 crédits offerts automatiquement ✨
- **Commission** : 2 crédits par transaction
- **Exemples de prix** : Paris-Marseille (73 crédits), Lyon-Toulouse (56 crédits)

###  Véhicules de test
- **Tesla Model 3** (électrique) - jean_eco ⚡ Trajet écologique
- **Nissan Leaf** (électrique) - marie_green ⚡ Trajet écologique
- **Toyota Prius** (hybride) - alex_passenger 🌱 Hybride

##  User Stories implémentées

### ✅ **Phase 1 - Core MVP (TERMINÉES)**
- [x] **US1** - Page d'accueil avec autocomplétion villes françaises ✨
- [x] **US2** - Menu navigation responsive avec gestion auth ✨
- [x] **US7** - Système d'authentification complet (inscription/connexion) ✨
- [x] **US3** - Recherche covoiturages avec pagination et alternatives ✨ **DERNIÈRE**

### 🔄 **Phase 2 - Fonctionnalités avancées (EN COURS)**
- [ ] **US4** - Filtres avancés des covoiturages
- [ ] **US5** - Détail d'un covoiturage avec avis conducteur
- [ ] **US6** - Réservation de places avec système crédits
- [ ] **US8** - Espace utilisateur (profil, véhicules)
- [ ] **US9** - Proposition de covoiturage
- [ ] **US10** - Système d'avis et notes

### 📋 **Phase 3 - Administration**
- [ ] **US11** - Interface d'administration
- [ ] **US12** - Modération des contenus  
- [ ] **US13** - Statistiques et analytics

##  Fonctionnalités détaillées

### 🏠 **US1 - Page d'accueil** ✅
- **Hero section** avec image écologique et formulaire de recherche
- **Autocomplétion intelligente** des villes françaises (API geo.api.gouv.fr)
- **Responsive parfait** : iPhone SE, iPad Pro, Desktop
- **Performance optimisée** : Cache API, images WebP, < 200ms

### 🧭 **US2 - Menu navigation** ✅  
- **Menu burger mobile** avec animations fluides
- **Dropdown utilisateur** avec photos de profil dynamiques (RandomUser.me)
- **États actifs** avec indicateurs visuels
- **Gestion auth** : Connexion/déconnexion sécurisée

### 🔐 **US7 - Authentification** ✅
- **Inscription sécurisée** : Validation complète, 20 crédits offerts
- **Connexion robuste** : Sessions PHP, protection CSRF/XSS  
- **Dashboard utilisateur** : Tableau de bord personnalisé
- **Sécurité renforcée** : Rate limiting, honeypot anti-bot

### 🔍 **US3 - Recherche covoiturages** ✅ **DERNIÈRE IMPLÉMENTATION**
- **Recherche multicritères** : Ville départ/arrivée, date, passagers
- **Autocomplétion temps réel** avec navigation clavier
- **Cartes modernes** avec avatars chauffeurs et badges écologiques  
- **Pagination intelligente** : 6 résultats/page avec navigation
- **Alternatives automatiques** : Autres dates/villes si aucun résultat
- **Mode double** : Navigation générale + recherche ciblée

##  Base de données

### Structure MySQL optimisée
- **8 tables principales** : utilisateur, vehicule, covoiturage, participation, avis
- **Système de crédits** intégré avec table transaction_credit
- **Triggers de sécurité** pour contrôler les réservations  
- **Index optimisés** pour performances de recherche

### Données actuelles
- **utilisateur** : 10 comptes de test avec rôles variés
- **Véhicules** : 7 véhicules (3 électriques, 2 hybrides, 2 essence)
- **Covoiturages** : 9 trajets actifs (7 futurs, 2 passés)
- **Crédits en circulation** : 1,276 crédits répartis
- **Transactions** : 11 échanges effectués

### Documentation complète
Voir [`docs/documentation-bdd-mysql.md`](docs/documentation-bdd-mysql.md)

##  Architecture technique

### Modèle MVC respecté
```
app/
├── controllers/
│   ├── HomeController.php     # US1, US3 - Pages publiques et recherche
│   └── AuthController.php     # US7 - Authentification sécurisée
├── models/
│   └── UserModel.php          # US7 - Gestion utilisateurs et crédits
├── views/
│   ├── layouts/               # US2 - Header/Footer avec menu
│   ├── home/                  # US1 - Page d'accueil
│   ├── auth/                  # US7 - Inscription/Connexion  
│   ├── user/                  # US7 - Dashboard utilisateur
│   └── rides/                 # US3 - Recherche covoiturages
└── config/
    └── database.php           # Configuration PDO sécurisée
```

### Sécurité niveau professionnel
- **Authentification** : Hash Bcrypt, sessions sécurisées, regeneration ID
- **Protection XSS** : `htmlspecialchars()` sur tous les outputs  
- **Injection SQL** : Requêtes préparées PDO exclusivement
- **CSRF** : Tokens uniques par formulaire
- **Rate limiting** : 5 tentatives / 10 minutes
- **Validation** : Côté client JS + serveur PHP

##  Design et UX

### Charte graphique écologique
- **Couleurs principales** : 
  - `--primary-color: #2C3E20` (Vert principal)
  - `--eco-green: #435334` (Vert éco)  
  - `--secondary-color: #CEDEBD` (Vert secondaire)
- **Typography** : Roboto (Google Fonts)
- **Style** : Moderne, épuré, éco-responsable

### Responsive design avancé
- **Breakpoints optimisés** : 
  - iPhone SE (375px) ✅
  - iPad Pro (1024px) ✅  
  - Desktop 1920px+ ✅
- **Mobile-first** : Grilles adaptatives CSS Grid/Flexbox
- **Performance** : Images WebP, lazy loading, animations fluides

##  Tests et qualité

### Tests fonctionnels réalisés ✅

#### US1 - Page d'accueil
- ✅ Autocomplétion villes : Test "Par", "Mont", "Saint"
- ✅ Responsive : iPhone SE, iPad Pro, Desktop
- ✅ Performance API : < 500ms avec cache

#### US2 - Menu navigation  
- ✅ Burger mobile : Animations et fermeture auto
- ✅ États actifs : Indicateurs visuels corrects
- ✅ Dropdown utilisateur : Photos et navigation

#### US7 - Authentification
- ✅ Inscription : Validation complète, 20 crédits
- ✅ Connexion : Sessions sécurisées
- ✅ Sécurité : CSRF, rate limiting, honeypot

#### US3 - Recherche covoiturages
- ✅ Recherche multicritères : Paris→Lyon fonctionnel
- ✅ Pagination : Navigation 6 résultats/page
- ✅ Alternatives : Dates et villes automatiques
- ✅ Autocomplétion : Navigation clavier parfaite

### Métriques performance
- **Temps de réponse** : < 200ms (local), < 500ms (recherche)
- **Base de données** : Index optimisés, requêtes < 100ms
- **JavaScript** : Vanilla pour performance maximale
- **CSS** : Variables, optimisation bundle

##  Workflow Git conforme ECF

### Structure de branches
```
main (production stable)
├── develop (intégration)
    ├── feature/us1-page-accueil ✅
    ├── feature/us2-menu-navigation ✅  
    ├── feature/us7-authentification ✅
    └── feature/us3-recherche-covoiturages ✅ ACTUELLE
```

### Tags de version
- `v1.0-us1` - Page d'accueil avec autocomplétion
- `v1.1-us2` - Menu navigation responsive  
- `v1.2-us7` - Authentification complète
- `v1.3-us3` - Recherche covoiturages ✨ **DERNIÈRE**

##  Roadmap

### ✅ **Phase actuelle : Core Features (TERMINÉE)**
- [x] Architecture MVC solide
- [x] Authentification sécurisée 
- [x] Interface moderne responsive
- [x] Recherche et pagination
- [x] Base de données optimisée

### 🔄 **Prochaines étapes prioritaires**
1. **US4** - Filtres avancés (prix, durée, note)
2. **US5** - Page détail covoiturage avec avis
3. **US6** - Réservation avec système crédits
4. **US8** - Gestion profil et véhicules
5. **Déploiement production** sur Heroku/Vercel

##  Tests utilisateur

### Parcours complets testés ✅
1. **Visiteur → Inscription → Dashboard** (US7)
2. **Recherche covoiturage → Résultats → Pagination** (US3)  
3. **Navigation mobile → Menu burger → Pages** (US2)
4. **Autocomplétion → Sélection ville → Recherche** (US1+US3)

### Données de test cohérentes
- **10 utilisateurs** avec rôles variés (admin, chauffeur, passager)
- **9 covoiturages** avec véhicules électriques/hybrides/essence
- **Système crédits** opérationnel (20-551 crédits par user)

##  Documentation

### 📋 **Livrables ECF disponibles**
- **README.md** : Guide complet (ce fichier)
- **docs/us1-documentation.md** : US1 Page d'accueil détaillée
- **docs/us2-documentation.md** : US2 Menu navigation
- **docs/us3-documentation.md** : US3 Recherche covoiturages ✨
- **docs/us7-documentation.md** : US7 Authentification
- **database/ecoride_structure.sql** : Structure BDD complète
- **database/ecoride_data.sql** : Données de test

### 🔧 **Documentation technique**
- **Architecture MVC** : Séparation claire des responsabilités
- **Sécurité** : Mesures implémentées et validées
- **Performance** : Optimisations et métriques
- **Git Workflow** : Branches et tags conformes ECF

##  Contribution

Ce projet est développé dans le cadre d'un ECF (Evaluation en Cours de Formation) Studi. 

### Standards de code respectés
- **PSR-12** pour le code PHP
- **Semantic HTML5** pour la structure
- **BEM methodology** pour le CSS
- **Conventional Commits** pour les messages Git

##  Métriques du projet

### Code quality
- **Lignes de code** : ~2,500 lignes (PHP + HTML + CSS + JS)
- **Fichiers** : 25+ fichiers organisés MVC
- **Commentaires** : Code documenté et maintenable
- **Validation** : W3C HTML/CSS conformes

### Fonctionnalités livrées
- **4/13 User Stories** terminées (US1, US2, US3, US7)
- **100% des exigences ECF** respectées pour chaque US
- **Architecture évolutive** pour les prochaines US
- **Documentation complète** conforme standards

##  Déploiement

### Environnement de développement
- **Local** : XAMPP sur Windows
- **URL** : http://ecoride.local ou http://localhost/ecoride/public
- **Base** : MySQL locale avec données de test

### Production (Prévu)
- **Hébergement** : Heroku ou Vercel
- **Base de données** : MySQL ou PostgreSQL  
- **CI/CD** : GitHub Actions pour déploiement automatique

##  Contact et support

**👨‍💻 Développeur :** Léa Mukuna  
**🏫 Formation :** Développeur Web et Web Mobile - Studi  
**📅 Promotion :** Novembre - Décembre 2025  
**📧 Email :** [lea.mukuna@ecoride.fr]  

### Liens du projet
- **🔗 Repository GitHub :** [https://github.com/Leamuk1/Ecoride](https://github.com/Leamuk1/Ecoride)
- **🎨 Maquettes Figma :** [Lien vers maquettes](https://www.figma.com/design/SP2eWynqJNo68pc0cPaaao/EcoRide)
- **📋 Gestion projet :** Trello Kanban (conforme ECF)

---

##  Remerciements

- **👨‍🏫 Formateurs Studi** pour l'accompagnement technique
- **🌍 API Géographique française** pour les données de qualité  
- **💡 Communauté PHP/MySQL** pour la documentation
- **🎨 Font Awesome & Google Fonts** pour les ressources

---

 **⭐ N'hésitez pas à mettre une étoile si ce projet ECF vous intéresse !**

> **🎯 Status actuel :** US3 Recherche covoiturages terminée - Prêt pour US4 Filtres avancés#  EcoRide - Plateforme de Covoiturage Écologique

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
-  **Recherche multicritères** avec autocomplétion des villes françaises ✨ **NOUVEAU**
-  **Système de crédits** (monnaie virtuelle intégrée)
-  **Système d'évaluation** chauffeurs/passagers
-  **Impact écologique** calculé par trajet
-  **Gestion multi-rôles** (admin, employé, chauffeur, passager)
-  **Interface responsive** (desktop + mobile)
-  **Pagination intelligente** et alternatives de recherche ✨ **NOUVEAU**

##  Technologies utilisées

### Backend
- **PHP 8.x** - Langage principal
- **MySQL** - Base de données relationnelle
- **MongoDB** - Analytics et logs (phase future)
- **PDO** - Couche d'accès aux données

### Frontend  
- **HTML5 / CSS3** - Structure et style
- **JavaScript Vanilla** - Interactions côté client + Autocomplétion ✨
- **Responsive Design** - Compatible mobile
- **API geo.api.gouv.fr** - Autocomplétion villes françaises ✨ **NOUVEAU**

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
- **Connexion Internet** pour l'autocomplétion des villes ✨

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
SOURCE database/sql/create_tables
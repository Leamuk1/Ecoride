# Documentation US3 - Vue des covoiturages

## Description de l'User Story

**US 3 : Vue des covoiturages**  
**Utilisateur concerné :** Visiteur

Une vue est nécessaire afin de proposer une interface simple et récapitulative des covoiturages sur la plateforme.

## Objectifs

- Permettre aux visiteurs de rechercher des covoiturages selon des critères précis
- Afficher les résultats de recherche de manière claire et attrayante
- Fournir toutes les informations essentielles pour chaque trajet
- Identifier les trajets écologiques (véhicules électriques)
- Proposer des alternatives en cas d'absence de résultats

## Critères d'acceptation

### Fonctionnalités principales

1. **Formulaire de recherche obligatoire**
   - Par défaut, aucun covoiturage n'est visible
   - Le visiteur doit remplir un formulaire avec :
     - Adresse de départ
     - Adresse d'arrivée  
     - Date de voyage
     - Nombre de passagers (optionnel)

2. **Affichage des résultats**
   - Seuls les itinéraires avec au minimum une place disponible sont proposés
   - Chaque résultat affiche :
     - ✅ Le pseudo du chauffeur
     - ✅ La photo du chauffeur (avatar)
     - ✅ La note du chauffeur
     - ✅ Nombre de places restantes
     - ✅ Prix du trajet
     - ✅ Date et heure de départ/arrivée
     - ✅ Indication si c'est un voyage écologique
     - ✅ Un bouton "détail"

3. **Critères de recherche**
   - La recherche se base sur la ville et la date
   - Un voyage est considéré écologique s'il est effectué avec une voiture électrique

4. **Gestion des cas particuliers**
   - Si aucun trajet n'est disponible, proposer de modifier la date
   - Suggérer la date du premier itinéraire le plus proche

##  Implémentation technique

### Architecture MVC

#### Contrôleur : `HomeController.php`
```php
/**
 * Page covoiturages avec recherche US3 et pagination
 */
public function rides() {
    // Récupération des critères de recherche
    $departure = trim($_GET['departure'] ?? '');
    $arrival = trim($_GET['arrival'] ?? '');
    $date = trim($_GET['date'] ?? '');
    $passengers = (int)($_GET['passengers'] ?? 1);
    
    // Logique de recherche et pagination
    // ...
}
```

#### Méthodes principales

1. **`rechercherCovoiturages()`**
   - Construction dynamique de requête SQL avec filtres
   - Gestion des paramètres de recherche
   - Pagination intégrée
   - Formatage des données pour l'affichage

2. **`getAlternatives()`**
   - Recherche automatique d'alternatives
   - Alternatives de dates (±1 jour, ±1 semaine)
   - Alternatives de villes populaires

3. **`estimerDuree()` / `estimerDistance()`**
   - Calcul automatique des durées de trajet
   - Base de données des routes principales françaises
   - Estimation basique pour les trajets non répertoriés

### Base de données

#### Requête principale
```sql
SELECT 
    c.id_covoiturage,
    c.ville_depart,
    c.ville_arrivee,
    c.date_depart,
    c.heure_depart,
    c.prix_par_personne,
    c.nb_places_disponibles,
    c.commentaire,
    u.pseudo as conducteur_pseudo,
    u.prenom as conducteur_prenom,
    u.note_moyenne as conducteur_note,
    v.energie as type_vehicule,
    m.libelle as marque,
    v.modele,
    v.couleur
FROM covoiturage c
JOIN utilisateur u ON c.id_conducteur = u.id_utilisateur
LEFT JOIN vehicule v ON c.id_vehicule = v.id_vehicule
LEFT JOIN marque m ON v.id_marque = m.id_marque
WHERE c.statut = 'planifie'
AND c.date_depart > NOW()
AND c.nb_places_disponibles >= :passengers
```

#### Filtres dynamiques
- **Ville de départ :** `c.ville_depart LIKE :departure`
- **Ville d'arrivée :** `c.ville_arrivee LIKE :arrival`  
- **Date :** `DATE(c.date_depart) = :date`
- **Places disponibles :** `c.nb_places_disponibles >= :passengers`

### Vue : `rides/index.php`

#### Deux modes d'affichage

1. **Mode recherche** (`$isSearch = true`)
   - Affichage des critères de recherche
   - Liste des résultats trouvés
   - Alternatives automatiques si aucun résultat
   - Pagination des résultats

2. **Mode navigation** (`$isSearch = false`)
   - Hero section avec image de fond
   - Formulaire de recherche
   - Affichage de tous les trajets disponibles
   - Call-to-action

#### Autocomplétion des villes
- API gouvernementale française : `geo.api.gouv.fr`
- Cache des résultats pour optimiser les performances
- Navigation clavier (flèches, Entrée, Échap)
- Positionnement intelligent du dropdown

##  Interface utilisateur

### Design écologique
- **Palette de couleurs :** Tons verts (#435334, #9eb384, #CEDEBD)
- **Images de fond :** Visuels liés au covoiturage et à l'écologie
- **Badges visuels :** Identification claire des trajets écologiques

### Cartes de trajets
- **Header :** Trajet et prix mis en évidence
- **Corps :** Informations du conducteur avec avatar
- **Détails :** Véhicule, places, durée, distance
- **Actions :** Boutons Contacter, Détails, Réserver

### Responsive design
- Grille adaptative : `repeat(auto-fit, minmax(350px, 1fr))`
- Version mobile optimisée
- Autocomplétion adaptée aux écrans tactiles

##  Fonctionnalités avancées

### Pagination intelligente
- 6 trajets par page par défaut
- Navigation avec numéros de pages
- Boutons Précédent/Suivant
- Informations de pagination

### Alternatives automatiques
```php
/**
 * Rechercher des alternatives automatiquement
 */
private function getAlternatives($departure, $arrival, $date, $passengers) {
    $alternatives = [
        'dates' => [],      // Autres dates disponibles
        'villes' => []      // Villes similaires
    ];
    // ...
}
```

### Badges écologiques
- **Trajet Écologique :** Rectangle vert pour véhicules électriques
- **Type de véhicule :** Texte coloré simple (Électrique, Hybride, Essence, Diesel)

##  Sécurité

### Validation des données
- **Sanitisation :** `htmlspecialchars()` sur tous les outputs
- **Paramètres bindés :** Protection contre l'injection SQL
- **Types spécifiques :** `PDO::PARAM_INT` pour LIMIT/OFFSET

### Gestion d'erreurs
- **Try-catch :** Sur toutes les opérations de base de données
- **Logs :** `error_log()` pour traçabilité
- **Fallback :** Données par défaut en cas d'erreur

##  Performances

### Optimisations
- **Cache API :** Map des résultats d'autocomplétion
- **Requêtes optimisées :** JOINs efficaces et index appropriés
- **Pagination :** Limitation des résultats par page
- **Lazy loading :** Chargement progressif des données

### Métriques
- **Temps de recherche :** < 500ms pour une requête standard
- **Autocomplétion :** < 300ms avec mise en cache
- **Affichage :** 6 trajets par page pour une navigation fluide

##  Tests

### Cas de test principaux

1. **Recherche avec résultats**
   - ✅ Saisie : Paris → Lyon, date : demain
   - ✅ Résultat attendu : Liste des trajets disponibles

2. **Recherche sans résultat**
   - ✅ Saisie : Ville inexistante
   - ✅ Résultat attendu : Message + alternatives

3. **Autocomplétion**
   - ✅ Saisie : "Par"
   - ✅ Résultat attendu : Paris, Partenay, etc.

4. **Pagination**
   - ✅ Plus de 6 résultats
   - ✅ Résultat attendu : Navigation par pages

### Identifiants de test
- **Visiteur :** Accès libre à la recherche
- **Données test :** Trajets factices avec différents types de véhicules

##  Métriques de succès

- **Taux d'utilisation :** % de visiteurs utilisant la recherche
- **Taux de conversion :** % de recherches menant à une consultation de détails
- **Performance :** Temps de réponse moyen < 500ms
- **UX :** Taux d'utilisation de l'autocomplétion > 70%

##  Évolutions futures

### Fonctionnalités prévues
- **Filtres avancés :** Prix, durée, note du conducteur
- **Géolocalisation :** Recherche par proximité
- **Notifications :** Alertes pour nouveaux trajets
- **Historique :** Sauvegarde des recherches

### Optimisations techniques
- **API caching :** Redis pour l'autocomplétion
- **Search engine :** Elasticsearch pour recherche avancée
- **Real-time :** WebSockets pour mises à jour live

---

##  Notes de développement

**Statut :** ✅ Implémenté et testé  
**Version :** 1.0  
**Dernière mise à jour :** Décembre 2024  
**Développeur :** Léa Mukuna  
**Validation :** José (Directeur technique EcoRide)
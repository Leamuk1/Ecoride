# Documentation GitHub - EcoRide

## Vue d'ensemble

Cette documentation explique comment utiliser Git et GitHub pour le projet EcoRide, les bonnes pratiques à suivre, et les commandes essentielles.

## Structure des branches

### Branches principales

```
main (production)
├── develop (développement)
│   ├── feature/us1-homepage
│   ├── feature/us2-navigation
│   ├── feature/us7-authentication
│   └── feature/us3-ride-listing
└── hotfix/critical-bug (si nécessaire)
```

### Utilisation des branches

#### **main** (branche principale)
- **Usage** : Code stable et prêt pour production
- **Protection** : Ne jamais push directement
- **Mise à jour** : Uniquement via merge depuis `develop`

#### **develop** (branche de développement)
- **Usage** : Intégration de toutes les fonctionnalités
- **Base** : Pour créer les branches features
- **Tests** : Code testé avant merge vers `main`

#### **feature/*** (branches de fonctionnalités)
- **Nomenclature** : `feature/us1-homepage`, `feature/us7-auth`
- **Cycle de vie** : Créée depuis `develop` → supprimée après merge
- **Portée** : Une User Story par branche

## Workflow Git recommandé

### Workflow pour une nouvelle fonctionnalité

```bash
# 1. Se positionner sur develop et la mettre à jour
git checkout develop
git pull origin develop

# 2. Créer une nouvelle branche feature
git checkout -b feature/us1-homepage

# 3. Développer la fonctionnalité
# ... modifier les fichiers ...

# 4. Ajouter et commiter régulièrement
git add .
git commit -m "US1: Ajout de la structure HTML de la page d'accueil"

# 5. Pousser la branche sur GitHub
git push -u origin feature/us1-homepage

# 6. Une fois terminé, merger dans develop
git checkout develop
git merge feature/us1-homepage
git push origin develop

# 7. Supprimer la branche feature (optionnel)
git branch -d feature/us1-homepage
git push origin --delete feature/us1-homepage
```

## Convention des messages de commit

### Format recommandé

```
<type>(portée): <description>

[corps du message optionnel]

[footer optionnel]
```

### Types de commit

| Type | Description | Exemple |
|------|-------------|---------|
| `feat` | Nouvelle fonctionnalité | `feat(auth): ajout du système de connexion` |
| `fix` | Correction de bug | `fix(database): correction erreur connexion MySQL` |
| `docs` | Documentation | `docs: mise à jour du README` |
| `style` | Style/CSS | `style(homepage): amélioration responsive mobile` |
| `refactor` | Refactoring | `refactor(models): optimisation classe User` |
| `test` | Tests | `test(auth): ajout tests unitaires login` |
| 🔧 `chore` | Maintenance | `🔧 chore: mise à jour dépendances` |

### Exemples de bons commits

```bash
git commit -m "feat(US1): implémentation page d'accueil

- Structure HTML complète
- CSS responsive desktop/mobile  
- Intégration avec les maquettes Figma
- Liens vers inscription/connexion

Closes #1"

git commit -m "fix(database): correction trigger places disponibles"

git commit -m "docs(setup): ajout guide installation XAMPP"

git commit -m "style(navigation): amélioration menu mobile"
```

## Commandes Git essentielles

### Vérification de l'état

```bash
# Voir l'état actuel (fichiers modifiés, staged, etc.)
git status

# Voir l'historique des commits
git log --oneline --graph

# Voir les différences
git diff                    # Changements non stagés
git diff --staged          # Changements stagés
git diff HEAD~1            # Différences avec le commit précédent
```

### Gestion des branches

```bash
# Lister toutes les branches
git branch -a

# Créer et basculer sur une nouvelle branche
git checkout -b feature/nouvelle-fonctionnalite

# Basculer sur une branche existante
git checkout develop

# Supprimer une branche locale
git branch -d feature/ancienne-branche

# Supprimer une branche distante
git push origin --delete feature/ancienne-branche
```

### Synchronisation avec GitHub

```bash
# Récupérer les dernières modifications
git fetch origin

# Récupérer et merger
git pull origin develop

# Envoyer les commits
git push origin develop

# Envoyer une nouvelle branche
git push -u origin feature/ma-branche
```

### Commandes de récupération

```bash
# Annuler les modifications non committées
git checkout -- filename.php
git checkout .                # Tous les fichiers

# Annuler le dernier commit (garder les modifications)
git reset HEAD~1

# Annuler le dernier commit (supprimer les modifications)
git reset --hard HEAD~1

# Revenir à un commit spécifique
git reset --hard abc123def

# Créer un commit qui annule un autre commit
git revert abc123def
```

## Système de tags et releases

### Créer une release

```bash
# Créer un tag pour une version
git tag -a v1.0.0 -m "Version 1.0.0 - MVP EcoRide

✅ Système d'authentification
✅ Gestion des covoiturages  
✅ Système de crédits
✅ Interface responsive"

# Pousser le tag sur GitHub
git push origin v1.0.0

# Pousser tous les tags
git push origin --tags
```

### Convention de versioning

**Format** : `vMAJOR.MINOR.PATCH`

- **MAJOR** : Changements incompatibles
- **MINOR** : Nouvelles fonctionnalités compatibles
- **PATCH** : Corrections de bugs

**Exemples** :
- `v0.1.0` : Premier prototype
- `v0.2.0` : Ajout authentification
- `v0.2.1` : Correction bug connexion
- `v1.0.0` : MVP complet

## Gestion des fichiers sensibles

### .gitignore important

```gitignore
# Configuration sensible
app/config/database.php
.env
*.key

# Uploads utilisateurs
public/uploads/*
!public/uploads/.gitkeep

# Logs et cache
logs/*
cache/*
*.log

# Dépendances
vendor/
node_modules/
```

### Sécurité des credentials

```bash
# Si tu as commité des infos sensibles par erreur
git rm --cached app/config/database.php
git commit -m "security: suppression fichier config sensible"

# Puis ajouter le fichier au .gitignore
echo "app/config/database.php" >> .gitignore
git add .gitignore
git commit -m "🔧 chore: ajout config au gitignore"
```

## Collaboration et Pull Requests

###  Processus de Pull Request

1. **Créer une branche feature**
```bash
git checkout -b feature/us3-ride-listing
```

2. **Développer et commiter**
```bash
git add .
git commit -m "feat(US3): ajout liste covoiturages avec filtres"
```

3. **Pousser la branche**
```bash
git push -u origin feature/us3-ride-listing
```

4. **Créer la Pull Request sur GitHub**
- Aller sur GitHub.com
- "Compare & pull request"
- Base: `develop` ← Compare: `feature/us3-ride-listing`
- Décrire les changements
- Assigner des reviewers si nécessaire

###  Template Pull Request

```markdown
##  Description
Implémentation de la User Story US3 - Liste des covoiturages

## Changements
- [x] Affichage de la liste des covoiturages
- [x] Filtres par ville de départ/arrivée
- [x] Filtre par type de véhicule (écologique)
- [x] Pagination des résultats
- [x] Design responsive

## Tests effectués
- [x] Affichage correct sur desktop
- [x] Affichage correct sur mobile
- [x] Filtres fonctionnels
- [x] Pagination fonctionnelle

## Screenshots
![Desktop](link-vers-screenshot-desktop)
![Mobile](link-vers-screenshot-mobile)

## Liens
- Maquette Figma: [lien]
- Issue liée: #3
```

## Suivi et métriques

### Commandes de statistiques

```bash
# Nombre de commits par auteur
git shortlog -sn

# Activité récente
git log --since="1 week ago" --oneline

# Taille du repository
git count-objects -vH

# Fichiers les plus modifiés
git log --name-only --pretty=format: | sort | uniq -c | sort -nr
```

###  Checklist avant commit

- [ ] Code testé localement 
- [ ] Pas de `console.log()` ou `var_dump()` 
- [ ] Respect des conventions de nommage 
- [ ] Fichiers sensibles dans .gitignore 
- [ ] Message de commit explicite 
- [ ] Fonctionnalité complète 

##  Résolution de problèmes courants

###  Problèmes fréquents

#### **Conflit de merge**
```bash
# Lors d'un merge conflict
git status                    # Voir les fichiers en conflit
# Éditer les fichiers pour résoudre les conflits
git add fichier-resolu.php
git commit -m " merge: résolution conflit US1/US2"
```

#### **Mauvaise branche pour le commit**
```bash
# Tu as commité sur main au lieu de develop
git log --oneline -3          # Voir les derniers commits
git checkout develop
git cherry-pick abc123def     # Récupérer le commit
git checkout main
git reset --hard HEAD~1       # Supprimer de main
```

#### **Fichier supprimé par erreur**
```bash
git checkout HEAD~1 -- fichier-supprime.php
```

###  Aide et ressources

#### **Commandes d'aide Git**
```bash
git help                      # Aide générale
git help commit              # Aide sur une commande spécifique
git help -a                  # Liste toutes les commandes
```

#### **Liens utiles**
- [Documentation Git officielle](https://git-scm.com/docs)
- [GitHub Guides](https://guides.github.com/)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [Gitignore.io](https://www.toptal.com/developers/gitignore)

##  Checklist projet EcoRide

### Setup initial (terminé)
- [x] Repository créé et configuré
- [x] Branches main/develop créées
- [x] .gitignore configuré
- [x] Documentation initiale
- [x] Structure MVC commitée

###  Prochaines étapes
- [ ] Branche feature/us1-homepage
- [ ] Développement User Stories
- [ ] Tests et validation
- [ ] Release v1.0.0

---

##  Support

Pour toute question sur Git/GitHub dans le contexte d'EcoRide :

1. **Consulter cette documentation** 
2. **Vérifier les issues GitHub** 
3. **Demander de l'aide** si nécessaire 

**Dernière mise à jour** : Juillet 2025  
**Version** : 1.0
# =====================================
# COMMANDES GIT POUR US5
# =====================================

# 1. Vérifier le statut actuel
git status

# 2. Ajouter tous les fichiers modifiés/créés pour US5
git add .

# Ou ajouter spécifiquement les fichiers US5 :
# git add app/controllers/HomeController.php
# git add app/views/rides/detail.php  
# git add public/css/pages/ride-detail.css
# git add public/index.php

# 3. Commit avec message descriptif
git commit -m " US5: Implémentation page détail covoiturage

 Fonctionnalités ajoutées:
- Page détail complète (/rides?id=X)
- Affichage infos trajet (route, date, prix, places)
- Profil conducteur (nom, note, ancienneté)
- Détails véhicule (marque, énergie, places)
- Préférences voyage (fumeur, animaux, musique)
- Section avis passagers
- Boutons actions (participer/contacter)
- Design responsive mobile/desktop

 Technique:
- Routage intelligent avec paramètre ID
- Méthodes HomeController::rideDetail() et getRideDetails()
- Template detail.php avec sections modulaires
- CSS spécifique ride-detail.css
- Sécurité XSS et injection SQL
- Gestion erreurs avec redirections

 UX:
- Navigation retour vers liste
- États connexion (connecté/non-connecté)
- Codes couleur préférences (vert/rouge)
- Boutons adaptatifs selon places disponibles
- Messages informatifs et préparation US6

 Tous les critères Trello validés"

# 4. Push vers GitHub
git push origin main

# OU si tu travailles sur une branche dédiée :
# git checkout -b feature/us5-detail-covoiturage
# git push origin feature/us5-detail-covoiturage

# =====================================
# COMMANDES ALTERNATIVES
# =====================================

# Si tu veux créer une branche spécifique pour US5 :
git checkout -b feature/us5-page-detail
git add .
git commit -m " US5: Page détail covoiturage complète"
git push origin feature/us5-page-detail

# Ensuite merge dans main :
git checkout main
git merge feature/us5-page-detail
git push origin main

# =====================================
# VÉRIFICATIONS POST-PUSH
# =====================================

# Vérifier que le push s'est bien passé
git log --oneline -5

# Vérifier l'état du repository
git status

# =====================================
# CRÉATION TAG VERSION (OPTIONNEL)
# =====================================

# Créer un tag pour marquer cette version
git tag -a v1.5.0 -m "US5: Page détail covoiturage"
git push origin v1.5.0
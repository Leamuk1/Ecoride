<?php
// app/views/rides/detail.php - Page détail covoiturage US5
?>

<!-- CSS spécifique pour la page détail -->
<link rel="stylesheet" href="/css/pages/ride-detail.css">

<main class="ride-detail-main">
    <div class="container">
        
        <!-- Navigation de retour -->
        <div class="back-navigation">
            <a href="/ecoride/public/rides" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Retour aux covoiturages
            </a>
        </div>

        <!-- En-tête du trajet -->
        <section class="ride-header">
            <div class="ride-route">
                <h1><?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?></h1>
                <div class="ride-datetime">
                    <i class="fas fa-calendar"></i>
                    <?= $ride['formatted_date'] ?> à <?= $ride['formatted_time'] ?>
                </div>
                <div class="ride-info">
                    <span class="duration">
                        <i class="fas fa-clock"></i>
                        <?= $ride['duree_estimee'] ?>
                    </span>
                    <span class="distance">
                        <i class="fas fa-route"></i>
                        <?= $ride['distance_estimee'] ?> km
                    </span>
                </div>
            </div>
            
            <div class="ride-price">
                <div class="price-amount"><?= (int)$ride['prix_par_personne'] ?> crédits</div>
                <div class="price-per">par personne</div>
                <div class="places-available">
                    <?= $ride['nb_places_disponibles'] ?> place<?= $ride['nb_places_disponibles'] > 1 ? 's' : '' ?> disponible<?= $ride['nb_places_disponibles'] > 1 ? 's' : '' ?>
                </div>
            </div>
        </section>

        <!-- Contenu principal -->
        <div class="detail-content">
            
            <!-- Informations du conducteur -->
            <section class="driver-section">
                <h2>Votre conducteur</h2>
                
                <div class="driver-card">
                    <div class="driver-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    
                    <div class="driver-info">
                        <h3><?= htmlspecialchars($ride['conducteur_prenom']) ?></h3>
                        <p class="driver-pseudo">@<?= htmlspecialchars($ride['conducteur_pseudo']) ?></p>
                        
                        <div class="driver-stats">
                            <div class="stat">
                                <span class="label">Note moyenne :</span>
                                <?php if ($ride['conducteur_note']): ?>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= $ride['conducteur_note'] ? 'active' : '' ?>"></i>
                                        <?php endfor; ?>
                                        <span><?= round($ride['conducteur_note'], 1) ?>/5</span>
                                    </div>
                                <?php else: ?>
                                    <span>Nouveau conducteur</span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="stat">
                                <span class="label">Membre depuis :</span>
                                <span><?= $ride['conducteur_depuis_format'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($ride['commentaire'])): ?>
                <div class="trip-message">
                    <h4>Message du conducteur :</h4>
                    <p><?= htmlspecialchars($ride['commentaire']) ?></p>
                </div>
                <?php endif; ?>
            </section>

            <!-- Informations du véhicule -->
            <section class="vehicle-section">
                <h2>Véhicule</h2>
                
                <div class="vehicle-card">
                    <div class="vehicle-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    
                    <div class="vehicle-details">
                        <h3><?= htmlspecialchars($ride['marque']) ?> <?= htmlspecialchars($ride['modele']) ?></h3>
                        
                        <div class="vehicle-specs">
                            <div class="spec">
                                <span class="label">Couleur :</span>
                                <span><?= htmlspecialchars($ride['couleur']) ?></span>
                            </div>
                            
                            <div class="spec">
                                <span class="label">Énergie :</span>
                                <span class="energy-type"><?= $ride['eco_badge']['text'] ?></span>
                            </div>
                            
                            <div class="spec">
                                <span class="label">Places totales :</span>
                                <span><?= $ride['nombre_places'] ?> places</span>
                            </div>
                        </div>
                        
                        <?php if ($ride['type_vehicule'] === 'electrique'): ?>
                        <div class="eco-badge">
                            <i class="fas fa-leaf"></i>
                            Trajet 100% écologique
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

<!-- Préférences de voyage -->
<?php if (isset($ride['fumeur']) || isset($ride['animal']) || isset($ride['musique']) || isset($ride['discussion'])): ?>
<section class="preferences-section">
    <h2>Préférences de voyage</h2>
    
    <div class="preferences-grid">
        <?php if (isset($ride['fumeur'])): ?>
        <div class="preference-item <?= $ride['fumeur'] == 1 ? 'allowed' : 'forbidden' ?>">
            <i class="fas <?= $ride['fumeur'] == 1 ? 'fa-smoking' : 'fa-smoking-ban' ?>"></i>
            <span><?= $ride['fumeur'] == 1 ? 'Fumeur accepté' : 'Non-fumeur' ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($ride['animal'])): ?>
        <div class="preference-item <?= $ride['animal'] == 1 ? 'allowed' : 'forbidden' ?>">
            <i class="fas fa-paw"></i>
            <span><?= $ride['animal'] == 1 ? 'Animaux acceptés' : 'Pas d\'animaux' ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($ride['musique'])): ?>
        <div class="preference-item <?= $ride['musique'] == 1 ? 'allowed' : 'forbidden' ?>">
            <i class="fas fa-music"></i>
            <span><?= $ride['musique'] == 1 ? 'Musique pendant le trajet' : 'Trajet silencieux' ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($ride['discussion'])): ?>
        <div class="preference-item <?= $ride['discussion'] == 1 ? 'allowed' : 'forbidden' ?>">
            <i class="fas fa-comments"></i>
            <span><?= $ride['discussion'] == 1 ? 'Aime discuter' : 'Voyage tranquille' ?></span>
        </div>
        <?php endif; ?>
    </div>
    
    <?php if (!empty($ride['autres_preferences'])): ?>
    <div class="other-preferences">
        <h4>Autres préférences :</h4>
        <p><?= htmlspecialchars($ride['autres_preferences']) ?></p>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>


            <!-- Avis des passagers -->
            <?php if (!empty($reviews)): ?>
            <section class="reviews-section">
                <h2>Avis des passagers (<?= count($reviews) ?>)</h2>
                
                <div class="reviews-list">
                    <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="reviewer-name"><?= htmlspecialchars($review['passager_prenom']) ?></span>
                            <span class="review-date"><?= $review['formatted_date'] ?></span>
                        </div>
                        
                        <div class="review-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?= $i <= $review['note'] ? 'active' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if (!empty($review['commentaire'])): ?>
                        <div class="review-comment">
                            <?= htmlspecialchars($review['commentaire']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php else: ?>
            <section class="reviews-section">
                <h2>Avis des passagers</h2>
                <p class="no-reviews">Aucun avis pour le moment.</p>
            </section>
            <?php endif; ?>

            <!-- US6: MISE À JOUR DU BOUTON PARTICIPER DANS detail.php -->
<!-- Remplacer la section actions dans app/views/rides/detail.php -->

<!-- Actions -->
<section class="actions-section">
    <div class="action-buttons">
        <?php if ($ride['nb_places_disponibles'] > 0): ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- NOUVEAU BOUTON US6 FONCTIONNEL -->
                <button class="btn-participate" id="btnParticiper" 
                        data-id-covoiturage="<?= $ride['id_covoiturage'] ?>"
                        data-prix="<?= $ride['prix_par_personne'] ?>"
                        data-places-dispo="<?= $ride['nb_places_disponibles'] ?>">
                    <i class="fas fa-check"></i>
                    Participer à ce covoiturage
                </button>
            <?php else: ?>
                <a href="/ecoride/public/auth/login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter pour participer
                </a>
            <?php endif; ?>
        <?php else: ?>
            <button class="btn-full" disabled>
                <i class="fas fa-times"></i>
                Covoiturage complet
            </button>
        <?php endif; ?>
        
        <button class="btn-contact">
            <i class="fas fa-envelope"></i>
            Contacter le conducteur
        </button>
    </div>
</section>

<!-- US6: MODAL DE CONFIRMATION DOUBLE -->
<div id="modalReservation" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <!-- Étape 1: Sélection nombre de places -->
        <div id="modalStep1" class="modal-step active">
            <div class="modal-header">
                <h3>Réserver votre place</h3>
                <button type="button" class="modal-close" onclick="closeReservationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="trip-recap">
                    <h4>Récapitulatif du trajet</h4>
                    <div class="recap-info">
                        <div class="recap-route">
                            <strong><?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?></strong>
                        </div>
                        <div class="recap-datetime">
                            <?= $ride['formatted_date'] ?> à <?= $ride['formatted_time'] ?>
                        </div>
                        <div class="recap-driver">
                            Conducteur : <?= htmlspecialchars($ride['conducteur_prenom']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="places-selection">
                    <label for="nbPlaces">Nombre de places à réserver :</label>
                    <select id="nbPlaces" class="form-select">
                        <?php for ($i = 1; $i <= min(4, $ride['nb_places_disponibles']); $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> place<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="price-calculation">
                    <div class="price-line">
                        <span>Prix par personne :</span>
                        <span class="price-value"><?= (int)$ride['prix_par_personne'] ?> crédits</span>
                    </div>
                    <div class="price-line total">
                        <span>Total à payer :</span>
                        <span class="price-total" id="priceTotal"><?= (int)$ride['prix_par_personne'] ?> crédits</span>
                    </div>
                </div>
                
                <div class="user-credits">
                    <div class="credits-info">
                        <i class="fas fa-coins"></i>
                        <span>Votre solde : <strong id="userCredits">Chargement...</strong> crédits</span>
                    </div>
                    <div class="credits-warning" id="creditsWarning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Crédits insuffisants</span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeReservationModal()">
                    Annuler
                </button>
                <button type="button" class="btn-primary" id="btnContinuer" onclick="goToStep2()">
                    Continuer
                </button>
            </div>
        </div>
        
        <!-- Étape 2: Confirmation finale -->
        <div id="modalStep2" class="modal-step">
            <div class="modal-header">
                <h3>Confirmer votre réservation</h3>
                <button type="button" class="modal-close" onclick="closeReservationModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-body">
                <div class="confirmation-warning">
                    <i class="fas fa-info-circle"></i>
                    <p><strong>Attention :</strong> Cette action va débiter vos crédits immédiatement. 
                    Vous pourrez annuler votre réservation jusqu'à 24h avant le départ.</p>
                </div>
                
                <div class="final-recap">
                    <h4>Récapitulatif final</h4>
                    <div class="recap-grid">
                        <div class="recap-item">
                            <span class="label">Trajet :</span>
                            <span class="value"><?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?></span>
                        </div>
                        <div class="recap-item">
                            <span class="label">Date :</span>
                            <span class="value"><?= $ride['formatted_date'] ?> à <?= $ride['formatted_time'] ?></span>
                        </div>
                        <div class="recap-item">
                            <span class="label">Places :</span>
                            <span class="value" id="finalPlaces">1 place</span>
                        </div>
                        <div class="recap-item">
                            <span class="label">Montant :</span>
                            <span class="value final-price" id="finalPrice">0 crédits</span>
                        </div>
                        <div class="recap-item">
                            <span class="label">Conducteur :</span>
                            <span class="value"><?= htmlspecialchars($ride['conducteur_prenom']) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="terms-acceptance">
                    <label class="checkbox-container">
                        <input type="checkbox" id="acceptTerms">
                        <span class="checkmark"></span>
                        J'accepte les <a href="#" target="_blank">conditions de réservation</a>
                    </label>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="goToStep1()">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </button>
                <button type="button" class="btn-primary" id="btnConfirmer" onclick="confirmerReservation()" disabled>
                    <i class="fas fa-credit-card"></i>
                    Confirmer et payer
                </button>
            </div>
        </div>
        
        <!-- Loading overlay -->
        <div id="modalLoading" class="modal-loading" style="display: none;">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Traitement de votre réservation...</p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript US6 -->
<script>
// JavaScript US6 - avec prix entiers
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentCovoiturage = null;
    let userCredits = 0;
    
    // Bouton participer principal
    const btnParticiper = document.getElementById('btnParticiper');
    if (btnParticiper) {
        btnParticiper.addEventListener('click', function() {
            currentCovoiturage = {
                id: this.dataset.idCovoiturage,
                prix: parseInt(this.dataset.prix), // CHANGÉ: parseInt au lieu de parseFloat
                placesDisponibles: parseInt(this.dataset.placesDispo)
            };
            
            openReservationModal();
        });
    }
    
    // Gestion du nombre de places
    const nbPlacesSelect = document.getElementById('nbPlaces');
    if (nbPlacesSelect) {
        nbPlacesSelect.addEventListener('change', updatePriceCalculation);
    }
    
    // Gestion des conditions
    const acceptTerms = document.getElementById('acceptTerms');
    if (acceptTerms) {
        acceptTerms.addEventListener('change', function() {
            document.getElementById('btnConfirmer').disabled = !this.checked;
        });
    }
    
    /**
     * Ouvrir la modal de réservation
     */
    function openReservationModal() {
        // Récupérer le solde utilisateur
        fetchUserCredits();
        
        // Reset de la modal
        document.getElementById('modalStep1').classList.add('active');
        document.getElementById('modalStep2').classList.remove('active');
        document.getElementById('nbPlaces').value = '1';
        document.getElementById('acceptTerms').checked = false;
        
        // Afficher la modal
        document.getElementById('modalReservation').style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Calculer le prix initial
        updatePriceCalculation();
    }
    
    /**
     * Fermer la modal
     */
    window.closeReservationModal = function() {
        document.getElementById('modalReservation').style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    /**
     * Récupérer le solde de crédits utilisateur
     */
    function fetchUserCredits() {
        // Utiliser la valeur PHP directement
        userCredits = <?= $user_credits ?? 0 ?>;
        document.getElementById('userCredits').textContent = userCredits;
        updatePriceCalculation();
    }
    
    /**
     * Mettre à jour le calcul du prix ( PRIX ENTIERS)
     */
    function updatePriceCalculation() {
        if (!currentCovoiturage) return;
        
        const nbPlaces = parseInt(document.getElementById('nbPlaces').value);
        const total = currentCovoiturage.prix * nbPlaces; // Déjà des entiers
        
        // Mettre à jour l'affichage (SANS décimales)
        document.getElementById('priceTotal').textContent = total + ' crédits';
        
        // Vérifier si l'utilisateur a assez de crédits
        const creditsWarning = document.getElementById('creditsWarning');
        const btnContinuer = document.getElementById('btnContinuer');
        
        if (total > userCredits) {
            creditsWarning.style.display = 'flex';
            btnContinuer.disabled = true;
            btnContinuer.textContent = 'Crédits insuffisants';
        } else {
            creditsWarning.style.display = 'none';
            btnContinuer.disabled = false;
            btnContinuer.textContent = 'Continuer';
        }
    }
    
    /**
     * Passer à l'étape 2
     */
    window.goToStep2 = function() {
        const nbPlaces = parseInt(document.getElementById('nbPlaces').value);
        const total = currentCovoiturage.prix * nbPlaces;
        
        // Mettre à jour le récapitulatif final (SANS décimales)
        document.getElementById('finalPlaces').textContent = nbPlaces + ' place' + (nbPlaces > 1 ? 's' : '');
        document.getElementById('finalPrice').textContent = total + ' crédits';
        
        // Changer d'étape
        document.getElementById('modalStep1').classList.remove('active');
        document.getElementById('modalStep2').classList.add('active');
    }
    
    /**
     * Retourner à l'étape 1
     */
    window.goToStep1 = function() {
        document.getElementById('modalStep2').classList.remove('active');
        document.getElementById('modalStep1').classList.add('active');
    }
    
    /**
     * Confirmer la réservation
     */
    window.confirmerReservation = function() {
        if (!document.getElementById('acceptTerms').checked) {
            alert('Vous devez accepter les conditions de réservation');
            return;
        }
        
        // Afficher le loading
        document.getElementById('modalLoading').style.display = 'flex';
        
        // Préparer les données
        const formData = new FormData();
        formData.append('id_covoiturage', currentCovoiturage.id);
        formData.append('nb_places', document.getElementById('nbPlaces').value);
        formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?>');
        
        // Envoyer la requête
        fetch('/ecoride/public/reservation/create', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Masquer le loading
            document.getElementById('modalLoading').style.display = 'none';
            
            if (data.success) {
                // Succès
                showSuccessMessage(data.message, data.data);
                closeReservationModal();
                
                // Optionnel: recharger la page ou mettre à jour l'affichage
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                // Erreur
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            document.getElementById('modalLoading').style.display = 'none';
            showErrorMessage('Erreur de connexion. Veuillez réessayer.');
            console.error('Erreur:', error);
        });
    }
    
    /**
     * Afficher message de succès
     */
    function showSuccessMessage(message, data) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <div>
                <strong>Réservation confirmée !</strong>
                <p>${message}</p>
                ${data ? `<small>Nouveau solde : ${data.nouveau_solde} crédits</small>` : ''}
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    /**
     * Afficher message d'erreur
     */
    function showErrorMessage(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-error';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Erreur</strong>
                <p>${message}</p>
            </div>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    // Fermer la modal en cliquant en dehors
    document.getElementById('modalReservation').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReservationModal();
        }
    });
    
    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReservationModal();
        }
    });
});
</script>
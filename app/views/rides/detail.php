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
                <div class="price-amount"><?= number_format($ride['prix_par_personne'], 0) ?> crédits</div>
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

            <!-- Actions -->
            <section class="actions-section">
                <div class="action-buttons">
                    <?php if ($ride['nb_places_disponibles'] > 0): ?>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn-participate">
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
                
                <p class="action-note">
                    Le bouton "Participer" sera fonctionnel dans l'US6 (système de réservation).
                </p>
            </section>

        </div>
    </div>
</main>

<!-- JavaScript simple pour les interactions -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bouton participer (préparation US6)
    const btnParticipate = document.querySelector('.btn-participate');
    if (btnParticipate) {
        btnParticipate.addEventListener('click', function() {
            alert('Fonctionnalité de réservation à implémenter dans l\'US6 !');
        });
    }
    
    // Bouton contacter (préparation US future)
    const btnContact = document.querySelector('.btn-contact');
    if (btnContact) {
        btnContact.addEventListener('click', function() {
            alert('Système de messagerie à implémenter dans une prochaine US !');
        });
    }
});
</script>
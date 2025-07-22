<?php
// ==========================================
// app/views/profile/preferences.php - Page préférences
// Adapté pour utiliser header.php et footer.php existants
// ==========================================
?>

<!-- CSS spécifique à la page préférences -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/pages/user-profile.css">
<!-- CSS principal EcoRide pour le dropdown -->
<link rel="stylesheet" href="/css/styles.css">


<div class="preferences-container">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="profile-sidebar">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php if (isset($user['profile_picture']) && $user['profile_picture']): ?>
                                <img src="/ecoride/public/uploads/profile/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Photo de profil">
                            <?php else: ?>
                                <i class="fas fa-user-circle"></i>
                            <?php endif; ?>
                        </div>
                        <h3><?php echo htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '') ?: $user['pseudo'] ?? 'Utilisateur'); ?></h3>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                    </div>
                    
                    <nav class="profile-nav">
                        <a href="/ecoride/public/mon-profil" class="nav-link">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                        <a href="/ecoride/public/mon-vehicule" class="nav-link">
                            <i class="fas fa-car"></i> Mon Véhicule
                        </a>
                        <a href="/ecoride/public/mes-preferences" class="nav-link active">
                            <i class="fas fa-cog"></i> Préférences
                        </a>
                        <a href="/ecoride/public/dashboard" class="nav-link">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Contenu principal -->
            <div class="col-md-9 main-content">
                <div class="page-header">
                    <h1><i class="fas fa-cog"></i> Mes Préférences</h1>
                    <p class="text-muted">Personnalisez votre expérience de covoiturage</p>
                </div>
                
                <div class="row">
                    <!-- Préférences de voyage -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-route"></i> Préférences de voyage</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/ecoride/public/profile/update-preferences">
                                    <!-- Préférences générales -->
                                    <div class="preference-section">
                                        <h6><i class="fas fa-sliders-h"></i> Général</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rôle préféré</label>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="role_both" name="role_prefere" value="both" 
                                                               <?php echo (!isset($preferences['role_prefere']) || $preferences['role_prefere'] === 'both') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="role_both">
                                                            <i class="fas fa-exchange-alt"></i> Les deux
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="role_driver" name="role_prefere" value="driver"
                                                               <?php echo (isset($preferences['role_prefere']) && $preferences['role_prefere'] === 'driver') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="role_driver">
                                                            <i class="fas fa-steering-wheel"></i> Conducteur
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" id="role_passenger" name="role_prefere" value="passenger"
                                                               <?php echo (isset($preferences['role_prefere']) && $preferences['role_prefere'] === 'passenger') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="role_passenger">
                                                            <i class="fas fa-user-friends"></i> Passager
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Préférences de confort -->
                                    <div class="preference-section">
                                        <h6><i class="fas fa-heart"></i> Confort et ambiance</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="musique" name="preferences[]" value="musique"
                                                           <?php echo (isset($preferences['musique']) && $preferences['musique']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="musique">
                                                        <i class="fas fa-music"></i> J'aime la musique pendant le trajet
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="discussion" name="preferences[]" value="discussion"
                                                           <?php echo (isset($preferences['discussion']) && $preferences['discussion']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="discussion">
                                                        <i class="fas fa-comments"></i> J'aime discuter pendant le trajet
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="animaux" name="preferences[]" value="animaux"
                                                           <?php echo (isset($preferences['animal']) && $preferences['animal']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="animaux">
                                                        <i class="fas fa-paw"></i> J'accepte les animaux de compagnie
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="non_fumeur" name="preferences[]" value="non_fumeur"
                                                           <?php echo (!isset($preferences['fumeur']) || !$preferences['fumeur']) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="non_fumeur">
                                                        <i class="fas fa-smoking-ban"></i> Véhicule non-fumeur uniquement
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="climatisation" name="preferences[]" value="climatisation">
                                                    <label class="form-check-label" for="climatisation">
                                                        <i class="fas fa-snowflake"></i> Climatisation appréciée
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="bagages" name="preferences[]" value="bagages">
                                                    <label class="form-check-label" for="bagages">
                                                        <i class="fas fa-suitcase"></i> J'ai souvent des bagages
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Notifications -->
                                    <div class="preference-section">
                                        <h6><i class="fas fa-bell"></i> Notifications</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="notif_email" name="notifications[]" value="email" checked>
                                                    <label class="form-check-label" for="notif_email">
                                                        <i class="fas fa-envelope"></i> Notifications par email
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="notif_reservation" name="notifications[]" value="reservation" checked>
                                                    <label class="form-check-label" for="notif_reservation">
                                                        <i class="fas fa-calendar-check"></i> Nouvelles réservations
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="notif_rappels" name="notifications[]" value="rappels" checked>
                                                    <label class="form-check-label" for="notif_rappels">
                                                        <i class="fas fa-clock"></i> Rappels de trajets
                                                    </label>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" id="notif_messages" name="notifications[]" value="messages" checked>
                                                    <label class="form-check-label" for="notif_messages">
                                                        <i class="fas fa-comment"></i> Nouveaux messages
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Zone de recherche -->
                                    <div class="preference-section">
                                        <h6><i class="fas fa-map-marked-alt"></i> Zone de recherche</h6>
                                        
                                        <div class="mb-3">
                                            <label for="rayon_recherche" class="form-label">
                                                Rayon de recherche pour les trajets : 
                                                <span id="rayonValue" class="text-primary fw-bold">20</span> km
                                            </label>
                                            <input type="range" class="form-range" id="rayon_recherche" name="rayon_recherche" 
                                                   min="5" max="100" step="5" value="20"
                                                   oninput="document.getElementById('rayonValue').textContent = this.value">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="ville_principale" class="form-label">Ville principale</label>
                                            <input type="text" class="form-control" id="ville_principale" name="ville_principale" 
                                                   value=""
                                                   placeholder="ex: Paris, Lyon, Marseille...">
                                            <div class="form-text">Ville où vous effectuez le plus de trajets</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Autres préférences -->
                                    <div class="preference-section">
                                        <h6><i class="fas fa-comment-alt"></i> Autres préférences</h6>
                                        
                                        <div class="mb-3">
                                            <label for="autres_preferences" class="form-label">Commentaires additionnels</label>
                                            <textarea class="form-control" id="autres_preferences" name="autres_preferences" rows="3" 
                                                      placeholder="Décrivez vos autres préférences ou besoins spécifiques..."><?php echo htmlspecialchars($preferences['autres_preferences'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="/ecoride/public/dashboard" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Retour
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Sauvegarder mes préférences
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conseils et informations -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-info-circle"></i> À savoir</h5>
                            </div>
                            <div class="card-body">
                                <div class="tip-item">
                                    <i class="fas fa-shield-alt text-success"></i>
                                    <div>
                                        <strong>Confidentialité</strong>
                                        <small>Vos préférences ne sont visibles que par vous et utilisées pour améliorer vos recommandations.</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-search text-primary"></i>
                                    <div>
                                        <strong>Recherche optimisée</strong>
                                        <small>Nous utilisons vos préférences pour vous proposer les trajets les plus adaptés.</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-bell text-warning"></i>
                                    <div>
                                        <strong>Notifications</strong>
                                        <small>Restez informé des nouveaux trajets correspondant à vos critères.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-pie"></i> Mes statistiques</h5>
                            </div>
                            <div class="card-body">
                                <div class="tip-item">
                                    <i class="fas fa-route text-primary"></i>
                                    <div>
                                        <strong>0</strong>
                                        <small>Trajets effectués</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-users text-info"></i>
                                    <div>
                                        <strong>0</strong>
                                        <small>Covoitureurs rencontrés</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-euro-sign text-success"></i>
                                    <div>
                                        <strong>0€</strong>
                                        <small>Économies réalisées</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-leaf text-success"></i>
                                    <div>
                                        <strong>0 kg</strong>
                                        <small>CO² économisé</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-question-circle"></i> Besoin d'aide ?</h5>
                            </div>
                            <div class="card-body text-center">
                                <p class="text-muted">Des questions sur les préférences ?</p>
                                <a href="/ecoride/public/contact" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope"></i> Nous contacter
                                </a>
                                <a href="/ecoride/public/help" class="btn btn-outline-secondary btn-sm mt-2">
                                    <i class="fas fa-book"></i> Guide d'aide
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Messages flash pour les préférences -->
<?php if (isset($_SESSION['success_message'])): ?>
<div class="alert alert-success alert-dismissible fade show position-fixed" style="top: 100px; right: 20px; z-index: 1050;">
    <i class="fas fa-check-circle"></i>
    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['info_message'])): ?>
<div class="alert alert-info alert-dismissible fade show position-fixed" style="top: 100px; right: 20px; z-index: 1050;">
    <i class="fas fa-info-circle"></i>
    <?php echo $_SESSION['info_message']; unset($_SESSION['info_message']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du slider de rayon de recherche
    const rayonSlider = document.getElementById('rayon_recherche');
    const rayonValue = document.getElementById('rayonValue');
    
    if (rayonSlider && rayonValue) {
        rayonSlider.addEventListener('input', function() {
            rayonValue.textContent = this.value;
            
            // Changer la couleur selon la valeur
            if (this.value <= 20) {
                rayonValue.className = 'text-success fw-bold';
            } else if (this.value <= 50) {
                rayonValue.className = 'text-warning fw-bold';
            } else {
                rayonValue.className = 'text-danger fw-bold';
            }
        });
        
        // Initialiser la couleur
        rayonSlider.dispatchEvent(new Event('input'));
    }
    
    // Animation des sections de préférences
    const prefSections = document.querySelectorAll('.preference-section');
    prefSections.forEach(section => {
        section.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        section.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Gestion du formulaire
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Animation de soumission
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sauvegarde...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Auto-masquer les alertes après 5 secondes
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        });
    }, 5000);
    
    console.log('✅ Page Préférences US8 chargée');
    console.log('Nombre de sections de préférences:', prefSections.length);
});
</script>
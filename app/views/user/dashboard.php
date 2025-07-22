<?php
// ==========================================
// app/views/user/dashboard.php - Dashboard US8 
// ==========================================




// Récupérer les données utilisateur si nécessaire
if (!isset($user_data)) {
    require_once '../app/controllers/ProfileController.php';
    $profileController = new ProfileController();
    $user_data = $profileController->getUserProfile($_SESSION['user_id']);
}

$user_role = $_SESSION['user_role'] ?? 'both';
?>

<link rel="stylesheet" href="/css/pages/user-profile.css">

<div class="dashboard-container">
    <!-- En-tête du dashboard -->
    <div class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>
                        <i class="fas fa-tachometer-alt"></i>
                        Bonjour <?php echo htmlspecialchars($user_data['prenom'] ?? $user_data['pseudo'] ?? 'Utilisateur'); ?> !
                    </h1>
                    <p class="lead">Gérez vos trajets et votre profil EcoRide</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="user-stats">
                        <div class="stat-item">
                            <i class="fas fa-coins text-warning"></i>
                            <span><?php echo $user_data['credit'] ?? 0; ?> crédits</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Section Rôle utilisateur -->
        <div class="role-section mb-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-tag"></i> Mon rôle sur EcoRide</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="role-option <?php echo $user_role === 'passenger' ? 'active' : ''; ?>" 
                                 data-role="passenger">
                                <i class="fas fa-user-friends"></i>
                                <h5>Passager</h5>
                                <p>Je cherche des trajets</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="role-option <?php echo $user_role === 'driver' ? 'active' : ''; ?>" 
                                 data-role="driver">
                                <i class="fas fa-car"></i>
                                <h5>Conducteur</h5>
                                <p>Je propose des trajets</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="role-option <?php echo $user_role === 'both' ? 'active' : ''; ?>" 
                                 data-role="both">
                                <i class="fas fa-exchange-alt"></i>
                                <h5>Les deux</h5>
                                <p>Conducteur et passager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides contextuelles -->
        <div class="quick-actions mb-4" id="quickActions">
            <!-- Le contenu sera mis à jour dynamiquement selon le rôle -->
        </div>

        <!-- Statistiques et raccourcis -->
        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Mes trajets récents -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h4><i class="fas fa-route"></i> Mes derniers trajets</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-car fa-3x mb-3"></i>
                            <p>Aucun trajet récent</p>
                            <a href="/ecoride/public/rides" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i> Rechercher des trajets
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notifications / Messages -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-bell"></i> Notifications</h4>
                    </div>
                    <div class="card-body">
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-bell-slash fa-2x mb-3"></i>
                            <p>Aucune nouvelle notification</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Profil rapide -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user"></i> Mon profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            <i class="fas fa-user-circle fa-4x text-muted"></i>
                        </div>
                        <h6><?php echo htmlspecialchars(($user_data['prenom'] ?? '') . ' ' . ($user_data['nom'] ?? '')); ?></h6>
                        <p class="text-muted small"><?php echo htmlspecialchars($user_data['email'] ?? ''); ?></p>
                        
                        <div class="d-grid gap-2 mt-3">
                            <a href="/ecoride/public/mon-profil" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Modifier mon profil
                            </a>
                            <a href="/ecoride/public/mon-vehicule" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-car"></i> Mon véhicule
                            </a>
                            <a href="/ecoride/public/mes-preferences" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-cog"></i> Mes préférences
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-line"></i> Mes statistiques</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-row">
                            <div class="stat-item">
                                <i class="fas fa-route text-primary"></i>
                                <div>
                                    <strong>0</strong>
                                    <small>Trajets effectués</small>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-leaf text-success"></i>
                                <div>
                                    <strong>0 kg</strong>
                                    <small>CO² économisé</small>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-star text-warning"></i>
                                <div>
                                    <strong><?php echo number_format($user_data['note_moyenne'] ?? 5.0, 1); ?></strong>
                                    <small>Note moyenne</small>
                                </div>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-euro-sign text-info"></i>
                                <div>
                                    <strong>0€</strong>
                                    <small>Économies réalisées</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript pour l'interactivité -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du changement de rôle
    const roleOptions = document.querySelectorAll('.role-option');
    const quickActions = document.getElementById('quickActions');
    
    roleOptions.forEach(option => {
        option.addEventListener('click', function() {
            const role = this.dataset.role;
            
            // Mise à jour visuelle
            roleOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            // Mise à jour des actions rapides
            updateQuickActions(role);
            
            // Sauvegarder le rôle (AJAX)
            fetch('/ecoride/public/profile/update-role', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ role: role })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Rôle mis à jour:', data.role);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        });
    });
    
    // Initialiser les actions rapides selon le rôle actuel
    const currentRole = '<?php echo $user_role; ?>';
    updateQuickActions(currentRole);
    
    function updateQuickActions(role) {
        let actionsHTML = '<div class="row">';
        
        if (role === 'passenger') {
            actionsHTML += `
                <div class="col-md-6 mb-3">
                    <a href="/ecoride/public/rides" class="action-card d-block">
                        <i class="fas fa-search text-primary"></i>
                        <h5>Rechercher un trajet</h5>
                        <p>Trouvez votre prochain covoiturage</p>
                    </a>
                </div>
                <div class="col-md-6 mb-3">
                    <a href="/ecoride/public/mes-reservations" class="action-card d-block">
                        <i class="fas fa-calendar-check text-success"></i>
                        <h5>Mes réservations</h5>
                        <p>Gérez vos trajets réservés</p>
                    </a>
                </div>
            `;
        } else if (role === 'driver') {
            actionsHTML += `
                <div class="col-md-4 mb-3">
                    <a href="/ecoride/public/mon-vehicule" class="action-card d-block">
                        <i class="fas fa-car text-success"></i>
                        <h5>Mon véhicule</h5>
                        <p>Gérer mes informations véhicule</p>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="/ecoride/public/rides/create" class="action-card d-block">
                        <i class="fas fa-plus text-primary"></i>
                        <h5>Proposer un trajet</h5>
                        <p>Créer un nouveau covoiturage</p>
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="/ecoride/public/mes-trajets" class="action-card d-block">
                        <i class="fas fa-list text-info"></i>
                        <h5>Mes trajets</h5>
                        <p>Gérer mes covoiturages proposés</p>
                    </a>
                </div>
            `;
        } else { // both
            actionsHTML += `
                <div class="col-md-3 mb-3">
                    <a href="/ecoride/public/rides" class="action-card d-block">
                        <i class="fas fa-search text-primary"></i>
                        <h5>Rechercher</h5>
                        <p>Trouver un trajet</p>
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="/ecoride/public/rides/create" class="action-card d-block">
                        <i class="fas fa-plus text-success"></i>
                        <h5>Proposer</h5>
                        <p>Créer un trajet</p>
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="/ecoride/public/mon-vehicule" class="action-card d-block">
                        <i class="fas fa-car text-warning"></i>
                        <h5>Mon véhicule</h5>
                        <p>Gérer ma voiture</p>
                    </a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="/ecoride/public/mes-reservations" class="action-card d-block">
                        <i class="fas fa-calendar text-info"></i>
                        <h5>Mes trajets</h5>
                        <p>Toutes mes réservations</p>
                    </a>
                </div>
            `;
        }
        
        actionsHTML += '</div>';
        quickActions.innerHTML = actionsHTML;
    }
});
</script>
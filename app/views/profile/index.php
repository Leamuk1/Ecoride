<?php 

// ==========================================
// app/views/profile/index.php - Page profil utilisateur
// Adapté pour utiliser header.php et footer.php existants
// ==========================================
?>

<!-- CSS spécifique à la page profil -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/pages/user-profile.css">

<div class="profile-container">
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
                        <a href="/ecoride/public/mon-profil" class="nav-link active">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                        <a href="/ecoride/public/mon-vehicule" class="nav-link">
                            <i class="fas fa-car"></i> Mon Véhicule
                        </a>
                        <a href="/ecoride/public/mes-preferences" class="nav-link">
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
                    <h1><i class="fas fa-user"></i> Mon Profil</h1>
                    <p class="text-muted">Gérez vos informations personnelles et votre profil EcoRide</p>
                </div>
                
                <div class="row">
                    <!-- Informations personnelles -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-id-card"></i> Informations personnelles</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/ecoride/public/profile/update">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="prenom" class="form-label">Prénom</label>
                                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                                       value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nom" class="form-label">Nom</label>
                                                <input type="text" class="form-control" id="nom" name="nom" 
                                                       value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="pseudo" class="form-label">Pseudo</label>
                                        <input type="text" class="form-control" id="pseudo" name="pseudo" 
                                               value="<?php echo htmlspecialchars($user['pseudo'] ?? ''); ?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="telephone" class="form-label">Téléphone</label>
                                        <input type="tel" class="form-control" id="telephone" name="telephone" 
                                               value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="credits" class="form-label">Crédits</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="credits" 
                                                   value="<?php echo $user['credit'] ?? 0; ?>" readonly>
                                            <span class="input-group-text">
                                                <i class="fas fa-coins text-warning"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        Les informations personnelles ne peuvent être modifiées que par un administrateur.
                                        Pour toute demande de modification, contactez le support.
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions rapides -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-bolt"></i> Actions rapides</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="/ecoride/public/mon-vehicule" class="btn btn-outline-primary">
                                        <i class="fas fa-car"></i> Gérer mon véhicule
                                    </a>
                                    <a href="/ecoride/public/mes-preferences" class="btn btn-outline-info">
                                        <i class="fas fa-cog"></i> Mes préférences
                                    </a>
                                    <a href="/ecoride/public/upload-photo" class="btn btn-outline-warning">
                                        <i class="fas fa-camera"></i> Changer ma photo
                                    </a>
                                    <a href="/ecoride/public/dashboard" class="btn btn-success">
                                        <i class="fas fa-tachometer-alt"></i> Retour au dashboard
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
                                        <strong><?php echo number_format($user['note_moyenne'] ?? 5.0, 1); ?></strong>
                                        <small>Note moyenne</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
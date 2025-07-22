<?php
// ==========================================
// app/views/profile/vehicle.php - Page gestion véhicule
// Sans CSS - utilise user-profile.css externe
// ==========================================
?>

<!-- CSS externe -->
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
                        <a href="/ecoride/public/mon-profil" class="nav-link">
                            <i class="fas fa-user"></i> Mon Profil
                        </a>
                        <a href="/ecoride/public/mon-vehicule" class="nav-link active">
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
                    <h1><i class="fas fa-car"></i> Mon Véhicule</h1>
                    <p>Gérez les informations de votre véhicule pour le covoiturage</p>
                </div>
                
                <!-- Alerte si pas de véhicule -->
                <?php if (empty($vehicles)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Aucun véhicule enregistré</strong><br>
                    Pour proposer des trajets en tant que conducteur, vous devez d'abord enregistrer votre véhicule.
                </div>
                <?php endif; ?>
                
                <div class="row">
                    <!-- Formulaire d'ajout/modification -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-plus"></i> 
                                    <?php echo empty($vehicles) ? 'Ajouter mon véhicule' : 'Modifier mon véhicule'; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/ecoride/public/profile/add-vehicle" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="marque" class="form-label">Marque *</label>
                                                <input type="text" class="form-control" id="marque" name="marque" 
                                                       placeholder="ex: Peugeot, Renault..." required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="modele" class="form-label">Modèle *</label>
                                                <input type="text" class="form-control" id="modele" name="modele" 
                                                       placeholder="ex: 208, Clio..." required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="annee" class="form-label">Année</label>
                                                <input type="number" class="form-control" id="annee" name="annee" 
                                                       min="1990" max="2024" placeholder="2020">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="couleur" class="form-label">Couleur</label>
                                                <input type="text" class="form-control" id="couleur" name="couleur" 
                                                       placeholder="ex: Rouge, Blanc...">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="immatriculation" class="form-label">Immatriculation *</label>
                                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" 
                                               placeholder="ex: AB-123-CD" required>
                                        <div class="form-text">Format: XX-XXX-XX</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="nb_places" class="form-label">Nombre de places disponibles *</label>
                                                <select class="form-control" id="nb_places" name="nb_places" required>
                                                    <option value="">Choisir...</option>
                                                    <option value="1">1 place</option>
                                                    <option value="2">2 places</option>
                                                    <option value="3">3 places</option>
                                                    <option value="4">4 places</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="carburant" class="form-label">Type de carburant</label>
                                                <select class="form-control" id="carburant" name="carburant">
                                                    <option value="">Choisir...</option>
                                                    <option value="essence">Essence</option>
                                                    <option value="diesel">Diesel</option>
                                                    <option value="hybride">Hybride</option>
                                                    <option value="electrique">Électrique</option>
                                                    <option value="gpl">GPL</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Équipements</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="climatisation" name="equipements[]" value="climatisation">
                                                    <label class="form-check-label" for="climatisation">
                                                        <i class="fas fa-snowflake"></i> Climatisation
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="bluetooth" name="equipements[]" value="bluetooth">
                                                    <label class="form-check-label" for="bluetooth">
                                                        <i class="fab fa-bluetooth"></i> Bluetooth
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="gps" name="equipements[]" value="gps">
                                                    <label class="form-check-label" for="gps">
                                                        <i class="fas fa-map-marked-alt"></i> GPS
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="non_fumeur" name="equipements[]" value="non_fumeur">
                                                    <label class="form-check-label" for="non_fumeur">
                                                        <i class="fas fa-smoking-ban"></i> Non-fumeur
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="photo_vehicule" class="form-label">Photo du véhicule</label>
                                        <input type="file" class="form-control" id="photo_vehicule" name="photo_vehicule" 
                                               accept="image/*">
                                        <div class="form-text">Formats acceptés: JPG, PNG (max 2Mo)</div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <a href="/ecoride/public/dashboard" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Retour
                                        </a>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save"></i> Enregistrer mon véhicule
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations et conseils -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-lightbulb"></i> Conseils</h5>
                            </div>
                            <div class="card-body">
                                <div class="tip-item">
                                    <i class="fas fa-camera"></i>
                                    <div>
                                        <strong>Photo de qualité</strong>
                                        <small>Une belle photo de votre véhicule augmente la confiance des passagers</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-star"></i>
                                    <div>
                                        <strong>Soyez précis</strong>
                                        <small>Plus votre profil véhicule est complet, plus vous aurez de demandes</small>
                                    </div>
                                </div>
                                <div class="tip-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <div>
                                        <strong>Assurance</strong>
                                        <small>Vérifiez que votre assurance couvre le covoiturage</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-leaf"></i> Impact écologique</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="stat-item">
                                    <i class="fas fa-leaf"></i>
                                    <div>
                                        <strong>0 kg</strong>
                                        <small>CO² économisé grâce à vos trajets</small>
                                    </div>
                                </div>
                                <p class="text-muted mt-3">
                                    Chaque trajet partagé contribue à réduire l'empreinte carbone !
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
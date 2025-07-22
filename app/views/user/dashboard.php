<?php
// app/views/user/dashboard.php
$title = "Dashboard - EcoRide";
include_once __DIR__ . '/../layouts/header.php';

// Récupérer l'utilisateur connecté
$currentUser = AuthController::getCurrentUser();
?>

<link rel="stylesheet" href="/css/dashboard.css">

<main class="dashboard-main">
    <div class="container">
        
        <!-- Header avec photo et informations -->
        <div class="dashboard-header">
            <div class="header-left">
                <!-- Photo de profil simple -->
                <div class="profile-photo-section">
                    <div class="profile-photo-container">
                        <div class="photo-placeholder" id="photoPlaceholder">
                            <i class="fas fa-user"></i>
                        </div>
                        <button type="button" class="photo-upload-btn" onclick="alert('Fonctionnalité photo à venir')">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Informations utilisateur -->
                <div class="welcome-section">
                    <h1 class="dashboard-title">
                        Bonjour <?= htmlspecialchars($currentUser['pseudo']) ?> !
                    </h1>
                    <p class="dashboard-subtitle">
                        Bienvenue sur votre tableau de bord EcoRide
                    </p>
                </div>
            </div>
            
            <!-- Crédits -->
            <div class="user-credits">
                <div class="credits-card">
                    <i class="fas fa-coins"></i>
                    <div class="credits-info">
                        <span class="credits-amount"><?= htmlspecialchars($currentUser['credits']) ?></span>
                        <span class="credits-label">crédits</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="dashboard-content">
            <div class="dashboard-grid">
                
                <!-- Actions rapides -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt"></i>
                            Actions rapides
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="quick-actions">
                            <a href="/ecoride/public/rides" class="action-btn search-btn">
                                <i class="fas fa-search"></i>
                                <span>Rechercher un trajet</span>
                            </a>
                            <a href="/ecoride/public/rides/create" class="action-btn create-btn">
                                <i class="fas fa-plus"></i>
                                <span>Proposer un trajet</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistiques utilisateur -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line"></i>
                            Vos statistiques
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="stats-grid">
                            <?php
                            $stats = [
                                ['icon' => 'fa-car', 'number' => '0', 'label' => 'Trajets effectués'],
                                ['icon' => 'fa-leaf', 'number' => '0 kg', 'label' => 'CO₂ économisé'],
                                ['icon' => 'fa-star', 'number' => '-', 'label' => 'Note moyenne']
                            ];
                            
                            foreach ($stats as $stat): ?>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas <?= $stat['icon'] ?>"></i>
                                    </div>
                                    <div class="stat-content">
                                        <span class="stat-number"><?= $stat['number'] ?></span>
                                        <span class="stat-label"><?= $stat['label'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="dashboard-card full-width">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history"></i>
                            Activité récente
                        </h3>
                        <a href="/ecoride/public/rides/history" class="card-link">
                            Voir tout
                        </a>
                    </div>
                    <div class="card-content">
                        <div class="activity-empty">
                            <i class="fas fa-calendar-times"></i>
                            <p>Aucune activité récente</p>
                            <span>Commencez par rechercher ou proposer un trajet !</span>
                        </div>
                    </div>
                </div>

                <!-- Informations du compte -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-cog"></i>
                            Mon compte
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="account-info">
                            <?php
                            $accountInfo = [
                                'Email' => $currentUser['email'],
                                'Membre depuis' => date('d/m/Y', strtotime($currentUser['created']))
                            ];
                            
                            foreach ($accountInfo as $label => $value): ?>
                                <div class="account-item">
                                    <span class="account-label"><?= $label ?> :</span>
                                    <span class="account-value"><?= htmlspecialchars($value) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="account-actions">
                            <a href="/ecoride/public/profile" class="btn-secondary">
                                <i class="fas fa-edit"></i>
                                Modifier le profil
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation rapide -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-compass"></i>
                            Navigation
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="nav-links">
                            <?php
                            $navLinks = [
                                ['href' => '/ecoride/public/rides', 'icon' => 'fa-search', 'text' => 'Recherche de trajets'],
                                ['href' => '/ecoride/public/rides/my-rides', 'icon' => 'fa-list', 'text' => 'Mes trajets'],
                                ['href' => '/ecoride/public/profile', 'icon' => 'fa-user', 'text' => 'Mon profil'],
                                ['href' => '/ecoride/public/contact', 'icon' => 'fa-headset', 'text' => 'Support']
                            ];
                            
                            foreach ($navLinks as $link): ?>
                                <a href="<?= $link['href'] ?>" class="nav-link">
                                    <i class="fas <?= $link['icon'] ?>"></i>
                                    <span><?= $link['text'] ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation simple des cartes
    animateCards();
    
    console.log('Dashboard chargé pour:', '<?= $currentUser['pseudo'] ?>');
    console.log('Crédits disponibles:', <?= $currentUser['credits'] ?>);
});

function animateCards() {
    const cards = document.querySelectorAll('.dashboard-card');
    
    cards.forEach((card, index) => {
        // Animation d'apparition simple
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
}
</script>

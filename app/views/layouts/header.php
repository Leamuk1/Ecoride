<?php
// app/views/layouts/header.php

// Récupérer la page actuelle pour les états actifs
$currentPage = $_SERVER['REQUEST_URI'] ?? '/';
$currentPage = rtrim($currentPage, '/'); // Supprimer le slash final

// Fonction pour déterminer si un lien est actif
function isActive($pagePath, $currentPage) {
    if ($pagePath === '/' && ($currentPage === '' || $currentPage === '/ecoride/public' || $currentPage === '/ecoride/public/')) {
        return true;
    }
    return strpos($currentPage, $pagePath) !== false && $pagePath !== '/';
}

// Gestion de l'état utilisateur
require_once '../app/controllers/AuthController.php';
$isLoggedIn = AuthController::isLoggedIn();
$currentUser = AuthController::getCurrentUser();

// Variables
$userName = $isLoggedIn ? ($currentUser['pseudo'] ?: $currentUser['prenom'] ?: 'Utilisateur') : null;
$userEmail = $isLoggedIn ? $currentUser['email'] : null;
$userCredits = $isLoggedIn ? $currentUser['credits'] : 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'EcoRide - Covoiturage Écologique' ?></title>
    
    <!-- CSS EcoRide -->
    <link rel="stylesheet" href="/css/styles.css">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Favicon EcoRide -->
    <link rel="icon" type="image/png" href="/images/EcoRide Favicon.png">
</head>
<body>

<!-- Header EcoRide -->
<header class="header" id="header">
    <nav class="nav-container">
        <!-- Logo EcoRide - Retour page d'accueil -->
        <a href="/ecoride/public/" class="logo">
            <span class="eco">ECO</span><span class="barre">|</span><span class="ride">RIDE</span>
        </a>

        <!-- Menu Navigation Desktop -->
        <ul class="nav-menu" id="nav-menu">
            <!-- Retour vers la page d'accueil -->
            <li>
                <a href="/ecoride/public/" class="<?= isActive('/', $currentPage) ? 'active' : '' ?>">
                    <span>Accueil</span>
                </a>
            </li>

            <!-- Accès aux covoiturages -->
            <li>
                <a href="/ecoride/public/rides" class="<?= isActive('/rides', $currentPage) ? 'active' : '' ?>">
                <span>Covoiturages</span>
                </a>
            </li>

            <!-- Contact -->
            <li>
                <a href="/ecoride/public/contact" class="<?= isActive('/contact', $currentPage) ? 'active' : '' ?>">
                    <span>Contact</span>
                </a>
            </li>

            <!--Connexion / Espace utilisateur -->
            <?php if ($isLoggedIn && $currentUser): ?>
                <!-- Utilisateur connecté - Dropdown -->
                <li class="user-dropdown">
                    <a href="#" class="user-toggle">
                        <!-- Photo de profil réaliste -->
                        <img src="https://randomuser.me/api/portraits/<?= (crc32($currentUser['email']) % 2 === 0) ? 'women' : 'men' ?>/<?= abs(crc32($currentUser['email'])) % 99 ?>.jpg" 
                             alt="Avatar <?= htmlspecialchars($userName) ?>" 
                             class="user-avatar"
                             onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($userName) ?>&background=435334&color=fff&size=32&bold=true&format=svg'">
                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                        <i class="fas fa-chevron-down user-arrow"></i>
                    </a>
                    
                    <!-- Menu déroulant utilisateur -->
                    <div class="dropdown-menu">
                        <!-- En-tête utilisateur avec données -->
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($userName) ?></strong>
                            <?php if ($userEmail): ?>
                                <small><?= htmlspecialchars($userEmail) ?></small>
                            <?php endif; ?>
                            <!-- Affichage des crédits en évidence -->
                            <div class="user-credits-display">
                                <i class="fas fa-coins"></i>
                                <span><?= $userCredits ?> crédits</span>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <!-- Menu utilisateur -->
                        <a href="/ecoride/public/dashboard">
                             Tableau de bord
                        </a>
                        <a href="/ecoride/public/profile">
                             Mon profil
                        </a>
                        <a href="/ecoride/public/rides/my-rides">
                             Mes trajets
                        </a>
                        <a href="/ecoride/public/rides/create">
                             Proposer un trajet
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="/ecoride/public/settings">
                            Paramètres
                        </a>
                        <a href="/ecoride/public/help">
                             Aide
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="/ecoride/public/auth/logout" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </div>
                </li>
            <?php else: ?>
                <!-- Utilisateur non connecté - Boutons connexion/inscription -->
                <li class="auth-buttons-container">
                    <a href="/ecoride/public/auth/login" class="btn-connexion">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion</span> 
                    </a>
                    <a href="/ecoride/public/auth/register" class="btn-connexion">
                        <i class="fas fa-user-plus"></i>
                        <span>Inscription</span> 
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Bouton burger mobile -->
        <div class="mobile-toggle" id="mobile-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>

<!--Messages flash système -->
<?php if (isset($_GET['message'])): ?>
    <div class="flash-messages">
        <?php
        $message = $_GET['message'];
        $messageText = '';
        $messageType = 'info';
        
        switch ($message) {
            case 'disconnected':
                $messageText = 'Vous avez été déconnecté avec succès.';
                $messageType = 'success';
                break;
            case 'login_required':
                $messageText = 'Vous devez être connecté pour accéder à cette page.';
                $messageType = 'warning';
                break;
            case 'access_denied':
                $messageText = 'Accès non autorisé.';
                $messageType = 'error';
                break;
            case 'account_created':
                $messageText = 'Compte créé avec succès ! Bienvenue sur EcoRide.';
                $messageType = 'success';
                break;
            default:
                $messageText = htmlspecialchars($message);
                break;
        }
        ?>
        
        <?php if ($messageText): ?>
            <div class="flash-message flash-<?= $messageType ?>" id="flashMessage">
                <div class="flash-content">
                    <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : ($messageType === 'error' ? 'exclamation-circle' : ($messageType === 'warning' ? 'exclamation-triangle' : 'info-circle')) ?>"></i>
                    <span><?= $messageText ?></span>
                </div>
                <button class="flash-close" onclick="closeFlashMessage()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>



<!-- JavaScript Navigation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===================================
    // BURGER MENU MOBILE FONCTIONNEL
    // ===================================
    
    const mobileToggle = document.getElementById('mobile-toggle');
    const navMenu = document.getElementById('nav-menu');
    const header = document.getElementById('header');
    
    // Toggle du menu mobile
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Toggle des classes actives
            mobileToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            
            // Empêcher le scroll du body quand menu ouvert
            if (navMenu.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }
    
    // Fermer le menu mobile au clic sur un lien
    const navLinks = navMenu?.querySelectorAll('a:not(.user-toggle)');
    if (navLinks) {
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Fermer le menu mobile
                mobileToggle?.classList.remove('active');
                navMenu?.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
    
    // Fermer le menu mobile au clic extérieur
    document.addEventListener('click', function(e) {
        if (navMenu?.classList.contains('active') && 
            !navMenu.contains(e.target) && 
            !mobileToggle?.contains(e.target)) {
            
            mobileToggle?.classList.remove('active');
            navMenu?.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // ===================================
    // HEADER SCROLL EFFECT
    // ===================================
    
    let lastScrollY = window.scrollY;
    
    window.addEventListener('scroll', function() {
        const currentScrollY = window.scrollY;
        
        if (header) {
            if (currentScrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
        
        lastScrollY = currentScrollY;
    });
    
    // ===================================
    // DROPDOWN UTILISATEUR
    // ===================================
    
    const userDropdown = document.querySelector('.user-dropdown');
    const userToggle = document.querySelector('.user-toggle');
    
    if (userDropdown && userToggle) {
        // Gestion mobile du dropdown utilisateur
        userToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
             if (window.innerWidth <= 768) {
                userDropdown.classList.toggle('mobile-active');
            } else {
                // Desktop : toggle normal
                userDropdown.classList.toggle('active');
            }
        });
        
        // Fermer le dropdown en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target)) {
                userDropdown.classList.remove('active');
                userDropdown.classList.remove('mobile-active');
            }
        });
    }
    
    // ===================================
    // HIGHLIGHT NAVIGATION ACTIVE
    // ===================================
    
    // Améliorer la visibilité du lien actif
    const activeLink = document.querySelector('.nav-menu a.active');
    if (activeLink && !activeLink.classList.contains('btn-connexion') && !activeLink.classList.contains('user-toggle')) {
        console.log('Lien actif détecté:', activeLink.textContent.trim());
    }

    // ===================================
    // GESTION DÉCONNEXION RÉELLE
    // ===================================
    
    const logoutLink = document.querySelector('.logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmLogout = confirm('Êtes-vous sûr de vouloir vous déconnecter ?');
            if (confirmLogout) {
                // Animation de déconnexion
                const userToggle = document.querySelector('.user-toggle');
                if (userToggle) {
                    userToggle.style.opacity = '0.5';
                    userToggle.style.pointerEvents = 'none';
                }
                
                // Redirection vers la déconnexion réelle
                window.location.href = '/ecoride/public/auth/logout';
            }
        });
    }
    
    // ===================================
    // PERFORMANCE: RESIZE HANDLER
    // ===================================
    
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            // Fermer le menu mobile si on passe en desktop
            if (window.innerWidth > 768) {
                mobileToggle?.classList.remove('active');
                navMenu?.classList.remove('active');
                document.body.style.overflow = '';
                
                // Fermer aussi le dropdown utilisateur
                userDropdown?.classList.remove('mobile-active');
            }
        }, 250);
    });

    // ===================================
    // MESSAGES FLASH AUTO-CLOSE
    // ===================================
    
    // Auto-fermeture des messages flash après 5 secondes
    setTimeout(function() {
        const flashMessage = document.getElementById('flashMessage');
        if (flashMessage) {
            closeFlashMessage();
        }
    }, 5000);

    // ===================================
    // TESTS NAVIGATION & AUTH
    // ===================================
    
    console.log('US7 Tests Navigation & Authentification:');
    console.log('Menu burger:', mobileToggle ? 'OK' : 'MANQUANT');
    console.log('Menu navigation:', navMenu ? 'OK' : 'MANQUANT');
    console.log('Dropdown utilisateur:', userDropdown ? 'OK' : 'Non connecté');
    console.log('Page active:', window.location.pathname);
    console.log('Utilisateur connecté:', <?= $isLoggedIn ? 'true' : 'false' ?>);
    <?php if ($isLoggedIn): ?>
    console.log('Utilisateur:', '<?= htmlspecialchars($userName) ?>');
    console.log('Crédits:', <?= $userCredits ?>);
    <?php endif; ?>
    
    // Test des liens
    const allNavLinks = document.querySelectorAll('.nav-menu a');
    console.log(`Nombre de liens navigation: ${allNavLinks.length}`);
    
    allNavLinks.forEach((link, index) => {
        const href = link.getAttribute('href');
        const text = link.textContent.trim();
        const isActive = link.classList.contains('active');
        console.log(`   ${index + 1}. ${text} → ${href} ${isActive ? '(ACTIF)' : ''}`);
    });
});

// Fonction pour fermer les messages flash
function closeFlashMessage() {
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        flashMessage.style.opacity = '0';
        setTimeout(() => {
            flashMessage.style.display = 'none';
        }, 300);
    }
}
</script>
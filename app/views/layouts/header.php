<?php
// app/views/layouts/header.php - Header EcoRide US2

// Récupérer la page actuelle pour les états actifs
$currentPage = $_SERVER['REQUEST_URI'] ?? '/';
$currentPage = rtrim($currentPage, '/'); // Supprimer le slash final
// Fonction pour déterminer si un lien est actif
function isActive($pagePath, $currentPage) {
    if ($pagePath === '/' && ($currentPage === '' || $currentPage === '/')) {
        return true;
    }
    return strpos($currentPage, $pagePath) !== false && $pagePath !== '/';
}
// Simulation état utilisateur (à adapter selon ton système d'auth)
$isLoggedIn = isset($_SESSION['user_id']) ?? false;
$userName = $_SESSION['user_name'] ?? 'Utilisateur';

// Gestion état utilisateur (US2 - Gestion connexion/déconnexion)

$userName = $isLoggedIn ? ($_SESSION['user_name'] ?? $_SESSION['user_pseudo'] ?? 'Utilisateur') : null;
$userEmail = $isLoggedIn ? ($_SESSION['user_email'] ?? '') : null;
$userCredits = $isLoggedIn ? ($_SESSION['user_credits'] ?? 0) : 0;

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

<!-- Header EcoRide US2 -->
<header class="header" id="header">
    <nav class="nav-container">
        <!-- Logo EcoRide - Retour page d'accueil -->
        <a href="/" class="logo">
            <span class="eco">ECO</span><span class="barre">|</span><span class="ride">RIDE</span>
        </a>

        <!-- Menu Navigation Desktop -->
        <ul class="nav-menu" id="nav-menu">
            <!-- Retour vers la page d'accueil -->
            <li>
                <a href="/" class="<?= isActive('/', $currentPage) ? 'active' : '' ?>">
                    <span>Accueil</span>
                </a>
            </li>

            <!-- Accès aux covoiturages -->
            <li>
                <a href="/rides" class="<?= isActive('/rides', $currentPage) ? 'active' : '' ?>">
                <span>Covoiturages</span>
                </a>
            </li>

            <!-- Contact -->
            <li>
                <a href="/contact" class="<?= isActive('/contact', $currentPage) ? 'active' : '' ?>">
                    <span>Contact</span>
                </a>
            </li>

            <!-- Connexion / Espace utilisateur -->
            <?php if ($isLoggedIn): ?>
                <!-- Utilisateur connecté - Dropdown -->
                <li class="user-dropdown">
                    <a href="#" class="user-toggle">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($userName) ?>&background=435334&color=fff&size=32&bold=true&format=svg" 
                             alt="Avatar <?= htmlspecialchars($userName) ?>" 
                             class="user-avatar">
                        <span class="user-name"><?= htmlspecialchars($userName) ?></span>
                        <i class="fas fa-chevron-down user-arrow"></i>
                    </a>
                    
                    <!-- Menu déroulant utilisateur -->
                    <div class="dropdown-menu">
<!-- En-tête utilisateur -->
                        <div class="dropdown-header">
                            <strong><?= htmlspecialchars($userName) ?></strong>
                            <?php if ($userEmail): ?>
                                <small><?= htmlspecialchars($userEmail) ?></small>
                            <?php endif; ?>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <!-- Menu utilisateur -->
                        <a href="/profile">
                            <i class="fas fa-user"></i> Mon profil
                        </a>
                        <a href="/my-rides">
                            <i class="fas fa-history"></i> Mes trajets
                        </a>
                        <a href="/create-ride">
                            <i class="fas fa-plus-circle"></i> Proposer un trajet
                        </a>
                        <a href="/credits">
                            <i class="fas fa-coins"></i> Mes crédits
                            <span class="credits-badge"><?= $userCredits ?></span>
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="/settings">
                            <i class="fas fa-cog"></i> Paramètres
                        </a>
                        
                        <div class="dropdown-divider"></div>
                        
                        <a href="/auth/logout" class="logout-link">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion
                        </a>
                    </div>
                </li>
            <?php else: ?>

                    <!-- Utilisateur non connecté - Bouton connexion -->
                 <li class="auth-buttons-container">
                    <a href="/auth/login" class="btn-connexion">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion</span> 
                    </a>
                    <a href="/auth/register" class="btn-connexion">
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
            if (window.innerWidth <= 768) {
                e.preventDefault();
                e.stopPropagation();
                userDropdown.classList.toggle('mobile-active');
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
    // US2 - GESTION DÉCONNEXION
    // ===================================
    
    const logoutLink = document.querySelector('.logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmLogout = confirm('Êtes-vous sûr de vouloir vous déconnecter ?');
            if (confirmLogout) {
                // Optionnel : Animation de déconnexion
                const userToggle = document.querySelector('.user-toggle');
                if (userToggle) {
                    userToggle.style.opacity = '0.5';
                    userToggle.style.pointerEvents = 'none';
                }
                
                // Redirection vers la déconnexion
                window.location.href = '/auth/logout';
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
              
            }
        }, 250);
    });


// ===================================
    // US2 - TESTS NAVIGATION (développement)
    // ===================================
    
    console.log('US2 Tests Navigation:');
    console.log('Menu burger:', mobileToggle ? 'OK' : 'MANQUANT');
    console.log('Menu navigation:', navMenu ? 'OK' : 'MANQUANT');
    console.log('Dropdown utilisateur:', userDropdown ? 'OK' : 'Non connecté');
    console.log('Page active:', window.location.pathname);
    
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
</script>
<?php
// app/views/layouts/header.php
// Header navigation conforme à tes maquettes EcoRide
?>

<header class="header">
    <div class="nav-container">
        <!-- Logo EcoRide -->
        <div class="logo">
            <a href="/" class="footer-logo">
             <span class="eco">ECO</span><span class="barre">|</span><span class="ride">RIDE</span>
            </a>
        </div>
        
        <!-- Navigation principale -->
        <nav>
            <ul class="nav-menu">
                <li>
                    <a href="<?= url('/') ?>" 
                       class="<?= (basename($_SERVER['REQUEST_URI']) === '' || basename($_SERVER['REQUEST_URI']) === '/') ? 'active' : '' ?>">
                        Accueil
                    </a>
                </li>
                <li>
                    <a href="<?= url('/rides') ?>" 
                       class="<?= (strpos($_SERVER['REQUEST_URI'], '/rides') === 0) ? 'active' : '' ?>">
                        Covoiturages
                    </a>
                </li>
                <li>
                    <a href="<?= url('/contact') ?>" 
                       class="<?= (strpos($_SERVER['REQUEST_URI'], '/contact') === 0) ? 'active' : '' ?>">
                        Contact
                    </a>
                </li>
                <li>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']): ?>
                        <!-- Utilisateur connecté -->
                        <div class="user-dropdown">
                            <a href="#" class="user-toggle">
                                <i class="fas fa-user-circle"></i>
                                <?= htmlspecialchars($_SESSION['user']['prenom']) ?>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a href="<?= url('/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt"></i>Tableau de bord
                                </a>
                                <a href="<?= url('/profile') ?>">
                                    <i class="fas fa-user"></i>Mon profil
                                </a>
                                <a href="<?= url('/my-rides') ?>">
                                    <i class="fas fa-car"></i>Mes trajets
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="<?= url('/logout') ?>" class="logout-link">
                                    <i class="fas fa-sign-out-alt"></i>Déconnexion
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Utilisateur non connecté -->
                        <a href="<?= url('/login') ?>" class="btn-connexion">Connexion</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
        
        <!-- Bouton menu mobile -->
        <div class="mobile-toggle" onclick="toggleMobileMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</header>

<script>
// Fonctionnalités du header
document.addEventListener('DOMContentLoaded', function() {
    // Animation au scroll
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScrollTop = scrollTop;
    });
});

// Menu mobile
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const mobileToggle = document.querySelector('.mobile-toggle');
    
    navMenu.classList.toggle('active');
    mobileToggle.classList.toggle('active');
}

// Fermer le menu mobile en cliquant sur un lien
document.querySelectorAll('.nav-menu a').forEach(link => {
    link.addEventListener('click', function() {
        const navMenu = document.querySelector('.nav-menu');
        const mobileToggle = document.querySelector('.mobile-toggle');
        
        navMenu.classList.remove('active');
        mobileToggle.classList.remove('active');
    });
});
</script>
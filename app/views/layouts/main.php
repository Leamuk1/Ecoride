<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $meta_description ?? 'EcoRide - Plateforme de covoiturage écologique conforme à vos valeurs environnementales' ?>">
    <title><?= $page_title ?? 'EcoRide - Covoiturage Écologique' ?></title>
    
    <!-- Google Fonts - Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS principal -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/EcoRide Favicon.png">
    
    <!-- Meta tags pour SEO -->
    <meta name="keywords" content="covoiturage, écologique, EcoRide, transport, environnement, partage, économique">
    <meta name="author" content="EcoRide">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph pour réseaux sociaux -->
    <meta property="og:title" content="<?= $page_title ?? 'EcoRide - Covoiturage Écologique' ?>">
    <meta property="og:description" content="<?= $meta_description ?? 'Plateforme de covoiturage écologique' ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="images/Logo ECORIDE vert.png">
    <meta property="og:site_name" content="EcoRide">
    
   
</head>
<body>
    
    <!-- Navigation Header -->
    <?php include 'header.php'; ?>
    
    <!-- Contenu principal de la page -->
    <main id="main-content" role="main">
        
        <!-- Messages flash / notifications -->
        <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($_SESSION['success_message']) ?></span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($_SESSION['error_message']) ?></span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['warning_message'])): ?>
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($_SESSION['warning_message']) ?></span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['warning_message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['info_message'])): ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            <span><?= htmlspecialchars($_SESSION['info_message']) ?></span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php unset($_SESSION['info_message']); ?>
        <?php endif; ?>
        
        <!-- Message d'erreur passé directement -->
        <?php if (isset($error_message)): ?>
        <div class="alert alert-warning" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <span><?= htmlspecialchars($error_message) ?></span>
            <button type="button" class="btn-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <?php endif; ?>
        
        <?php 
        // Inclure le contenu de la page spécifique
        $viewPath = '../app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            // Page d'erreur personnalisée
            echo '<div class="container" style="padding: 4rem 1.5rem; text-align: center;">
                    <div style="background: #FFFFFF; padding: 3rem; border-radius: 12px; box-shadow: var(--shadow-medium); max-width: 600px; margin: 0 auto;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #f57c00; margin-bottom: 1.5rem;"></i>
                        <h2 style="color: var(--text-color); margin-bottom: 1rem; font-family: Roboto;">Page non trouvée</h2>
                        <p style="color: rgba(24, 35, 15, 0.8); margin-bottom: 2rem;">La page demandée n\'existe pas ou a été déplacée.</p>
                        <a href="/" style="background: var(--eco-green); color: #FFFFFF; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-home"></i>
                            Retour à l\'accueil
                        </a>
                    </div>
                  </div>';
        }
        ?>
    </main>
    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    
    <!-- Scripts EcoRide -->
    <script>
        // Configuration globale
        window.EcoRide = {
            version: '1.0.0',
            environment: '<?= defined('APP_ENV') ? APP_ENV : 'production' ?>',
            currentUser: <?= json_encode($_SESSION['user'] ?? null) ?>,
            csrfToken: '<?= $_SESSION['csrf_token'] ?? '' ?>',
            colors: {
                primary: '#2C3E20',
                secondary: '#CEDEBD', 
                ecoGreen: '#435334',
                text: '#18230F'
            }
        };
        
        // ===================================
        // UTILITAIRES GLOBAUX ECORIDE
        // ===================================
        
        // Animation des éléments au scroll
        function initScrollAnimations() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            // Observer les éléments avec la classe animate-on-scroll
            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';
                el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(el);
            });
        }
        
        // Auto-hide des alertes après 5 secondes
        function initAlertAutoHide() {
            setTimeout(() => {
                document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
                    if (alert.style.display !== 'none') {
                        alert.style.animation = 'slideOutUp 0.4s ease-in forwards';
                        setTimeout(() => alert.remove(), 400);
                    }
                });
            }, 5000);
        }
        
        // Smooth scroll amélioré
        function initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        const headerHeight = document.querySelector('.header')?.offsetHeight || 80;
                        const targetPosition = target.offsetTop - headerHeight - 20;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        }
        
        // Gestion des erreurs globales
        function handleGlobalError(error) {
            console.error('EcoRide Error:', error);
            
            if (window.EcoRide.environment === 'development') {
                console.log('Error details:', error);
            }
        }
        
        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser les fonctionnalités
            initScrollAnimations();
            initAlertAutoHide();
            initSmoothScroll();
            
            // Ajouter la classe loaded au body pour les animations CSS
            document.body.classList.add('loaded');
            
            // Log de debug en développement
            if (window.EcoRide.environment === 'development') {
                console.log('EcoRide initialized successfully!', window.EcoRide);
            }
        });
        
        // Gestion des erreurs JavaScript globales
        window.addEventListener('error', handleGlobalError);
        window.addEventListener('unhandledrejection', handleGlobalError);
        
        // Animation slideOutUp pour les alertes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOutUp {
                from {
                    opacity: 1;
                    transform: translateY(0);
                }
                to {
                    opacity: 0;
                    transform: translateY(-20px);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    
    <!-- Scripts spécifiques à la page -->
    <?php if (isset($page_scripts) && is_array($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="js/<?= htmlspecialchars($script) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Analytics (production seulement) -->
    <?php if (isset($analytics_code) && defined('APP_ENV') && APP_ENV === 'production'): ?>
    <script>
        // Code Google Analytics ou autre
        <?= $analytics_code ?>
    </script>
    <?php endif; ?>
    
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Erreur - EcoRide' ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header simple -->
    <header class="main-header">
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-logo">
                    <a href="/" class="footer-logo">
                        <span class="eco">ECO</span><span class="barre">|</span><span class="ride">RIDE</span>
                    </a>
        </div>
            </div>
        </nav>
    </header>

    <!-- Contenu erreur -->
    <main class="error-page">
        <div class="container">
            <div class="error-content">
                
                <!-- Icône d'erreur -->
                <div class="error-icon">
                    <?php if (http_response_code() === 404): ?>
                        <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>
                
                <!-- Code d'erreur -->
                <div class="error-code">
                    <?= http_response_code() === 404 ? '404' : '500' ?>
                </div>
                
                <!-- Titre d'erreur -->
                <h1 class="error-title">
                    <?= isset($error_title) ? htmlspecialchars($error_title) : 'Une erreur est survenue' ?>
                </h1>
                
                <!-- Message d'erreur -->
                <p class="error-message">
                    <?= isset($error_message) ? htmlspecialchars($error_message) : 'Nous rencontrons des difficultés techniques.' ?>
                </p>
                
                <!-- Actions -->
                <div class="error-actions">
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-home" aria-hidden="true"></i>
                        Retour à l'accueil
                    </a>
                    
                    <button onclick="history.back()" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left" aria-hidden="true"></i>
                        Page précédente
                    </button>
                    
                    <?php if (http_response_code() === 404): ?>
                    <a href="/rides" class="btn btn-outline-primary">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        Rechercher un covoiturage
                    </a>
                    <?php endif; ?>
                </div>
                
                <!-- Suggestions -->
                <?php if (http_response_code() === 404): ?>
                <div class="error-suggestions">
                    <h3>Peut-être cherchiez-vous :</h3>
                    <ul class="suggestions-list">
                        <li>
                            <a href="/rides">
                                <i class="fas fa-car" aria-hidden="true"></i>
                                Tous les covoiturages
                            </a>
                        </li>
                        <li>
                            <a href="/rides/search">
                                <i class="fas fa-search" aria-hidden="true"></i>
                                Rechercher un trajet
                            </a>
                        </li>
                        <li>
                            <a href="/rides/create">
                                <i class="fas fa-plus" aria-hidden="true"></i>
                                Proposer un trajet
                            </a>
                        </li>
                        <li>
                            <a href="/about">
                                <i class="fas fa-info-circle" aria-hidden="true"></i>
                                À propos d'EcoRide
                            </a>
                        </li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <!-- Contact support -->
                <div class="error-support">
                    <p>
                        <i class="fas fa-life-ring text-primary" aria-hidden="true"></i>
                        Besoin d'aide ? 
                        <a href="/contact" class="text-primary">Contactez notre support</a>
                    </p>
                </div>
                
            </div>
        </div>
    </main>

    <!-- Footer simple -->
    <footer class="simple-footer">
        <div class="container">
            <div class="footer-content">
                <p>&copy; <?= date('Y') ?> EcoRide. Tous droits réservés.</p>
                <div class="footer-links">
                    <a class="footer-link1" href="/privacy">Confidentialité</a>
                    <a class="footer-link1" href="/terms">CGU</a>
                    <a class="footer-link1" href="/contact">Contact</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
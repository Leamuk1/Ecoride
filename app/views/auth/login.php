<?php
// app/views/auth/login.php - Page connexion temporaire
$title = "Connexion - EcoRide";
include_once __DIR__ . '/../layouts/header.php';
?>

<main style="padding: 6rem 2rem 2rem; min-height: 80vh;">
    <div class="container">
        <div style="max-width: 400px; margin: 0 auto;">
            <h1 style="color: var(--text-color); margin-bottom: 2rem; text-align: center;">Connexion</h1>
            
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
                <h2 style="color: var(--eco-green); margin-bottom: 1rem; text-align: center;">US7 - À venir</h2>
                <p style="text-align: center; color: var(--text-color); margin-bottom: 2rem;">
                    Le système d'authentification sera implémenté dans l'US7 - Création de compte.
                </p>
                
                <div style="text-align: center;">
                    <a href="/" class="btn-outline-primary">
                        <i class="fas fa-arrow-left"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
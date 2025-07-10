<?php
// app/views/layouts/footer.php
?>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Logo EcoRide -->
            <a href="/" class="footer-logo">
                <span class="eco">ECO</span><span class="barre">|</span><span class="ride">RIDE</span>
            </a>
            
            <!-- Liens footer alignés à droite -->
            <div class="footer-links">
                <a href="mailto:contact@ecoride.fr" class="footer-email">contact@ecoride.fr</a>
                <a href="/mentions-legales" class="footer-link">Mentions légales</a>
                <span class="footer-copyright">© 2025 EcoRide</span>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au scroll pour le footer
    const footer = document.querySelector('.footer');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const footerObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationDelay = '0.2s';
                footerObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    if (footer) {
        footerObserver.observe(footer);
    }
    
    // Click sur le logo pour retourner en haut
    const footerLogo = document.querySelector('.footer-logo');
    if (footerLogo) {
        footerLogo.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
</script>
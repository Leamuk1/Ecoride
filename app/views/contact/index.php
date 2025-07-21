<?php
// app/views/contact/index.php - Page Contact
?>
<!-- CSS de la page -->
<link rel="stylesheet" href="/css/pages/contact.css">

<!-- Hero Contact Section -->
<section class="contact-hero">
    <div class="container">
        <div class="contact-hero-content">
            <h1 class="contact-title">Contactez-nous</h1>
            <p class="contact-subtitle">
                Une question ? Un problème ? Nous sommes là pour vous aider !
                L'équipe EcoRide est à votre écoute pour améliorer votre expérience de covoiturage écologique.
            </p>
        </div>
    </div>
</section>

<!-- Contact Content Section -->
<section class="contact-section">
    <div class="container">
        <div class="contact-content">
            
            <!-- Formulaire de contact -->
            <div class="contact-form-container">
                <div class="contact-form-wrapper">
                    <h2 class="form-title">Envoyez-nous un message</h2>
                    
                    <form class="contact-form" id="contactForm" method="POST" action="/contact/send">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">Prénom *</label>
                                <input type="text" 
                                       class="form-input" 
                                       id="firstName" 
                                       name="firstName" 
                                       placeholder="Votre prénom"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="lastName">Nom *</label>
                                <input type="text" 
                                       class="form-input" 
                                       id="lastName" 
                                       name="lastName" 
                                       placeholder="Votre nom"
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" 
                                   class="form-input" 
                                   id="email" 
                                   name="email" 
                                   placeholder="votre.email@exemple.com"
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Sujet *</label>
                            <select class="form-input" id="subject" name="subject" required>
                                <option value="">Choisissez un sujet</option>
                                <option value="question">Question générale</option>
                                <option value="problem">Problème technique</option>
                                <option value="suggestion">Suggestion d'amélioration</option>
                                <option value="billing">Facturation / Crédits</option>
                                <option value="safety">Sécurité / Signalement</option>
                                <option value="other">Autre</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea class="form-textarea" 
                                      id="message" 
                                      name="message" 
                                      rows="5"
                                      placeholder="Décrivez votre demande en détail..."
                                      required></textarea>
                        </div>
                        
                        <div class="checkbox-group">
    <input type="checkbox" id="terms" name="terms" required>
    <span class="checkbox-custom"></span>
    <label for="terms" class="checkbox-label">
        J'accepte les <a href="/terms" target="_blank">conditions d'utilisation</a> 
        et la <a href="/privacy" target="_blank">politique de confidentialité</a>
    </label>
</div>
                        
                        <button type="submit" class="btn-contact">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Informations de contact -->
            <div class="contact-info-container">
                <div class="contact-info-wrapper">
                    <h2 class="info-title">Nos coordonnées</h2>
                    
                    <div class="contact-info-grid">
                        <!-- Email -->
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>
                                    <a href="mailto:contact@ecoride.fr">contact@ecoride.fr</a>
                                </p>
                                <small>Réponse sous 24h en moyenne</small>
                            </div>
                        </div>
                        
                        <!-- Téléphone -->
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <h3>Téléphone</h3>
                                <p>
                                    <a href="tel:+33123456789">01 23 45 67 89</a>
                                </p>
                                <small>Lun-Ven : 9h-18h</small>
                            </div>
                        </div>
                        
                        <!-- Adresse -->
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-content">
                                <h3>Adresse</h3>
                                <p>
                                    123 Rue de l'Écologie<br>
                                    75001 Paris, France
                                </p>
                            </div>
                        </div>
                        
                        <!-- Horaires -->
                        <div class="contact-info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <h3>Support</h3>
                                <p>
                                    Lundi - Vendredi<br>
                                    9h00 - 18h00
                                </p>
                                <small>Hors jours fériés</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- FAQ rapide -->
                    <div class="faq-quick">
                        <h3>Questions fréquentes</h3>
                        <div class="faq-list">
                            <details class="faq-item">
                                <summary>Comment créer un compte ?</summary>
                                <p>Cliquez sur "Connexion" puis "Créer un compte". Remplissez le formulaire et validez votre email.</p>
                            </details>
                            
                            <details class="faq-item">
                                <summary>Comment fonctionne le système de crédits ?</summary>
                                <p>Vous recevez 20 crédits gratuits à l'inscription. Chaque trajet coûte des crédits selon la distance.</p>
                            </details>
                            
                            <details class="faq-item">
                                <summary>Que faire en cas de problème avec un trajet ?</summary>
                                <p>Contactez-nous immédiatement via ce formulaire avec le numéro de votre trajet.</p>
                            </details>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JavaScript Contact -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation basique
            const requiredFields = contactForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            if (isValid) {
                // Simulation envoi
                const submitBtn = contactForm.querySelector('.btn-contact');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
                submitBtn.disabled = true;
                
                setTimeout(() => {
                    alert('Message envoyé avec succès ! Nous vous répondrons sous 24h.');
                    contactForm.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 2000);
            } else {
                alert('Veuillez remplir tous les champs obligatoires.');
            }
        });
    }
    
    // Animation des champs au focus
    const formInputs = document.querySelectorAll('.form-input, .form-textarea');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const checkboxGroups = document.querySelectorAll('.checkbox-group');
    
    checkboxGroups.forEach(group => {
        const checkbox = group.querySelector('input[type="checkbox"]');
        const customBox = group.querySelector('.checkbox-custom');
        const label = group.querySelector('.checkbox-label');
        
        // Clic sur la checkbox custom ou le label
        [customBox, label].forEach(element => {
            if (element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    
                    // Déclencher l'événement change
                    const event = new Event('change', { bubbles: true });
                    checkbox.dispatchEvent(event);
                });
            }
        });
    });
});
</script>


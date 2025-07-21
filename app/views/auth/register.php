<?php
// app/views/auth/register.php - Page inscription
$title = "Inscription - EcoRide";
include_once __DIR__ . '/../layouts/header.php';


?>

<link rel="stylesheet" href="/css/pages/register.css">

<main class="auth-main">
    <div class="container">
        <div class="auth-container register-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1 class="auth-title">Inscription</h1>
                    <p class="auth-subtitle">Rejoignez la communauté EcoRide</p>
                </div>

                <!-- Messages d'erreur/succès -->
                <div id="auth-messages" class="auth-messages"></div>

                <form class="auth-form" id="registerForm" action="/ecoride/public/auth/register" method="POST">
                    <!-- Protection CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">


                    <!-- Honeypot anti-bot -->
                    <div style="position: absolute; left: -9999px; opacity: 0;">
                        <input type="text" name="robot_check" value="" autocomplete="off" tabindex="-1">
                    </div>
                    
                    <!-- Pseudo -->
                    <div class="form-group">
                        <label for="pseudo" class="form-label">
                            Pseudo
                        </label>
                        <input type="text" 
                               id="pseudo" 
                               name="pseudo" 
                               class="form-control" 
                               placeholder="Mon pseudo"
                               required
                               autocomplete="username">
                        <div class="field-error" id="pseudo-error"></div>
                    </div>

                    <!-- Nom et Prénom -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="prenom" class="form-label">
                                Prénom
                            </label>
                            <input type="text" 
                                   id="prenom" 
                                   name="prenom" 
                                   class="form-control" 
                                   placeholder="Prénom"
                                   autocomplete="given-name">
                            <div class="field-error" id="prenom-error"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nom" class="form-label">
                                Nom
                            </label>
                            <input type="text" 
                                   id="nom" 
                                   name="nom" 
                                   class="form-control" 
                                   placeholder="Nom"
                                   autocomplete="family-name">
                            <div class="field-error" id="nom-error"></div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="mon.email@email.com"
                               required
                               autocomplete="email">
                        <div class="field-error" id="email-error"></div>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="telephone" class="form-label">
                            Téléphone
                        </label>
                        <input type="tel" 
                               id="telephone" 
                               name="telephone" 
                               class="form-control" 
                               placeholder="06 12 34 56 78"
                               autocomplete="tel">
                        <div class="field-error" id="telephone-error"></div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Mot de passe
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Mot de passe"
                                   required
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div class="field-error" id="password-error"></div>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">
                            Confirmer le mot de passe
                        </label>
                        <div class="password-input-wrapper">
                            <input type="password" 
                                   id="password_confirm" 
                                   name="password_confirm" 
                                   class="form-control" 
                                   placeholder="Confirmation mot de passe"
                                   required
                                   autocomplete="new-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirm')">
                                <i class="fas fa-eye" id="password_confirm-eye"></i>
                            </button>
                        </div>
                        <div class="field-error" id="password_confirm-error"></div>
                    </div>

                    <!-- Conditions d'utilisation -->
                    <div class="form-group terms-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" id="terms" required>
                            <span class="checkmark"></span>
                            <span class="checkbox-text">
                                J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> 
                                et la <a href="#" target="_blank">politique de confidentialité</a>
                            </span>
                        </label>
                        <div class="field-error" id="terms-error"></div>
                    </div>

                    <!-- Information sur les crédits -->
                    <div class="credits-info">
                        <i class="fas fa-coins"></i>
                        <span>Vous recevrez automatiquement 20 crédits à l'inscription !</span>
                    </div>

                    <!-- Bouton d'inscription -->
                    <button type="submit" class="btn-auth" id="registerBtn">
                        Créer mon compte
                        <div class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>

                <!-- Lien vers connexion -->
                <div class="auth-footer">
                    <p class="auth-link-text">
                        Vous avez déjà un compte ? 
                        <a href="/ecoride/public/auth/login" class="auth-link">
                            Se connecter
                        </a>
                     </p>
                </div>
             </div>
        </div>
    </div>
</main>

<!-- JavaScript inscription -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const submitBtn = document.getElementById('registerBtn');
    
    // Validation en temps réel
    document.getElementById('pseudo').addEventListener('blur', () => validateField('pseudo', value => {
        if (!value) return 'Le pseudo est requis';
        if (value.length < 3) return 'Au moins 3 caractères';
        return null;
    }));
    
    document.getElementById('email').addEventListener('blur', () => validateField('email', value => {
        if (!value) return 'L\'email est requis';
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) return 'Format invalide';
        return null;
    }));
    
    document.getElementById('password').addEventListener('input', () => validateField('password', value => {
        if (!value) return 'Le mot de passe est requis';
        if (value.length < 8) return 'Au moins 8 caractères';
        if (!/[A-Z]/.test(value)) return 'Au moins une majuscule';
        if (!/[0-9]/.test(value)) return 'Au moins un chiffre';
        
        const faibles = ['password', 'password123', '123456', 'azerty', 'qwerty'];
        if (faibles.includes(value.toLowerCase())) return 'Mot de passe trop commun';
        
        return null;
    }));
    
    document.getElementById('password_confirm').addEventListener('input', () => {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirm').value;
        
        const error = !confirm ? 'Confirmation requise' : 
                     password !== confirm ? 'Les mots de passe ne correspondent pas' : null;
        
        showFieldError('password_confirm', error);
    });
    
    // Soumission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!validateAllFields()) return;
        
        setLoading(true);
        
        try {
            const response = await fetch('/ecoride/public/auth/register', {
                method: 'POST',
                body: new FormData(form)
            });
            
            const data = await response.json();
            
            if (data.success) {
                showMessage('Compte créé avec succès ! Redirection...', 'success');
                setTimeout(() => window.location.href = data.redirect, 2000);
            } else {
                showMessage(data.message, 'error');
            }
            
        } catch (error) {
            showMessage('Erreur de connexion', 'error');
        } finally {
            setLoading(false);
        }
    });
    
    // Fonctions utiles
    function validateField(fieldId, validator) {
        const field = document.getElementById(fieldId);
        const error = validator(field.value.trim());
        showFieldError(fieldId, error);
        return !error;
    }
    
    function validateAllFields() {
        const pseudo = validateField('pseudo', value => value.length < 3 ? 'Au moins 3 caractères' : null);
        const email = validateField('email', value => !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) ? 'Email invalide' : null);
        const password = validateField('password', value => {
            if (value.length < 8) return 'Au moins 8 caractères';
            if (!/[A-Z]/.test(value)) return 'Au moins une majuscule';
            if (!/[0-9]/.test(value)) return 'Au moins un chiffre';
            return null;
        });
        
        return pseudo && email && password;
    }
    
    function showFieldError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + '-error');
        const input = document.getElementById(fieldId);
        
        if (message) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
            input.style.borderColor = '#c53030';
        } else {
            errorElement.classList.remove('show');
            input.style.borderColor = '';
        }
    }
    
    function showMessage(message, type) {
        const container = document.getElementById('auth-messages');
        const alertClass = type === 'error' ? 'alert-error' : 'alert-success';
        const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        
        container.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="fas ${icon}"></i>
                ${message}
            </div>
        `;
    }
    
    function setLoading(loading) {
        submitBtn.disabled = loading;
        const icon = submitBtn.querySelector('i');
        const loader = submitBtn.querySelector('.btn-loader');
        
        if (loading) {
            icon.style.display = 'none';
            loader.style.display = 'block';
        } else {
            icon.style.display = 'inline';
            loader.style.display = 'none';
        }
    }
});

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
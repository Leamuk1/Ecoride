<?php
// app/views/auth/login.php - Page connexion
?>

<link rel="stylesheet" href="/css/pages/login.css">

<main class="auth-main">
    <div class="container">
        <div class="auth-container login-container">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <h1 class="auth-title">Connexion</h1>
                    <p class="auth-subtitle">Connectez-vous à votre compte EcoRide</p>
                </div>

                <!-- Messages d'erreur/succès -->
                <div id="auth-messages" class="auth-messages"></div>

                <form class="auth-form" id="loginForm" action="/ecoride/public/auth/login" method="POST">
                    <!-- Protection CSRF -->
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                           Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-control" 
                               placeholder="votre@email.com"
                               required
                               autocomplete="email">
                        <div class="field-error" id="email-error"></div>
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
                                   placeholder="Votre mot de passe"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        <div class="field-error" id="password-error"></div>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="remember-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me" id="remember_me">
                            <span class="checkmark"></span>
                            <span class="checkbox-text">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="forgot-link">
                            Mot de passe oublié ?
                        </a>
                    </div>

                    <!-- Bouton de connexion -->
                    <button type="submit" class="btn-auth btn-login" id="loginBtn">
                        Se connecter
                        <div class="btn-loader" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </form>

                <!-- Lien vers inscription -->
                <div class="auth-footer">
                    <p class="auth-link-text">
                        Pas encore de compte ? 
                        <a href="/ecoride/public/auth/register" class="auth-link">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- JavaScript connexion -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    // Validation temps réel
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    emailInput.addEventListener('blur', validateEmail);
    passwordInput.addEventListener('blur', validatePassword);
    
    // Soumission formulaire
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitLogin();
        }
    });
    
    function validateEmail() {
        const email = emailInput.value.trim();
        const emailError = document.getElementById('email-error');
        
        if (!email) {
            showError(emailError, 'L\'email est requis');
            return false;
        }
        
        if (!isValidEmail(email)) {
            showError(emailError, 'Format d\'email invalide');
            return false;
        }
        
        hideError(emailError);
        return true;
    }
    
    function validatePassword() {
        const password = passwordInput.value;
        const passwordError = document.getElementById('password-error');
        
        if (!password) {
            showError(passwordError, 'Le mot de passe est requis');
            return false;
        }
        
        if (password.length < 6) {
            showError(passwordError, 'Le mot de passe doit contenir au moins 6 caractères');
            return false;
        }
        
        hideError(passwordError);
        return true;
    }
    
    function validateForm() {
        return validateEmail() && validatePassword();
    }
    
    async function submitLogin() {
        const formData = new FormData(loginForm);
        
        // Afficher loader
        showLoader();
        
        try {
            const response = await fetch('/ecoride/public/auth/login', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showSuccess('Connexion réussie ! Redirection...');
                
                setTimeout(() => {
                    window.location.href = data.redirect || '/ecoride/public/dashboard';
                }, 1500);
                
            } else {
                showAlert(data.message || 'Erreur de connexion', 'error');
            }
            
        } catch (error) {
            console.error('Erreur connexion:', error);
            showAlert('Erreur de connexion. Veuillez réessayer.', 'error');
        } finally {
            hideLoader();
        }
    }
    
    function showLoader() {
        loginBtn.disabled = true;
        loginBtn.querySelector('i').style.display = 'none';
        loginBtn.querySelector('.btn-loader').style.display = 'block';
    }
    
    function hideLoader() {
        loginBtn.disabled = false;
        loginBtn.querySelector('i').style.display = 'inline';
        loginBtn.querySelector('.btn-loader').style.display = 'none';
    }
    
    function showError(errorElement, message) {
        errorElement.textContent = message;
        errorElement.classList.add('show');
        errorElement.parentElement.querySelector('.form-control').style.borderColor = '#c53030';
    }
    
    function hideError(errorElement) {
        errorElement.classList.remove('show');
        errorElement.parentElement.querySelector('.form-control').style.borderColor = '';
    }
    
    function showAlert(message, type) {
        const messagesContainer = document.getElementById('auth-messages');
        const alertClass = type === 'error' ? 'alert-error' : 'alert-success';
        const icon = type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle';
        
        messagesContainer.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="fas ${icon}"></i>
                ${message}
            </div>
        `;
    }
    
    function showSuccess(message) {
        showAlert(message, 'success');
    }
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
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
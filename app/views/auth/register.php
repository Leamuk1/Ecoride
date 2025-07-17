<?php
// app/views/auth/register.php - Page inscription temporaire EcoRide
?>

<main style="padding: 6rem 2rem 2rem; min-height: 80vh; background: var(--background-light);">
    <div class="container">
        <div style="max-width: 500px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <i class="fas fa-user-plus" style="font-size: 3rem; color: var(--eco-green); margin-bottom: 1rem;"></i>
                <h1 style="color: var(--text-color); margin-bottom: 0.5rem;">Inscription</h1>
                <p style="color: var(--eco-green-light);">Rejoignez la communauté EcoRide</p>
            </div>
            
            <div style="background: white; padding: 2.5rem; border-radius: 8px; box-shadow: var(--shadow); margin-bottom: 2rem;">
                <div style="background: rgba(67, 83, 52, 0.1); padding: 1.5rem; border-radius: 6px; border-left: 4px solid var(--eco-green); margin-bottom: 2rem;">
                    <h3 style="color: var(--eco-green); margin-bottom: 1rem; display: flex; align-items: center;">
                        <i class="fas fa-rocket" style="margin-right: 0.5rem;"></i>
                        Bientôt disponible !
                    </h3>
                    <p style="color: var(--text-color); margin-bottom: 1rem;">
                        L'inscription sera implémentée dans l'US7 avec un système complet et sécurisé.
                    </p>
                </div>
                
                <!-- Aperçu du futur formulaire d'inscription -->
                <form style="opacity: 0.6; pointer-events: none;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-color);">
                                Prénom
                            </label>
                            <input type="text" 
                                   style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-gray); border-radius: 6px;" 
                                   placeholder="Jean"
                                   disabled>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-color);">
                                Nom
                            </label>
                            <input type="text" 
                                   style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-gray); border-radius: 6px;" 
                                   placeholder="Dupont"
                                   disabled>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-color);">
                            Email
                        </label>
                        <input type="email" 
                               style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-gray); border-radius: 6px;" 
                               placeholder="jean.dupont@email.com"
                               disabled>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-color);">
                            Téléphone
                        </label>
                        <input type="tel" 
                               style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-gray); border-radius: 6px;" 
                               placeholder="06 12 34 56 78"
                               disabled>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-color);">
                            Mot de passe
                        </label>
                        <input type="password" 
                               style="width: 100%; padding: 0.75rem; border: 2px solid var(--border-gray); border-radius: 6px;" 
                               placeholder="••••••••"
                               disabled>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <label style="display: flex; align-items: flex-start; gap: 0.75rem; cursor: not-allowed;">
                            <input type="checkbox" 
                                   style="margin-top: 0.25rem;" 
                                   disabled>
                            <span style="color: var(--text-color); font-size: 0.9rem; line-height: 1.4;">
                                J'accepte les <a href="#" style="color: var(--eco-green);">conditions d'utilisation</a> 
                                et la <a href="#" style="color: var(--eco-green);">politique de confidentialité</a>
                            </span>
                        </label>
                    </div>
                    
                    <button type="submit" 
                            style="width: 100%; background: var(--eco-green); color: white; padding: 0.875rem; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: not-allowed;"
                            disabled>
                        Créer mon compte
                    </button>
                </form>
                
                <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-gray);">
                    <p style="color: var(--eco-green-light); margin-bottom: 1rem;">
                        Vous avez déjà un compte ?
                    </p>
                    <a href="/ecoride/public/login" 
                       style="color: var(--eco-green); text-decoration: none; font-weight: 500; opacity: 0.6; pointer-events: none;">
                        Se connecter
                    </a>
                </div>
            </div>
            
            <div style="text-align: center;">
                <a href="/ecoride/public/" 
                   class="btn-outline-primary"
                   style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</main>
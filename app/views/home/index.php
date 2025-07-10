<?php 
// app/views/home/index.php - Page d'accueil EcoRide
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6 order-lg-2">
                    <div class="hero-text">
                        <h1 class="hero-title">
                            Ensemble,<br>
                            Roulez pour la Planète
                        </h1>
                        <p class="hero-description">
                            Rejoignez la révolution du covoiturage avec Ecoride, 
                            la plateforme dédiée à réduire l'empreinte carbone de vos déplacements. 
                            Écologique, économique et convivial, nous vous accompagnons pour voyager 
                            autrement, tout en respectant notre planète. Avec Ecoride, chaque trajet 
                            compte pour un avenir plus vert.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6 order-lg-1">
                    <div class="search-form-container">
                        <form class="search-form" id="searchForm">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-record-vinyl input-icon"></i>
                                    <input type="text" 
                                           class="form-input" 
                                           id="departure" 
                                           placeholder="Ville de départ" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" 
                                           class="form-input" 
                                           id="arrival" 
                                           placeholder="Ville d'arrivée" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="fas fa-calendar-alt input-icon"></i>
                                    <input type="date" 
                                           class="form-input" 
                                           id="date" 
                                           required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <select class="form-input" id="passengers" required>
                                        <option value="1">1 passager</option>
                                        <option value="2">2 passagers</option>
                                        <option value="3">3 passagers</option>
                                        <option value="4">4 passagers</option>
                                    </select>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn-search">
                                Rechercher
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2 class="section-title">Qui sommes nous ?</h2>
                <p class="section-description">
                    EcoRide est une jeune startup française qui s'est donnée pour mission de 
                    révolutionner les déplacements en promouvant le covoiturage écologique et 
                    économique. Une plateforme qui soit simple à utiliser et qui s'inscrive dans 
                    une démarche écoresponsable, c'est notre promesse afin de réduire l'impact 
                    carbone et partager les frais.
                </p>
            </div>
            <div class="about-image">
                <img src="/images/qui-sommes-nous.jpg" 
                     alt="EcoRide - Qui sommes nous" 
                     class="about-img">
            </div>
        </div>
    </div>
</section>

<!-- Eco Section -->
<section class="eco-section">
    <div class="container">
        <div class="eco-content">
            <div class="eco-image">
                <img src="/images/electric-car-4381728_1280.jpg" 
                     alt="Covoiturage écologique" 
                     class="eco-img">
            </div>
            <div class="eco-text">
                <h2 class="section-title-white">Un covoiturage écologique</h2>
                <p class="eco-description">
                    EcoRide s'engage à réduire l'empreinte carbone des déplacements en encourageant
                     le covoiturage écologique, ce qui contribue à réduire les émissions de gaz à effet de serre: 
                     Moins de voitures sur les routes signifie moins de pollution atmosphérique.
Préserver les ressources naturelles, en partageant un véhicule, nous réduisons la consommation de carburant et la dépendance
 aux énergies fossiles. 
Économiser de l'argent et favoriser une mobilité durable.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="cta-text">
                <h2 class="section-title">Voyagez en toute sécurité</h2>
                <p class="cta-description">
                    EcoRide est la solution idéale pour un covoiturage à la fois écologique et 
                    sécurisé. La plateforme met l'accent sur la responsabilité et garantit ainsi 
                    une expérience de voyage sereine et éthique.
                </p>
            </div>
            <div class="cta-image">
                <img src="/images/uaz-8254778_1280.jpg" 
                     alt="Sécurité covoiturage" 
                     class="cta-img">
            </div>
        </div>
    </div>
</section>

<!-- Covoiturages populaires depuis la BDD -->
<?php if (!empty($rides)): ?>
<section class="rides-section py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Trajets populaires</h2>
                <p class="section-description">Découvrez les covoiturages les plus demandés</p>
            </div>
        </div>
        
        <div class="row">
            <?php foreach (array_slice($rides, 0, 3) as $ride): ?>
            <div class="col-md-4 mb-4">
                <div class="card ride-card h-100">
                    <div class="card-header ride-header">
                        <h5 class="card-title"><?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?></h5>
                        <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($ride['date_depart'])) ?></small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <i class="fas fa-user-circle me-2"></i>
                                <strong><?= htmlspecialchars($ride['prenom']) ?> <?= strtoupper(substr($ride['nom'], 0, 1)) ?>.</strong>
                                <?php if ($ride['type_vehicule'] === 'electrique'): ?>
                                    <span class="badge bg-success ms-2">
                                        <i class="fas fa-bolt me-1"></i>Électrique
                                    </span>
                                <?php elseif ($ride['type_vehicule'] === 'hybride'): ?>
                                    <span class="badge bg-info ms-2">
                                        <i class="fas fa-leaf me-1"></i>Hybride
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="price-tag"><?= number_format($ride['prix_place'], 2) ?>€</div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i><?= $ride['places_disponibles'] ?> place(s) disponible(s)
                            </small>
                            <?php if (!empty($ride['note_moyenne'])): ?>
                            <small class="text-muted">
                                <i class="fas fa-star text-warning me-1"></i><?= number_format($ride['note_moyenne'], 1) ?> 
                                (<?= $ride['nb_avis'] ?> avis)
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= url('/rides') ?>" class="btn btn-outline-primary btn-lg">
                Voir tous les trajets <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les éléments une seule fois
    const dateInput = document.getElementById('date');
    const selectInput = document.getElementById('passengers');
    const departureInput = document.getElementById('departure');
    const arrivalInput = document.getElementById('arrival');
    const searchForm = document.getElementById('searchForm');
    
    // STYLES UNIFIÉS POUR TOUS LES CHAMPS
    function addUnifiedHoverStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Style unifié pour tous les champs au survol et focus */
            .form-input:hover {
                border-color: #999 !important;
                background-color: #f8f9fa !important;
                transition: all 0.2s ease !important;
            }
            
            .form-input:focus {
                border-color: #007bff !important;
                background-color: white !important;
                box-shadow: 0 0 0 2px rgba(0,123,255,0.25) !important;
                outline: none !important;
            }
            
            /* Style unifié pour les options du select */
            #passengers option {
                background-color: white !important;
                color: #333 !important;
                padding: 8px 12px !important;
            }
            
            #passengers option:hover {
                background-color: #007bff !important;
                color: white !important;
            }
            
            #passengers option:checked,
            #passengers option:selected {
                background-color: #0056b3 !important;
                color: white !important;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Gestion des couleurs dynamiques des champs
    if (dateInput) {
        function updateDateColor() {
            if (dateInput.value) {
                dateInput.setAttribute('data-filled', 'true');
            } else {
                dateInput.removeAttribute('data-filled');
            }
        }
        
        // Initialiser SANS pré-remplir
        setTimeout(updateDateColor, 200);
        
        // Écouter les changements
        dateInput.addEventListener('change', updateDateColor);
        dateInput.addEventListener('input', updateDateColor);
    }
    
    // Champ select - gérer la couleur
    if (selectInput) {
        selectInput.addEventListener('change', function() {
            if (this.value) {
                this.style.setProperty('color', '#18230F', 'important');
            } else {
                this.style.setProperty('color', 'rgba(67,83,52,0.6)', 'important');
            }
        });
    }
    
    // Champs texte - gérer la couleur en temps réel
    [departureInput, arrivalInput].forEach(function(input) {
        if (input) {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.style.setProperty('color', '#18230F', 'important');
                } else {
                    this.style.setProperty('color', 'rgba(67,83,52,0.6)', 'important');
                }
            });
        }
    });
    
    // Gestion du formulaire de recherche
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const departure = departureInput.value.trim();
            const arrival = arrivalInput.value.trim();
            const date = dateInput.value;
            const passengers = selectInput.value;
            
            // Validation
            if (!departure || !arrival || !date) {
                alert('Veuillez remplir tous les champs de recherche');
                return;
            }
            
            if (departure === arrival) {
                alert('La ville de départ et d\'arrivée ne peuvent pas être identiques');
                return;
            }
            
            const selectedDate = new Date(date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate < today) {
                alert('Vous ne pouvez pas rechercher un trajet dans le passé');
                return;
            }
            
            // Redirection vers la page de recherche
            const searchParams = new URLSearchParams({
                departure: departure,
                arrival: arrival,
                date: date,
                passengers: passengers
            });
            
            window.location.href = `/ecoride/rides/search?${searchParams.toString()}`;
        });
    }
    
    // Animation au scroll (optionnel)
    function animateOnScroll() {
        const elements = document.querySelectorAll('.about-content, .eco-content, .cta-content, .ride-card');
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    }
    
    // Initialiser les animations
    window.addEventListener('scroll', animateOnScroll);
    
    // AUTO-COMPLÉTION 
function createAutocomplete(inputElement) {
    let autocompleteContainer = null;
    let selectedIndex = -1;
    let currentRequest = null;
    let isMouseDownOnSuggestion = false;

    // Cache pour éviter les requêtes répétées
    const cache = new Map();

    async function searchCities(query) {
        // Vérifier le cache d'abord
        if (cache.has(query)) {
            return cache.get(query);
        }

        try {
            // Annuler la requête précédente si elle existe
            if (currentRequest) {
                currentRequest.abort();
            }

            // Créer un AbortController pour cette requête
            const controller = new AbortController();
            currentRequest = controller;

            const response = await fetch(
                `https://geo.api.gouv.fr/communes?nom=${encodeURIComponent(query)}&fields=nom,code,population&limit=10`,
                { signal: controller.signal }
            );

            if (!response.ok) {
                throw new Error('Erreur API');
            }

            const cities = await response.json();
            
            // Traiter et trier les résultats - SANS codes postaux
            const processedCities = cities
                .map(city => ({
                    name: city.nom,
                    population: city.population || 0
                }))
                .sort((a, b) => {
                    // Priorité aux villes qui commencent par la recherche
                    const aStarts = a.name.toLowerCase().startsWith(query.toLowerCase());
                    const bStarts = b.name.toLowerCase().startsWith(query.toLowerCase());
                    
                    if (aStarts && !bStarts) return -1;
                    if (!aStarts && bStarts) return 1;
                    
                    // Ensuite trier par population (plus grande d'abord)
                    return b.population - a.population;
                });

            // Mettre en cache le résultat
            cache.set(query, processedCities);
            return processedCities;

        } catch (error) {
            if (error.name === 'AbortError') {
                // Requête annulée, normal
                return [];
            }
            console.warn('Erreur lors de la recherche de villes:', error);
            
            // Fallback sur une liste locale minimale en cas d'erreur
            const fallbackCities = [
                'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 
                'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille', 'Rennes'
            ];
            
            return fallbackCities
                .filter(city => city.toLowerCase().includes(query.toLowerCase()))
                .map(city => ({ name: city, population: 0 }));
        }
    }

    function showSuggestions(suggestions) {
        hideSuggestions();
        
        if (suggestions.length === 0) return;

        autocompleteContainer = document.createElement('div');
        autocompleteContainer.className = 'autocomplete-suggestions';
        
        // CALCUL DE POSITION MOBILE
        const inputRect = inputElement.getBoundingClientRect();
        const viewport = {
            width: window.innerWidth,
            height: window.innerHeight
        };
        
        // Détection mobile
        const isMobile = viewport.width <= 768;
        
        let topPosition, leftPosition, widthValue;
        
        if (isMobile) {
            // MOBILE : Position relative au champ avec vérification
            const containerRect = inputElement.closest('.search-form').getBoundingClientRect();
            const inputRelativeTop = inputRect.top - containerRect.top;
            
            topPosition = (containerRect.top + inputRelativeTop + inputRect.height + 2) + 'px';
            leftPosition = Math.max(inputRect.left, 10) + 'px'; // Min 10px du bord
            widthValue = Math.min(inputRect.width, viewport.width - 20) + 'px'; // Max largeur - 20px
        } else {
            // DESKTOP : Position normale
            topPosition = (inputRect.bottom + window.scrollY + 2) + 'px';
            leftPosition = (inputRect.left + window.scrollX) + 'px';
            widthValue = inputRect.width + 'px';
        }
        
        // Styles cohérents avec le reste du formulaire
        autocompleteContainer.style.position = 'fixed';
        autocompleteContainer.style.top = topPosition;
        autocompleteContainer.style.left = leftPosition;
        autocompleteContainer.style.width = widthValue;
        autocompleteContainer.style.backgroundColor = 'white';
        autocompleteContainer.style.border = '1px solid #ccc';
        autocompleteContainer.style.borderRadius = '4px';
        autocompleteContainer.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        autocompleteContainer.style.zIndex = '9999';
        autocompleteContainer.style.maxHeight = isMobile ? '120px' : '200px';
        autocompleteContainer.style.overflowY = 'auto';

        suggestions.forEach((city, index) => {
            const item = document.createElement('div');
            item.className = 'autocomplete-item';
            
            // Affichage simple - SEULEMENT le nom de la ville
            item.textContent = city.name;
            
            // Styles adaptés mobile/desktop
            const itemPadding = isMobile ? '8px 10px' : '10px 12px';
            const itemFontSize = isMobile ? '14px' : '16px';
            
            item.style.padding = itemPadding;
            item.style.cursor = 'pointer';
            item.style.borderBottom = '1px solid #eee';
            item.style.color = '#333';
            item.style.fontFamily = '"Roboto", sans-serif';
            item.style.fontSize = itemFontSize;
            item.style.transition = 'all 0.2s ease';

            // Gestion des événements souris pour éviter les conflits
            item.addEventListener('mouseenter', () => {
                item.style.backgroundColor = '#007bff';
                item.style.color = 'white';
                selectedIndex = index;
            });

            item.addEventListener('mouseleave', () => {
                item.style.backgroundColor = 'transparent';
                item.style.color = '#333';
            });

            // Marquer quand on commence à cliquer
            item.addEventListener('mousedown', (e) => {
                e.preventDefault(); // Empêche le blur de l'input
                isMouseDownOnSuggestion = true;
            });

            // Gérer le clic
            item.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                selectCity(city.name);
            });

            autocompleteContainer.appendChild(item);
        });

        // Vérification finale de position pour éviter les débordements
        document.body.appendChild(autocompleteContainer);
        
        if (isMobile) {
            // Vérification mobile : ajuster si débordement
            const containerRect = autocompleteContainer.getBoundingClientRect();
            
            // Si débordement à droite
            if (containerRect.right > viewport.width - 10) {
                autocompleteContainer.style.left = '10px';
                autocompleteContainer.style.width = (viewport.width - 20) + 'px';
            }
            
            // Si débordement en bas
            if (containerRect.bottom > viewport.height - 10) {
                autocompleteContainer.style.top = (inputRect.top - 120 + window.scrollY) + 'px';
                autocompleteContainer.style.maxHeight = '100px';
            }
        }
        
        selectedIndex = -1;
    }

    function hideSuggestions() {
        if (autocompleteContainer) {
            autocompleteContainer.remove();
            autocompleteContainer = null;
            selectedIndex = -1;
        }
        isMouseDownOnSuggestion = false; // Reset du flag
    }

    function selectCity(cityName) {
        inputElement.value = cityName;
        hideSuggestions();
        
        // Déclencher l'événement input pour la couleur
        const event = new Event('input', { bubbles: true });
        inputElement.dispatchEvent(event);
        
        inputElement.focus();
    }

    function updateSelection() {
        if (!autocompleteContainer) return;
        
        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.style.backgroundColor = '#007bff';
                item.style.color = 'white';
            } else {
                item.style.backgroundColor = 'transparent';
                item.style.color = '#333';
            }
        });
    }

    // Debounce pour éviter trop de requêtes
    let timeoutId = null;

    // Événements sur l'input
    inputElement.addEventListener('input', function() {
        const value = this.value.trim();
        
        if (value.length < 2) {
            hideSuggestions();
            return;
        }

        // Annuler le timeout précédent
        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        // Attendre 300ms avant de faire la requête
        timeoutId = setTimeout(async () => {
            try {
                const suggestions = await searchCities(value);
                if (suggestions.length > 0) {
                    showSuggestions(suggestions);
                } else {
                    hideSuggestions();
                }
            } catch (error) {
                console.warn('Erreur lors de la recherche:', error);
                hideSuggestions();
            }
        }, 300);
    });

    inputElement.addEventListener('keydown', function(e) {
        if (!autocompleteContainer) return;

        const items = autocompleteContainer.querySelectorAll('.autocomplete-item');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection();
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection();
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    const cityName = items[selectedIndex].textContent;
                    selectCity(cityName);
                }
                break;
                
            case 'Escape':
                hideSuggestions();
                break;
        }
    });

    // Gestion du blur
    inputElement.addEventListener('blur', function() {
        // Si on est en train de cliquer sur une suggestion, ne pas masquer
        if (isMouseDownOnSuggestion) {
            // Remettre le focus sur l'input après un court délai
            setTimeout(() => {
                inputElement.focus();
            }, 10);
            return;
        }
        
        // Sinon, masquer les suggestions après un court délai
        setTimeout(() => {
            if (!isMouseDownOnSuggestion) {
                hideSuggestions();
            }
        }, 150);
    });

    // Masquer l'auto-complétion lors du scroll sur mobile
    window.addEventListener('scroll', function() {
        if (window.innerWidth <= 768 && autocompleteContainer) {
            hideSuggestions();
        }
    });

    // Recalculer la position lors du redimensionnement
    window.addEventListener('resize', function() {
        if (autocompleteContainer) {
            hideSuggestions();
        }
    });
}
    
    // APPLIQUER TOUTES LES FONCTIONNALITÉS
    addUnifiedHoverStyles();
    if (departureInput) createAutocomplete(departureInput);
    if (arrivalInput) createAutocomplete(arrivalInput);
});
</script>

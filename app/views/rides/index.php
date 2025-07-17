<?php
// app/views/rides/index.php - Page covoiturages finale

// Récupérer les paramètres de recherche
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = $_GET['passengers'] ?? '1';

// Déterminer si c'est une recherche ou un affichage général
$isSearch = !empty($departure) || !empty($arrival) || !empty($date);
?>

<?php if ($isSearch): ?>
    <!-- ================================================ -->
    <!-- MODE RECHERCHE AVEC RÉSULTATS -->
    <!-- ================================================ -->
    <main style="padding: 6rem 2rem 2rem; min-height: 80vh;">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <h1 style="color: var(--text-color); margin-bottom: 1rem;">Résultats de recherche</h1>
                
                <!-- Afficher les critères de recherche -->
                <div style="background: rgba(67, 83, 52, 0.1); padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
                    <h3 style="color: var(--eco-green); margin-bottom: 1rem;">Critères de recherche :</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <?php if ($departure): ?>
                            <div>
                                <strong style="color: var(--text-color);">Départ :</strong>
                                <span style="color: var(--eco-green);"><?= htmlspecialchars($departure) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($arrival): ?>
                            <div>
                                <strong style="color: var(--text-color);">Arrivée :</strong>
                                <span style="color: var(--eco-green);"><?= htmlspecialchars($arrival) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($date): ?>
                            <div>
                                <strong style="color: var(--text-color);">Date :</strong>
                                <span style="color: var(--eco-green);"><?= htmlspecialchars(date('d/m/Y', strtotime($date))) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <strong style="color: var(--text-color);">Passagers :</strong>
                            <span style="color: var(--eco-green);"><?= htmlspecialchars($passengers) ?></span>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div style="margin-top: 1rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="/ecoride/public/" 
                           style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--eco-green); text-decoration: none; font-weight: 500;">
                            <i class="fas fa-edit"></i>
                            Modifier la recherche
                        </a>
                        <a href="/ecoride/public/rides" 
                           style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--eco-green); text-decoration: none; font-weight: 500;">
                            <i class="fas fa-list"></i>
                            Voir tous les trajets
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Simulation de résultats -->
            <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: var(--shadow);">
                <h2 style="color: var(--eco-green); margin-bottom: 1.5rem;">
                    <i class="fas fa-car"></i>
                    Trajets trouvés
                </h2>
                
                <?php
                // Simulation de résultats basée sur la recherche
                $mockResults = [];
                
                if (stripos($departure, 'paris') !== false && stripos($arrival, 'lyon') !== false) {
                    $mockResults[] = [
                        'route' => 'Paris → Lyon',
                        'date' => $date ?: '2025-07-25',
                        'time' => '14:30',
                        'price' => 45,
                        'seats' => 2,
                        'driver' => 'Marie D.',
                        'rating' => 4.8
                    ];
                }
                
                if (stripos($departure, 'marseille') !== false && stripos($arrival, 'nice') !== false) {
                    $mockResults[] = [
                        'route' => 'Marseille → Nice',
                        'date' => $date ?: '2025-07-26',
                        'time' => '09:00',
                        'price' => 18,
                        'seats' => 3,
                        'driver' => 'Thomas L.',
                        'rating' => 4.5
                    ];
                }
                
                // Ajouter un résultat générique si aucune correspondance
                if (empty($mockResults)) {
                    $mockResults = [
                        [
                            'route' => ($departure && $arrival) ? "$departure → $arrival" : 'Toulouse → Bordeaux',
                            'date' => $date ?: '2025-07-27',
                            'time' => '16:45',
                            'price' => 35,
                            'seats' => 1,
                            'driver' => 'Sophie M.',
                            'rating' => 4.9
                        ]
                    ];
                }
                ?>
                
                <?php if (!empty($mockResults)): ?>
                    <div style="color: var(--eco-green); margin-bottom: 1.5rem; font-weight: 600;">
                        <i class="fas fa-check-circle"></i>
                        <?= count($mockResults) ?> trajet<?= count($mockResults) > 1 ? 's' : '' ?> correspondant<?= count($mockResults) > 1 ? 's' : '' ?> à votre recherche
                    </div>
                    
                    <?php foreach ($mockResults as $result): ?>
                        <div style="border: 1px solid var(--border-gray); border-radius: 8px; padding: 1.5rem; margin-bottom: 1rem; transition: box-shadow 0.3s ease;" 
                             onmouseover="this.style.boxShadow='0 4px 12px rgba(67, 83, 52, 0.15)'" 
                             onmouseout="this.style.boxShadow='none'">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div>
                                    <h3 style="color: var(--text-color); margin-bottom: 0.5rem; font-size: 1.3rem;">
                                        <?= htmlspecialchars($result['route']) ?>
                                    </h3>
                                    <p style="color: var(--eco-green-light); margin: 0;">
                                        <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars(date('d/m/Y', strtotime($result['date']))) ?> à <?= htmlspecialchars($result['time']) ?>
                                    </p>
                                </div>
                                <div style="background: var(--eco-green); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600;">
                                    <?= $result['price'] ?> crédits
                                </div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 2rem;">
                                    <span style="color: var(--text-color);">
                                        <i class="fas fa-user-circle" style="margin-right: 0.5rem;"></i>
                                        <strong><?= htmlspecialchars($result['driver']) ?></strong>
                                    </span>
                                    <span style="color: var(--text-color);">
                                        <i class="fas fa-star" style="color: #ffc107; margin-right: 0.25rem;"></i>
                                        <?= $result['rating'] ?>
                                    </span>
                                    <span style="color: var(--eco-green);">
                                        <i class="fas fa-users" style="margin-right: 0.5rem;"></i>
                                        <?= $result['seats'] ?> place<?= $result['seats'] > 1 ? 's' : '' ?> libre<?= $result['seats'] > 1 ? 's' : '' ?>
                                    </span>
                                </div>
                                
                                <button style="background: var(--eco-green); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background-color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='var(--primary-color)'"
                                        onmouseout="this.style.backgroundColor='var(--eco-green)'"
                                        onclick="alert('Système de réservation à implémenter dans US6 !')">
                                    <i class="fas fa-check" style="margin-right: 0.5rem;"></i>
                                    Réserver
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                <?php else: ?>
                    <div style="text-align: center; padding: 2rem; color: var(--eco-green-light);">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <h3 style="color: var(--text-color);">Aucun trajet trouvé</h3>
                        <p>Essayez de modifier vos critères de recherche.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php else: ?>
    <!-- ================================================ -->
    <!-- MODE NAVIGATION NORMALE - TON FICHIER COMPLET -->
    <!-- ================================================ -->
    
    <!-- CSS de la page -->
    <link rel="stylesheet" href="/css/pages/rides.css">

    <!-- Hero Covoiturages Section -->
    <section class="rides-hero">
        <div class="container">       
            <div class="rides-hero-content">
                <h1 class="rides-title">Trouvez votre covoiturage</h1>
                <p class="rides-subtitle">
                    Découvrez des trajets écologiques et économiques partout en France.
                    Voyagez autrement avec EcoRide !
                </p>
            </div>
        </div>
    </section>

    <!-- Formulaire de recherche harmonisé -->
    <section class="rides-search-section">
        <div class="container">
            <div class="rides-search-container">
                <h2 class="search-section-title">Rechercher un trajet</h2>
                
                <form class="rides-search-form" id="ridesSearchForm" action="/ecoride/public/rides" method="GET">
                    <div class="search-form-grid">
                        <div class="search-group">
                            <label for="searchDeparture">Ville de départ</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-record-vinyl input-icon" aria-hidden="true"></i>
                                <input type="text" 
                                       class="search-input" 
                                       id="searchDeparture"
                                       name="departure" 
                                       placeholder="Paris, Lyon, Marseille..."
                                       autocomplete="address-line1"
                                       aria-label="Ville de départ"
                                       required>
                            </div>
                        </div>
                        
                        <div class="search-group">
                            <label for="searchArrival">Ville d'arrivée</label>
                            <div class="input-with-icon">
                                <i class="fas fa-map-marker-alt input-icon" aria-hidden="true"></i>
                                <input type="text" 
                                       class="search-input" 
                                       id="searchArrival"
                                       name="arrival" 
                                       placeholder="Destination..."
                                       autocomplete="address-line2"
                                       aria-label="Ville d'arrivée"
                                       required>
                            </div>
                        </div>
                        
                        <div class="search-group">
                            <label for="searchDate">Date de départ</label>
                            <div class="input-with-icon">
                                <i class="fas fa-calendar-alt input-icon" aria-hidden="true"></i>
                                <input type="date" 
                                       class="search-input" 
                                       id="searchDate"
                                       name="date"
                                       autocomplete="off"
                                       aria-label="Date de départ"
                                       required>
                            </div>
                        </div>
                        
                        <div class="search-group">
                            <label for="searchPassengers">Passagers</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon" aria-hidden="true"></i>
                                <select class="search-input" 
                                        id="searchPassengers"
                                        name="passengers"
                                        aria-label="Nombre de passagers"
                                        required>
                                    <option value="1">1 passager</option>
                                    <option value="2">2 passagers</option>
                                    <option value="3">3 passagers</option>
                                    <option value="4">4 passagers</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="btn-search-rides"
                            name="search-submit"
                            aria-label="Lancer la recherche de trajets">
                        <span class="btn-icon">
                            <i class="fas fa-search" aria-hidden="true"></i>
                            Rechercher
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Filtres -->
    <section class="rides-filters-section">
        <div class="container">
            <div class="filters-container">
                <h3 class="filters-title">Filtrer les résultats</h3>
                
                <form class="filters-form" name="filtersForm">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="priceMax">Crédits maximum</label>
                            <input type="range" 
                                   id="priceMax"
                                   name="priceMax" 
                                   class="price-slider" 
                                   min="5" 
                                   max="100" 
                                   value="50"
                                   aria-label="Crédits maximum"> 
                            <span class="price-display" aria-live="polite">50 crédits</span>
                        </div>
                        
                        <fieldset class="filter-group">
                            <legend>Type de véhicule</legend>
                            <div class="checkbox-filters">
                                <label class="filter-checkbox">
                                    <input type="checkbox" 
                                           name="vehicle[]" 
                                           value="electrique" 
                                           id="vehicle-electric">
                                    <span class="checkmark"></span>
                                    Électrique
                                </label>
                                <label class="filter-checkbox">
                                    <input type="checkbox" 
                                           name="vehicle[]" 
                                           value="hybride" 
                                           id="vehicle-hybrid">
                                    <span class="checkmark"></span>
                                    Hybride
                                </label>
                                <label class="filter-checkbox">
                                    <input type="checkbox" 
                                           name="vehicle[]" 
                                           value="essence" 
                                           id="vehicle-gasoline">
                                    <span class="checkmark"></span>
                                    Essence
                                </label>
                            </div>
                        </fieldset>
                        
                        <div class="filter-group">
                            <label for="minRating">Note minimum</label>
                            <select id="minRating" 
                                    name="minRating"
                                    class="filter-select"
                                    aria-label="Note minimum requise">
                                <option value="0">Toutes les notes</option>
                                <option value="3">3+ étoiles</option>
                                <option value="4">4+ étoiles</option>
                                <option value="4.5">4.5+ étoiles</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label for="durationFilter">Durée du trajet</label>
                            <select id="durationFilter" name="durationFilter" class="filter-select">
                                <option value="">Toutes les durées</option>
                                <option value="1">Moins d'1h</option>
                                <option value="2">1h à 2h</option>
                                <option value="4">2h à 4h</option>
                                <option value="6">4h à 6h</option>
                                <option value="12">Plus de 6h</option>
                            </select>
                        </div>
                        
                        <button type="button" 
                                class="btn-reset-filters" 
                                id="resetFilters"
                                name="resetFilters"
                                aria-label="Réinitialiser tous les filtres">
                            <i class="fas fa-times" aria-hidden="true"></i>
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Résultats des covoiturages -->
    <section class="rides-results-section">
        <div class="container">
            <div class="results-header">
                <h2 class="results-title">Trajets disponibles</h2>
                <div class="results-count">
                    <span id="resultsCount">Chargement...</span>
                </div>
            </div>
            
            <!-- Liste des trajets -->
            <div class="rides-grid" id="ridesGrid">
                <!-- Les trajets seront générés dynamiquement -->
            </div>
            
            <!-- Message si aucun résultat -->
            <div class="no-results" id="noResults" style="display: none;">
                <div class="no-results-content">
                    <i class="fas fa-search"></i>
                    <h3>Aucun trajet trouvé</h3>
                    <p>Essayez de modifier vos critères de recherche ou vos filtres.</p>
                    <button class="btn-reset-search" onclick="resetSearch()">
                        Nouvelle recherche
                    </button>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination">
                    <button class="pagination-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </button>
                    
                    <div class="pagination-numbers">
                        <button class="pagination-number active">1</button>
                        <button class="pagination-number">2</button>
                        <button class="pagination-number">3</button>
                        <span class="pagination-dots">...</span>
                        <button class="pagination-number">8</button>
                    </div>
                    
                    <button class="pagination-btn next">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="rides-cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Vous ne trouvez pas votre trajet ?</h2>
                <p>Créez une alerte pour être notifié dès qu'un nouveau trajet correspond à vos critères.</p>
                <div class="cta-buttons">
                    <button class="btn-alert">
                        <i class="fas fa-bell"></i>
                        Créer une alerte
                    </button>
                    <a href="/rides/create" class="btn-create">
                        <i class="fas fa-plus"></i>
                        Proposer un trajet
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- TON JAVASCRIPT COMPLET -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Récupérer les éléments du formulaire
        const dateInput = document.getElementById('searchDate');
        const selectInput = document.getElementById('searchPassengers');
        const departureInput = document.getElementById('searchDeparture');
        const arrivalInput = document.getElementById('searchArrival');
        const searchForm = document.getElementById('ridesSearchForm');
        
        // GESTION DES COULEURS DYNAMIQUES
        function applyHarmonizedColors() {
            // Date input
            if (dateInput) {
                function updateDateColor() {
                    if (dateInput.value) {
                        dateInput.style.setProperty('color', '#18230F', 'important');
                    } else {
                        dateInput.style.setProperty('color', '#435334', 'important');
                    }
                }
                setTimeout(updateDateColor, 200);
                dateInput.addEventListener('change', updateDateColor);
                dateInput.addEventListener('input', updateDateColor);
            }
            
            // Select input
            if (selectInput) {
                selectInput.addEventListener('change', function() {
                    if (this.value) {
                        this.style.setProperty('color', '#18230F', 'important');
                    } else {
                        this.style.setProperty('color', '#435334', 'important');
                    }
                });
                selectInput.style.setProperty('color', '#18230F', 'important');
            }
            
            // Text inputs
            [departureInput, arrivalInput].forEach(function(input) {
                if (input) {
                    input.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.style.setProperty('color', '#18230F', 'important');
                        } else {
                            this.style.setProperty('color', '#435334', 'important');
                        }
                    });
                    
                    if (input.value.trim()) {
                        input.style.setProperty('color', '#18230F', 'important');
                    } else {
                        input.style.setProperty('color', '#435334', 'important');
                    }
                }
            });
        }
        
        applyHarmonizedColors();
        
        // AUTO-COMPLÉTION - TON CODE COMPLET
        function createAutocomplete(inputElement) {
            let autocompleteContainer = null;
            let selectedIndex = -1;
            let currentRequest = null;
            let isMouseDownOnSuggestion = false;
            const cache = new Map();

            async function searchCities(query) {
                if (cache.has(query)) {
                    return cache.get(query);
                }

                try {
                    if (currentRequest) {
                        currentRequest.abort();
                    }

                    const controller = new AbortController();
                    currentRequest = controller;

                    const response = await fetch(
                        `https://geo.api.gouv.fr/communes?nom=${encodeURIComponent(query)}&fields=nom,code,population&limit=8`,
                        { signal: controller.signal }
                    );

                    if (!response.ok) {
                        throw new Error('Erreur API');
                    }

                    const cities = await response.json();
                    
                    const processedCities = cities
                        .map(city => ({
                            name: city.nom,
                            population: city.population || 0
                        }))
                        .sort((a, b) => {
                            const aStarts = a.name.toLowerCase().startsWith(query.toLowerCase());
                            const bStarts = b.name.toLowerCase().startsWith(query.toLowerCase());
                            
                            if (aStarts && !bStarts) return -1;
                            if (!aStarts && bStarts) return 1;
                            
                            return b.population - a.population;
                        });

                    cache.set(query, processedCities);
                    return processedCities;

                } catch (error) {
                    if (error.name === 'AbortError') {
                        return [];
                    }
                    console.warn('Erreur lors de la recherche de villes:', error);
                    return [];
                }
            }

            function showSuggestions(suggestions) {
                hideSuggestions();
                
                if (suggestions.length === 0) return;

                autocompleteContainer = document.createElement('div');
                autocompleteContainer.className = 'ecoride-autocomplete';
                
                const inputRect = inputElement.getBoundingClientRect();
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
                
                const topPosition = inputRect.bottom + scrollTop + 2;
                const leftPosition = inputRect.left + scrollLeft;
                
                Object.assign(autocompleteContainer.style, {
                    position: 'absolute',
                    top: topPosition + 'px',
                    left: leftPosition + 'px',
                    width: inputRect.width + 'px',
                    backgroundColor: 'white',
                    border: '2px solid #435334',
                    borderRadius: '4px',
                    boxShadow: '0 8px 24px rgba(67, 83, 52, 0.25)',
                    zIndex: '999999',
                    maxHeight: '180px',
                    overflowY: 'auto',
                    fontFamily: 'Roboto, sans-serif'
                });

                suggestions.forEach((city, index) => {
                    const item = document.createElement('div');
                    item.textContent = city.name;
                    item.className = 'ecoride-autocomplete-item';
                    
                    Object.assign(item.style, {
                        padding: '12px 15px',
                        cursor: 'pointer',
                        borderBottom: index === suggestions.length - 1 ? 'none' : '1px solid #eee',
                        color: '#333',
                        fontSize: '16px',
                        transition: 'all 0.2s ease',
                        backgroundColor: 'transparent'
                    });

                    item.addEventListener('mouseenter', () => {
                        item.style.backgroundColor = '#435334';
                        item.style.color = 'white';
                        selectedIndex = index;
                    });

                    item.addEventListener('mouseleave', () => {
                        item.style.backgroundColor = 'transparent';
                        item.style.color = '#333';
                    });

                    item.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        isMouseDownOnSuggestion = true;
                        selectCity(city.name);
                    });

                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        selectCity(city.name);
                    });

                    autocompleteContainer.appendChild(item);
                });

                document.body.appendChild(autocompleteContainer);
                selectedIndex = -1;
            }

            function hideSuggestions() {
                if (autocompleteContainer) {
                    autocompleteContainer.remove();
                    autocompleteContainer = null;
                    selectedIndex = -1;
                }
                isMouseDownOnSuggestion = false;
            }

            function selectCity(cityName) {
                // Flag pour empêcher l'auto-complétion
                inputElement.setAttribute('data-selecting', 'true');
                
                hideSuggestions();
                inputElement.value = cityName;
                inputElement.style.setProperty('color', 'var(--text-color)', 'important');
                
                setTimeout(() => {
                    const changeEvent = new Event('change', { bubbles: true });
                    inputElement.dispatchEvent(changeEvent);
                    
                    setTimeout(() => {
                        inputElement.removeAttribute('data-selecting');
                    }, 100);
                }, 50);
                
                console.log('Ville sélectionnée:', cityName);
            }

            function updateSelection() {
                if (!autocompleteContainer) return;
                
                const items = autocompleteContainer.children;
                for (let i = 0; i < items.length; i++) {
                    if (i === selectedIndex) {
                        items[i].style.backgroundColor = '#435334';
                        items[i].style.color = 'white';
                    } else {
                        items[i].style.backgroundColor = 'transparent';
                        items[i].style.color = '#333';
                    }
                }
            }

            let timeoutId = null;

            inputElement.addEventListener('input', function() {
                if (this.getAttribute('data-selecting') === 'true') {
                    return;
                }
                
                const value = this.value.trim();
                
                if (value.length < 3) {
                    hideSuggestions();
                    return;
                }

                if (timeoutId) {
                    clearTimeout(timeoutId);
                }

                timeoutId = setTimeout(async () => {
                    if (inputElement.getAttribute('data-selecting') === 'true') {
                        return;
                    }
                    
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
                }, 500);
            });

            inputElement.addEventListener('keydown', function(e) {
                if (!autocompleteContainer) return;

                const items = autocompleteContainer.children;
                
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
                        e.preventDefault();
                        hideSuggestions();
                        break;
                }
            });

            inputElement.addEventListener('blur', function() {
                setTimeout(() => {
                    if (!isMouseDownOnSuggestion) {
                        hideSuggestions();
                    }
                }, 200);
            });

            window.addEventListener('scroll', hideSuggestions);
            window.addEventListener('resize', hideSuggestions);

            document.addEventListener('click', function(e) {
                if (!inputElement.contains(e.target) && !autocompleteContainer?.contains(e.target)) {
                    hideSuggestions();
                }
            });
        }
        
        // Appliquer l'auto-complétion
        if (departureInput) createAutocomplete(departureInput);
        if (arrivalInput) createAutocomplete(arrivalInput);
        
        // DONNÉES DE SECOURS
        function getFallbackRides() {
            return [
                {
                    id: 1,
                    departure: 'Paris',
                    arrival: 'Lyon',
                    date: '2025-07-25',
                    time: '14:30',
                    price: 25,
                    seats: 2,
                    driver: { name: 'Marie D.', rating: 4.8, reviews: 24 },
                    vehicle: { brand: 'Tesla', model: 'Model 3', energy: 'electrique', eco: true },
                    duration: '4h15'
                },
                {
                    id: 2,
                    departure: 'Marseille',
                    arrival: 'Nice',
                    date: '2025-07-26',
                    time: '09:00',
                    price: 18,
                    seats: 3,
                    driver: { name: 'Thomas L.', rating: 4.5, reviews: 18 },
                    vehicle: { brand: 'Toyota', model: 'Prius', energy: 'hybride', eco: false },
                    duration: '2h30'
                },
                {
                    id: 3,
                    departure: 'Toulouse',
                    arrival: 'Bordeaux',
                    date: '2025-07-27',
                    time: '16:45',
                    price: 35,
                    seats: 1,
                    driver: { name: 'Sophie M.', rating: 4.9, reviews: 31 },
                    vehicle: { brand: 'Renault', model: 'Clio', energy: 'essence', eco: false },
                    duration: '2h45'
                }
            ];
        }

        // GÉNÉRATION DES CARTES
        function hashCode(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                const char = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash;
            }
            return Math.abs(hash);
        }

        function generateRideCard(ride) {
            const driverName = ride.driver?.name || 'Conducteur';
            const hash = hashCode(driverName);
            const isWoman = hash % 2 === 0;
            const photoId = hash % 99;
            const avatarUrl = isWoman 
                ? `https://randomuser.me/api/portraits/women/${photoId}.jpg`
                : `https://randomuser.me/api/portraits/men/${photoId}.jpg`;

            const rating = ride.driver?.rating || 0;
            const starsDisplay = '★'.repeat(Math.floor(rating)) + 
                                (rating % 1 >= 0.5 ? '☆' : '') + 
                                '☆'.repeat(5 - Math.ceil(rating));
            
            const energy = ride.vehicle?.energy || 'essence';
            const rideDate = new Date(ride.date);
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            let dateDisplay;
            if (rideDate.toDateString() === today.toDateString()) {
                dateDisplay = `Aujourd'hui • ${ride.time}`;
            } else if (rideDate.toDateString() === tomorrow.toDateString()) {
                dateDisplay = `Demain • ${ride.time}`;
            } else {
                const options = { weekday: 'long', day: 'numeric', month: 'short' };
                dateDisplay = `${rideDate.toLocaleDateString('fr-FR', options)} • ${ride.time}`;
            }
            
            const isEco = ride.vehicle?.eco || energy === 'electrique';
            
            return `
                <div class="ride-card-modern" data-price="${ride.price}" data-rating="${rating}" data-vehicle="${energy}" data-duration="${parseDuration(ride.duration)}" data-id="${ride.id}">
                    <div class="ride-card-header">
                        <div class="route-info">
                            <h3 class="route">${ride.departure} → ${ride.arrival}</h3>
                            <p class="date-time">${dateDisplay}</p>
                        </div>
                        <div class="price-badge">${Math.round(ride.price)} crédits</div>
                    </div>
                    
                    <div class="ride-card-body">
                        <div class="driver-info">
                            <div class="driver-avatar">
                                <img src="${avatarUrl}" 
                                     alt="Photo de ${driverName}" 
                                     class="avatar-img"
                                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(driverName)}&background=435334&color=fff&size=64&bold=true&format=svg'">
                            </div>
                            <div class="driver-details">
                                <h4>${driverName}</h4>
                                <div class="driver-rating">
                                    <span class="stars">${starsDisplay}</span>
                                    <span class="rating-value">${rating}</span>
                                    <span class="reviews-count">(${ride.driver?.reviews || 0} avis)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="ride-details">
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <span>${ride.seats} place${ride.seats > 1 ? 's' : ''} libre${ride.seats > 1 ? 's' : ''}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-car"></i>
                                <span>${ride.vehicle?.brand || ''} ${ride.vehicle?.model || ''} - ${energy.charAt(0).toUpperCase() + energy.slice(1)}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>${ride.duration || 'Durée non précisée'}</span>
                            </div>
                        </div>
                        
                        ${isEco ? `
                            <div class="ride-badges">
                                <span class="eco-badge">
                                    <i class="fas fa-leaf"></i>
                                    Trajet Écologique
                                </span>
                            </div>
                        ` : ''}
                    </div>
                    
                    <div class="ride-card-footer">
                        <button class="btn-details" data-ride-id="${ride.id}">
                            <i class="fas fa-info-circle"></i>
                            Détails
                        </button>
                        <button class="btn-book" data-ride-id="${ride.id}">
                            <i class="fas fa-check"></i>
                            Réserver
                        </button>
                    </div>
                </div>
            `;
        }

        function parseDuration(duration) {
            if (!duration || duration === 'Non précisée') return 12;
            const match = duration.match(/(\d+)h?(\d+)?/);
            if (match) {
                const hours = parseInt(match[1]) || 0;
                const minutes = parseInt(match[2]) || 0;
                return hours + (minutes / 60);
            }
            return 12;
        }

        // AFFICHAGE DES RÉSULTATS
        async function displaySearchResults() {
            const ridesGrid = document.getElementById('ridesGrid');
            const resultsCount = document.getElementById('resultsCount');
            const noResults = document.getElementById('noResults');
            
            if (!ridesGrid) return;
            
            const rides = getFallbackRides();
            
            if (rides.length > 0) {
                const ridesHTML = rides.map(ride => generateRideCard(ride)).join('');
                ridesGrid.innerHTML = ridesHTML;
                ridesGrid.style.display = 'grid';
                noResults.style.display = 'none';
                resultsCount.textContent = `${rides.length} trajet${rides.length > 1 ? 's' : ''} disponible${rides.length > 1 ? 's' : ''}`;
                
                attachBookingEvents();
            } else {
                ridesGrid.style.display = 'none';
                noResults.style.display = 'block';
                resultsCount.textContent = 'Aucun trajet trouvé';
            }
        }

        function attachBookingEvents() {
            const detailButtons = document.querySelectorAll('.btn-details');
            const bookButtons = document.querySelectorAll('.btn-book');
            
            detailButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    alert('Redirection vers la page de détails (à implémenter dans US5)');
                });
            });
            
            bookButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    alert('Système de réservation à implémenter dans US6 !');
                });
            });
        }

        // FILTRES
        function attachFilterEvents() {
            const priceSlider = document.getElementById('priceMax');
            const priceDisplay = document.querySelector('.price-display');
            const vehicleFilters = document.querySelectorAll('input[type="checkbox"]');
            const ratingFilter = document.getElementById('minRating');
            const durationFilter = document.getElementById('durationFilter');
            const resetBtn = document.getElementById('resetFilters');
            
            if (priceSlider && priceDisplay) {
                priceSlider.addEventListener('input', function() {
                    priceDisplay.textContent = this.value + ' crédits';
                    filterRides();
                });
            }

            if (durationFilter) {
                durationFilter.addEventListener('change', filterRides);
            }
            
            vehicleFilters.forEach(filter => {
                filter.addEventListener('change', filterRides);
            });
            
            if (ratingFilter) {
                ratingFilter.addEventListener('change', filterRides);
            }
            
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    priceSlider.value = 50;
                    priceDisplay.textContent = '50 crédits';
                    ratingFilter.value = 0;
                    durationFilter.value = '';
                    
                    vehicleFilters.forEach(filter => {
                        filter.checked = false;
                    });
                    
                    filterRides();
                });
            }
        }

        function filterRides() {
            const priceSlider = document.getElementById('priceMax');
            const ratingFilter = document.getElementById('minRating');
            const vehicleFilters = document.querySelectorAll('input[type="checkbox"]');
            const durationFilter = document.getElementById('durationFilter');
            const ridesGrid = document.getElementById('ridesGrid');
            const resultsCount = document.getElementById('resultsCount');
            const noResults = document.getElementById('noResults');
            
            const maxPrice = parseFloat(priceSlider?.value || 50);
            const minRating = parseFloat(ratingFilter?.value || 0);
            const selectedDuration = durationFilter?.value || '';
            const selectedVehicles = Array.from(vehicleFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            
            const rideCards = document.querySelectorAll('.ride-card-modern');
            let visibleCount = 0;
            
            rideCards.forEach(card => {
                const price = parseFloat(card.dataset.price);
                const rating = parseFloat(card.dataset.rating);
                const vehicle = card.dataset.vehicle;
                const duration = parseFloat(card.dataset.duration || 12);
                
                let matchesDuration = true;
                if (selectedDuration) {
                    if (selectedDuration === "1") {
                        matchesDuration = duration < 1;
                    } else if (selectedDuration === "2") {
                        matchesDuration = duration >= 1 && duration <= 2;
                    } else if (selectedDuration === "4") {
                        matchesDuration = duration > 2 && duration <= 4;
                    } else if (selectedDuration === "6") {
                        matchesDuration = duration > 4 && duration <= 6;
                    } else if (selectedDuration === "12") {
                        matchesDuration = duration > 6;
                    }
                }

                const matchesPrice = price <= maxPrice;
                const matchesRating = rating >= minRating;
                const matchesVehicle = selectedVehicles.length === 0 || selectedVehicles.includes(vehicle);
                
                if (matchesPrice && matchesRating && matchesVehicle && matchesDuration) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            if (resultsCount) {
                resultsCount.textContent = `${visibleCount} trajet${visibleCount > 1 ? 's' : ''} trouvé${visibleCount > 1 ? 's' : ''}`;
            }
            
            if (ridesGrid && noResults) {
                if (visibleCount === 0) {
                    ridesGrid.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    ridesGrid.style.display = 'grid';
                    noResults.style.display = 'none';
                }
            }
        }

        // FONCTION RESET
        window.resetSearch = function() {
            document.getElementById('ridesSearchForm').reset();
            document.getElementById('noResults').style.display = 'none';
            document.getElementById('ridesGrid').style.display = 'grid';
            
            displaySearchResults();
            
            const inputs = document.querySelectorAll('.search-input');
            inputs.forEach(input => {
                if (input.type === 'text' && !input.value.trim()) {
                    input.style.setProperty('color', '#435334', 'important');
                }
            });
            
            const resultsTitle = document.querySelector('.results-title');
            if (resultsTitle) {
                resultsTitle.textContent = 'Trajets disponibles';
            }
        }
        
        // INITIALISATION
        attachFilterEvents();
        
        // Charger les trajets par défaut après un délai
        setTimeout(async () => {
            await displaySearchResults();
        }, 500);
    });
    </script>

<?php endif; ?>
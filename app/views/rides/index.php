<?php
// app/views/rides/index.php - Version finale US3 propre

// Récupérer les paramètres de recherche
$departure = $_GET['departure'] ?? '';
$arrival = $_GET['arrival'] ?? '';
$date = $_GET['date'] ?? '';
$passengers = $_GET['passengers'] ?? '1';

// Déterminer si c'est une recherche ou un affichage général
$isSearch = !empty($departure) || !empty($arrival) || !empty($date);
?>

<!-- CSS des pages rides -->
<link rel="stylesheet" href="/css/pages/rides.css">

<?php if ($isSearch): ?>
    <!-- ================================================ -->
    <!-- MODE RECHERCHE AVEC ALTERNATIVES AUTOMATIQUES -->
    <!-- ================================================ -->
    <main class="search-main">
        <div class="container">
            <div style="margin-bottom: 2rem;">
                <h1 style="color: var(--text-color); margin-bottom: 1rem;">Résultats de recherche</h1>
                
                <!-- Afficher les critères de recherche -->
                <div class="search-criteria">
                    <h3>Critères de recherche :</h3>
                    <div class="search-criteria-grid">
                        <?php if ($departure): ?>
                            <div>
                                <strong>Départ :</strong>
                                <span><?= htmlspecialchars($departure) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($arrival): ?>
                            <div>
                                <strong>Arrivée :</strong>
                                <span><?= htmlspecialchars($arrival) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($date): ?>
                            <div>
                                <strong>Date :</strong>
                                <span><?= htmlspecialchars(date('d/m/Y', strtotime($date))) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <strong>Passagers :</strong>
                            <span><?= htmlspecialchars($passengers) ?></span>
                        </div>
                    </div>
                    
                    <!-- Boutons d'action -->
                    <div class="search-actions">
                        <a href="/ecoride/public/" class="search-action-link">
                            Modifier la recherche
                        </a>
                        <a href="/ecoride/public/rides" class="search-action-link">
                            Voir tous les trajets
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- VRAIS RÉSULTATS DE LA BDD -->
            <div class="search-results">
                <h2>Trajets trouvés</h2>
                
                <?php if (isset($rides) && !empty($rides)): ?>
                    <div class="search-count">
                        <?= count($rides) ?> trajet<?= count($rides) > 1 ? 's' : '' ?> correspondant<?= count($rides) > 1 ? 's' : '' ?> à votre recherche
                    </div>
                    
                    <?php foreach ($rides as $ride): ?>
                        <div class="ride-result-card">
                            
                            <!-- Header avec trajet et prix -->
                            <div class="ride-header">
                                <div>
                                    <h3 class="ride-route">
                                        <?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?>
                                    </h3>
                                    <p class="ride-datetime">
                                        <?= $ride['formatted_date'] ?> à <?= $ride['formatted_time'] ?>
                                    </p>
                                </div>
                                <div class="ride-price">
                                    <?= number_format($ride['prix_par_personne'], 0) ?> crédits
                                </div>
                            </div>
                            
                            <!-- Infos conducteur -->
                            <div class="ride-info">
                                <div class="ride-info-left">
                                    <span>
                                        <strong>Conducteur :</strong> <?= htmlspecialchars($ride['conducteur_prenom']) ?>
                                    </span>
                                    <span>
                                        <strong>Note :</strong> 
                                        <?php 
                                        $note = $ride['conducteur_note'] ?: 0;
                                        if ($note > 0) {
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= floor($note)) {
                                                    echo '<span style="color: #ffc107;">★</span>';
                                                } elseif ($i <= $note) {
                                                    echo '<span style="color: #ffc107;">☆</span>';
                                                } else {
                                                    echo '<span style="color: #ddd;">☆</span>';
                                                }
                                            }
                                        }
                                        ?>
                                    </span>
                                    <span style="color: var(--eco-green);">
                                        <strong>Places :</strong> <?= $ride['nb_places_disponibles'] ?> libre<?= $ride['nb_places_disponibles'] > 1 ? 's' : '' ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Véhicule et durée -->
                            <div class="ride-vehicle">
                                <div class="ride-vehicle-info">
                                    <strong>Véhicule :</strong> <?= htmlspecialchars($ride['marque']) ?> <?= htmlspecialchars($ride['modele']) ?>
                                    <span style="color: var(--eco-green); font-weight: 500; margin-left: 1rem;">
                                        <?= $ride['eco_badge']['text'] ?>
                                    </span>
                                    <?php if ($ride['type_vehicule'] === 'electrique'): ?>
                                        <span class="eco-trip-badge">
                                            Trajet Écologique
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="ride-duration-distance">
                                    <strong>Durée :</strong> <?= $this->estimerDuree($ride['ville_depart'], $ride['ville_arrivee']) ?> 
                                    <span style="margin-left: 1rem;"><strong>Distance :</strong> <?= $this->estimerDistance($ride['ville_depart'], $ride['ville_arrivee']) ?> km</span>
                                </div>
                            </div>
                            
                            <!-- Commentaire -->
                            <?php if (!empty($ride['commentaire'])): ?>
                            <div class="ride-comment">
                                <strong>Commentaire :</strong> <?= htmlspecialchars($ride['commentaire']) ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Actions -->
                            <div class="ride-actions">
                                <button class="ride-action-btn btn-contact" onclick="alert('Messagerie à implémenter dans une prochaine US')">
                                    <i class="fas fa-envelope"></i>
                                    Contacter
                                </button>
                                <a href="/ecoride/public/rides?id=<?= $ride['id_covoiturage'] ?>" class="ride-action-btn btn-details">
                                    <i class="fas fa-info-circle"></i>
                                    Voir détails
                                </a>
                                <button class="ride-action-btn btn-reserve" onclick="alert('Système de réservation à implémenter dans US6 !')">
                                    Réserver
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Pagination pour les recherches -->
                    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="search-pagination">
                        <div class="search-pagination-nav">
                            
                            <!-- Bouton Précédent -->
                            <?php if ($pagination['has_previous']): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])) ?>" 
                               class="pagination-btn">
                                ← Précédent
                            </a>
                            <?php else: ?>
                            <span class="pagination-btn" style="background: #ddd; color: #999;">
                                ← Précédent
                            </span>
                            <?php endif; ?>
                            
                            <!-- Numéros de pages -->
                            <?php 
                            $start_page = max(1, $pagination['current_page'] - 2);
                            $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++): 
                            ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                <span class="pagination-btn active">
                                    <?= $i ?>
                                </span>
                                <?php else: ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                                   class="pagination-btn">
                                    <?= $i ?>
                                </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <!-- Bouton Suivant -->
                            <?php if ($pagination['has_next']): ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" 
                               class="pagination-btn">
                                Suivant →
                            </a>
                            <?php else: ?>
                            <span class="pagination-btn" style="background: #ddd; color: #999;">
                                Suivant →
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        
                    </div>
                    <?php endif; ?>
                    
                <?php else: ?>
                    <div class="no-results">
                        <h3>Aucun trajet trouvé</h3>
                        <p>Essayez de modifier vos critères de recherche :</p>
                        <ul>
                            <li>Vérifiez l'orthographe des villes</li>
                            <li>Élargissez les dates de recherche</li>
                            <li>Réduisez le nombre de places demandées</li>
                        </ul>
                        
                        <!-- ALTERNATIVES AUTOMATIQUES -->
                        <?php if (isset($alternatives) && !empty($alternatives)): ?>
                        
                            <!-- Alternatives de dates -->
                            <?php if (!empty($alternatives['dates'])): ?>
                            <div class="alternatives-section">
                                <h4>Trajets disponibles à d'autres dates :</h4>
                                <div class="alternatives-grid">
                                    <?php foreach ($alternatives['dates'] as $alt): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['date' => $alt['date']])) ?>" 
                                       class="alternative-btn">
                                        <?= $alt['formatted'] ?> (<?= $alt['label'] ?>)
                                        <br><span class="alternative-count"><?= $alt['count'] ?> trajet<?= $alt['count'] > 1 ? 's' : '' ?></span>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Alternatives de villes -->
                            <?php if (!empty($alternatives['villes'])): ?>
                            <div class="alternatives-section">
                                <h4>Trajets similaires vers d'autres destinations :</h4>
                                <div class="alternatives-grid">
                                    <?php foreach (array_slice($alternatives['villes'], 0, 4) as $alt): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['departure' => $alt['departure'], 'arrival' => $alt['arrival']])) ?>" 
                                       class="alternative-btn secondary">
                                        <?= htmlspecialchars($alt['label']) ?>
                                        <br><span class="alternative-count"><?= $alt['count'] ?> trajet<?= $alt['count'] > 1 ? 's' : '' ?></span>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        <?php endif; ?>
                        
                        <!-- Suggestion d'élargir la recherche -->
                        <?php if (!empty($departure) || !empty($arrival)): ?>
                        <div style="margin-top: 1rem;">
                            <a href="?<?= http_build_query(array_merge($_GET, ['date' => ''])) ?>" 
                               style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 6px; display: inline-block;">
                                Voir tous les trajets <?= $departure ? htmlspecialchars($departure) : '' ?><?= $departure && $arrival ? ' → ' : '' ?><?= $arrival ? htmlspecialchars($arrival) : '' ?>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php else: ?>
    <!-- ================================================ -->
    <!-- MODE NAVIGATION NORMALE AVEC VRAIES DONNÉES BDD -->
    <!-- ================================================ -->

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
                                       aria-label="Ville de départ">
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
                                       aria-label="Ville d'arrivée">
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
                                       aria-label="Date de départ">
                            </div>
                        </div>
                        
                        <div class="search-group">
                            <label for="searchPassengers">Passagers</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-user input-icon" aria-hidden="true"></i>
                                <select class="search-input" 
                                        id="searchPassengers"
                                        name="passengers"
                                        aria-label="Nombre de passagers">
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

    <!-- Résultats des covoiturages avec VRAIES DONNÉES -->
    <section class="rides-results-section">
        <div class="container">
            <div class="results-header">
                <h2 class="results-title">Trajets disponibles</h2>
                <div class="results-count">
                    <span id="resultsCount">
                        <?php if (isset($pagination)): ?>
                            <?= $pagination['total_rides'] ?> trajet<?= $pagination['total_rides'] > 1 ? 's' : '' ?> disponible<?= $pagination['total_rides'] > 1 ? 's' : '' ?>
                        <?php else: ?>
                            Chargement...
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            
            <!-- Liste des trajets DEPUIS LA BDD -->
            <div class="rides-grid" id="ridesGrid">
                <?php if (isset($rides) && !empty($rides)): ?>
                    <?php foreach ($rides as $ride): ?>
                    <div class="ride-card-modern" data-id="<?= $ride['id_covoiturage'] ?>">
                        <div class="ride-card-header">
                            <div class="route-info">
                                <h3 class="route"><?= htmlspecialchars($ride['ville_depart']) ?> → <?= htmlspecialchars($ride['ville_arrivee']) ?></h3>
                                <p class="date-time"><?= $ride['formatted_date'] ?> • <?= $ride['formatted_time'] ?></p>
                            </div>
                            <div class="price-badge"><?= number_format($ride['prix_par_personne'], 0) ?> crédits</div>
                        </div>
                        
                        <div class="ride-card-body">
                            <div class="driver-info">
                                <div class="driver-avatar">
                                    <i class="fas fa-user-circle"></i>
                                </div>
                                <div class="driver-details">
                                    <h4><?= htmlspecialchars($ride['conducteur_prenom']) ?></h4>
                                    <div class="driver-rating">
                                        <?php 
                                        $note = $ride['conducteur_note'] ?: 0;
                                        if ($note > 0) {
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= floor($note)) {
                                                    echo '<span style="color: #ffc107;">★</span>';
                                                } elseif ($i <= $note) {
                                                    echo '<span style="color: #ffc107;">☆</span>';
                                                } else {
                                                    echo '<span style="color: #ddd;">☆</span>';
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="ride-details">
                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span><?= $ride['nb_places_disponibles'] ?> place<?= $ride['nb_places_disponibles'] > 1 ? 's' : '' ?> libre<?= $ride['nb_places_disponibles'] > 1 ? 's' : '' ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-car"></i>
                                    <span><?= htmlspecialchars($ride['marque']) ?> <?= htmlspecialchars($ride['modele']) ?> - <span style="color: var(--eco-green); font-weight: 500;"><?= $ride['eco_badge']['text'] ?></span></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?= $this->estimerDuree($ride['ville_depart'], $ride['ville_arrivee']) ?> • <?= $this->estimerDistance($ride['ville_depart'], $ride['ville_arrivee']) ?> km</span>
                                </div>
                                
                                <?php if ($ride['type_vehicule'] === 'electrique'): ?>
                                <div class="ride-badges">
                                    <span class="eco-badge">
                                        Trajet Écologique
                                    </span>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($ride['commentaire'])): ?>
                            <div style="background: #f8f9fa; padding: 1rem; border-radius: 6px; margin: 1rem 0; font-size: 0.9rem; color: var(--text-color);">
                                <?= htmlspecialchars($ride['commentaire']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ride-card-footer">
                            <button class="btn-contact" onclick="alert('Messagerie à implémenter dans une prochaine US')">
                                <i class="fas fa-envelope"></i>
                                Contacter
                            </button>
                            <a href="/ecoride/public/rides?id=<?= $ride['id_covoiturage'] ?>" class="btn-details">
                                <i class="fas fa-info-circle"></i>
                                Détails
                            </a>
                            <button class="btn-book" onclick="alert('Système de réservation US6 à implémenter')">
                                <i class="fas fa-check"></i>
                                Réserver
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Message si aucun résultat -->
            <?php if (isset($rides) && empty($rides)): ?>
            <div class="no-results" id="noResults">
                <div class="no-results-content">
                    <i class="fas fa-search"></i>
                    <h3>Aucun trajet disponible actuellement</h3>
                    <p>Tous les covoiturages sont soit complets, soit déjà partis.</p>
                    
                    <!-- Suggestion pour rechercher à des dates spécifiques -->
                    <div class="alternatives-section">
                        <h4>Recherchez à des dates précises :</h4>
                        <div class="alternatives-grid">
                            <?php
                            $today = date('Y-m-d');
                            $tomorrow = date('Y-m-d', strtotime('+1 day'));
                            $weekend = date('Y-m-d', strtotime('next saturday'));
                            $nextWeek = date('Y-m-d', strtotime('+7 days'));
                            ?>
                            
                            <a href="?date=<?= $today ?>" class="alternative-btn">
                                Aujourd'hui (<?= date('d/m', strtotime($today)) ?>)
                            </a>
                            
                            <a href="?date=<?= $tomorrow ?>" class="alternative-btn">
                                Demain (<?= date('d/m', strtotime($tomorrow)) ?>)
                            </a>
                            
                            <a href="?date=<?= $weekend ?>" class="alternative-btn secondary">
                                Ce week-end (<?= date('d/m', strtotime($weekend)) ?>)
                            </a>
                            
                            <a href="?date=<?= $nextWeek ?>" class="alternative-btn secondary">
                                Semaine prochaine (<?= date('d/m', strtotime($nextWeek)) ?>)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Pagination avec vraies données PHP -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="pagination-container">
                <div class="pagination">
                    
                    <!-- Bouton Précédent -->
                    <?php if ($pagination['has_previous']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['previous_page']])) ?>" 
                       class="pagination-btn prev">
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </a>
                    <?php else: ?>
                    <button class="pagination-btn prev" disabled>
                        <i class="fas fa-chevron-left"></i>
                        Précédent
                    </button>
                    <?php endif; ?>
                    
                    <!-- Numéros de pages -->
                    <div class="pagination-numbers">
                        <?php 
                        $start_page = max(1, $pagination['current_page'] - 2);
                        $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++): 
                        ?>
                            <?php if ($i == $pagination['current_page']): ?>
                            <button class="pagination-number active"><?= $i ?></button>
                            <?php else: ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                               class="pagination-number"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($end_page < $pagination['total_pages']): ?>
                            <span class="pagination-dots">...</span>
                            <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['total_pages']])) ?>" 
                               class="pagination-number"><?= $pagination['total_pages'] ?></a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Bouton Suivant -->
                    <?php if ($pagination['has_next']): ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['next_page']])) ?>" 
                       class="pagination-btn next">
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php else: ?>
                    <button class="pagination-btn next" disabled>
                        Suivant
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <?php endif; ?>
                </div>
                
                
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="rides-cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Vous ne trouvez pas votre trajet ?</h2>
                <p>Créez une alerte pour être notifié dès qu'un nouveau trajet correspond à vos critères.</p>
                <div class="cta-buttons">
                    <button class="btn-alert" onclick="alert('Fonctionnalité d\'alerte à implémenter')">
                        <i class="fas fa-bell"></i>
                        Créer une alerte
                    </button>
                    <button class="btn-create" onclick="alert('Proposition de trajet à implémenter dans une prochaine US')">
                        <i class="fas fa-plus"></i>
                        Proposer un trajet
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript avec autocomplétion fonctionnelle -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const departureInput = document.getElementById('searchDeparture');
        const arrivalInput = document.getElementById('searchArrival');
        
        // AUTO-COMPLÉTION COMPLÈTE 
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
                
                // Positionnement relatif au conteneur parent
                Object.assign(autocompleteContainer.style, {
                    position: 'absolute',
                    top: '100%',
                    left: '0',
                    right: '0',
                    backgroundColor: 'white',
                    border: '2px solid #435334',
                    borderRadius: '4px',
                    boxShadow: '0 8px 24px rgba(67, 83, 52, 0.25)',
                    zIndex: '999999',
                    maxHeight: '180px',
                    overflowY: 'auto',
                    fontFamily: 'inherit',
                    marginTop: '2px'
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

                // Ajouter le conteneur d'autocomplétion au parent de l'input plutôt qu'au body
                const inputParent = inputElement.closest('.input-with-icon') || inputElement.parentElement;
                inputParent.style.position = 'relative';
                inputParent.appendChild(autocompleteContainer);
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
        
        // Appliquer l'autocomplétion aux deux champs
        if (departureInput) createAutocomplete(departureInput);
        if (arrivalInput) createAutocomplete(arrivalInput);
    });
    </script>

<?php endif; ?>
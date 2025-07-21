// ================================================
// STRUCTURE MONGODB ECORIDE - PHASE FUTURE
// Collections pour logs, analytics et cache
// ================================================

// ================================================
// COLLECTION: activity_logs
// Purpose: Tracer toutes les actions utilisateur
// ================================================
db.activity_logs.insertOne({
  user_id: 3,
  action: "search_rides",
  details: {
    search_criteria: {
      ville_depart: "Paris",
      ville_arrivee: "Marseille", 
      date_depart: "2025-07-25",
      nb_places_min: 1,
      prix_max: 80,
      type_carburant_prefere: "electrique"
    },
    results_count: 3,
    filters_applied: ["eco_vehicles", "price_range"]
  },
  ip_address: "192.168.1.100",
  user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
  session_id: "sess_abc123def456",
  created_at: new Date()
});

db.activity_logs.insertOne({
  user_id: 7,
  action: "book_ride",
  details: {
    covoiturage_id: 1,
    nb_places_reserved: 1,
    credit_amount: 73,
    payment_method: "credits",
    booking_source: "web_interface"
  },
  ip_address: "192.168.1.101", 
  user_agent: "Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X)",
  session_id: "sess_mobile789",
  created_at: new Date()
});

// ================================================
// COLLECTION: analytics
// Purpose: Statistiques et rapports de performance
// ================================================
db.analytics.insertOne({
  type: "daily_stats",
  period: {
    start: new Date("2025-07-20T00:00:00Z"),
    end: new Date("2025-07-20T23:59:59Z")
  },
  data: {
    new_users: 5,
    total_rides_created: 12,
    total_bookings: 8,
    eco_rides_percentage: 75.5,
    credits_circulation: 2456,
    platform_commission: 16,
    popular_routes: [
      { route: "Paris-Marseille", bookings: 3 },
      { route: "Lyon-Toulouse", bookings: 2 },
      { route: "Nice-Cannes", bookings: 1 }
    ],
    top_eco_vehicles: [
      { brand: "Tesla", model: "Model 3", usage_count: 4 },
      { brand: "Nissan", model: "Leaf", usage_count: 3 }
    ],
    user_activity: {
      new_registrations: 5,
      active_drivers: 12,
      active_passengers: 18,
      average_session_duration: "15.3 minutes"
    }
  },
  generated_at: new Date(),
  generated_by: "analytics_cron_job"
});

db.analytics.insertOne({
  type: "monthly_report", 
  period: {
    start: new Date("2025-07-01T00:00:00Z"),
    end: new Date("2025-07-31T23:59:59Z")
  },
  data: {
    total_users: 127,
    new_users_this_month: 23,
    total_rides_completed: 156,
    total_credits_exchanged: 12450,
    platform_revenue_credits: 312,
    eco_impact: {
      electric_rides: 89,
      hybrid_rides: 34,
      standard_rides: 33,
      co2_saved_estimate: "1.2 tonnes"
    },
    user_satisfaction: {
      average_rating: 4.7,
      total_reviews: 134,
      complaints: 3
    },
    growth_metrics: {
      user_growth_rate: "22%",
      ride_frequency_increase: "15%",
      eco_vehicle_adoption: "68%"
    }
  },
  generated_at: new Date(),
  generated_by: "monthly_analytics_job"
});

// ================================================
// COLLECTION: search_cache
// Purpose: Cache des recherches fréquentes
// ================================================
db.search_cache.insertOne({
  query_hash: "md5_hash_of_search_params_abc123",
  criteria: {
    ville_depart: "Paris",
    ville_arrivee: "Marseille",
    date_depart: new Date("2025-07-25"),
    nb_places_min: 1,
    prix_max: 100,
    type_carburant: ["electrique", "hybride"]
  },
  results: [
    {
      covoiturage_id: 1,
      chauffeur: "jean_eco",
      vehicule: "Tesla Model 3",
      prix: 73,
      places_disponibles: 3,
      note_chauffeur: 4.67,
      eco_score: 100
    },
    {
      covoiturage_id: 5,
      chauffeur: "marie_green", 
      vehicule: "Nissan Leaf",
      prix: 75,
      places_disponibles: 2,
      note_chauffeur: 5.0,
      eco_score: 95
    }
  ],
  cache_hits: 12,
  expires_at: new Date(Date.now() + 3600000), // 1 heure
  created_at: new Date()
});

// ================================================
// COLLECTION: user_preferences_advanced
// Purpose: Préférences utilisateur avancées et ML
// ================================================
db.user_preferences_advanced.insertOne({
  user_id: 3,
  preferences: {
    favorite_routes: [
      { depart: "Paris", arrivee: "Marseille", frequency: 5 },
      { depart: "Lyon", arrivee: "Nice", frequency: 2 }
    ],
    vehicle_preferences: {
      preferred_brands: ["Tesla", "Nissan"],
      required_features: ["air_conditioning", "wifi"],
      eco_priority: true
    },
    driver_preferences: {
      min_rating: 4.0,
      preferred_age_range: [25, 45],
      smoking_tolerance: false,
      pet_tolerance: true
    },
    notification_settings: {
      price_alerts: true,
      route_suggestions: true,
      eco_tips: true,
      marketing: false
    },
    behavioral_data: {
      avg_booking_advance_days: 3.5,
      preferred_departure_times: ["08:00-10:00", "18:00-20:00"],
      price_sensitivity: "medium",
      eco_consciousness_score: 8.5
    }
  },
  ml_predictions: {
    likely_to_book_eco: 0.92,
    price_tolerance: 85,
    churn_risk: 0.15,
    lifetime_value_estimate: 450
  },
  last_updated: new Date()
});

// ================================================
// COLLECTION: eco_impact_tracking
// Purpose: Suivi de l'impact écologique
// ================================================
db.eco_impact_tracking.insertOne({
  user_id: 3,
  period: "2025-07",
  eco_metrics: {
    rides_offered: 8,
    rides_taken: 2,
    total_km_shared: 1250,
    vehicle_types_used: {
      electric: { rides: 6, km: 950 },
      hybrid: { rides: 2, km: 180 },
      standard: { rides: 2, km: 120 }
    },
    environmental_impact: {
      co2_saved_kg: 95.5,
      fuel_saved_liters: 42.3,
      eco_score: 8.7
    },
    eco_badges_earned: [
      { badge: "Eco Driver", earned_date: new Date("2025-07-15") },
      { badge: "Electric Pioneer", earned_date: new Date("2025-07-20") }
    ]
  },
  calculated_at: new Date()
});

// ================================================
// COLLECTION: platform_notifications
// Purpose: Notifications système et marketing
// ================================================
db.platform_notifications.insertOne({
  notification_id: "notif_eco_tip_001",
  type: "eco_tip",
  target_audience: {
    user_segments: ["eco_conscious", "frequent_drivers"],
    min_eco_score: 7.0,
    location: ["Paris", "Lyon", "Marseille"]
  },
  content: {
    title: "💡 Astuce Écologique du Jour",
    message: "Saviez-vous qu'en partageant votre Tesla Model 3 aujourd'hui, vous pouvez économiser jusqu'à 45kg de CO2 ?",
    cta_button: "Proposer un trajet",
    cta_link: "/rides/create"
  },
  delivery_settings: {
    channels: ["app_push", "email"],
    send_at: new Date("2025-07-25T08:00:00Z"),
    expires_at: new Date("2025-07-25T20:00:00Z")
  },
  metrics: {
    sent_count: 0,
    opened_count: 0,
    clicked_count: 0,
    conversion_count: 0
  },
  created_at: new Date(),
  status: "scheduled"
});

// ================================================
// INDEX RECOMMANDÉS POUR PERFORMANCE
// ================================================

// Index pour activity_logs
db.activity_logs.createIndex({ user_id: 1, created_at: -1 });
db.activity_logs.createIndex({ action: 1, created_at: -1 });
db.activity_logs.createIndex({ created_at: -1 });

// Index pour analytics  
db.analytics.createIndex({ type: 1, "period.start": -1 });
db.analytics.createIndex({ generated_at: -1 });

// Index pour search_cache
db.search_cache.createIndex({ query_hash: 1 });
db.search_cache.createIndex({ expires_at: 1 }, { expireAfterSeconds: 0 });
db.search_cache.createIndex({ 
  "criteria.ville_depart": 1, 
  "criteria.ville_arrivee": 1, 
  "criteria.date_depart": 1 
});

// Index pour user_preferences_advanced
db.user_preferences_advanced.createIndex({ user_id: 1 });
db.user_preferences_advanced.createIndex({ last_updated: -1 });

// Index pour eco_impact_tracking
db.eco_impact_tracking.createIndex({ user_id: 1, period: -1 });
db.eco_impact_tracking.createIndex({ calculated_at: -1 });

// Index pour platform_notifications
db.platform_notifications.createIndex({ status: 1, "delivery_settings.send_at": 1 });
db.platform_notifications.createIndex({ type: 1, created_at: -1 });

// ================================================
// REQUÊTES D'EXEMPLE UTILES
// ================================================

// Statistiques d'utilisation par utilisateur
db.activity_logs.aggregate([
  {
    $match: {
      created_at: { 
        $gte: new Date("2025-07-01"), 
        $lt: new Date("2025-08-01") 
      }
    }
  },
  {
    $group: {
      _id: "$user_id",
      total_actions: { $sum: 1 },
      unique_actions: { $addToSet: "$action" },
      last_activity: { $max: "$created_at" }
    }
  },
  {
    $sort: { total_actions: -1 }
  }
]);

// Recherches populaires
db.search_cache.aggregate([
  {
    $group: {
      _id: {
        depart: "$criteria.ville_depart",
        arrivee: "$criteria.ville_arrivee"
      },
      search_count: { $sum: "$cache_hits" },
      avg_results: { $avg: { $size: "$results" } }
    }
  },
  {
    $sort: { search_count: -1 }
  },
  {
    $limit: 10
  }
]);

// Impact écologique mensuel
db.eco_impact_tracking.aggregate([
  {
    $match: { period: "2025-07" }
  },
  {
    $group: {
      _id: null,
      total_co2_saved: { $sum: "$eco_metrics.environmental_impact.co2_saved_kg" },
      total_fuel_saved: { $sum: "$eco_metrics.environmental_impact.fuel_saved_liters" },
      avg_eco_score: { $avg: "$eco_metrics.environmental_impact.eco_score" },
      total_shared_km: { $sum: "$eco_metrics.total_km_shared" }
    }
  }
]);

// ================================================
// CONFIGURATION RECOMMANDÉE
// ================================================

/*
Configuration MongoDB pour EcoRide:

1. Replica Set recommandé pour la production
2. Sharding si > 1TB de données
3. TTL indexes pour les logs (rétention 1 an)
4. Compression zstd pour économiser l'espace
5. Monitoring avec MongoDB Atlas ou Prometheus

Exemple de TTL pour nettoyage automatique:
db.activity_logs.createIndex(
  { created_at: 1 }, 
  { expireAfterSeconds: 31536000 } // 1 an
);
*/
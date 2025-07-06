<?php
require_once '../app/config/database.php';

echo "<h2>🧪 Test de connexion EcoRide</h2>";

$db = new Database();
$result = $db->testConnection();

if($result) {
    echo "<p style='color: green; font-weight: bold;'>🎉 Tout fonctionne parfaitement !</p>";
    echo "<hr>";
    echo "<h3>📋 Prochaines étapes :</h3>";
    echo "<ul>";
    echo "<li>✅ Configuration MySQL OK</li>";
    echo "<li>⏳ Configurer Virtual Host</li>";
    echo "<li>⏳ Créer les tables</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Problème de connexion</p>";
}
?>
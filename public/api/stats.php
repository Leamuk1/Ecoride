<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$stats = [
    'total_rides' => rand(1200, 1300),
    'active_drivers' => rand(300, 400),
    'avg_rating' => round(rand(420, 480) / 100, 1),
    'co2_saved' => rand(15000, 16000),
    'rides_today' => rand(45, 75)
];

echo json_encode(['success' => true, 'data' => $stats]);
?>
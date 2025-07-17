<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$vehicles = [
    ['id' => 'electrique', 'name' => 'Électrique', 'icon' => ' ', 'eco' => true],
    ['id' => 'hybride', 'name' => 'Hybride', 'icon' => ' ', 'eco' => false],
    ['id' => 'essence', 'name' => 'Essence', 'icon' => ' ', 'eco' => false]
];

echo json_encode(['success' => true, 'data' => $vehicles]);
?>
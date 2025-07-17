<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$regions = [
    ['id' => '11', 'name' => 'Île-de-France', 'active_rides' => 234],
    ['id' => '84', 'name' => 'Auvergne-Rhône-Alpes', 'active_rides' => 156],
    ['id' => '76', 'name' => 'Occitanie', 'active_rides' => 98],
    ['id' => '93', 'name' => 'Provence-Alpes-Côte d\'Azur', 'active_rides' => 87]
];

echo json_encode(['success' => true, 'data' => $regions]);
?>
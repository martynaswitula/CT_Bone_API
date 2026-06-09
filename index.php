<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://ct-bone-viewer.azurewebsites.net');

require_once 'db_connect.php';

$stats = [];

$stats['lekarze'] = $conn->query("SELECT COUNT(*) as cnt FROM lekarze WHERE rola = 'lekarz'")->fetch()['cnt'];
$stats['badania'] = $conn->query("SELECT COUNT(*) as cnt FROM badania_ct")->fetch()['cnt'];
$stats['ostatnie'] = $conn->query("SELECT patient_id, anatomy_type, created_at FROM badania_ct ORDER BY created_at DESC LIMIT 5")->fetchAll();
$stats['status'] = 'ok';
$stats['timestamp'] = date('Y-m-d H:i:s');

echo json_encode($stats);
?>
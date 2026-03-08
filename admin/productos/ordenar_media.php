<?php
require __DIR__ . '/../../includes/db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['orden'])) exit;

foreach ($data['orden'] as $pos => $id) {
    $stmt = $pdo->prepare("UPDATE productos_media SET orden = ? WHERE id = ?");
    $stmt->execute([$pos, $id]);
}

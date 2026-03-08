<?php
require __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT producto_id FROM productos_media WHERE id=?");
$stmt->execute([$id]);
$media = $stmt->fetch();

if(!$media){
    echo json_encode(['success'=>false]);
    exit;
}

$producto_id = $media['producto_id'];

$pdo->prepare("UPDATE productos_media SET es_principal=0 WHERE producto_id=?")
    ->execute([$producto_id]);

$pdo->prepare("UPDATE productos_media SET es_principal=1 WHERE id=?")
    ->execute([$id]);

echo json_encode(['success'=>true]);

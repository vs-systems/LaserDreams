<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$id       = (int)($_POST['id'] ?? 0);
$titulo   = trim($_POST['titulo'] ?? '');
$precio   = (float)($_POST['precio'] ?? 0);
$imagen   = $_POST['imagen'] ?? '';
$cantidad = (int)($_POST['cantidad'] ?? 1);

if ($id <= 0 || $precio <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['cantidad'] += $cantidad;
} else {
    $_SESSION['cart'][$id] = [
        'producto_id' => $id,
        'titulo' => $titulo,
        'precio' => $precio,
        'cantidad' => $cantidad,
        'imagen' => $imagen
    ];
}

echo json_encode([
    'ok' => true,
    'total_items' => array_sum(array_column($_SESSION['cart'], 'cantidad'))
]);

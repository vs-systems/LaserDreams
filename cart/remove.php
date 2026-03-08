<?php
session_start();

$id = (int)($_POST['id'] ?? 0);

if ($id > 0 && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

echo json_encode([
    'ok' => true,
    'total_items' => isset($_SESSION['cart'])
        ? array_sum(array_column($_SESSION['cart'], 'cantidad'))
        : 0
]);

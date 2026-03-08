<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre !== '') {
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, estado) VALUES (?, 1)");
        $stmt->execute([$nombre]);
    }
}
header('Location: index.php');
exit;

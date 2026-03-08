<?php
require __DIR__ . '/../../includes/db.php';
require __DIR__ . '/../../includes/auth.php';

// Solo Sistemas puede eliminar consultas
if (empty($_SESSION['rol']) || $_SESSION['rol'] !== 'Sistemas') {
    header('Location: index.php?error=unauthorized');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php?ok=eliminado');
exit;

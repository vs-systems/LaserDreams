<?php
require __DIR__ . '/../../includes/auth.php';

// Solo el rol Sistemas puede eliminar este tipo de comprobante
if ($_SESSION['rol'] !== 'Sistemas') {
    die('No autorizado.');
}

require __DIR__ . '/../../includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM solicitudes_mayoristas WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;

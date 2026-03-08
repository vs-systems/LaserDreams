<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
$accion = $_GET['accion'] ?? '';

if ($id > 0 && in_array($accion, ['activar', 'desactivar'])) {
    $estado = ($accion === 'activar') ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE categorias SET estado = ? WHERE id = ?");
    $stmt->execute([$estado, $id]);
}

header('Location: index.php');
exit;

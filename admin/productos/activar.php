<?php
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE productos SET activo = 1 WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php?ok=activado');
exit;

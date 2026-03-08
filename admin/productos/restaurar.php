<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';
;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: papelera.php');
    exit;
}

$stmt = $pdo->prepare("UPDATE productos SET activo = 1 WHERE id = ?");
$stmt->execute([$id]);

header('Location: papelera.php?ok=restaurado');
exit;

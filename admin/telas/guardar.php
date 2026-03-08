<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . '/includes/db.php';
require_once BASE_PATH . '/includes/auth.php';

$nombre = strtoupper(trim($_POST['nombre'] ?? ''));

if ($nombre === '') {
    header('Location: crear.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO telas (nombre) VALUES (?)");
$stmt->execute([$nombre]);

header('Location: listar.php');
exit;

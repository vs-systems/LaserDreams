<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . '/includes/db.php';
require_once BASE_PATH . '/includes/auth.php';

$id = (int)$_POST['id'];
$nombre = strtoupper(trim($_POST['nombre']));

$stmt = $pdo->prepare("UPDATE telas SET nombre = ? WHERE id = ?");
$stmt->execute([$nombre, $id]);

header('Location: listar.php');
exit;

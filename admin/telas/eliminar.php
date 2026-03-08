<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . '/includes/db.php';
require_once BASE_PATH . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);

$pdo->prepare("DELETE FROM telas WHERE id = ?")->execute([$id]);

header('Location: listar.php');
exit;

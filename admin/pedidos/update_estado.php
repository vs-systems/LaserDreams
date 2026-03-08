<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';
$stmt=$pdo->prepare("UPDATE pedidos SET estado=? WHERE id=?");
$stmt->execute([$_POST['estado'],$_POST['id']]);
header('Location: index.php');

<?php
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);

// Evitar que el usuario se elimine a sí mismo (seguridad básica)
if (isset($_SESSION['user_id']) && $id === $_SESSION['user_id']) {
    header('Location: index.php?error=self');
    exit;
}

if ($id > 0) {
    // Verificar rol antes de borrar
    $stmt = $pdo->prepare("SELECT rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $rol = $stmt->fetchColumn();

    if ($rol === 'Sistemas') {
        header('Location: index.php?error=protected');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php?ok=eliminado');
exit;

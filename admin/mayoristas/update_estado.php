<?php
require __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
    $id = (int) $_POST['id'];
    $estado = $_POST['estado'];

    // Solo permitir opciones válidas
    if (in_array($estado, ['Pendiente', 'Contactado', 'Cerrado'])) {
        $stmt = $pdo->prepare("UPDATE solicitudes_mayoristas SET estado = ? WHERE id = ?");
        $stmt->execute([$estado, $id]);
    }
}

// Redirect back to referring page or index
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
} else {
    header('Location: index.php');
}
exit;

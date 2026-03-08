<?php
require __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !isset($_GET['columna'])) {
    echo json_encode(['success' => false, 'error' => 'No ID/columna provided']);
    exit;
}

$id = (int) $_GET['id'];
$columna = $_GET['columna'];

$validColumns = ['foto_principal', 'foto_2', 'foto_3', 'foto_4', 'video', 'manual_tecnico'];
if (!in_array($columna, $validColumns)) {
    echo json_encode(['success' => false, 'error' => 'Invalid column']);
    exit;
}

// Obtener ruta del archivo
$stmt = $pdo->prepare("SELECT $columna FROM productos WHERE id = ?");
$stmt->execute([$id]);
$archivo = $stmt->fetchColumn();

if ($archivo) {
    $rutaFisica = __DIR__ . '/../../uploads/productos/' . $archivo;
    if (file_exists($rutaFisica)) {
        unlink($rutaFisica);
    }

    $pdo->prepare("UPDATE productos SET $columna = NULL WHERE id = ?")->execute([$id]);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Archivo no encontrado en BD']);
}

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
$modo = $_GET['modo'] ?? 'ocultar';

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    if ($modo === 'fisico') {
        // 1. Borrar archivos físicos de media
        $stmtM = $pdo->prepare("SELECT archivo FROM productos_media WHERE producto_id = ?");
        $stmtM->execute([$id]);
        $archivos = $stmtM->fetchAll(PDO::FETCH_COLUMN);
        foreach ($archivos as $archivo) {
            $path = __DIR__ . '/../../uploads/productos/' . $archivo;
            if (file_exists($path))
                unlink($path);
        }

        // 2. Borrar de tablas secundarias
        $pdo->prepare("DELETE FROM productos_media WHERE producto_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM productos_colores WHERE producto_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM productos_telas WHERE producto_id = ?")->execute([$id]);

        // 3. Borrar producto
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $msg = 'eliminado';
    } else {
        // Ocultado Lógico
        $stmt = $pdo->prepare("UPDATE productos SET activo = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $msg = 'ocultado';
    }

    header("Location: index.php?ok=$msg");
} catch (PDOException $e) {
    header('Location: index.php?error=' . urlencode($e->getMessage()));
}
exit;

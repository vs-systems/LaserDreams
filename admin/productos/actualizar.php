<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$codigo = strtoupper(trim($_POST['codigo'] ?? ''));
$titulo = trim($_POST['titulo'] ?? '');
$marca = trim($_POST['marca'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

$categoria_id = !empty($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : null;

$tipo_bulto = $_POST['tipo_bulto'] ?? 'Caja de Cartón';
$unidades_por_bulto = (int) ($_POST['unidades_por_bulto'] ?? 1);

$costo_compra = (float) ($_POST['costo_compra'] ?? 0);
$margen_porcentaje = (float) ($_POST['margen_porcentaje'] ?? 0);
$precio_venta_usd = (float) ($_POST['precio_venta_usd'] ?? 0);

$activo = isset($_POST['activo']) ? 1 : 0;
$oferta = isset($_POST['es_oferta']) ? 1 : 0;
$nuevo = isset($_POST['es_nuevo']) ? 1 : 0;
$destacado = isset($_POST['es_destacado']) ? 1 : 0;

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        UPDATE productos
        SET codigo = ?,
            titulo = ?,
            marca = ?,
            descripcion = ?,
            categoria_id = ?,
            activo = ?,
            es_oferta = ?,
            es_nuevo = ?,
            es_destacado = ?,
            tipo_bulto = ?,
            unidades_por_bulto = ?,
            costo_compra = ?,
            margen_porcentaje = ?,
            precio_venta_usd = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $codigo,
        $titulo,
        $marca,
        $descripcion,
        $categoria_id,
        $activo,
        $oferta,
        $nuevo,
        $destacado,
        $tipo_bulto,
        $unidades_por_bulto,
        $costo_compra,
        $margen_porcentaje,
        $precio_venta_usd,
        $id
    ]);

    $pdo->commit();

    header('Location: index.php?ok=editado');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction())
        $pdo->rollBack();
    die('Error al actualizar producto: ' . $e->getMessage());
}

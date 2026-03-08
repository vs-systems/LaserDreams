<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';


$stmt = $pdo->query("
    SELECT p.*, c.nombre AS categoria
    FROM productos p
    LEFT JOIN categorias c ON c.id = p.categoria_id
    WHERE p.activo = 0
    ORDER BY p.id DESC
");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Papelera de productos</title>
</head>
<body>

<h1>Papelera de productos</h1>

<p><a href="listar.php">⬅ Volver al listado</a></p>

<table border="1" cellpadding="6">
<tr>
    <th>ID</th>
    <th>Código</th>
    <th>Título</th>
    <th>Categoría</th>
    <th>Acciones</th>
</tr>

<?php if (!$productos): ?>
<tr><td colspan="5">La papelera está vacía.</td></tr>
<?php endif; ?>

<?php foreach ($productos as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['codigo']) ?></td>
    <td><?= htmlspecialchars($p['titulo']) ?></td>
    <td><?= htmlspecialchars($p['categoria']) ?></td>
    <td>
        <a href="restaurar.php?id=<?= $p['id'] ?>">♻ Restaurar</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>

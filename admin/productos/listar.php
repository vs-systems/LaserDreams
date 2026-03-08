<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

$productos = $pdo->query("
SELECT 
    p.id,
    p.codigo,
    p.titulo,
    c.nombre AS categoria,
    GROUP_CONCAT(DISTINCT col.nombre SEPARATOR ', ') AS colores,
    GROUP_CONCAT(DISTINCT t.nombre SEPARATOR ', ') AS telas,
    CONCAT(
        IF(p.oferta=1,'Oferta ',''),
        IF(p.novedad=1,'Novedad ',''),
        IF(p.destacado=1,'Destacado','')
    ) AS etiquetas
FROM productos p
LEFT JOIN categorias c ON c.id = p.categoria_id
LEFT JOIN productos_colores pc ON pc.producto_id = p.id
LEFT JOIN colores col ON col.id = pc.color_id
LEFT JOIN productos_telas pt ON pt.producto_id = p.id
LEFT JOIN telas t ON t.id = pt.tela_id
GROUP BY p.id
ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

function e($v){
    return htmlspecialchars((string)($v ?? '-'), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>

<h1>Productos</h1>
<a href="crear.php">+ Nuevo producto</a>

<table>
<tr>
<th>ID</th>
<th>Código</th>
<th>Título</th>
<th>Categoría</th>
<th>Colores</th>
<th>Telas</th>
<th>Etiquetas</th>
<th>Acciones</th>
</tr>

<?php foreach($productos as $p): ?>
<tr>
<td><?= $p['id'] ?></td>
<td><?= e($p['codigo']) ?></td>
<td><?= e($p['titulo']) ?></td>
<td><?= e($p['categoria']) ?></td>
<td><?= e($p['colores']) ?></td>
<td><?= e($p['telas']) ?></td>
<td><?= e($p['etiquetas']) ?></td>
<td>
    <a href="editar.php?id=<?= $p['id'] ?>">✏ Editar</a> |
    <a href="eliminar.php?id=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar producto?')">❌ Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>

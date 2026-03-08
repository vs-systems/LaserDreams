<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . '/includes/db.php';
require_once BASE_PATH . '/includes/auth.php';

$telas = $pdo->query("SELECT * FROM telas ORDER BY nombre")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Telas</title>
</head>
<body>

<h1>Telas</h1>

<p>
<a href="crear.php">➕ Nueva tela</a> |
<a href="../productos/listar.php">⬅ Productos</a>
</p>

<table border="1" cellpadding="6">
<tr>
  <th>ID</th>
  <th>Nombre</th>
  <th>Acciones</th>
</tr>

<?php foreach ($telas as $t): ?>
<tr>
  <td><?= $t['id'] ?></td>
  <td><?= htmlspecialchars($t['nombre']) ?></td>
  <td>
    <a href="editar.php?id=<?= $t['id'] ?>">✏️</a>
    <a href="eliminar.php?id=<?= $t['id'] ?>"
       onclick="return confirm('¿Eliminar tela?')">🗑️</a>
  </td>
</tr>
<?php endforeach; ?>

<?php if (!$telas): ?>
<tr><td colspan="3">No hay telas cargadas.</td></tr>
<?php endif; ?>

</table>

</body>
</html>

<?php
require_once __DIR__ . '/../../includes/config.php';
require_once BASE_PATH . '/includes/db.php';
require_once BASE_PATH . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM telas WHERE id = ?");
$stmt->execute([$id]);
$tela = $stmt->fetch();

if (!$tela) {
    header('Location: listar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar tela</title>
</head>
<body>

<h1>Editar tela</h1>

<form action="actualizar.php" method="post">
<input type="hidden" name="id" value="<?= $tela['id'] ?>">
<input type="text" name="nombre" value="<?= htmlspecialchars($tela['nombre']) ?>" required>
<br><br>
<button>Actualizar</button>
<a href="listar.php">Cancelar</a>
</form>

</body>
</html>

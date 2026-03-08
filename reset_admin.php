<?php
require __DIR__ . '/includes/db.php';

$usuario = 'sistemas';
$pass_plana = '985236@';
$pass_hash = password_hash($pass_plana, PASSWORD_BCRYPT);

try {
    $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, rol = 'Sistemas' WHERE usuario = ?");
    $stmt->execute([$pass_hash, $usuario]);

    if ($stmt->rowCount() === 0) {
        // Si no existe, lo creamos
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES ('Sistemas', ?, ?, 'Sistemas')");
        $stmt->execute([$usuario, $pass_hash]);
        echo "Usuario 'sistemas' creado con éxito.\n";
    } else {
        echo "Contraseña de 'sistemas' actualizada con éxito.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

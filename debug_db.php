<?php
require __DIR__ . '/includes/db.php';
header('Content-Type: text/plain');

try {
    echo "--- Usuarios Table Structure ---\n";
    $stmt = $pdo->query("DESCRIBE usuarios");
    while ($row = $stmt->fetch()) {
        print_r($row);
    }

    echo "\n--- Sample Data ---\n";
    $stmt = $pdo->query("SELECT id, nombre, usuario, rol FROM usuarios LIMIT 5");
    while ($row = $stmt->fetch()) {
        print_r($row);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

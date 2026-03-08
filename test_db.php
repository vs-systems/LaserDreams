<?php
require __DIR__ . '/includes/db.php';

echo "<h2>Chequeo de Base de Datos</h2>";

$tablas = ['productos', 'categorias', 'colores', 'telas', 'productos_colores', 'productos_telas', 'productos_media', 'usuarios', 'ajustes', 'pedidos'];

foreach ($tablas as $t) {
    echo "<h3>Tabla: $t</h3>";
    try {
        $stmt = $pdo->query("DESCRIBE $t");
        echo "<table border='1' cellpadding='5'><tr><th>Field</th><th>Type</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr><td>{$row['Field']}</td><td>{$row['Type']}</td></tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<p style='color:red'>ERROR: " . $e->getMessage() . "</p>";
    }
}

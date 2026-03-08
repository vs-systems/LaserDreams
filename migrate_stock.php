<?php
// migrate_stock.php - Script temporal para agregar la columna en_stock
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/includes/db.php';

try {
    $pdo->exec("ALTER TABLE productos ADD COLUMN en_stock TINYINT(1) DEFAULT 1 AFTER es_destacado");
    echo "<h1>Columna 'en_stock' agregada exitosamente.</h1>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<h1>La columna 'en_stock' ya existe. Todo en orden.</h1>";
    } else {
        echo "Error: " . $e->getMessage();
    }
}

// Auto-eliminar script por seguridad
unlink(__FILE__);
?>
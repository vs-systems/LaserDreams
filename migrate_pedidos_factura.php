<?php
require __DIR__ . '/includes/db.php';

try {
    echo "Agregando columnas de facturacion a 'pedidos'...<br>";
    $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS requiere_factura TINYINT(1) DEFAULT 0");
    $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS cuit VARCHAR(50) DEFAULT NULL");
    $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS razon_social VARCHAR(255) DEFAULT NULL");
    $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS tipo_factura ENUM('A', 'B', 'C') DEFAULT NULL");
    $pdo->exec("ALTER TABLE pedidos ADD COLUMN IF NOT EXISTS iva_aplicado DECIMAL(10,2) DEFAULT 0.00");

    echo "<br><b>Migracion completada exitosamente.</b>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

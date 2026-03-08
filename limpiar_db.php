<?php
require __DIR__ . '/includes/db.php';

$tablasABorrar = [
    'producto_telas',
    'producto_colores',
    'multimedia_producto',
    'imagenes_producto'
];

echo "<h3>Iniciando limpieza de tablas obsoletas...</h3><ul>";

foreach ($tablasABorrar as $tabla) {
    try {
        $pdo->exec("DROP TABLE IF EXISTS $tabla");
        echo "<li>✅ Tabla <b>$tabla</b> eliminada con éxito (o no existía).</li>";
    } catch (Exception $e) {
        echo "<li style='color:red;'>❌ Error al borrar <b>$tabla</b>: " . $e->getMessage() . "</li>";
    }
}

echo "</ul><p><b>Limpieza completada.</b> Tu base de datos ahora está más ligera y organizada.</p>";
echo "<a href='/admin/dashboard.php' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#F59E0B; color:black; text-decoration:none; border-radius:10px; font-weight:bold;'>Volver al Dashboard</a>";

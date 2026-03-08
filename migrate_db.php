<?php
require __DIR__ . '/includes/db.php';
try {
    $pdo->exec("ALTER TABLE productos ADD COLUMN marca VARCHAR(100) DEFAULT '' AFTER titulo;");
    echo "<h1 style='color:green; font-family:sans-serif'>¡Columna 'marca' agregada exitosamente a la base de datos!</h1>";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "<h1 style='color:blue; font-family:sans-serif'>La columna 'marca' ya existe. Todo OK.</h1>";
    } else {
        echo "<h1 style='color:red; font-family:sans-serif'>Error DB: " . htmlspecialchars($e->getMessage()) . "</h1>";
    }
}
@unlink(__FILE__); // Se autoelimina por seguridad despues de ejecutarse
?>
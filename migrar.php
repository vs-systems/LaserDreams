<?php
require __DIR__ . '/includes/db.php';

try {
    // 1. Tabla de Ajustes
    $pdo->exec("CREATE TABLE IF NOT EXISTS ajustes (
        clave VARCHAR(50) PRIMARY KEY,
        valor TEXT,
        descripcion VARCHAR(255)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("INSERT IGNORE INTO ajustes (clave, valor, descripcion) VALUES 
        ('etiqueta_coleccion', 'Colección 2026', 'Texto que aparece en los productos destacados'),
        ('whatsapp_nro', '+5492235655238', 'Número de WhatsApp para consultas'),
        ('email_contacto', 'ventas@mgmuebles.com.ar', 'Email de contacto oficial')
    ;");

    // 2. Tabla de Usuarios - Asegurar estructura
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        rol ENUM('Sistemas', 'Administrador', 'Vendedor') DEFAULT 'Vendedor',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Verificar y agregar columnas faltantes una por una
    $cols = $pdo->query("DESCRIBE usuarios")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('usuario', $cols)) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN usuario VARCHAR(50) NOT NULL UNIQUE AFTER nombre;");
    }
    if (!in_array('rol', $cols)) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN rol ENUM('Sistemas', 'Administrador', 'Vendedor') DEFAULT 'Vendedor' AFTER password;");
    } else {
        // Asegurar que el ENUM tiene 'Sistemas'
        $pdo->exec("ALTER TABLE usuarios MODIFY COLUMN rol ENUM('Sistemas', 'Administrador', 'Vendedor') DEFAULT 'Vendedor';");
    }
    if (!in_array('created_at', $cols)) {
        $pdo->exec("ALTER TABLE usuarios ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;");
    }

    // Parche: Eliminar columna 'email' de usuarios si existe (causa conflicto de Duplicate Entry)
    if (in_array('email', $cols)) {
        $pdo->exec("ALTER TABLE usuarios DROP COLUMN email;");
    }

    // Insertar usuarios iniciales si no existen
    $pass = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT IGNORE INTO usuarios (nombre, usuario, password, rol) VALUES 
        ('Sistemas', 'sistemas', '$pass', 'Sistemas'),
        ('Javier', 'admin', '$pass', 'Administrador')
    ;");
    $pdo->exec("CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        carrito TEXT NOT NULL,
        total DECIMAL(15,2) NOT NULL,
        estado ENUM('Nuevo', 'Cotizado', 'Confirmado', 'En Producción', 'Enviado', 'Cancelado') DEFAULT 'Nuevo',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // 4. Parches adicionales
    try {
        $pdo->exec("ALTER TABLE productos ADD COLUMN visitas INT DEFAULT 0;");
    } catch (Exception $e) {
    }
    try {
        $pdo->exec("ALTER TABLE productos ADD COLUMN es_pocas_unidades TINYINT(1) DEFAULT 0;");
    } catch (Exception $e) {
    }

    echo "Migración completada con éxito. Todas las tablas críticas están listas.";

} catch (PDOException $e) {
    echo "Error en migración: " . $e->getMessage();
}

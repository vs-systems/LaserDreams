<?php
require __DIR__ . '/includes/db.php';

$sql = "
CREATE TABLE IF NOT EXISTS ajustes (
    clave VARCHAR(50) PRIMARY KEY,
    valor TEXT,
    descripcion VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO ajustes (clave, valor, descripcion) VALUES 
    ('etiqueta_coleccion', 'Novedades', 'Texto que aparece en los productos destacados'),
    ('whatsapp_nro', '+5491100000000', 'Número de WhatsApp para consultas'),
    ('email_contacto', 'info@laserdreams.com.ar', 'Email de contacto oficial');

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Sistemas', 'Administrador', 'Vendedor') DEFAULT 'Administrador',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO usuarios (nombre, usuario, password, rol) VALUES 
    ('Javier', 'admin', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    titulo VARCHAR(255) NOT NULL,
    marca VARCHAR(100) DEFAULT '',
    descripcion LONGTEXT,
    categoria_id INT,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    es_oferta BOOLEAN NOT NULL DEFAULT FALSE,
    es_nuevo BOOLEAN NOT NULL DEFAULT FALSE,
    es_destacado BOOLEAN NOT NULL DEFAULT FALSE,
    tipo_bulto ENUM('Caja de Cartón', 'Anvil Flight Case') DEFAULT 'Caja de Cartón',
    unidades_por_bulto INT DEFAULT 1,
    costo_compra DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    margen_porcentaje DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    precio_venta_usd DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    foto_principal VARCHAR(255),
    foto_2 VARCHAR(255),
    foto_3 VARCHAR(255),
    foto_4 VARCHAR(255),
    video VARCHAR(255),
    manual_tecnico VARCHAR(255),
    visitas INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    carrito TEXT NOT NULL,
    total DECIMAL(15,2) NOT NULL,
    estado ENUM('Nuevo', 'Cotizado', 'Confirmado', 'En Producción', 'Enviado', 'Cancelado') DEFAULT 'Nuevo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdo->exec($sql);
    echo "<div style='text-align:center; font-family:sans-serif; margin-top:50px;'>";
    echo "<h1 style='color:green;'>¡Base de Datos de Laserdreams Instalada Exitosamente!</h1>";
    echo "<p>Las tablas necesarias y el usuario administrador han sido creados.</p>";
    echo "<a href='/' style='padding:15px 30px; background:red; color:white; text-decoration:none; border-radius:10px; font-weight:bold; display:inline-block; margin-top:20px;'>Ver Sitio Web</a>";
    echo "</div>";

    // Auto-borrado local y en el servidor por seguridad post instalación
    @unlink(__FILE__);
} catch (PDOException $e) {
    echo "<div style='text-align:center; font-family:sans-serif; margin-top:50px;'>";
    echo "<h1 style='color:red;'>Error creando tablas: " . htmlspecialchars($e->getMessage()) . "</h1>";
    echo "</div>";
}
?>
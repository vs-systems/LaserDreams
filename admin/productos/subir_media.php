<?php
ini_set('display_errors', 0); // Evitar que errores rompan el JSON
error_reporting(E_ALL);

require __DIR__ . '/../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_FILES['file']) || !isset($_POST['producto_id'])) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

$producto_id = (int) $_POST['producto_id'];
$file = $_FILES['file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Error al subir archivo']);
    exit;
}

// Obtener el producto para verificar huecos libres
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$producto_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'error' => 'Producto no encontrado']);
    exit;
}

$productCode = $product['codigo'] ?: 'S_C';
$productCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $productCode);

$uploadDir = __DIR__ . '/../../uploads/productos/' . $productCode . '/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$nombreBase = uniqid() . '_' . time();
$columna = '';

switch ($ext) {
    case 'jpg':
    case 'jpeg':
    case 'png':
    case 'webp':
        // Asignar a foto_principal, foto_2, foto_3, foto_4
        if (empty($product['foto_principal']))
            $columna = 'foto_principal';
        elseif (empty($product['foto_2']))
            $columna = 'foto_2';
        elseif (empty($product['foto_3']))
            $columna = 'foto_3';
        elseif (empty($product['foto_4']))
            $columna = 'foto_4';
        else {
            echo json_encode(['success' => false, 'error' => 'Límite de 4 fotos alcanzado']);
            exit;
        }

        $nombreArchivoBase = $nombreBase . '.webp';
        $nombreArchivoFull = $productCode . '/' . $nombreArchivoBase;
        $destino = $uploadDir . $nombreArchivoBase;

        // Cargar imagen según extensión
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $img = @imagecreatefromjpeg($file['tmp_name']);
        } elseif ($ext === 'png') {
            $img = @imagecreatefrompng($file['tmp_name']);
            if ($img !== false) {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }
        } elseif ($ext === 'webp') {
            $img = @imagecreatefromwebp($file['tmp_name']);
        }

        if (isset($img) && $img !== false) {
            // Optimización: Redimensionar si es muy grande (max 1200px)
            $width = imagesx($img);
            $height = imagesy($img);
            if ($width > 1200) {
                $newWidth = 1200;
                $newHeight = ($height / $width) * 1200;
                $tmp = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($img);
                $img = $tmp;
            }

            imagewebp($img, $destino, 80); // Calidad 80 para balance calidad/peso
            imagedestroy($img);
        } else {
            // Fallback si falla la librería GD
            move_uploaded_file($file['tmp_name'], $destino);
        }
        break;

    case 'mp4':
        if (!empty($product['video'])) {
            echo json_encode(['success' => false, 'error' => 'Límite de 1 video alcanzado']);
            exit;
        }
        $columna = 'video';
        $nombreArchivoBase = $nombreBase . '.mp4';
        $nombreArchivoFull = $productCode . '/' . $nombreArchivoBase;
        $destino = $uploadDir . $nombreArchivoBase;
        move_uploaded_file($file['tmp_name'], $destino);
        break;

    case 'pdf':
        if (!empty($product['manual_tecnico'])) {
            echo json_encode(['success' => false, 'error' => 'Ya existe un manual técnico para este producto']);
            exit;
        }
        $columna = 'manual_tecnico';
        $nombreArchivoBase = $nombreBase . '.pdf';
        $nombreArchivoFull = $productCode . '/' . $nombreArchivoBase;
        $destino = $uploadDir . $nombreArchivoBase;
        move_uploaded_file($file['tmp_name'], $destino);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Formato no permitido (solo JPG, PNG, WEBP, MP4, PDF)']);
        exit;
}

$stmtUpdate = $pdo->prepare("UPDATE productos SET $columna = ? WHERE id = ?");
$stmtUpdate->execute([$nombreArchivoFull, $producto_id]);

echo json_encode(['success' => true, 'file' => $nombreArchivoFull, 'type' => $columna]);

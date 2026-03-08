<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$producto_id = (int)($_POST['producto_id'] ?? 0);
if ($producto_id <= 0) {
    http_response_code(400);
    die('Producto inválido');
}

// Verificar producto
$check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
$check->execute([$producto_id]);
if (!$check->fetch()) {
    http_response_code(400);
    die('Producto inexistente');
}


$dir = "../../uploads/productos/$producto_id/";
if (!is_dir($dir)) {
    if (!mkdir($dir, 0775, true)) {
        http_response_code(500);
        die('No se pudo crear el directorio');
    }
}

$maxVideo = 20 * 1024 * 1024;

foreach ($_FILES['media']['tmp_name'] as $i => $tmp) {

    if (!is_uploaded_file($tmp)) continue;

    $size = $_FILES['media']['size'][$i];
    $type = $_FILES['media']['type'][$i];

    /* =====================
       IMÁGENES
    ===================== */
    if (str_starts_with($type, 'image/')) {

        $data = file_get_contents($tmp);
        if (!$data) continue;

        $img = @imagecreatefromstring($data);
        if (!$img) continue;

        $w = imagesx($img);
        $h = imagesy($img);
        $max = 1600;

        if ($w > $max || $h > $max) {
            $scale = min($max / $w, $max / $h);
            $nw = (int)($w * $scale);
            $nh = (int)($h * $scale);
            $res = imagecreatetruecolor($nw, $nh);
            imagecopyresampled($res, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagedestroy($img);
            $img = $res;
        }

        // WEBP si existe, JPG si no
        if (function_exists('imagewebp')) {
            $file = uniqid('img_') . '.webp';
            imagewebp($img, $dir . $file, 80);
        } else {
            $file = uniqid('img_') . '.jpg';
            imagejpeg($img, $dir . $file, 85);
        }

        imagedestroy($img);

        $pdo->prepare("
            INSERT INTO productos_media (producto_id, archivo, tipo, orden)
            VALUES (?, ?, 'imagen', 0)
        ")->execute([$producto_id, $file]);
    }

    /* =====================
       VIDEOS
    ===================== */
    if ($type === 'video/mp4' && $size <= $maxVideo) {

        $file = uniqid('vid_') . '.mp4';
        if (move_uploaded_file($tmp, $dir . $file)) {
            $pdo->prepare("
                INSERT INTO productos_media (producto_id, archivo, tipo, orden)
                VALUES (?, ?, 'video', 0)
            ")->execute([$producto_id, $file]);
        }
    }
}

header("Location: media.php?id=$producto_id");
exit;

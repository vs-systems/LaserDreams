<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Return a visible error for debugging if needed, but safe for production
    die('Error de conexión a la base de datos. Por favor, verifica las credenciales en config.php. Detalles ocultos por seguridad.');
}

/**
 * Obtener un ajuste dinámico de la tabla 'ajustes'
 */
function get_ajuste($clave, $default = '')
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT valor FROM ajustes WHERE clave = ?");
        $stmt->execute([$clave]);
        return $stmt->fetchColumn() ?: $default;
    } catch (Exception $e) {
        return $default;
    }
}

function set_ajuste($clave, $valor, $descripcion = '')
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO ajustes (clave, valor, descripcion) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE valor = ?");
        $stmt->execute([$clave, $valor, $descripcion, $valor]);
    } catch (Exception $e) {
    }
}

function get_cotizacion_dolar()
{
    $last_time = (int) get_ajuste('usd_blue_time', 0);
    $current_val = (float) get_ajuste('usd_blue_val', 0);

    // Fetch every 30 minutes (1800 seconds)
    if (time() - $last_time > 1800) {
        try {
            $api_url = "https://dolarapi.com/v1/dolares/blue";

            // Use cURL instead of file_get_contents for better compatibility on shared hosting
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 seconds timeout
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $json = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 200 && $json) {
                $data = json_decode($json, true);
                if (isset($data['venta'])) {
                    $current_val = (float) $data['venta'];
                    set_ajuste('usd_blue_val', $current_val, 'Valor base dolar blue venta');
                    set_ajuste('usd_blue_time', time(), 'Ultima actualizacion api dolar');
                }
            }
        } catch (Exception $e) {
            // Silently fail and use last known value
        }
    }

    // Cotizacion aplicada = Venta + 15
    return $current_val > 0 ? $current_val + 15 : 0;
}

$GLOBALS['cotizacion_aplicada'] = get_cotizacion_dolar();
$GLOBALS['dolar_blue_base'] = (float) get_ajuste('usd_blue_val', 0);


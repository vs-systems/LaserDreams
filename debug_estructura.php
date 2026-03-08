<?php
function mostrar($dir, $nivel = 0) {
    if (!is_dir($dir)) return;

    $archivos = scandir($dir);
    foreach ($archivos as $a) {
        if ($a === '.' || $a === '..') continue;

        echo str_repeat('— ', $nivel) . $a;

        if (is_dir($dir . '/' . $a)) {
            echo "/<br>";
            mostrar($dir . '/' . $a, $nivel + 1);
        } else {
            echo "<br>";
        }
    }
}

echo "<h2>Estructura del proyecto</h2>";
mostrar(__DIR__);

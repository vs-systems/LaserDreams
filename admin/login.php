<?php
require_once '../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = $_POST['usuario'] ?? '';
    $clave_input = $_POST['clave'] ?? '';

    // 1. Intento por Base de Datos
    require_once '../includes/db.php';
    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$user_input]);
        $user = $stmt->fetch();

        if ($user && password_verify($clave_input, $user['password'])) {
            session_start();
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol'] = $user['rol'];
            header('Location: /admin/dashboard.php');
            exit;
        }
    } catch (Exception $e) {
        // Error de DB, fallar al hardcoded
    }

    // 2. Fallback de emergencia (Solo para Javier en caso de fallos de DB)
    if ($user_input === 'Javier' && $clave_input === 'Andrea1910@!!') {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['rol'] = 'Sistemas';
        $_SESSION['nombre'] = 'Javier';
        header('Location: /admin/dashboard.php');
        exit;
    }

    $error = 'Credenciales incorrectas o usuario no encontrado.';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Admin | Laserdreams</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">LASER<span class="text-red-600">DREAMS</span>
            </h1>
            <p class="text-gray-500 font-medium mt-2">Panel de Administración</p>
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-8">Iniciar Sesión</h2>

            <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl text-sm font-bold mb-6 border border-red-100">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Usuario</label>
                    <input type="text" name="usuario" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all font-medium">
                </div>

                <div>
                    <label
                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Contraseña</label>
                    <input type="password" name="clave" required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all font-medium">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full bg-gray-900 text-white py-5 rounded-2xl font-black text-lg hover:bg-red-600 hover:text-white transition-all transform active:scale-95 shadow-xl shadow-gray-200">
                        Ingresar al Sistema
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-10 text-center">
            <a href="/" class="text-gray-400 font-bold hover:text-gray-900 transition-colors text-sm">
                ← Volver al sitio web
            </a>
        </div>
    </div>

</body>

</html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_path = __DIR__ . '/../../includes/db.php';
if (!file_exists($db_path)) {
    die("Error: No se encuentra el archivo de base de datos en: " . $db_path);
}
require_once $db_path;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nombre = trim($_POST['nombre']);
        $usuario = trim($_POST['usuario']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $rol = $_POST['rol'];

        if (empty($usuario))
            throw new Exception("El usuario no puede estar vacío");

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        if ($stmt->fetch()) {
            throw new Exception("El nombre de usuario '$usuario' ya está en uso.");
        }

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $usuario, $password, $rol]);

        header('Location: index.php?ok=creado');
        exit;
    } catch (PDOException $e) {
        $error_msg = "Error PDO: " . $e->getMessage();
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
    }
}

$adminTitle = 'Nuevo Usuario';
require __DIR__ . '/../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
        <?php if (isset($error_msg)): ?>
            <div class="bg-red-50 text-red-600 p-6 rounded-3xl font-bold mb-8 border border-red-100">
                <?= $error_msg ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-8">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Nombre
                    Completo</label>
                <input type="text" name="nombre" required placeholder="Ej: Juan Pérez"
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Usuario
                        (Login)</label>
                    <input type="text" name="usuario" required placeholder="juan.perez"
                        class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Rol /
                    Permisos</label>
                <select name="rol" required
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900 uppercase text-xs tracking-widest">
                    <option value="Vendedor">Vendedor (Solo lectura/carga)</option>
                    <option value="Administrador">Administrador (Gestión total)</option>
                    <option value="Sistemas">Sistemas (Acceso técnico)</option>
                </select>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full bg-gray-900 text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-violet-500 hover:text-black transition-all shadow-xl shadow-gray-200 flex items-center justify-center gap-3">
                    Crear Usuario Ahora
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
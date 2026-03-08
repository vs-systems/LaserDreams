<?php
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $rol = $_POST['rol'];

    // Protección: No permitir cambiar el rol de un Sistemas a menos que sea otro Sistemas (o simplemente bloquearlo)
    if ($usuario['rol'] === 'Sistemas' && $rol !== 'Sistemas') {
        header('Location: index.php?error=role_protected');
        exit;
    }

    try {
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rol = ?, password = ? WHERE id = ?");
            $stmt->execute([$nombre, $rol, $password, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?");
            $stmt->execute([$nombre, $rol, $id]);
        }
        header('Location: index.php?ok=actualizado');
        exit;
    } catch (PDOException $e) {
        $error_msg = "Error DB: " . $e->getMessage();
    }
}

$adminTitle = 'Editar Usuario: ' . $usuario['nombre'];
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
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Usuario
                        (Fijo)</label>
                    <input type="text" value="<?= htmlspecialchars($usuario['usuario']) ?>" disabled
                        class="w-full px-6 py-4 rounded-2xl bg-gray-100 border-none font-bold text-gray-400 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Nueva
                        Contraseña</label>
                    <input type="password" name="password" placeholder="Dejar en blanco para no cambiar"
                        class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3">Rol /
                    Permisos</label>
                <select name="rol" required
                    class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900 uppercase text-xs tracking-widest">
                    <option value="Vendedor" <?= $usuario['rol'] === 'Vendedor' ? 'selected' : '' ?>>Vendedor (Solo
                        lectura/carga)</option>
                    <option value="Administrador" <?= $usuario['rol'] === 'Administrador' ? 'selected' : '' ?>>
                        Administrador (Gestión total)</option>
                    <option value="Sistemas" <?= $usuario['rol'] === 'Sistemas' ? 'selected' : '' ?>>Sistemas (Acceso
                        técnico)</option>
                </select>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full bg-violet-500 text-black py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-gray-900 hover:text-white transition-all shadow-xl shadow-violet-500/20 flex items-center justify-center gap-3">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
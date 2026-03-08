<?php
require __DIR__ . '/../../includes/db.php';

// Obtener todos los ajustes
$ajustes = $pdo->query("SELECT * FROM ajustes ORDER BY clave ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['ajuste'] as $clave => $valor) {
        $stmt = $pdo->prepare("UPDATE ajustes SET valor = ? WHERE clave = ?");
        $stmt->execute([$valor, $clave]);
    }
    header('Location: index.php?ok=1');
    exit;
}

$adminTitle = '⚙️ Configuración del Sitio';
require __DIR__ . '/../includes/header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
        <form method="post" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <?php foreach ($ajustes as $a): ?>
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <?= htmlspecialchars($a['descripcion'] ?: $a['clave']) ?>
                        </label>
                        <input type="text" name="ajuste[<?= htmlspecialchars($a['clave']) ?>]"
                            value="<?= htmlspecialchars($a['valor']) ?>"
                            class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pt-8 border-t border-gray-50 flex justify-end">
                <button type="submit"
                    class="bg-gray-900 text-white px-10 py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-violet-500 hover:text-black transition-all shadow-xl shadow-gray-200 flex items-center gap-3">
                    🚀 Guardar Configuraciones
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div
            class="mt-8 bg-green-50 text-green-600 p-6 rounded-3xl font-black text-xs uppercase tracking-widest text-center border border-green-100 animate-bounce">
            ✨ ¡Configuraciones actualizadas con éxito!
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
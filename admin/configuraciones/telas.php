<?php
require __DIR__ . '/../../includes/db.php';

$mensaje = '';
$error = false;

if (isset($_POST['nueva_tela'])) {
    $nombre = trim($_POST['nombre']);
    if ($nombre) {
        try {
            $pdo->prepare("INSERT INTO telas (nombre) VALUES (?)")->execute([$nombre]);
            $mensaje = 'Tela agregada con éxito.';
        } catch (PDOException $e) {
            $mensaje = 'La tela ya existe.';
            $error = true;
        }
    }
}

if (isset($_GET['toggle'])) {
    $pdo->prepare("UPDATE telas SET activa = IF(activa=1,0,1) WHERE id=?")
        ->execute([$_GET['toggle']]);
    header('Location: telas.php');
    exit;
}

$telas = $pdo->query("SELECT * FROM telas ORDER BY nombre")->fetchAll();

$adminTitle = 'Textiles / Telas';
require __DIR__ . '/../includes/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

    <!-- Formulario -->
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm sticky top-32">
            <h2 class="text-xl font-black text-gray-900 mb-6">Nueva Tela</h2>

            <?php if ($mensaje): ?>
                <div
                    class="<?= $error ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' ?> p-4 rounded-2xl text-xs font-bold mb-6 border <?= $error ? 'border-red-100' : 'border-green-100' ?>">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>

            <form method="post" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nombre del
                        Textil</label>
                    <input type="text" name="nombre" placeholder="Ej: Pana, Chenille, Lino..." required
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 focus:border-violet-500 focus:ring-2 focus:ring-violet-500/20 outline-none transition-all font-bold text-gray-900">
                </div>
                <button name="nueva_tela"
                    class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-violet-500 hover:text-black transition-all shadow-xl shadow-gray-200 active:scale-95">
                    Agregar Tela
                </button>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-8 py-5 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($telas)): ?>
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center">
                                <span class="text-4xl mb-4 block">🧶</span>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No hay telas cargadas
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($telas as $t): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <span
                                    class="font-black text-gray-900 tracking-tight transition-colors group-hover:text-violet-600"><?= htmlspecialchars($t['nombre']) ?></span>
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?= $t['activa'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $t['activa'] ? 'Activa' : 'Inactiva' ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <a href="?toggle=<?= $t['id'] ?>"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all <?= $t['activa'] ? 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white' : 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' ?>">
                                    <?= $t['activa'] ? 'Desactivar' : 'Activar' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
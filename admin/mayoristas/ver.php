<?php
require __DIR__ . '/../../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM solicitudes_mayoristas WHERE id = ?");
$stmt->execute([$id]);
$solicitud = $stmt->fetch();

if (!$solicitud) {
    header('Location: index.php');
    exit;
}

$productos_ids = json_decode($solicitud['productos_interes'], true) ?: [];
$productos = [];
if (!empty($productos_ids)) {
    $in = str_repeat('?,', count($productos_ids) - 1) . '?';
    $sql = "SELECT titulo, codigo, foto_principal FROM productos WHERE id IN ($in)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($productos_ids);
    $productos = $stmt->fetchAll();
}

$adminTitle = 'Detalle de Solicitud MAY-' . str_pad($solicitud['id'], 5, '0', STR_PAD_LEFT);
require __DIR__ . '/../includes/header.php';
?>

<div class="mb-6 flex gap-3 pb-6 border-b border-gray-200">
    <a href="index.php"
        class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-900 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 transition-all border border-gray-200 shadow-sm">
        <span>←</span> Volver al Listado
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Detalle Cliente -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-violet-50 rounded-full -mr-16 -mt-16"></div>

            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 block border-b pb-2">
                Información de Contacto</h3>

            <div class="space-y-6 relative z-10">
                <div>
                    <span class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Nombre
                        Completo</span>
                    <span class="text-base font-black text-gray-900">
                        <?= htmlspecialchars($solicitud['nombre']) ?>
                    </span>
                </div>

                <div>
                    <span class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Localidad /
                        Provincia</span>
                    <span class="text-sm font-bold text-gray-700">
                        <?= htmlspecialchars($solicitud['localidad']) ?>
                    </span>
                </div>

                <div>
                    <span class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">WhatsApp /
                        Teléfono</span>
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $solicitud['telefono']) ?>" target="_blank"
                        class="inline-flex items-center gap-2 text-sm font-bold text-green-600 hover:text-green-700 hover:underline">
                        💬
                        <?= htmlspecialchars($solicitud['telefono']) ?> <span>↗</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 block border-b pb-2">Perfil
                Comercial</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Tipo de
                        Cliente</span>
                    <span class="text-sm font-bold text-gray-900 px-3 py-1 bg-gray-100 rounded-lg inline-block">
                        <?= htmlspecialchars($solicitud['tipo_cliente'] === 'Otro' ? $solicitud['tipo_cliente_otro'] : $solicitud['tipo_cliente']) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 block border-b pb-2">Gestión
                de CRM</h3>
            <form method="post" action="update_estado.php" class="flex items-center gap-3">
                <input type="hidden" name="id" value="<?= $solicitud['id'] ?>">
                <select name="estado"
                    class="w-full text-xs font-bold px-4 py-3 rounded-xl border border-gray-200 outline-none focus:border-violet-500 transition-all bg-gray-50">
                    <?php foreach (['Pendiente', 'Contactado', 'Cerrado'] as $e): ?>
                        <option value="<?= $e ?>" <?= $solicitud['estado'] == $e ? 'selected' : '' ?>>
                            <?= $e ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit"
                    class="bg-gray-900 text-white px-4 py-3 rounded-xl font-black text-xs uppercase hover:bg-violet-600 transition-colors">
                    Actualizar
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Productos -->
    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm h-full flex flex-col">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6 block border-b pb-2">
                Productos Destacados como Interés</h3>

            <?php if (empty($productos)): ?>
                <div class="flex-grow flex flex-col justify-center items-center py-12">
                    <span class="text-4xl mb-4 opacity-50">📦</span>
                    <p class="text-sm font-black text-gray-400 uppercase tracking-widest text-center max-w-xs">El cliente no
                        especificó ningún producto en particular en el formulario interactivo.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($productos as $item):
                        $img = !empty($item['foto_principal']) ? '/uploads/' . $item['foto_principal'] : '/assets/img/logo.png';
                        ?>
                        <div
                            class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors">
                            <img src="<?= htmlspecialchars($img) ?>" alt=""
                                class="w-12 h-12 object-cover rounded-lg bg-gray-100 shadow-sm shrink-0">
                            <div class="flex-grow">
                                <h4 class="font-bold text-gray-900 line-clamp-1">
                                    <?= htmlspecialchars($item['titulo']) ?>
                                </h4>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-0.5">Código:
                                    <?= htmlspecialchars($item['codigo'] ?: 'N/A') ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
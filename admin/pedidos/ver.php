<?php
require __DIR__ . '/../../includes/db.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if (!$pedido) {
    header('Location: index.php');
    exit;
}

$items = json_decode($pedido['carrito'], true) ?: [];

$adminTitle = 'Detalle de Consulta #' . $id;
require __DIR__ . '/../includes/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Información del Cliente -->
    <div class="lg:col-span-1 space-y-8">
        <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm space-y-8">
            <h2 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span class="text-2xl">👤</span> Datos del Cliente
            </h2>

            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Nombre y
                        Apellido</label>
                    <p class="font-black text-gray-900 text-xl leading-tight"><?= htmlspecialchars($pedido['nombre']) ?>
                    </p>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Email de
                        Contacto</label>
                    <p class="font-bold text-gray-600"><?= htmlspecialchars($pedido['email'] ?: 'No proporcionado') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Localidad /
                        Ciudad</label>
                    <p class="font-black text-gray-900 text-lg leading-tight">
                        <?= htmlspecialchars($pedido['localidad'] ?: 'No proporcionada') ?>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Fecha y
                        Hora</label>
                    <p class="font-bold text-gray-600"><?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>
                </div>

                <div class="pt-4">
                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', get_ajuste('whatsapp_nro')) ?>?text=Hola%20<?= urlencode($pedido['nombre']) ?>%2C%20te%20contacto%20desde%20Laserdreams%20por%20tu%20consulta%20del%20<?= date('d/m', strtotime($pedido['created_at'])) ?>"
                        target="_blank"
                        class="w-full bg-green-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-green-600 transition-all shadow-xl shadow-green-500/20 flex items-center justify-center gap-3">
                        <span class="text-lg">💬</span> Contactar por WhatsApp
                    </a>
                </div>
            </div>
        </div>

        <?php if ($pedido['requiere_factura']): ?>
            <div class="bg-violet-50 p-10 rounded-[40px] border border-violet-100 shadow-sm space-y-6">
                <h2 class="text-lg font-black text-violet-900 tracking-tight flex items-center gap-2">
                    <span>📋</span> Datos de Facturación
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-violet-400 mb-1">Razón
                            Social</label>
                        <p class="font-bold text-violet-900"><?= htmlspecialchars($pedido['razon_social']) ?></p>
                    </div>
                    <div>
                        <label
                            class="block text-[9px] font-black uppercase tracking-widest text-violet-400 mb-1">CUIT</label>
                        <p class="font-bold text-violet-900"><?= htmlspecialchars($pedido['cuit']) ?></p>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-violet-400 mb-1">Tipo de
                            Factura</label>
                        <p class="font-black text-violet-900 text-xl">Factura
                            <?= htmlspecialchars($pedido['tipo_factura']) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-red-600 p-10 rounded-[40px] shadow-2xl space-y-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-10 text-6xl">💰</div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-red-200 relative z-10">Presupuesto
                Estimado</p>
            <div class="flex flex-col relative z-10">
                <span
                    class="text-4xl font-black text-white tracking-tighter">$<?= number_format($pedido['total'], 0, ',', '.') ?></span>
                <?php if ($pedido['requiere_factura']): ?>
                    <span class="text-[10px] font-bold text-red-200 uppercase tracking-widest mt-2 flex items-center gap-2">
                        <span>Incluye IVA ($<?= number_format($pedido['iva_aplicado'], 0, ',', '.') ?>)</span>
                    </span>
                <?php else: ?>
                    <span class="text-[10px] font-bold text-red-200 uppercase tracking-widest mt-2">Sujeto a cambios - NO
                        INCLUYE IVA</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="pt-4">
            <a href="eliminar.php?id=<?= $pedido['id'] ?>"
                onclick="return confirm('¿Eliminar esta consulta permanentemente?')"
                class="w-full bg-red-50 text-red-500 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all text-center block">
                ⚠️ Eliminar Registro
            </a>
        </div>
    </div>

    <!-- Desglose de Productos -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100">
                <h2 class="text-lg font-black text-gray-900 tracking-tight uppercase">Productos en la Consulta</h2>
            </div>
            <div class="divide-y divide-gray-50">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Producto</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Cantidad</th>
                            <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Embalaje</th>
                            <th
                                class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                                Precio Ref.</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($items as $i): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="p-8">
                                    <div class="flex items-center gap-6">
                                        <div
                                            class="w-16 h-16 rounded-2xl overflow-hidden shadow-sm border border-gray-100 shrink-0">
                                            <img src="<?= htmlspecialchars($i['imagen']) ?>"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <h3 class="font-black text-gray-900 text-base"><?= htmlspecialchars($i['titulo']) ?>
                                        </h3>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <span
                                        class="w-8 h-8 flex items-center justify-center bg-gray-900 text-xs font-black text-white rounded-lg"><?= $i['cantidad'] ?></span>
                                </td>
                                <td class="px-8 py-4">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-gray-500"><?= htmlspecialchars($i['tipo_bulto'] ?? 'S/D') ?></span>
                                </td>
                                <td class="px-8 py-4 text-right">
                                    <span
                                        class="font-black text-violet-600 text-lg">$<?= number_format($i['precio'], 0, ',', '.') ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
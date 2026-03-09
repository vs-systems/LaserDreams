<?php
require __DIR__ . '/../../includes/db.php';

$pedidos = $pdo->query("SELECT * FROM solicitudes_mayoristas ORDER BY fecha DESC")->fetchAll();

$adminTitle = 'Solicitudes Mayoristas';
require __DIR__ . '/../includes/header.php';
?>

<div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Fecha /
                        Referencia</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Cliente y
                        Contacto</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Tipo de Negocio
                    </th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Interés</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Estado CRM</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                        Detalle</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($pedidos)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-32 text-center">
                            <span class="text-6xl mb-6 block">🏢</span>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No hay solicitudes
                                registradas aún</p>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($pedidos as $p):
                    $items = json_decode($p['productos_interes'], true) ?: [];
                    $fecha = date('d/m/Y H:i', strtotime($p['fecha']));
                    ?>
                    <tr class="hover:bg-gray-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-violet-600 mb-1">MAY-
                                    <?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?>
                                </span>
                                <span class="text-xs font-bold text-gray-400">
                                    <?= $fecha ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span
                                    class="font-black text-gray-900 tracking-tight transition-colors group-hover:text-violet-600">
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                    <?= htmlspecialchars($p['localidad']) ?>
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Tel:
                                    <?= htmlspecialchars($p['telefono']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-[10px] font-black uppercase tracking-widest rounded-lg">
                                <?= htmlspecialchars($p['tipo_cliente'] === 'Otro' ? $p['tipo_cliente_otro'] : $p['tipo_cliente']) ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <?php if (count($items) > 0): ?>
                                <span class="text-[10px] font-black text-violet-600 uppercase tracking-widest">
                                    <?= count($items) ?> Productos listados
                                </span>
                            <?php else: ?>
                                <span class="text-[10px] font-bold text-gray-400">Sin especificar</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6">
                            <form method="post" action="update_estado.php">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <select name="estado" onchange="this.form.submit()"
                                    class="text-[9px] font-black uppercase tracking-widest px-4 py-2 rounded-xl border-none outline-none focus:ring-4 focus:ring-violet-500/10 shadow-sm cursor-pointer transition-all
                                    <?= $p['estado'] === 'Pendiente' ? 'bg-yellow-100 text-yellow-700' :
                                        ($p['estado'] === 'Contactado' ? 'bg-blue-100 text-blue-700' :
                                            ($p['estado'] === 'Cerrado' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600')) ?>">
                                    <?php foreach (['Pendiente', 'Contactado', 'Cerrado'] as $e): ?>
                                        <option value="<?= $e ?>" <?= $p['estado'] == $e ? 'selected' : '' ?>>
                                            <?= $e ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="ver.php?id=<?= $p['id'] ?>"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-violet-500 hover:text-white transition-all shadow-xl shadow-gray-200">
                                    Detalle <span>→</span>
                                </a>
                                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'Sistemas'): ?>
                                    <a href="eliminar.php?id=<?= $p['id'] ?>"
                                        onclick="return confirm('¿Eliminar esta solicitud vinculada Permanentemente?')"
                                        class="p-2.5 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                        🗑️
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
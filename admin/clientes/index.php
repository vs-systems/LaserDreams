<?php
require __DIR__ . '/../../includes/db.php';

// Obtener datos de clientes de los pedidos
// Agrupamos por email o teléfono si estuvieran disponibles, pero usaremos el nombre y localidad como base.
$stmt = $pdo->query("
    SELECT 
        nombre, 
        max(localidad) as localidad, 
        max(email) as email,
        max(created_at) as ultimo_pedido,
        count(id) as cantidad_consultas,
        group_concat(estado) as estados,
        group_concat(carrito) as carritos
    FROM pedidos 
    GROUP BY nombre 
    ORDER BY ultimo_pedido DESC
");
$clientes = $stmt->fetchAll();

$adminTitle = 'Datos de Clientes';
require __DIR__ . '/../includes/header.php';
?>

<div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-lg font-black text-gray-900 tracking-tight uppercase">Base de Clientes (CRM)</h2>
        <button onclick="window.print()"
            class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-violet-600 transition-colors">
            🖨️ Imprimir / PDF
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Cliente</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Contacto</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Ubicación</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Estado Compra
                    </th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Intereses
                        (Productos)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <span class="text-6xl mb-6 block">👥</span>
                            <p class="text-sm font-black text-gray-400 uppercase tracking-widest">No hay clientes
                                registrados aún</p>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($clientes as $c):
                    // Determinar si compró
                    $estados = explode(',', $c['estados']);
                    $compro = in_array('Confirmado', $estados) || in_array('Enviado', $estados);

                    // Extraer nombres de productos únicos
                    $carritos_json = explode('],[', trim($c['carritos'], '[]'));
                    $productos_interes = [];
                    foreach ($carritos_json as $json_str) {
                        $json_str = '[' . trim($json_str, '[]') . ']';
                        $items = json_decode($json_str, true);
                        if (is_array($items)) {
                            foreach ($items as $item) {
                                if (isset($item['titulo'])) {
                                    $productos_interes[$item['titulo']] = true;
                                }
                            }
                        }
                    }
                    $lista_productos = array_keys($productos_interes);
                    ?>
                    <tr class="hover:bg-gray-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <span class="font-black text-gray-900 text-base">
                                <?= htmlspecialchars($c['nombre']) ?>
                            </span>
                            <span class="block text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">
                                <?= $c['cantidad_consultas'] ?> consultas
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-2">
                                <span class="text-xs font-bold text-gray-600 block">
                                    <?= htmlspecialchars($c['email'] ?: 'S/D') ?>
                                </span>
                                <input type="text" placeholder="WhatsApp manual..."
                                    class="text-xs px-3 py-1.5 bg-gray-50 border border-gray-200 rounded text-gray-600 outline-none focus:border-violet-500 w-full max-w-[150px]">
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-bold text-gray-600 uppercase tracking-widest">
                                <?= htmlspecialchars($c['localidad'] ?: 'S/D') ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <?php if ($compro): ?>
                                <span
                                    class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Compró</span>
                            <?php else: ?>
                                <span
                                    class="bg-gray-100 text-gray-500 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">Consulta</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-wrap gap-1 max-w-xs">
                                <?php foreach (array_slice($lista_productos, 0, 3) as $prod): ?>
                                    <span
                                        class="text-[9px] font-black text-violet-600 bg-violet-50 px-2 py-0.5 rounded border border-violet-100 line-clamp-1 truncate block max-w-[150px]"
                                        title="<?= htmlspecialchars($prod) ?>">
                                        <?= htmlspecialchars($prod) ?>
                                    </span>
                                <?php endforeach; ?>
                                <?php if (count($lista_productos) > 3): ?>
                                    <span
                                        class="text-[9px] font-black text-gray-500 px-2 py-0.5 rounded border border-gray-200">+
                                        <?= count($lista_productos) - 3 ?>
                                    </span>
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
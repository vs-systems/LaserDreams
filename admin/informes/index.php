<?php
require __DIR__ . '/../../includes/db.php';

// --- CONSULTAS PARA MÉTRICAS ---
$totalProductos = $pdo->query("SELECT COUNT(*) FROM productos WHERE activo = 1")->fetchColumn();
$totalConsultas = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$pedidosConfirmados = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'Confirmado'")->fetchColumn();
$totalVisitas = $pdo->query("SELECT SUM(visitas) FROM productos")->fetchColumn() ?: 0;

// Nuevas Metricas CRM y Mayoristas
try {
    $totalMayoristasPendientes = $pdo->query("SELECT COUNT(*) FROM solicitudes_mayoristas WHERE estado = 'Pendiente'")->fetchColumn();
    $totalDescargasLista = $pdo->query("SELECT COUNT(*) FROM descargas_listas")->fetchColumn();
    $pedidosPendientes = $pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'Nuevo'")->fetchColumn();
    $ultimasDescargas = $pdo->query("SELECT * FROM descargas_listas ORDER BY fecha DESC LIMIT 5")->fetchAll();
} catch (PDOException $e) {
    // If table doesn't exist yet/migration not run
    $totalMayoristasPendientes = 0;
    $totalDescargasLista = 0;
    $pedidosPendientes = 0;
    $ultimasDescargas = [];
}

$productosVisitas = $pdo->query("SELECT titulo, visitas FROM productos WHERE activo = 1 ORDER BY visitas DESC LIMIT 5")->fetchAll();

// 2. Estados de Pedido (Anillo/Doughnut)
$orderStats = $pdo->query("
    SELECT estado, COUNT(*) as total 
    FROM pedidos 
    GROUP BY estado
")->fetchAll();

// 3. Productos por Categoría (Doughnut)
$catStats = $pdo->query("
    SELECT c.nombre, COUNT(p.id) as total 
    FROM categorias c
    LEFT JOIN productos p ON c.id = p.categoria_id AND p.activo = 1
    GROUP BY c.id
    HAVING total > 0
")->fetchAll();

// 4. Productos por Marca (NUEVO - Anillo)
$marcaStats = $pdo->query("
    SELECT m.nombre, COUNT(p.id) as total
    FROM marcas m
    LEFT JOIN productos p ON m.id = p.marca_id AND p.activo = 1
    GROUP BY m.id
    HAVING total > 0
")->fetchAll();

$adminTitle = '📊 Informes y Estadísticas';
require __DIR__ . '/../includes/header.php';

// Obtener la cotización aplicada para mostrar en esta pantalla
$cot_sanyi = $GLOBALS['cotizacion_aplicada'] ?? 0;
$cot_bigdipper = $GLOBALS['dolar_oficial_base'] ?? 0;
?>

<!-- Cotizaciones de Dólares -->
<div class="mb-8 flex flex-col sm:flex-row gap-4">
    <div
        class="bg-blue-900 border border-blue-800 text-white p-4 rounded-2xl flex-1 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-[10px] font-black uppercase tracking-widest text-blue-300">Dólar SANYI</h3>
            <p class="text-xs text-blue-200 mt-0.5">Dólar Blue (Venta) + $15</p>
        </div>
        <div class="text-2xl font-black">$<?= number_format($cot_sanyi, 2, ',', '.') ?></div>
    </div>

    <div
        class="bg-emerald-900 border border-emerald-800 text-white p-4 rounded-2xl flex-1 shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-[10px] font-black uppercase tracking-widest text-emerald-300">Dólar Bigdipper</h3>
            <p class="text-xs text-emerald-200 mt-0.5">Dólar Oficial del BNA (Venta)</p>
        </div>
        <div class="text-2xl font-black">$<?= number_format($cot_bigdipper, 2, ',', '.') ?></div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- ... (Grid de métricas se mantiene igual) ... -->

<!-- CRM Box y Descargas de Lista -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 rounded-full -mr-16 -mt-16"></div>
        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
            CRM: Control de Atención
        </h3>
        <div class="space-y-4">
            <div
                class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border <?= $pedidosPendientes > 0 ? 'border-red-200' : 'border-gray-100' ?>">
                <span class="text-sm font-bold text-gray-700">Consultas (Pedidos) Sin Atender</span>
                <span
                    class="text-xl font-black <?= $pedidosPendientes > 0 ? 'text-red-600' : 'text-green-600' ?>"><?= $pedidosPendientes ?></span>
            </div>
            <div
                class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl border <?= $totalMayoristasPendientes > 0 ? 'border-red-200' : 'border-gray-100' ?>">
                <span class="text-sm font-bold text-gray-700">Solicitudes Mayoristas Sin Atender</span>
                <span
                    class="text-xl font-black <?= $totalMayoristasPendientes > 0 ? 'text-red-600' : 'text-green-600' ?>"><?= $totalMayoristasPendientes ?></span>
            </div>

            <?php if ($pedidosPendientes == 0 && $totalMayoristasPendientes == 0): ?>
                <div class="text-center mt-6">
                    <span class="text-3xl block mb-2">🎉</span>
                    <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Todo el CRM está al día</p>
                </div>
            <?php else: ?>
                <div class="text-center mt-6">
                    <span class="text-3xl block mb-2">⚠️</span>
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500">Hay clientes esperando contacto
                        rápido</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-violet-50 rounded-full -mr-16 -mt-16"></div>
        <div class="flex justify-between items-center mb-6 relative z-10">
            <h3 class="text-lg font-black text-gray-900 flex items-center gap-2">
                Descargas de Lista Precios
            </h3>
            <span
                class="bg-violet-100 text-violet-700 font-black text-sm px-4 py-1.5 rounded-xl"><?= $totalDescargasLista ?>
                Totales</span>
        </div>

        <?php if (count($ultimasDescargas) > 0): ?>
            <div class="space-y-2 mt-4 relative z-10">
                <?php foreach ($ultimasDescargas as $d): ?>
                    <div
                        class="flex flex-col sm:flex-row justify-between sm:items-center p-3 hover:bg-gray-50 rounded-xl transition-colors border-b border-gray-50 last:border-0">
                        <div>
                            <span class="font-bold text-sm text-gray-900 block"><?= htmlspecialchars($d['nombre']) ?></span>
                            <span
                                class="text-[10px] font-black text-gray-400 tracking-widest uppercase"><?= htmlspecialchars($d['email']) ?></span>
                        </div>
                        <div class="flex flex-col sm:items-end mt-2 sm:mt-0">
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $d['whatsapp']) ?>" target="_blank"
                                class="text-green-600 font-bold text-xs hover:underline flex items-center gap-1">
                                💬 <?= htmlspecialchars($d['whatsapp']) ?>
                            </a>
                            <span
                                class="text-[9px] text-gray-400 uppercase tracking-widest mt-1"><?= date('d/m/Y H:i', strtotime($d['fecha'])) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8 relative z-10">
                <span class="text-4xl block mb-2 opacity-50">📄</span>
                <p class="text-xs font-bold text-gray-400">Nadie ha descargado la lista aún</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
    <!-- Distribución por Categoría -->
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-400 mb-8 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-violet-500"></span>
            Productos por Categoría
        </h3>
        <div class="aspect-square max-w-[300px] mx-auto">
            <canvas id="chartCategorias"></canvas>
        </div>
    </div>

    <!-- Estado de Pedidos (NUEVO) -->
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-400 mb-8 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
            Estado de Pedidos
        </h3>
        <div class="aspect-square max-w-[300px] mx-auto">
            <canvas id="chartEstados"></canvas>
        </div>
    </div>

    <!-- Productos por Marca (NUEVO) -->
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-400 mb-8 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-pink-500"></span>
            Productos por Marca
        </h3>
        <div class="aspect-square max-w-[300px] mx-auto">
            <canvas id="chartMarcas"></canvas>
        </div>
    </div>

    <!-- Ranking de Visitas (Curva) -->
    <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-black uppercase tracking-widest text-gray-400 mb-8 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
            Tendencia de Visitas por Producto
        </h3>
        <div class="h-[300px]">
            <canvas id="chartVisitas"></canvas>
        </div>
    </div>
</div>

<script>
    // Configuración de Gráfico de Categorías (Donut)
    const ctxCat = document.getElementById('chartCategorias').getContext('2d');
    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($catStats, 'nombre')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($catStats, 'total')) ?>,
                backgroundColor: ['#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899', '#6366F1'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold', size: 10 } } }
            }
        }
    });

    // Gráfico de Marcas (Anillo)
    const ctxMar = document.getElementById('chartMarcas').getContext('2d');
    new Chart(ctxMar, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($marcaStats, 'nombre')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($marcaStats, 'total')) ?>,
                backgroundColor: ['#EC4899', '#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                borderWidth: 0,
                cutout: '70%'
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold', size: 10 } } }
            }
        }
    });

    // Gráfico de Estados de Pedido (Doughnut)
    const ctxEst = document.getElementById('chartEstados').getContext('2d');
    const estadoColores = {
        'Nuevo': '#FACC15',       // Amarillo
        'Cotizado': '#F97316',    // Naranja
        'Confirmado': '#10B981',  // Verde
        'En Producción': '#FB923C', // Naranja Fluo/Llamat
        'Enviado': '#059669',     // Verde oscuro
        'Cancelado': '#EF4444'    // Rojo
    };

    const estadosData = <?= json_encode($orderStats) ?>;
    new Chart(ctxEst, {
        type: 'doughnut',
        data: {
            labels: estadosData.map(d => d.estado),
            datasets: [{
                data: estadosData.map(d => d.total),
                backgroundColor: estadosData.map(d => estadoColores[d.estado] || '#CBD5E1'),
                borderWidth: 0,
                cutout: '65%'
            }]
        },
        options: {
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold', size: 10 } } }
            }
        }
    });

    // Gráfico de Visitas (Línea / Curva)
    const ctxVis = document.getElementById('chartVisitas').getContext('2d');
    new Chart(ctxVis, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($productosVisitas, 'titulo')) ?>,
            datasets: [{
                label: 'Visitas',
                data: <?= json_encode(array_column($productosVisitas, 'visitas')) ?>,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#3B82F6'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

<?php require __DIR__ . '/../includes/footer.php'; ?>
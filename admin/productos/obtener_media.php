<?php
require __DIR__ . '/../../includes/db.php';

if (!isset($_GET['id']))
    exit;

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT foto_principal, foto_2, foto_3, foto_4, video, manual_tecnico FROM productos WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product)
    exit;

$media = [
    'foto_principal' => ['label' => 'Foto Principal', 'tipo' => 'imagen', 'val' => $product['foto_principal']],
    'foto_2' => ['label' => 'Foto 2', 'tipo' => 'imagen', 'val' => $product['foto_2']],
    'foto_3' => ['label' => 'Foto 3', 'tipo' => 'imagen', 'val' => $product['foto_3']],
    'foto_4' => ['label' => 'Foto 4', 'tipo' => 'imagen', 'val' => $product['foto_4']],
    'video' => ['label' => 'Video', 'tipo' => 'video', 'val' => $product['video']],
    'manual_tecnico' => ['label' => 'Manual Técnico', 'tipo' => 'pdf', 'val' => $product['manual_tecnico']],
];

foreach ($media as $columna => $data):
    if (empty($data['val']))
        continue;
    $archivo = htmlspecialchars($data['val']);
    $ruta = '/uploads/productos/' . $archivo;
    ?>
    <div class="relative group bg-gray-50 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow aspect-square flex flex-col items-center justify-center card-media"
        data-columna="<?= $columna ?>">
        <?php if ($data['tipo'] === 'video'): ?>
            <video src="<?= $ruta ?>" class="w-full h-full object-cover" controls muted></video>
        <?php elseif ($data['tipo'] === 'pdf'): ?>
            <div class="text-5xl mb-2 text-red-500">📄</div>
            <a href="<?= $ruta ?>" target="_blank" class="text-xs font-bold text-violet-600 underline">Ver PDF</a>
        <?php else: ?>
            <img src="<?= $ruta ?>"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500">
        <?php endif; ?>

        <!-- Gradiente superpuesto -->
        <div
            class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        </div>

        <!-- Acciones on hover -->
        <div
            class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex gap-2">
            <button type="button" onclick="eliminarMedia('<?= $columna ?>')"
                class="w-8 h-8 bg-white/10 backdrop-blur border border-white/20 text-white rounded-xl flex items-center justify-center hover:bg-red-500 hover:border-red-500 transition-all shadow-lg">
                <span class="text-sm">🗑️</span>
            </button>
        </div>

        <!-- Etiqueta inferior -->
        <div class="absolute bottom-0 left-0 right-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <span
                class="block text-[9px] font-black tracking-widest uppercase text-white/50 mb-0.5 truncate"><?= $archivo ?></span>
            <span class="block text-xs font-black text-white"><?= $data['label'] ?></span>
        </div>
    </div>
<?php endforeach; ?>
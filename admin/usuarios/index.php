<?php
require_once __DIR__ . '/../../includes/db.php';

try {
    $usuarios = $pdo->query("SELECT * FROM usuarios ORDER BY rol ASC, nombre ASC")->fetchAll();
} catch (Exception $e) {
    die("Error al consultar usuarios: " . $e->getMessage());
}

$adminTitle = 'Gestión de Usuarios';
require_once __DIR__ . '/../includes/header.php';
?>

<div
    class="mb-10 flex justify-between items-center bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm transition-all hover:shadow-md">
    <div>
        <h2 class="text-xl font-black text-gray-900 tracking-tight">Personal del Sistema</h2>
        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mt-1">Gestión de roles y accesos
            técnicos</p>
    </div>
    <a href="crear.php"
        class="bg-gray-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-violet-500 hover:text-black transition-all shadow-xl shadow-gray-200 flex items-center gap-3 active:scale-95">
        <span class="text-lg">👤</span> Nuevo Miembro
    </a>
</div>

<div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Usuario</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Rol</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Creado</th>
                    <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                        Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                <?php foreach ($usuarios as $u):
                    $isSistemas = ($u['rol'] === 'Sistemas');
                    ?>
                    <tr class="hover:bg-gray-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <span
                                class="font-bold text-gray-900 group-hover:text-violet-600 transition-colors"><?= htmlspecialchars($u['nombre']) ?></span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-xs font-medium text-gray-500"><?= htmlspecialchars($u['usuario']) ?></span>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-[0.1em] 
                                <?= $isSistemas ? 'bg-purple-100 text-purple-700' : ($u['rol'] === 'Administrador' ? 'bg-blue-100 text-blue-700' : 'bg-gray-50 text-gray-500') ?>">
                                <?= $u['rol'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-gray-400">
                                <?= !empty($u['created_at']) ? date('d/m/Y', strtotime($u['created_at'])) : 'Pendiente' ?>
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3 transition-all">
                                <a href="editar.php?id=<?= $u['id'] ?>"
                                    class="p-2.5 bg-gray-50 hover:bg-violet-500 hover:text-black rounded-xl transition-all"
                                    title="Editar">✏️</a>
                                <?php if (!$isSistemas): ?>
                                    <a href="eliminar.php?id=<?= $u['id'] ?>" onclick="return confirm('¿Eliminar usuario?')"
                                        class="p-2.5 bg-gray-50 hover:bg-red-500 hover:text-white rounded-xl transition-all"
                                        title="Eliminar">🗑️</a>
                                <?php else: ?>
                                    <span class="p-2.5 bg-gray-50 text-gray-300 rounded-xl cursor-not-allowed"
                                        title="Protegido">🔒</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<?php
require __DIR__ . '/../../includes/db.php';

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nombre ASC")->fetchAll();

$adminTitle = 'Familias / Categorías';
require __DIR__ . '/../includes/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Formulario Nueva Categoría -->
    <div class="lg:col-span-1 border-r border-gray-100 pr-0 lg:pr-10 lg:border-r">
        <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm sticky top-32">
            <h2 class="text-xl font-black text-gray-900 mb-6">Nueva Categoría</h2>
            <form action="guardar.php" method="POST" class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Nombre de
                        categoría</label>
                    <input type="text" name="nombre" placeholder="Ej: Sillones, Dormitorio..." required
                        class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-transparent focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all outline-none font-bold text-gray-900">
                </div>
                <button type="submit"
                    class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-violet-600 transition-all shadow-xl shadow-gray-200 active:scale-95">
                    Agregar Categoría
                </button>
            </form>
        </div>
    </div>

    <!-- Listado -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Categoría
                        </th>
                        <th
                            class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">
                            Estado</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center text-gray-400 font-bold">No hay categorías.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($categorias as $c): ?>
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="font-black text-gray-900 text-base">
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <?php if ($c['estado']): ?>
                                    <span
                                        class="bg-green-100 text-green-700 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-green-200">ACTIVA</span>
                                <?php else: ?>
                                    <span
                                        class="bg-red-50 text-red-500 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-red-100">INACTIVA</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <?php if ($c['estado']): ?>
                                    <a href="estado.php?id=<?= $c['id'] ?>&accion=desactivar"
                                        class="text-[10px] font-black uppercase tracking-widest text-red-500 hover:text-red-700 bg-red-50 px-4 py-2 rounded-xl transition-all">DESACTIVAR</a>
                                <?php else: ?>
                                    <a href="estado.php?id=<?= $c['id'] ?>&accion=activar"
                                        class="text-[10px] font-black uppercase tracking-widest text-green-600 hover:text-green-800 bg-green-50 px-4 py-2 rounded-xl transition-all">ACTIVAR</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
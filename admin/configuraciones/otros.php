<?php
require __DIR__ . '/../../includes/db.php';

$adminTitle = 'Configuraciones del Sistema';
require __DIR__ . '/../includes/header.php';
?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

    <!-- Tarjeta: Categorías -->
    <a href="categorias.php"
        class="group bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-violet-100/50 transition-all duration-300 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500">
        </div>
        <div class="relative">
            <div class="text-3xl mb-4">🏷️</div>
            <h3
                class="text-xl font-black text-gray-900 mb-2 group-hover:text-violet-600 transition-colors uppercase tracking-tight">
                Categorías</h3>
            <p class="text-gray-400 text-xs font-bold leading-relaxed mb-6 uppercase tracking-widest">Organización del
                Catálogo</p>
            <span
                class="inline-flex items-center text-violet-600 font-bold text-[10px] uppercase tracking-[0.2em] gap-2">Gestionar
                <span>→</span></span>
        </div>
    </a>

    <!-- Tarjeta: Colores -->
    <a href="colores.php"
        class="group bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-violet-100/50 transition-all duration-300 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500">
        </div>
        <div class="relative">
            <div class="text-3xl mb-4">🎨</div>
            <h3
                class="text-xl font-black text-gray-900 mb-2 group-hover:text-violet-600 transition-colors uppercase tracking-tight">
                Colores</h3>
            <p class="text-gray-400 text-xs font-bold leading-relaxed mb-6 uppercase tracking-widest">Variantes
                Cromáticas</p>
            <span
                class="inline-flex items-center text-violet-600 font-bold text-[10px] uppercase tracking-[0.2em] gap-2">Gestionar
                <span>→</span></span>
        </div>
    </a>

    <!-- Tarjeta: Telas -->
    <a href="telas.php"
        class="group bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-violet-100/50 transition-all duration-300 relative overflow-hidden">
        <div
            class="absolute top-0 right-0 w-24 h-24 bg-violet-50 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-500">
        </div>
        <div class="relative">
            <div class="text-3xl mb-4">🧶</div>
            <h3
                class="text-xl font-black text-gray-900 mb-2 group-hover:text-violet-600 transition-colors uppercase tracking-tight">
                Telas</h3>
            <p class="text-gray-400 text-xs font-bold leading-relaxed mb-6 uppercase tracking-widest">Muestrario de
                Textiles</p>
            <span
                class="inline-flex items-center text-violet-600 font-bold text-[10px] uppercase tracking-[0.2em] gap-2">Gestionar
                <span>→</span></span>
        </div>
    </a>

</div>

<div class="mt-12 bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
    <h2 class="text-xl font-black text-gray-900 mb-4 tracking-tight">Ajustes Generales</h2>
    <p class="text-gray-400 text-sm font-medium mb-8">Esta sección está preparada para futuras actualizaciones de
        configuración global (teléfono de WhatsApp, horarios, etc.).</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 opacity-40 grayscale pointer-events-none">
        <div class="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-200">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Próximamente</span>
            <div class="h-4 bg-gray-200 rounded w-1/2 mt-2"></div>
        </div>
        <div class="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-200">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Próximamente</span>
            <div class="h-4 bg-gray-200 rounded w-1/3 mt-2"></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
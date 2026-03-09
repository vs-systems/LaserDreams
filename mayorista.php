<?php
$is_home = false;
$titulo_pagina = 'Venta Mayorista';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/header.php';

// Obtener lista de productos para los checkboxes con fotos y datos para filtros
$stmt = $pdo->query("SELECT id, titulo, codigo, foto_principal, categoria_id, marca_id FROM productos WHERE activo = 1 ORDER BY titulo ASC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener categorías y marcas para los filtros
$categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
$marcas = $pdo->query("SELECT id, nombre FROM marcas ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <div class="mb-12 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Venta Mayorista</h1>
        <p class="text-lg text-gray-600">Completa el siguiente formulario para obtener beneficios exclusivos y precios
            al por mayor para tu negocio o evento.</p>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100">
        <form id="form-mayorista" class="space-y-8">
            <div id="msg-exito"
                class="hidden bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 font-bold text-center">
                ¡Gracias por tu solicitud! Nos pondremos en contacto a la brevedad.
            </div>
            <div id="msg-error"
                class="hidden bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 font-bold text-center">
            </div>

            <!-- Datos de Contacto -->
            <div>
                <h3 class="text-lg font-black text-gray-900 border-b pb-2 mb-4">Datos de Contacto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Nombre Completo
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nombre" required placeholder="Ej: Juan Pérez"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Teléfono /
                            WhatsApp <span class="text-red-500">*</span></label>
                        <input type="tel" name="telefono" required placeholder="Ej: 2235123456"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900">
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Localidad /
                            Provincia <span class="text-red-500">*</span></label>
                        <input type="text" name="localidad" required placeholder="Ej: Mar del Plata, Buenos Aires"
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900">
                    </div>
                </div>
            </div>

            <!-- Tipo de Cliente -->
            <div>
                <h3 class="text-lg font-black text-gray-900 border-b pb-2 mb-4">Perfil de Cliente <span
                        class="text-red-500">*</span></h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Usuario particular" required
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Usuario
                            particular</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Salón de eventos"
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Salón de
                            eventos</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Discoteca"
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Discoteca</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Club"
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Club</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Servicios de Alquiler"
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Servicios de
                            Alquiler</span>
                    </label>
                    <label
                        class="flex items-center gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all">
                        <input type="radio" name="tipo_cliente" value="Reventa"
                            class="w-5 h-5 text-violet-600 focus:ring-violet-500">
                        <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600">Reventa</span>
                    </label>
                    <label
                        class="flex items-start gap-3 cursor-pointer group p-3 border rounded-xl hover:border-violet-500 transition-all sm:col-span-3">
                        <input type="radio" name="tipo_cliente" value="Otro"
                            class="w-5 h-5 mt-1 text-violet-600 focus:ring-violet-500"
                            onchange="document.getElementById('div-otro').classList.toggle('hidden', !this.checked);">
                        <div class="w-full">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-violet-600 mb-2 block">Otro
                                (especificar)</span>
                            <div id="div-otro" class="hidden">
                                <input type="text" name="tipo_cliente_otro"
                                    placeholder="Detalle su tipo de negocio o uso..."
                                    class="w-full px-4 py-2 rounded-lg bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900 text-sm">
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Productos de Interés -->
            <div>
                <h3 class="text-lg font-black text-gray-900 border-b pb-2 mb-4">Productos de Interés</h3>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Filtrá y selecciona los
                    artículos que te interesan (Opcional)</p>

                <!-- Filtros -->
                <div class="flex flex-col sm:flex-row gap-3 mb-4">
                    <select id="filtro-categoria"
                        class="flex-1 px-4 py-2 rounded-xl bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900 text-sm">
                        <option value="">Todas las Categorías</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select id="filtro-marca"
                        class="flex-1 px-4 py-2 rounded-xl bg-gray-50 border-2 border-transparent focus:border-violet-500 transition-all outline-none font-bold text-gray-900 text-sm">
                        <option value="">Todas las Marcas</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="lista-productos"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 max-h-96 overflow-y-auto p-4 border rounded-2xl bg-gray-50">
                    <?php foreach ($productos as $p):
                        $img = !empty($p['foto_principal']) ? '/uploads/' . $p['foto_principal'] : '/assets/img/logo.png';
                        ?>
                        <label
                            class="item-producto flex items-center gap-3 cursor-pointer group p-2 border border-transparent hover:border-violet-200 rounded-xl transition-all"
                            data-categoria="<?= $p['categoria_id'] ?>" data-marca="<?= $p['marca_id'] ?>">
                            <input type="checkbox" name="productos_interes[]" value="<?= htmlspecialchars($p['id']) ?>"
                                class="w-5 h-5 rounded border-gray-300 text-violet-600 focus:ring-violet-500 shrink-0">

                            <img src="<?= htmlspecialchars($img) ?>" alt=""
                                class="w-12 h-12 object-cover rounded-lg shadow-sm shrink-0 bg-white">

                            <span
                                class="text-[11px] font-bold text-gray-700 group-hover:text-violet-600 leading-tight line-clamp-2">
                                <?= htmlspecialchars($p['titulo']) ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" id="btn-submit"
                class="w-full bg-violet-600 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-gray-900 hover:text-white transition-all shadow-xl shadow-violet-500/20 active:scale-95 flex items-center justify-center gap-3">
                Enviar Solicitud
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('form-mayorista').addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // Recopilar arrays de checkboxes (productos_interes)
        data.productos_interes = formData.getAll('productos_interes[]');

        // Ocultar campo de 'otro' si no está seleccionado
        if (data.tipo_cliente !== 'Otro') {
            data.tipo_cliente_otro = '';
        }

        const btn = document.getElementById('btn-submit');
        const msgExito = document.getElementById('msg-exito');
        const msgError = document.getElementById('msg-error');

        btn.disabled = true;
        btn.innerHTML = 'Enviando...';
        msgError.classList.add('hidden');
        msgExito.classList.add('hidden');

        try {
            const res = await fetch('/api/guardar_mayorista.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await res.json();

            if (json.success) {
                msgExito.classList.remove('hidden');
                form.reset();
                document.getElementById('div-otro').classList.add('hidden');
            } else {
                msgError.textContent = json.error || 'Ocurrió un error inesperado.';
                msgError.classList.remove('hidden');
            }
        } catch (error) {
            msgError.textContent = 'Error de conexión. Intentá nuevamente.';
            msgError.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Enviar Solicitud';
        }
    });

    // Manejar visibilidad manual interacciones directas JS
    const radios = document.querySelectorAll('input[name="tipo_cliente"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value !== 'Otro') {
                document.getElementById('div-otro').classList.add('hidden');
            }
        });
    });

    // Lógica de Filtros Combinados (Categoría y Marca)
    const filtroCat = document.getElementById('filtro-categoria');
    const filtroMarca = document.getElementById('filtro-marca');
    const itemsProducto = document.querySelectorAll('.item-producto');

    function aplicarFiltros() {
        const catValue = filtroCat.value;
        const marcaValue = filtroMarca.value;

        itemsProducto.forEach(item => {
            const itemCat = item.getAttribute('data-categoria');
            const itemMarca = item.getAttribute('data-marca');

            let matchCat = (catValue === '' || itemCat === catValue);
            let matchMarca = (marcaValue === '' || itemMarca === marcaValue);

            if (matchCat && matchMarca) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    filtroCat.addEventListener('change', aplicarFiltros);
    filtroMarca.addEventListener('change', aplicarFiltros);

</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
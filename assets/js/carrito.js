/**
 * CARRITO MG MUEBLES - LÓGICA CENTRALIZADA
 * 
 * Este script maneja el carrito usando localStorage y despacha eventos
 * para que la UI se mantenga sincronizada.
 */

const MG_CART_KEY = 'mg_muebles_cart';

window.MGCarrito = {
    get() {
        const cart = localStorage.getItem(MG_CART_KEY);
        return {
            items: cart ? JSON.parse(cart) : []
        };
    },

    save(items) {
        localStorage.setItem(MG_CART_KEY, JSON.stringify(items));
        this.emit('update');
    },

    add(product) {
        const cart = this.get();
        const existingIndex = cart.items.findIndex(item =>
            item.id === product.id && (item.color || '') === (product.color || '')
        );

        if (existingIndex !== -1) {
            cart.items[existingIndex].cantidad += (product.cantidad || 1);
        } else {
            cart.items.push({
                id: parseInt(product.id),
                titulo: product.titulo,
                precio: parseFloat(product.precio),
                imagen: product.imagen,
                color: product.color || '',
                url: product.url || '',
                cantidad: product.cantidad || 1
            });
        }

        this.save(cart.items);
        this.emit('add', product);
    },

    remove(id, color = '') {
        const cart = this.get();
        const items = cart.items.filter(item => !(item.id === id && item.color === color));
        this.save(items);
        this.emit('remove', { id, color });
    },

    updateCantidad(id, nuevaCantidad, color = '') {
        if (nuevaCantidad <= 0) {
            this.remove(id, color);
            return;
        }

        const cart = this.get();
        const index = cart.items.findIndex(item => item.id === id && item.color === color);

        if (index !== -1) {
            cart.items[index].cantidad = nuevaCantidad;
            this.save(cart.items);
        }
    },

    clear() {
        this.save([]);
        this.emit('clear');
    },

    count() {
        return this.get().items.reduce((acc, item) => acc + item.cantidad, 0);
    },

    emit(name, detail = {}) {
        document.dispatchEvent(new CustomEvent(`mg:carrito:${name}`, { detail }));
        this.updateBadge();
    },

    updateBadge() {
        const badge = document.getElementById('carrito-contador');
        if (badge) {
            badge.textContent = this.count();
        }
    }
};

// Exponer funciones globales para compatibilidad con botones HTML existentes
window.addToCartFromButton = function (btn) {
    MGCarrito.add({
        id: btn.dataset.id,
        titulo: btn.dataset.titulo,
        precio: btn.dataset.precio,
        imagen: btn.dataset.imagen,
        url: btn.dataset.url,
        color: btn.dataset.color || ''
    });

    // Feedback visual en el botón
    const originalContent = btn.innerHTML;
    const isSmall = btn.classList.contains('py-3'); // Identificar si es el del catálogo o de producto

    btn.innerHTML = '✅ Agregado';
    btn.classList.add('bg-green-600');
    btn.classList.remove('bg-gray-900');

    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.classList.remove('bg-green-600');
        btn.classList.add('bg-gray-900');
    }, 2000);

    // Toast de sistema
    showToast(`"${btn.dataset.titulo}" se agregó al carrito`);
};

function showToast(msg) {
    let toast = document.getElementById('mg-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'mg-toast';
        toast.className = 'fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] bg-gray-900 text-white px-8 py-4 rounded-2xl font-bold shadow-2xl transition-all opacity-0 translate-y-4 pointer-events-none border border-white/10';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.remove('opacity-0', 'translate-y-4');
    toast.classList.add('opacity-100', 'translate-y-0');

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4');
        toast.classList.remove('opacity-100', 'translate-y-0');
    }, 3000);
}

// Inicializar badge al cargar
document.addEventListener('DOMContentLoaded', () => {
    MGCarrito.updateBadge();
});

/* ================================= */
/* MINI CART – JS VANILLA (SAFE)     */
/* ================================= */

(function () {

    if (window.__MINICART_LOADED__) return;
    window.__MINICART_LOADED__ = true;

    const miniCart = document.getElementById('miniCart');
    const overlay = document.getElementById('miniCartOverlay');
    const itemsBox = document.getElementById('miniCartItems');
    const totalBox = document.getElementById('miniCartTotal');

    if (!miniCart) return;

    function getCart() {
        return JSON.parse(localStorage.getItem('cart')) || [];
    }

    function renderMiniCart() {
        const cart = getCart();
        itemsBox.innerHTML = '';

        if (!cart.length) {
            itemsBox.innerHTML = '<p class="empty">Tu carrito está vacío</p>';
            totalBox.textContent = '$0';
            return;
        }

        let total = 0;

        cart.forEach((item, i) => {
            total += item.precio * item.cantidad;

            itemsBox.innerHTML += `
                <div class="mini-item">
                    <img src="${item.imagen || ''}">
                    <div class="info">
                        <h4>${item.titulo}</h4>
                        ${item.color ? `<small>🎨 ${item.color}</small>` : ''}
                        <div class="mini-qty">
                            <button onclick="updateQty(${i},-1)">−</button>
                            <span>${item.cantidad}</span>
                            <button onclick="updateQty(${i},1)">+</button>
                            <button onclick="removeItem(${i})">🗑</button>
                        </div>
                    </div>
                </div>
            `;
        });

        totalBox.textContent = '$' + total.toLocaleString();
    }

    window.openMiniCart = function () {
        miniCart.classList.add('open');
        overlay.style.display = 'block';
        renderMiniCart();
    };

    function closeMiniCart() {
        miniCart.classList.remove('open');
        overlay.style.display = 'none';
    }

    overlay.addEventListener('click', closeMiniCart);
    document.getElementById('closeMiniCart')?.addEventListener('click', closeMiniCart);
    document.getElementById('continueShopping')?.addEventListener('click', closeMiniCart);

    window.updateQty = function (i, d) {
        const c = getCart();
        if (!c[i]) return;
        c[i].cantidad += d;
        if (c[i].cantidad <= 0) c.splice(i, 1);
        localStorage.setItem('cart', JSON.stringify(c));
        renderMiniCart();
    };

    window.removeItem = function (i) {
        const c = getCart();
        c.splice(i, 1);
        localStorage.setItem('cart', JSON.stringify(c));
        renderMiniCart();
    };

})();

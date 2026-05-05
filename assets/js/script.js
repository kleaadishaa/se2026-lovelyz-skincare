
// ─── LOCALSTORAGE HELPERS 
function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function getCartCount() {
    return getCart().reduce((sum, item) => sum + item.quantity, 0);
}

function updateCartBadge() {
    const badge = document.getElementById("cart-count");
    if (badge) badge.textContent = getCartCount();
}

// ─── ESCAPE HTML 
function escapeHTML(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}

// ─── TOGGLE DETAILS 
function toggleDetails(button) {
    const detailsDiv = button.nextElementSibling;
    if (!detailsDiv) return;

    if (detailsDiv.style.display === 'block') {
        detailsDiv.style.display = 'none';
        button.textContent = 'View Details';
    } else {
        detailsDiv.style.display = 'block';
        button.textContent = 'Hide Details';
    }
}

// ─── ADD TO CART 
function addToCart(productName, productPrice, button) {
    const card = button.closest('.product-card');
    if (!card) return;

    const quantityInput = card.querySelector('.quantity');
    const quantity = parseInt(quantityInput.value, 10);
    const maxStock = parseInt(quantityInput.dataset.max, 10);
    const productId = parseInt(card.dataset.id, 10);

    if (quantity < 1 || quantity > maxStock) {
        alert("Invalid quantity.");
        return;
    }

    const cart = getCart();
    const existing = cart.find(item => item.id === productId);

    if (existing) {
        const newQty = existing.quantity + quantity;
        if (newQty > maxStock) {
            alert(`You already have ${existing.quantity} in your cart. Can't exceed stock of ${maxStock}.`);
            return;
        }
        existing.quantity = newQty;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            maxStock: maxStock
        });
    }

    saveCart(cart);
    updateCartBadge();
    alert("Added to cart!");
}

// ─── CART MANIPULATION 
function changeQty(index, change) {
    const cart = getCart();
    const item = cart[index];
    if (!item) return;

    const newQty = item.quantity + change;

    if (newQty < 1) return;
    if (newQty > item.maxStock) {
        alert(`Max stock for "${item.name}" is ${item.maxStock}.`);
        return;
    }

    cart[index].quantity = newQty;
    saveCart(cart);
    renderCart();
}

function removeItem(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
    renderCart();
}

function clearCart() {
    if (confirm("Are you sure you want to clear your cart?")) {
        localStorage.removeItem('cart');
        renderCart();
    }
}

// ─── CHECKOUT FUNCTIONS 
async function proceedToCheckout() {
    const cart = getCart();
    if (cart.length === 0) { alert("Cart is empty"); return; }

    try {
        const res = await fetch('../api/save_order.php', {
            method: 'POST',
            headers: {'Content-Type':'application/json'},
            body: JSON.stringify({cart})
        });
        const data = await res.json();
        if (data.success) {
            window.location.href = 'checkout.html';
        } else {
            alert("Could not save order: " + (data.message || "Unknown error"));
        }
    } catch (err) {
        console.error(err);
        alert("Error connecting to server.");
    }
}

async function renderCheckout() {
    const ul = document.getElementById('cart-items');
    const totalEl = document.getElementById('total');

    if (!ul || !totalEl) return; // nuk jemi te checkout.html

    try {
        const res = await fetch('../api/checkout.php', { method: 'GET' });
        const data = await res.json();

        console.log('Checkout data:', data); // shiko ne F12

        if (!data.success) {
            ul.innerHTML = `<li style="color:red;">Error: ${data.message}</li>`;
            return;
        }

        ul.innerHTML = '';
        data.cartItems.forEach(item => {
            const li = document.createElement('li');
            li.textContent = `${item.name} — ${item.quantity} × ${parseFloat(item.price).toFixed(2)} € = ${parseFloat(item.total).toFixed(2)} €`;
            ul.appendChild(li);
        });

        totalEl.textContent = parseFloat(data.total).toFixed(2);

    } catch (err) {
        console.error('renderCheckout error:', err);
    }
}

async function payNow() {
    try {
        const res = await fetch('../api/checkout.php', { method: 'POST' });
        const data = await res.json();

        if (data.success) {
            localStorage.removeItem('cart'); 
            window.location.href = 'success.html';
        } else {
            alert("Payment failed.");
        }
    } catch (err) {
        console.error(err);
        alert("Error connecting to server.");
    }
}

// ─── RENDER CART 
function renderCart() {
    const cart = getCart();
    const container = document.getElementById('cart-content');
    if (!container) return;

    container.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = `<p style="text-align: center;">Your cart is empty.</p>`;
        updateCartBadge();
        return;
    }

    let total = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div class="cart-item-name">${escapeHTML(item.name)}</div>
            <div>Price: ${item.price.toFixed(2)} €</div>
            <div class="quantity-controls">
                <h4>Quantity:</h4>
                <button class="qty-minus">−</button>
                <span id="qty-${index}">${item.quantity}</span>
                <button class="qty-plus">+</button>
            </div>
            <div>Subtotal: <span id="sub-${index}">${subtotal.toFixed(2)} €</span></div>
            <button class="remove-button">Remove</button>
        `;
        container.appendChild(div);

        const minusBtn = div.querySelector('.qty-minus');
        const plusBtn = div.querySelector('.qty-plus');
        const removeBtn = div.querySelector('.remove-button');

        if (minusBtn) minusBtn.addEventListener('click', () => changeQty(index, -1));
        if (plusBtn) plusBtn.addEventListener('click', () => changeQty(index, 1));
        if (removeBtn) removeBtn.addEventListener('click', () => removeItem(index));
    });

    // Total and actions
    const actionsDiv = document.createElement('div');
    actionsDiv.innerHTML = `
        <div class="cart-total">
            <h2>Total: <span id="cart-total">${total.toFixed(2)} €</span></h2>
        </div>
        <div class="cart-actions">
            <button id="clear-cart-btn">Clear Cart</button>
            <button id="checkout-btn" class="checkout-btn">Proceed to Checkout →</button>
        </div>
    `;
    container.appendChild(actionsDiv);

    const clearBtn = document.getElementById('clear-cart-btn');
    const checkoutBtn = document.getElementById('checkout-btn');
    if (clearBtn) clearBtn.addEventListener('click', clearCart);
    if (checkoutBtn) checkoutBtn.addEventListener('click', proceedToCheckout);

    updateCartBadge();
}

document.addEventListener("DOMContentLoaded", () => {
    renderCart();
    updateCartBadge();
    renderCheckout();

    // Pay button
    const payBtn = document.getElementById('pay-btn');
    if (payBtn) payBtn.addEventListener('click', payNow);

    // Subscription form
const form = document.getElementById('subscriptionForm');
const msgDiv = document.getElementById('formMessage');

if (form && msgDiv) {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        msgDiv.innerHTML = "Submitting...";

        try {
            const res =await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            const text = await res.text();
            console.log("SERVER:", text);

            let data;
            try {
                data = JSON.parse(text);
            } catch {
                throw new Error("Invalid JSON response");
            }

            msgDiv.innerHTML = `
                <p style="color:${data.success ? 'green' : 'red'};">
                    ${data.message}
                </p>
            `;

            if (data.success) {
                form.reset();
                setTimeout(() => {
                    window.location.href = '../index.php';
                }, 2000);
            }

        } catch (err) {
            console.error(err);
            msgDiv.innerHTML = `<p style="color:red;">${err.message}</p>`;
        }
    });

    }
});
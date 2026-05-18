const token = localStorage.getItem('token');

// ─── OWL CAROUSEL SETTINGS ───────────────────────────────────────────────────
const owlSettings = {
    loop: true,
    margin: 10,
    nav: false,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    dots: false,
    responsive: {
        0:    { items: 1 },
        600:  { items: 3 },
        1000: { items: 5 }
    }
};

function buildCard(p) {
    return `
        <div class="item">
            <div class="card product-card" style="width: 19rem;" data-id="${p.product_id}">
                <img src="${p.image}" class="card-img-top" alt="${p.name}">
                <div class="card-body">
                    <h5 class="card-title">${p.name}</h5>
                    <p class="card-text">$${parseFloat(p.price).toFixed(2)}</p>
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                        <label style="font-size:0.8rem;">Qty:</label>
                        <input type="number" class="quantity" value="1" min="1" data-max="${p.stock}"
                            style="width:60px; padding:0.2rem 0.4rem; border:1px solid #ddd; border-radius:4px;">
                    </div>
                    <button class="cta" onclick="addToCart('${p.name.replace(/'/g, "\\'")}', ${p.price}, this)">
                        <span class="hover-underline-animation"> Add to Cart </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="10" viewBox="0 0 46 16">
                            <path d="M8,0,6.545,1.455l5.506,5.506H-30V9.039H12.052L6.545,14.545,8,16l8-8Z" transform="translate(30)"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// ─── LOAD PRODUCTS FROM API ───────────────────────────────────────────────────
async function loadProducts() {
    if (!token) return;

    try {
        const res = await fetch('api/products/get_products.php', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        const data = await res.json();
        if (!data.success) return;

        const categories = {
            'Cleansers': '#cleansers-carousel',
            'Suncream':  '#suncream-carousel',
            'Masks':     '#masks-carousel'
        };

        for (const [category, selector] of Object.entries(categories)) {
    const filtered = data.data.filter(p => p.category === category);
    if (filtered.length === 0) continue;

    $(selector).html(filtered.map(buildCard).join(''));

    setTimeout(() => {
        $(selector).owlCarousel({
            ...owlSettings,
            loop: filtered.length > 5, 
            autoplay: filtered.length > 5
        });
    }, 100);
}

    } catch (err) {
        console.error('Failed to load products:', err);
    }
}

// ─── ADD TO CART ──────────────────────────────────────────────────────────────
async function addToCart(name, price, btn) {
    if (!token) { alert('Please log in first.'); return; }

    const card = btn.closest('.product-card');
    const productId = card.dataset.id;
    const qtyInput = card.querySelector('.quantity');
    const quantity = parseInt(qtyInput.value) || 1;
    const maxStock = parseInt(qtyInput.dataset.max) || 99;

    if (quantity < 1 || quantity > maxStock) {
        alert(`Please enter a quantity between 1 and ${maxStock}.`);
        return;
    }

    try {
        const res = await fetch('api/cart/add_tto_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({product_id: productId,
                                  product_name: name,
                                  price: price,
                                  quantity: quantity})
        });

        const data = await res.json();
        alert(data.success ? `${name} added to cart!` : (data.message || 'Failed to add to cart.'));

    } catch (err) {
        console.error('Add to cart error:', err);
        alert('Something went wrong.');
    }
}

// ─── REVIEW SLIDER ────────────────────────────────────────────────────────────
let myProjects = [];
let slideIndex = 0;

function initializeSlider() {
    myProjects = Array.from(document.querySelectorAll('.reviewContent'));
    if (myProjects.length === 0) return;

    myProjects.forEach((slide, i) => {
        slide.style.display = i === 0 ? 'grid' : 'none';
        slide.classList.toggle('active', i === 0);
    });
}

function showSlide(index) {
    if (myProjects.length === 0) return;

    if (index < 0) slideIndex = myProjects.length - 1;
    else if (index >= myProjects.length) slideIndex = 0;
    else slideIndex = index;

    myProjects.forEach(slide => {
        slide.style.display = 'none';
        slide.classList.remove('active');
    });

    myProjects[slideIndex].style.display = 'grid';
    myProjects[slideIndex].classList.add('active');
}

function prevSlide() { showSlide(slideIndex - 1); }
function nextSlide() { showSlide(slideIndex + 1); }

// ─── SCROLL ANIMATION ─────────────────────────────────────────────────────────
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('show');
            observer.unobserve(entry.target);
        }
    });
});

// ─── INIT ON DOM READY ────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initializeSlider();
    loadProducts();
    document.querySelectorAll('.hidden').forEach(el => observer.observe(el));
});
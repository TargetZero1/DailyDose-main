@extends('layouts.app')

@section('content')
<style>
    .menu-hero {
        background: linear-gradient(to right, #d4a574, #8b6f47);
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 40px;
        color: white;
    }

    .menu-hero h1 {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin: 0 0 10px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .menu-hero p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 18px;
        margin: 0;
    }

    .menu-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .filter-section {
        display: flex;
        gap: 12px;
        margin-bottom: 40px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-btn {
        padding: 10px 20px;
        border: 2px solid #d4a574;
        background: white;
        color: #d4a574;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .filter-btn:hover {
        background: #f5f0e8;
    }

    .filter-btn.active {
        background: #d4a574;
        color: white;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .product-image-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f5f3f0;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #d4a574;
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-badge.new {
        background: #10b981;
    }

    .product-favorite-btn {
        position: absolute;
        top: 12px;
        left: 12px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .product-favorite-btn:hover {
        background: white;
        transform: scale(1.1);
    }

    .product-favorite-btn i {
        color: #d4a574;
        font-size: 18px;
    }

    .product-favorite-btn.favorited i {
        color: #ef4444;
    }

    .product-info {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-category {
        font-size: 12px;
        font-weight: 600;
        color: #d4a574;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .product-name {
        font-size: 18px;
        font-weight: 700;
        color: #352b06;
        margin: 0 0 8px 0;
        line-height: 1.3;
    }

    .product-description {
        font-size: 13px;
        color: #666;
        margin: 0 0 12px 0;
        line-height: 1.4;
        flex: 1;
    }

    .product-price {
        font-size: 24px;
        font-weight: 800;
        color: #d4a574;
        margin-bottom: 14px;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add-cart {
        flex: 1;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
    }

    .btn-add-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-quick-buy {
        background: #10b981;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 14px;
    }

    .btn-quick-buy:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-view-details {
        flex: 1;
        background: white;
        color: #d4a574;
        border: 2px solid #d4a574;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
    }

    .btn-view-details:hover {
        background: #f5f0e8;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 64px;
        color: #d4a574;
        margin-bottom: 20px;
    }

    .empty-state p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .stock-info {
        font-size: 12px;
        color: #999;
        margin-top: 8px;
    }

    .in-stock {
        color: #10b981;
        font-weight: 600;
    }

    .low-stock {
        color: #f59e0b;
        font-weight: 600;
    }

    .out-of-stock {
        color: #ef4444;
        font-weight: 600;
    }

    .rating-container {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 6px 0;
    }

    .rating-stars {
        color: #fbbf24;
        font-size: 12px;
    }

    .rating-count {
        color: #999;
        font-size: 12px;
    }

    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<div class="menu-hero">
    <h1>
        <i class="fas fa-utensils"></i>
        Our Menu
    </h1>
    <p>Browse and order your favorite items</p>
</div>

<div class="menu-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <button class="filter-btn active" onclick="filterByCategory('all')">
            <i class="fas fa-list"></i> All Items
        </button>
        <button class="filter-btn" onclick="filterByCategory('Cake')">
            <i class="fas fa-birthday-cake"></i> Cake
        </button>
        <button class="filter-btn" onclick="filterByCategory('Icecream')">
            <i class="fas fa-ice-cream"></i> Ice Cream
        </button>
        <button class="filter-btn" onclick="filterByCategory('Pancake')">
            <i class="fas fa-circle"></i> Pancake
        </button>
        <button class="filter-btn" onclick="filterByCategory('Pastry')">
            <i class="fas fa-croissant"></i> Pastry
        </button>
    </div>

    <!-- Products Grid -->
    <div class="products-grid" id="products-grid">
        <div class="empty-state">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Loading products...</p>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="success-toast" class="fixed top-20 right-4 bg-white shadow-xl rounded-lg p-4 hidden z-50 border-l-4 border-green-500" style="animation: slideIn 0.3s ease;">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-white"></i>
        </div>
        <div>
            <div class="font-bold text-gray-800" id="toast-message">Added to Cart!</div>
            <div class="text-sm text-gray-600">Product added successfully</div>
        </div>
    </div>
</div>

<script>
    let allProducts = [];
    let currentFilter = 'all';
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    // Load products from API
    async function loadProducts() {
        try {
            const response = await fetch('{{ route("products.api") }}');
            const data = await response.json();
            allProducts = data;
            renderProducts(allProducts);
            updateCartCount();
        } catch (error) {
            console.error('Error loading products:', error);
            document.getElementById('products-grid').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load products</p>
                </div>
            `;
        }
    }

    // Filter products by category
    function filterByCategory(category) {
        currentFilter = category;
        
        // Update button states
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.closest('.filter-btn').classList.add('active');

        // Filter and render
        if (category === 'all') {
            renderProducts(allProducts);
        } else {
            const filtered = allProducts.filter(p => p.category === category);
            renderProducts(filtered);
        }
    }

    // Render products
    function renderProducts(products) {
        const grid = document.getElementById('products-grid');

        if (products.length === 0) {
            grid.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No products found</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = products.map(product => {
            const imageUrl = product.gambar || '{{ asset("img/placeholder.png") }}';
            const stockClass = product.stock > 10 ? 'in-stock' : (product.stock > 0 ? 'low-stock' : 'out-of-stock');
            const stockText = product.stock > 10 ? 'In Stock' : (product.stock > 0 ? 'Low Stock' : 'Out of Stock');
            
            return `
                <div class="product-card">
                    <div class="product-image-container">
                        <img src="${imageUrl}" alt="${product.namaProduct}" class="product-image" onerror="this.src='{{ asset('img/placeholder.png') }}'">
                        ${product.is_new ? '<div class="product-badge new">NEW</div>' : '<div class="product-badge">Featured</div>'}
                        <button class="product-favorite-btn" data-product-id="${product.id}" onclick="toggleFavorite(${product.id}, event)">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                    </div>
                    <div class="product-info">
                        <div class="product-category">${product.category}</div>
                        <h3 class="product-name">${product.namaProduct}</h3>
                        <div class="rating-container">
                            <div class="rating-stars">★★★★★</div>
                            <div class="rating-count">(12 reviews)</div>
                        </div>
                        <p class="product-description">${product.description || 'Delicious handcrafted dessert made with premium ingredients.'}</p>
                        <div class="stock-info">
                            <span class="${stockClass}">${stockText}</span>
                        </div>
                        <div class="product-price">Rp ${new Intl.NumberFormat('id-ID').format(product.harga)}</div>
                        <div class="product-actions">
                            <button class="btn-add-cart" onclick="addToCart(${product.id}, '${product.namaProduct.replace(/'/g, "\\'")}', ${product.harga}, '${imageUrl.replace(/'/g, "\\'")}')">
                                <i class="fas fa-shopping-cart"></i> Add
                            </button>
                            <button class="btn-quick-buy" onclick="quickBuy(${product.id}, '${product.namaProduct.replace(/'/g, "\\'")}', ${product.harga}, '${imageUrl.replace(/'/g, "\\'")}')">
                                <i class="fas fa-bolt"></i> Buy
                            </button>
                            <button class="btn-view-details" onclick="viewDetails(${product.id})">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Add to cart
    function addToCart(id, name, price, image) {
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik'))
            showToast('⚠️ Admins cannot make purchases', 'warning');
            return false;
        @endif

        const existing = cart.find(item => item.namaProduct === name);
        
        if (existing) {
            existing.qty += 1;
            existing.subtotal = existing.harga * existing.qty;
        } else {
            cart.push({
                id: id,
                namaProduct: name,
                harga: price,
                qty: 1,
                subtotal: price,
                gambar: image
            });
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        showToast('Added to Cart!', 'success');
        updateCartCount();
        return true;
    }

    // Quick buy - add and redirect to checkout
    function quickBuy(id, name, price, image) {
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik'))
            showToast('⚠️ Admins cannot make purchases', 'warning');
            return;
        @endif

        if (addToCart(id, name, price, image)) {
            setTimeout(() => {
                window.location.href = '{{ route("checkout") }}';
            }, 300);
        }
    }

    // View product details
    function viewDetails(id) {
        window.location.href = `/products/${id}`;
    }

    // Toggle favorite
    async function toggleFavorite(productId, event) {
        event.stopPropagation();
        
        if (!isAuthenticated) {
            window.location.href = '{{ route("login") }}';
            return;
        }

        const btn = event.currentTarget;
        const icon = btn.querySelector('i');

        try {
            const response = await fetch(`/products/${productId}/favorite`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            const data = await response.json();
            
            if (data.status === 'added') {
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                btn.classList.add('favorited');
            } else if (data.status === 'removed') {
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                btn.classList.remove('favorited');
            }
        } catch (error) {
            console.error('Favorite error:', error);
        }
    }

    // Show toast notification
    function showToast(message = 'Added to Cart!', type = 'success') {
        if (typeof window.createToast === 'function') {
            window.createToast(type, type === 'warning' ? 'Notice' : 'Success', message);
        } else {
            // Fallback
            const toast = document.getElementById('success-toast');
            if (toast) {
                document.getElementById('toast-message').textContent = message;
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }
        }
    }

    // Update cart count in navbar
    function updateCartCount() {
        const total = cart.reduce((sum, item) => sum + item.qty, 0);
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            cartCountEl.textContent = total;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        loadProducts();
        
        // Load favorites if authenticated
        if (isAuthenticated) {
            setTimeout(() => {
                document.querySelectorAll('.product-favorite-btn').forEach(btn => {
                    const productId = btn.getAttribute('data-product-id');
                    fetch(`/favorites/check/${productId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.is_favorited) {
                                const icon = btn.querySelector('i');
                                icon.classList.remove('fa-regular');
                                icon.classList.add('fa-solid');
                                btn.classList.add('favorited');
                            }
                        });
                });
            }, 500);
        }
    });
</script>

@endsection

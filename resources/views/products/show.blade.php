@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
                @php $imageUrl = $product->getImageUrl(); @endphp
                <img src="{{ $imageUrl }}" alt="{{ $product->name ?? 'Product' }}" class="w-full h-64 object-cover rounded-lg">
            <div class="mt-4">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">{{ $product->category ?? 'Product' }}</span>
                <div class="mt-2">
                    <span id="stock-status" class="text-sm font-semibold {{ $product->isInStock() ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product->isInStock() ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <h1 class="text-2xl font-bold">{{ $product->name ?? 'Product Name' }}</h1>
            <div class="text-amber-700 text-2xl font-bold mt-2">Rp {{ number_format($product->getFinalPrice() ?? 0, 0, ',', '.') }}</div>
            <p class="text-gray-700 mt-4">{{ $product->description ?? 'Delicious handcrafted dessert made with premium ingredients.' }}</p>
            
            <div class="mt-4">
                <div class="mt-4 flex items-center gap-4">
                    <div>
                        <div class="text-sm font-medium text-gray-600 mb-1">Quantity</div>
                        <div class="flex items-center gap-2">
                            <button id="qty-decrease" class="px-3 py-1 border rounded">-</button>
                            <input id="qty-input" type="number" min="1" value="1" class="w-16 text-center p-1 border rounded">
                            <button id="qty-increase" class="px-3 py-1 border rounded">+</button>
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-medium text-gray-600 mb-1">Subtotal</div>
                        <div id="subtotal" class="text-amber-700 font-bold text-xl">Rp {{ number_format($product->getFinalPrice() ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 flex-wrap">
                    <button id="add-to-cart" class="px-6 py-3 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-semibold">
                        <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                    </button>
                    <button id="buy-now" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition font-semibold">
                        <i class="fas fa-bolt mr-2"></i>Buy Now
                    </button>
                    <button id="favorite-btn" class="px-4 py-3 border border-amber-700 rounded-lg flex items-center gap-2 hover:bg-amber-50 transition">
                        <i id="favorite-icon" class="fa-regular fa-heart text-amber-700"></i>
                        <span id="favorite-text" class="font-semibold text-amber-700">Add to Wishlist</span>
                    </button>
                </div>
            </div>

            <hr class="my-6">

            <div>
                <h3 class="text-xl font-bold mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-amber-700"></i>
                    Product Details
                </h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        Made with premium quality ingredients
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        Freshly baked daily
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        No artificial preservatives
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        Halal certified
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-600"></i>
                        Perfect for sharing or personal enjoyment
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts && $relatedProducts->count() > 0)
    <div class="max-w-5xl mx-auto mt-8">
        <h3 class="text-2xl font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-heart text-amber-700"></i>
            You May Also Like
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($relatedProducts as $related)
            <a href="{{ route('products.show', $related->id) }}" class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:scale-105">
                <img src="{{ $related->getImageUrl() }}" alt="{{ $related->name }}" class="w-full h-40 object-cover">
                <div class="p-3">
                    <h4 class="font-semibold text-sm mb-2 line-clamp-2">{{ $related->name }}</h4>
                    <p class="text-amber-700 font-bold">Rp {{ number_format($related->getFinalPrice(), 0, ',', '.') }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Success Toast -->
<div id="success-toast" class="fixed top-20 right-4 bg-white shadow-xl rounded-lg p-4 hidden z-50 border-l-4 border-green-500" style="animation: slideIn 0.3s ease;">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-white"></i>
        </div>
        <div>
            <div class="font-bold text-gray-800">Added to Cart!</div>
            <div class="text-sm text-gray-600">Product added successfully</div>
        </div>
    </div>
</div>

<style>
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
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    const productData = {
        id: {{ $product->id ?? 0 }},
        namaProduct: @json($product->name ?? ''),
        category: @json($product->category ?? ''),
        harga: {{ $product->getFinalPrice() ?? 0 }},
        gambar: @json($product->getImageUrl())
    };

    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    // Quantity controls
    document.getElementById('qty-decrease').addEventListener('click', () => {
        const input = document.getElementById('qty-input');
        let v = Number(input.value) || 1;
        if (v > 1) v--;
        input.value = v;
        updateSubtotal();
    });

    document.getElementById('qty-increase').addEventListener('click', () => {
        const input = document.getElementById('qty-input');
        let v = Number(input.value) || 1;
        v++;
        input.value = v;
        updateSubtotal();
    });

    document.getElementById('qty-input').addEventListener('change', () => {
        if (Number(document.getElementById('qty-input').value) < 1) {
            document.getElementById('qty-input').value = 1;
        }
        updateSubtotal();
    });

    function updateSubtotal() {
        const qty = Number(document.getElementById('qty-input').value || 1);
        const subtotal = productData.harga * qty;
        document.getElementById('subtotal').textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(subtotal);
    }

    // Initialize subtotal on page load
    updateSubtotal();

    // Add to cart
    document.getElementById('add-to-cart').addEventListener('click', () => {
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik'))
            showToast('⚠️ Admins cannot make purchases', 'warning');
            return;
        @endif

        const qty = Number(document.getElementById('qty-input').value || 1);
        const existingItem = cart.find(item => item.namaProduct === productData.namaProduct);

        if (existingItem) {
            existingItem.qty += qty;
            existingItem.subtotal = existingItem.harga * existingItem.qty;
        } else {
            cart.push({
                namaProduct: productData.namaProduct,
                harga: productData.harga,
                qty: qty,
                subtotal: productData.harga * qty,
                gambar: productData.gambar
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        showToast('Added to Cart!', 'success');
        updateCartCount();
    });

    // Buy now
    document.getElementById('buy-now').addEventListener('click', () => {
        @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik'))
            showToast('⚠️ Admins cannot make purchases', 'warning');
            return;
        @endif

        const qty = Number(document.getElementById('qty-input').value || 1);
        const existingItem = cart.find(item => item.namaProduct === productData.namaProduct);

        if (existingItem) {
            existingItem.qty += qty;
            existingItem.subtotal = existingItem.harga * existingItem.qty;
        } else {
            cart.push({
                namaProduct: productData.namaProduct,
                harga: productData.harga,
                qty: qty,
                subtotal: productData.harga * qty,
                gambar: productData.gambar
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        window.location.href = "{{ route('checkout') }}";
    });

    function setFavoriteState(isFavorited) {
        const icon = document.getElementById('favorite-icon');
        const text = document.getElementById('favorite-text');
        if (isFavorited) {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            icon.style.color = '#ef4444';
            text.textContent = 'Favorited';
            text.style.color = '#ef4444';
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            icon.style.color = '#d4a574';
            text.textContent = 'Add to Wishlist';
            text.style.color = '#d4a574';
        }
    }

    // Favorite toggle synced with backend
    document.getElementById('favorite-btn').addEventListener('click', () => {
        if (!isAuthenticated) {
            window.location.href = '{{ route('login') }}';
            return;
        }

        fetch('/products/' + productData.id + '/favorite', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'added') {
                setFavoriteState(true);
            } else if (data.status === 'removed') {
                setFavoriteState(false);
            }
        })
        .catch(() => setFavoriteState(false));
    });

    // Load initial favorite state from server when authenticated
    if (isAuthenticated) {
        fetch('/favorites/check/' + productData.id)
            .then(res => res.json())
            .then(data => setFavoriteState(!!data.is_favorited))
            .catch(() => setFavoriteState(false));
    } else {
        setFavoriteState(false);
    }

    function showToast(message = 'Added to Cart!', type = 'success') {
        if (typeof window.createToast === 'function') {
            window.createToast(type, type === 'warning' ? 'Notice' : 'Success', message);
        } else {
            // Fallback
            const toast = document.getElementById('success-toast');
            if (toast) {
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }
        }
    }

    function updateCartCount() {
        const cartCount = cart.reduce((sum, item) => sum + item.qty, 0);
        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            cartCountEl.textContent = cartCount;
        }
    }

    // Initialize
    updateCartCount();
</script>

@include('partials.footer')

@endsection

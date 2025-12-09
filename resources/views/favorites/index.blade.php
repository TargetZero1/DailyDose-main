@extends('layouts.app')

@section('content')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .favorites-container {
        background: linear-gradient(135deg, #fef3e2 0%, #fae9d0 100%);
        min-height: 100vh;
        padding: 40px 0;
    }

    .favorites-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .favorites-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 40px 24px;
        border-radius: 16px;
        margin-bottom: 40px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        text-align: center;
    }

    .favorites-header h1 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .favorites-header p {
        font-size: 16px;
        opacity: 0.95;
    }

    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 20px;
    }

    .favorite-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
    }

    .favorite-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transform: translateY(-8px);
        border-color: #d4a574;
    }

    .favorite-image-container {
        position: relative;
        width: 100%;
        padding-bottom: 100%;
        overflow: hidden;
        background: #f5f5f5;
    }

    .favorite-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .favorite-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: #d4a574;
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
    }

    .favorite-heart-btn {
        position: absolute;
        top: 12px;
        left: 12px;
        width: 40px;
        height: 40px;
        background: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
        color: #ff4757;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .favorite-heart-btn:hover {
        transform: scale(1.1);
    }

    .favorite-info {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .favorite-name {
        font-size: 16px;
        font-weight: 700;
        color: #352b06;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .favorite-category {
        font-size: 12px;
        color: #999;
        margin-bottom: 12px;
        text-transform: capitalize;
    }

    .favorite-price {
        font-size: 18px;
        font-weight: 700;
        color: #d4a574;
        margin-bottom: 16px;
    }

    .favorite-actions {
        display: flex;
        gap: 8px;
    }

    .favorite-btn {
        flex: 1;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-view {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.4);
    }

    .btn-cart {
        background: #fff3e0;
        color: #d4a574;
        border: 2px solid #d4a574;
    }

    .btn-cart:hover {
        background: #d4a574;
        color: white;
    }

    .empty-favorites {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-favorites i {
        font-size: 80px;
        color: #e0d5c7;
        margin-bottom: 20px;
    }

    .empty-favorites h2 {
        color: #666;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .empty-favorites p {
        color: #999;
        font-size: 16px;
        margin-bottom: 30px;
    }

    .back-to-shop {
        display: inline-block;
        padding: 12px 32px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .back-to-shop:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.4);
    }

    @media (max-width: 768px) {
        .favorites-grid {
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }

        .favorites-header h1 {
            font-size: 28px;
        }
    }

    @media (max-width: 480px) {
        .favorites-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .favorite-actions {
            flex-direction: column;
        }

        .favorite-btn {
            width: 100%;
        }
    }
</style>

<div class="favorites-container">
    <div class="favorites-wrapper">
        <!-- Header -->
        <div class="favorites-header">
            <h1>
                <i class="fas fa-heart" style="color: #ff4757;"></i>
                My Favorites
            </h1>
            <p>Your collection of loved items</p>
        </div>

        @if($favorites->isEmpty())
            <!-- Empty State -->
            <div class="empty-favorites">
                <i class="fas fa-heart-broken"></i>
                <h2>No Favorites Yet</h2>
                <p>Start adding items to your favorites!</p>
                <a href="{{ route('menu') }}" class="back-to-shop">
                    <i class="fas fa-shopping-bag"></i> Browse Products
                </a>
            </div>
        @else
            <!-- Favorites Grid -->
            <div class="favorites-grid">
                @foreach($favorites as $favorite)
                    @php
                        $product = $favorite->product;
                    @endphp
                    <div class="favorite-card">
                        <!-- Image Container -->
                        <div class="favorite-image-container">
                            <img src="{{ $product->image ?? asset('img/default-product.svg') }}" alt="{{ $product->name }}" class="favorite-image">
                            <span class="favorite-badge">❤️ Favorite</span>
                            <button 
                                class="favorite-heart-btn"
                                title="Remove from favorites"
                                data-favorite-id="{{ $favorite->id }}"
                                onclick="removeFavoriteWithData(this)"
                            >
                                ♥️
                            </button>
                        </div>

                        <!-- Info Section -->
                        <div class="favorite-info">
                            <h3 class="favorite-name">{{ $product->name ?? 'Product' }}</h3>
                            <span class="favorite-category">{{ $product->category ?? 'general' }}</span>
                            <span class="favorite-price">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</span>

                            <!-- Action Buttons -->
                            <div class="favorite-actions">
                                <a href="{{ route('products.show', $product->id) }}" class="favorite-btn btn-view">
                                    <i class="fas fa-eye"></i>View
                                </a>
                                <button class="favorite-btn btn-cart" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price ?? 0 }}" data-image="{{ $product->image ?? '' }}" onclick="addToCartFromData(this)">
                                    <i class="fas fa-shopping-cart"></i>Cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    function removeFavorite(favoriteId) {
        if (confirm('Remove from favorites?')) {
            fetch(`/products/${favoriteId}/favorite`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                location.reload();
            }).catch(err => {
                console.error('Error:', err);
                alert('Failed to remove from favorites');
            });
        }
    }

    function addToCart(id, name, price, image) {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const existingItem = cart.find(item => item.id === id);

        if (existingItem) {
            existingItem.qty += 1;
        } else {
            cart.push({
                id: id,
                namaProduct: name,
                harga: price,
                gambar: image,
                qty: 1,
                subtotal: price
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Update global cart
        if (window.updateGlobalCart) {
            window.updateGlobalCart();
        }

        alert('✅ Added to cart!');
    }

    function addToCartFromData(button) {
        const id = button.dataset.id;
        const name = button.dataset.name;
        const price = parseFloat(button.dataset.price);
        const image = button.dataset.image;
        addToCart(id, name, price, image);
    }

    function removeFavoriteWithData(button) {
        const favoriteId = button.dataset.favoriteId;
        removeFavorite(favoriteId);
    }
</script>

@endsection

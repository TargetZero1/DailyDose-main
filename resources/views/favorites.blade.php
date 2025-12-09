@extends('layouts.app')

@section('content')
<style>
    .wishlist-container {
        background: linear-gradient(135deg, #f5f3f0 0%, #eae6e0 100%);
        min-height: calc(100vh - 200px);
    }
    
    .wishlist-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .wishlist-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(212, 165, 116, 0.2);
    }
    
    .wishlist-card img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .wishlist-card:hover img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(212, 165, 116, 0.95);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .empty-wishlist {
        background: white;
        border-radius: 16px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<div class="wishlist-container py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-800 mb-4">
                <i class="fas fa-heart mr-3 text-[#d4a574]"></i>My Wishlist
            </h1>
            <p class="text-xl text-gray-600">Your favorite treats in one place</p>
        </div>

        <div id="favorites-list">
            @if($favorites->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($favorites as $fav)
                        @php $product = $fav->product; @endphp
                        @if($product)
                        <div class="wishlist-card" id="favorite-card-{{ $product->id }}">
                            <div class="relative overflow-hidden">
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}">
                                <span class="product-badge">
                                    <i class="fas fa-heart mr-1"></i>Favorited
                                </span>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $product->name }}</h3>
                                
                                @if($product->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <div class="text-2xl font-bold text-[#d4a574]">
                                            Rp {{ number_format($product->getFinalPrice(), 0, ',', '.') }}
                                        </div>
                                        @if($product->discount_price && $product->discount_price < $product->price)
                                        <div class="text-sm text-gray-500 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                    @if($product->stock > 0)
                                    <span class="text-green-600 text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>In Stock
                                    </span>
                                    @else
                                    <span class="text-red-600 text-sm font-semibold">
                                        <i class="fas fa-times-circle mr-1"></i>Out of Stock
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="flex-1 px-4 py-3 bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition text-center">
                                        <i class="fas fa-shopping-cart mr-2"></i>View Details
                                    </a>
                                    <button onclick="removeFavorite({{ $product->id }})" 
                                            class="px-4 py-3 border-2 border-red-500 text-red-500 font-bold rounded-lg hover:bg-red-500 hover:text-white transition"
                                            title="Remove from wishlist">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                
                <div class="mt-12">
                    {{ $favorites->links() }}
                </div>
            @else
                <div class="empty-wishlist">
                    <i class="fas fa-heart-broken text-6xl text-gray-300 mb-6"></i>
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Your Wishlist is Empty</h2>
                    <p class="text-gray-600 mb-8 text-lg">Start adding your favorite treats to keep track of them!</p>
                    <a href="{{ route('menu') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition">
                        <i class="fas fa-utensils mr-2"></i>Browse Menu
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function removeFavorite(productId) {
        if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
            return;
        }
        
        fetch('/products/' + productId + '/favorite', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(() => {
            const card = document.getElementById('favorite-card-' + productId);
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.remove();
                    if (!document.querySelector('[id^="favorite-card-"]')) {
                        document.getElementById('favorites-list').innerHTML = `
                            <div class="empty-wishlist">
                                <i class="fas fa-heart-broken text-6xl text-gray-300 mb-6"></i>
                                <h2 class="text-3xl font-bold text-gray-800 mb-4">Your Wishlist is Empty</h2>
                                <p class="text-gray-600 mb-8 text-lg">Start adding your favorite treats to keep track of them!</p>
                                <a href="{{ route('menu') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition">
                                    <i class="fas fa-utensils mr-2"></i>Browse Menu
                                </a>
                            </div>
                        `;
                    }
                }, 300);
            }
        })
        .catch(() => {
            alert('Failed to remove item. Please try again.');
        });
    }
</script>

@endsection

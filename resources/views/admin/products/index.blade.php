@extends('layouts.app')

@section('content')
<style>
    .admin-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
    }
    
    .admin-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #d4a574;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #352b06;
        margin-top: 8px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: flex-end;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #d4a574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter:hover {
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-filter-reset {
        background: #6b7280;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter-reset:hover {
        background: #4b5563;
    }
    
    .product-row {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .product-row:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    
    .product-info {
        display: flex;
        gap: 16px;
        flex: 1;
        align-items: center;
    }
    
    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        background: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-details {
        flex: 1;
    }
    
    .product-name {
        font-weight: 700;
        color: #352b06;
        font-size: 16px;
    }
    
    .product-meta {
        display: flex;
        gap: 16px;
        margin-top: 8px;
        font-size: 14px;
        color: #666;
    }
    
    .product-actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-action {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
    }
    
    .btn-edit {
        background: #3b82f6;
        color: white;
    }
    
    .btn-edit:hover {
        background: #2563eb;
    }
    
    .btn-toggle {
        background: #8b5cf6;
        color: white;
    }
    
    .btn-toggle:hover {
        background: #7c3aed;
    }
    
    .btn-delete {
        background: #ef4444;
        color: white;
    }
    
    .btn-delete:hover {
        background: #dc2626;
    }
    
    .btn-add {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 24px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-hidden {
        background: #fee2e2;
        color: #7f1d1d;
    }
</style>

<div class="admin-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black mb-2">Product Management</h1>
        <p class="text-amber-100">Manage your product catalog with advanced filtering & analytics</p>
    </div>
</div>

<div class="admin-container py-12">
    <div class="container mx-auto px-4">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                <p class="text-green-700"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</p>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-cube mr-2"></i>Total Products</div>
                <div class="stat-value">{{ $products->total() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <div class="stat-label"><i class="fas fa-check-circle mr-2"></i>Active Products</div>
                <div class="stat-value">{{ collect($products->items())->where('status', 'active')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-label"><i class="fas fa-eye-slash mr-2"></i>Hidden Products</div>
                <div class="stat-value">{{ collect($products->items())->where('status', 'hidden')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #3b82f6;">
                <div class="stat-label"><i class="fas fa-layer-group mr-2"></i>Categories</div>
                <div class="stat-value">{{ $products->pluck('category')->unique()->count() }}</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fas fa-sliders-h mr-2"></i>Advanced Filters</h3>
            <form method="GET" action="{{ route('admin.products') }}" class="space-y-4">
                <div class="filter-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by name</label>
                        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category">
                            <option value="">All Categories</option>
                            <option value="coffee" {{ request('category') == 'coffee' ? 'selected' : '' }}>Coffee</option>
                            <option value="food" {{ request('category') == 'food' ? 'selected' : '' }}>Food</option>
                            <option value="beverage" {{ request('category') == 'beverage' ? 'selected' : '' }}>Beverage</option>
                            <option value="dessert" {{ request('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                        <select name="sort">
                            <option value="">Default</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.products') }}" class="btn-filter-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add New Product Button -->
        <a href="{{ route('admin.products.create') }}" class="btn-add" style="margin-bottom: 20px;">
            <i class="fas fa-plus-circle"></i> Add New Product
        </a>

        <!-- Products List -->
        <div>
            @forelse($products as $product)
                <div class="product-row">
                    <div class="product-info">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" onerror="this.src='https://via.placeholder.com/80x80?text=No+Image'">
                            @else
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            @endif
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-meta">
                                <span><i class="fas fa-folder mr-1"></i> {{ $product->category }}</span>
                                <span><i class="fas fa-tag mr-1"></i> Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span><i class="fas fa-box mr-1"></i> Stock: {{ $product->stock }}</span>
                                <span>
                                    <span class="status-badge {{ $product->status === 'active' ? 'status-active' : 'status-hidden' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.products.toggle', $product) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-toggle">
                                <i class="fas fa-{{ $product->status === 'active' ? 'eye-slash' : 'eye' }}"></i>
                                {{ $product->status === 'active' ? 'Hide' : 'Show' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.products.delete', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-box text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-600 text-lg">No products found. <a href="{{ route('admin.products.create') }}" class="text-amber-700 font-bold">Create your first product!</a></p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

@include('partials.footer')

@endsection

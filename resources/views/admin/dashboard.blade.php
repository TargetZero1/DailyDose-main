@extends('layouts.app')

@section('content')
<style>
    .admin-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 900;
        color: #352b06;
    }
    
    .admin-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }
    
    .admin-nav {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    
    .admin-nav a,
    .admin-nav button {
        background: white;
        color: #352b06;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .admin-nav a:hover,
    .admin-nav button:hover {
        background: #d4a574;
        color: white;
    }
</style>

<div class="admin-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black mb-2">Admin Dashboard</h1>
        <p class="text-amber-100">Welcome back, {{ Auth::user()->username }}!</p>
    </div>
</div>

<div class="admin-container py-12">
    <div class="container mx-auto px-4">
        <!-- Navigation -->
        <div class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="!bg-amber-700 !text-white">
                <i class="fas fa-chart-line mr-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products') }}">
                <i class="fas fa-cube mr-2"></i> Products
            </a>
            <a href="{{ route('admin.orders.index') }}">
                <i class="fas fa-receipt mr-2"></i> Orders
            </a>
            <a href="{{ route('admin.reservations') }}">
                <i class="fas fa-calendar-check mr-2"></i> Reservations
            </a>
            <a href="{{ route('admin.users') }}">
                <i class="fas fa-users mr-2"></i> Users
            </a>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-12">
            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-semibold">Products</h3>
                    <div class="stat-icon bg-blue-100">
                        <i class="fas fa-cube text-blue-600"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $total_products }}</div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-semibold">Orders</h3>
                    <div class="stat-icon bg-green-100">
                        <i class="fas fa-receipt text-green-600"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $total_orders }}</div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-semibold">Users</h3>
                    <div class="stat-icon bg-purple-100">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $total_users }}</div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-semibold">Reservations</h3>
                    <div class="stat-icon bg-yellow-100">
                        <i class="fas fa-calendar-check text-yellow-600"></i>
                    </div>
                </div>
                <div class="stat-number">{{ $total_reservations }}</div>
            </div>

            <div class="stat-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-gray-600 font-semibold">Revenue</h3>
                    <div class="stat-icon bg-red-100">
                        <i class="fas fa-money-bill-wave text-red-600"></i>
                    </div>
                </div>
                <div class="stat-number text-lg">Rp {{ number_format($revenue / 1000000, 1) }}M</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- Recent Orders -->
            <div class="stat-card">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Recent Orders</h2>
                <div class="space-y-4">
                    @forelse($recent_orders as $order)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-900">#{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->username ?? 'Guest' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-700">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No recent orders</p>
                    @endforelse
                </div>
            </div>

            <!-- Popular Products -->
            <div class="stat-card">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Popular Products</h2>
                <div class="space-y-4">
                    @forelse($popular_products as $product)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-amber-700"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $product->category }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ $product->sale_count }}</p>
                                <p class="text-xs text-gray-600">Sales</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No products yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

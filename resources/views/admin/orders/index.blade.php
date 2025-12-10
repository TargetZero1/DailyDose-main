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

    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .metric-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        display: flex;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .metric-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .metric-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
        margin-top: 4px;
    }

    .filter-section {
        background: white;
        padding: 24px;
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
        border-radius: 8px;
        font-size: 14px;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #8b6f47;
        box-shadow: 0 0 0 3px rgba(139, 111, 71, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter:hover {
        box-shadow: 0 4px 12px rgba(139, 111, 71, 0.3);
        transform: translateY(-2px);
    }

    .btn-export {
        background: #10b981;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-export:hover {
        background: #059669;
        color: white;
        text-decoration: none;
    }

    .btn-reset {
        background: #6b7280;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-reset:hover {
        background: #4b5563;
        color: white;
        text-decoration: none;
    }

    .order-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .order-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .order-id {
        font-weight: 700;
        color: #1f2937;
        font-size: 20px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: #fef08a;
        color: #854d0e;
    }

    .status-processing {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-completed {
        background: #dcfce7;
        color: #166534;
    }

    .status-cancelled, .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .payment-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .payment-confirmed, .payment-paid {
        background: #dcfce7;
        color: #166534;
    }

    .payment-pending, .payment-unpaid {
        background: #fef08a;
        color: #854d0e;
    }

    .order-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 16px;
    }

    .order-meta strong {
        color: #1f2937;
    }

    .order-summary {
        background: #f9fafb;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .order-items {
        font-size: 14px;
        color: #4b5563;
    }

    .order-items > div {
        padding: 4px 0;
    }

    .order-total {
        font-size: 18px;
        font-weight: 700;
        color: #8b6f47;
    }

    .order-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
    }

    .btn-view:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }

    .payment-chart {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .progress-bar-container {
        width: 100%;
        background: #e5e7eb;
        border-radius: 8px;
        height: 10px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-bar {
        height: 100%;
        border-radius: 8px;
        transition: width 0.3s ease;
    }
</style>

<div class="admin-header">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-4xl font-black mb-2">Orders Management</h1>
                <p class="text-[#e8d4c0]">Comprehensive order tracking and analytics dashboard</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.export') }}" class="btn-export">
                    <i class="fas fa-file-csv"></i> Export CSV
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="btn-filter">
                    <i class="fas fa-chart-line"></i> Analytics
                </a>
            </div>
        </div>
    </div>
</div>

<div class="admin-container py-12">
    <div class="container mx-auto px-4">
        <!-- Key Metrics -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon" style="background: #3b82f6; color: white;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div>
                    <div class="metric-label">Total Orders</div>
                    <div class="metric-value">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #fbbf24; color: white;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="metric-label">Pending</div>
                    <div class="metric-value">{{ $stats['pending'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #06b6d4; color: white;">
                    <i class="fas fa-spinner"></i>
                </div>
                <div>
                    <div class="metric-label">Processing</div>
                    <div class="metric-value">{{ $stats['processing'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #10b981; color: white;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <div class="metric-label">Completed</div>
                    <div class="metric-value">{{ $stats['completed'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #ef4444; color: white;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div>
                    <div class="metric-label">Cancelled</div>
                    <div class="metric-value">{{ $stats['cancelled'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white;">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div>
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value">Rp{{ number_format($stats['revenue_total'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="payment-chart">
                <h5 class="text-lg font-bold mb-4 text-gray-800"><i class="fas fa-credit-card mr-2"></i>Payment Status Overview</h5>
                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600 font-medium">Confirmed Payments</span>
                        <strong class="text-green-600">{{ $stats['payment_confirmed'] ?? 0 }}</strong>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar bg-green-600" style="width: {{ max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) > 0 ? (($stats['payment_confirmed'] ?? 0) / max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600 font-medium">Pending Payments</span>
                        <strong class="text-yellow-600">{{ $stats['payment_pending'] ?? 0 }}</strong>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar bg-yellow-600" style="width: {{ max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) > 0 ? (($stats['payment_pending'] ?? 0) / max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            <div class="payment-chart">
                <h5 class="text-lg font-bold mb-4 text-gray-800"><i class="fas fa-calendar-day mr-2"></i>Today's Performance</h5>
                <div class="mb-3">
                    <span class="text-gray-600 font-medium">Today's Revenue</span>
                    <div class="text-green-600 text-2xl font-bold mt-2">Rp{{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}</div>
                </div>
                <hr class="my-4">
                <p class="text-sm text-gray-500 mb-0">Real-time metrics update every 5 minutes</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fas fa-filter mr-2"></i>Filter & Search Orders</h3>
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="filter-group mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" placeholder="Order ID, customer name or phone..." value="{{ request('search') }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select name="payment_status">
                            <option value="">All</option>
                            <option value="confirmed" {{ request('payment_status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort">
                            <option value="">Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="highest" {{ request('sort') == 'highest' ? 'selected' : '' }}>Highest Amount</option>
                            <option value="lowest" {{ request('sort') == 'lowest' ? 'selected' : '' }}>Lowest Amount</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders List -->
        <div>
            @forelse($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="flex items-center gap-3">
                            <div class="order-id">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="order-total">
                            Rp {{ number_format($order->total, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="order-meta">
                        <div>
                            <i class="fas fa-user mr-2"></i><strong>Customer:</strong> {{ $order->customer_name ?? $order->user->username ?? 'Guest' }}
                        </div>
                        <div>
                            <i class="fas fa-phone mr-2"></i><strong>Phone:</strong> {{ $order->customer_phone ?? 'N/A' }}
                        </div>
                        <div>
                            <i class="fas fa-calendar mr-2"></i><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <i class="fas fa-box mr-2"></i><strong>Items:</strong> {{ $order->items->count() }} items
                        </div>
                        <div>
                            <i class="fas fa-credit-card mr-2"></i><strong>Payment:</strong>
                            <span class="payment-badge payment-{{ $order->payment_status }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div class="order-summary">
                        <div class="font-semibold text-gray-700 mb-2"><i class="fas fa-list mr-2"></i>Order Items:</div>
                        <div class="order-items">
                            @foreach($order->items->take(3) as $item)
                                <div><i class="fas fa-check-circle text-green-600 mr-2"></i>{{ $item->product->name }} x {{ $item->quantity }} - Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="font-bold" style="color: #8b6f47;"><i class="fas fa-plus-circle mr-2"></i>+ {{ $order->items->count() - 3 }} more items</div>
                            @endif
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded text-sm text-blue-800 mb-3">
                            <i class="fas fa-sticky-note mr-2"></i><strong>Special Notes:</strong> {{ $order->notes }}
                        </div>
                    @endif

                    <div class="order-actions">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl shadow">
                    <i class="fas fa-inbox text-gray-300 text-7xl mb-4"></i>
                    <p class="text-gray-600 text-xl font-semibold">No orders found</p>
                    <p class="text-gray-500 mt-2">Try adjusting your filters or check back later</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>

@include('partials.footer')

@endsection

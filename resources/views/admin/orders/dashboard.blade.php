@extends('layouts.app')

@section('content')
<div class="admin-container">
    <div class="admin-header">
        <div class="container px-4 mx-auto max-w-7xl">
            <h1 class="text-3xl font-bold mb-0 text-white">Order Dashboard</h1>
        </div>
    </div>

    <div class="container px-4 mx-auto max-w-7xl">
        <!-- Key Metrics -->
        <div class="metrics-grid mb-4">
            <div class="metric-card">
                <div class="metric-icon" style="background: #007bff;">📊</div>
                <div class="metric-content">
                    <div class="metric-label">Total Orders</div>
                    <div class="metric-value">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #ffc107;">⏳</div>
                <div class="metric-content">
                    <div class="metric-label">Pending</div>
                    <div class="metric-value">{{ $stats['pending'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #17a2b8;">⚙️</div>
                <div class="metric-content">
                    <div class="metric-label">Processing</div>
                    <div class="metric-value">{{ $stats['processing'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #28a745;">✓</div>
                <div class="metric-content">
                    <div class="metric-label">Completed</div>
                    <div class="metric-value">{{ $stats['completed'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #dc3545;">✗</div>
                <div class="metric-content">
                    <div class="metric-label">Cancelled</div>
                    <div class="metric-value">{{ $stats['cancelled'] ?? 0 }}</div>
                </div>
            </div>
            <div class="metric-card">
                <div class="metric-icon" style="background: #d4a574;">💰</div>
                <div class="metric-content">
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value">Rp{{ number_format($stats['revenue_total'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Payment Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div class="bg-white rounded-lg shadow p-6">
                <h5 class="text-lg font-bold mb-4 text-gray-800">Payment Status</h5>
                <div class="mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Confirmed</span>
                        <strong class="text-green-600">{{ $stats['payment_confirmed'] ?? 0 }}</strong>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($stats['payment_confirmed'] ?? 0) / max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) * 100 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Pending</span>
                        <strong class="text-yellow-600">{{ $stats['payment_pending'] ?? 0 }}</strong>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ ($stats['payment_pending'] ?? 0) / max(($stats['payment_confirmed'] ?? 0) + ($stats['payment_pending'] ?? 0), 1) * 100 }}%"></div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h5 class="text-lg font-bold mb-4 text-gray-800">Today's Performance</h5>
                <p class="mb-3">
                    <strong class="text-gray-700">Today's Revenue:</strong>
                    <span class="text-green-600 text-lg font-bold block mt-1">Rp{{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}</span>
                </p>
                <hr>
                <p class="text-sm text-gray-500 mb-0 mt-3">Real-time metrics update every 5 minutes</p>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h5 class="text-lg font-bold mb-0 text-gray-800">Recent Orders</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Order ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Payment</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-medium">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4 text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $order->payment_status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, H:i') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium inline-block">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No orders yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 text-center text-muted small">
            <p>Last updated: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>

<style>
    .admin-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
        padding: 32px 0;
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
        border-radius: 8px;
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

    .card {
        border: none;
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0;
        border-bottom: 2px solid #e9ecef;
    }

    .table th {
        color: #666;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .badge {
        font-size: 12px;
        padding: 6px 12px;
    }

    .progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
    }
</style>

@include('partials.footer')

@endsection

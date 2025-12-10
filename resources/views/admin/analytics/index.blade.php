@extends('layouts.app')

@section('content')
<style>
    .analytics-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
    }
    
    .analytics-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }

    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-box {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid;
        transition: transform 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-change {
        font-size: 13px;
        font-weight: 600;
        margin-top: 8px;
    }

    .stat-change.positive {
        color: #10b981;
    }

    .stat-change.negative {
        color: #ef4444;
    }

    .export-section {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }

    .btn-export-large {
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border: none;
    }

    .btn-csv {
        background: #10b981;
        color: white;
    }

    .btn-csv:hover {
        background: #059669;
        color: white;
        text-decoration: none;
    }

    .btn-pdf {
        background: #ef4444;
        color: white;
    }

    .btn-pdf:hover {
        background: #dc2626;
        color: white;
        text-decoration: none;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f9fafb;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
    }

    .data-table td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
    }

    .data-table tr:hover {
        background: #f9fafb;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    .date-filter {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .date-filter input,
    .date-filter select {
        padding: 10px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }

    .date-filter input:focus,
    .date-filter select:focus {
        outline: none;
        border-color: #8b6f47;
        box-shadow: 0 0 0 3px rgba(139, 111, 71, 0.1);
    }

    .btn-filter-date {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-filter-date:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 111, 71, 0.3);
    }
</style>

<div class="analytics-header">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-4xl font-black mb-2">Analytics Dashboard</h1>
                <p class="text-[#e8d4c0]">Comprehensive data tracking and insights</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.orders.index') }}" class="btn-export-large btn-csv">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="analytics-container py-12">
    <div class="container mx-auto px-4">
        
        <!-- Date Range Filter -->
        <div class="chart-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-calendar-alt mr-2"></i>Date Range Filter</h3>
            <form method="GET" action="{{ route('admin.analytics.index') }}" class="date-filter">
                <input type="date" name="start_date" value="{{ request('start_date', date('Y-m-01')) }}" />
                <input type="date" name="end_date" value="{{ request('end_date', date('Y-m-d')) }}" />
                <select name="period">
                    <option value="">Custom Range</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                </select>
                <button type="submit" class="btn-filter-date">
                    <i class="fas fa-filter"></i> Apply
                </button>
            </form>
        </div>

        <!-- Key Metrics -->
        <div class="stat-grid">
            <div class="stat-box" style="border-left-color: #3b82f6;">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">Rp{{ number_format($analytics['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $analytics['revenue_growth'] ?? 0 }}% from last period
                </div>
            </div>
            <div class="stat-box" style="border-left-color: #10b981;">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">{{ $analytics['total_orders'] ?? 0 }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> {{ $analytics['orders_growth'] ?? 0 }}% from last period
                </div>
            </div>
            <div class="stat-box" style="border-left-color: #f59e0b;">
                <div class="stat-label">Average Order Value</div>
                <div class="stat-value">Rp{{ number_format($analytics['avg_order_value'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="stat-box" style="border-left-color: #8b5cf6;">
                <div class="stat-label">Total Customers</div>
                <div class="stat-value">{{ $analytics['total_customers'] ?? 0 }}</div>
            </div>
            <div class="stat-box" style="border-left-color: #ec4899;">
                <div class="stat-label">Products Sold</div>
                <div class="stat-value">{{ $analytics['products_sold'] ?? 0 }}</div>
            </div>
            <div class="stat-box" style="border-left-color: #14b8a6;">
                <div class="stat-label">Completion Rate</div>
                <div class="stat-value">{{ $analytics['completion_rate'] ?? 0 }}%</div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="export-section">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-download mr-2"></i>Export Analytics Data</h3>
            <p class="text-gray-600 mb-4">Download comprehensive analytics reports in your preferred format</p>
            <div class="flex gap-3 flex-wrap">
                <a href="{{ route('admin.analytics.export', ['format' => 'csv', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn-export-large btn-csv">
                    <i class="fas fa-file-csv"></i> Export to CSV
                </a>
                <a href="{{ route('admin.analytics.export', ['format' => 'pdf', 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn-export-large btn-pdf">
                    <i class="fas fa-file-pdf"></i> Export to PDF
                </a>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="chart-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-chart-line mr-2"></i>Revenue Trend</h3>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Orders Chart -->
        <div class="chart-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-chart-bar mr-2"></i>Orders by Status</h3>
            <div class="chart-container">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="chart-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-trophy mr-2"></i>Top Selling Products</h3>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Product Name</th>
                            <th>Units Sold</th>
                            <th>Revenue</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics['top_products'] ?? [] as $index => $product)
                            <tr>
                                <td><strong>#{{ $index + 1 }}</strong></td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['quantity'] }} units</td>
                                <td class="font-semibold text-gray-900">Rp{{ number_format($product['revenue'], 0, ',', '.') }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-[#d4a574] to-[#8b6f47] h-2 rounded-full" style="width: {{ $product['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-sm">{{ $product['percentage'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="chart-card">
            <h3 class="text-xl font-bold mb-4 text-gray-800"><i class="fas fa-history mr-2"></i>Recent Order Activity</h3>
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analytics['recent_orders'] ?? [] as $order)
                            <tr>
                                <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $order->customer_name }}</td>
                                <td class="font-semibold text-gray-900">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">No recent orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@include('partials.footer')

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($analytics['revenue_labels'] ?? []),
            datasets: [{
                label: 'Revenue (Rp)',
                data: @json($analytics['revenue_data'] ?? []),
                borderColor: '#8b6f47',
                backgroundColor: 'rgba(139, 111, 71, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Orders Chart
    // Orders by Status Chart
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersData = @json($analytics['orders_by_status'] ?? []);
    new Chart(ordersCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
            datasets: [{
                data: [
                    ordersData.pending || 0, 
                    ordersData.processing || 0, 
                    ordersData.completed || 0, 
                    ordersData.cancelled || 0
                ],
                backgroundColor: ['#fbbf24', '#06b6d4', '#10b981', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

@endsection

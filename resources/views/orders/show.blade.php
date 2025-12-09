@extends('layouts.app')

@section('content')
<style>
    .order-detail-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
    }

    .order-detail-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }

    .detail-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .detail-title {
        font-size: 18px;
        font-weight: 700;
        color: #352b06;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 600;
    }

    .detail-value {
        color: #352b06;
        font-weight: 600;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef08a;
        color: #854d0e;
    }

    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .items-list {
        background: #f9f7f4;
        border-radius: 8px;
        overflow: hidden;
    }

    .item-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        padding: 12px 16px;
        border-bottom: 1px solid #e0d5c7;
        align-items: center;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-row.header {
        background: #e8dcc8;
        font-weight: 700;
        color: #352b06;
    }

    .item-name {
        color: #352b06;
        font-weight: 600;
    }

    .item-qty {
        text-align: center;
        color: #666;
    }

    .item-price {
        text-align: right;
        color: #666;
    }

    .item-subtotal {
        text-align: right;
        color: #352b06;
        font-weight: 700;
    }

    .total-section {
        background: white;
        padding: 16px;
        text-align: right;
        border-top: 2px solid #f0f0f0;
    }

    .total-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 8px;
        gap: 32px;
    }

    .total-label {
        color: #666;
    }

    .total-amount {
        min-width: 120px;
        color: #352b06;
        font-weight: 600;
    }

    .total-final {
        display: flex;
        justify-content: flex-end;
        gap: 32px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
        font-size: 18px;
        font-weight: 700;
        color: #352b06;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-secondary {
        background: #6b7280;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #4b5563;
    }

    .notes-section {
        background: #efe8df;
        padding: 16px;
        border-radius: 8px;
        border-left: 4px solid #d4a574;
        margin-top: 16px;
    }

    .notes-title {
        font-weight: 700;
        color: #352b06;
        margin-bottom: 8px;
    }

    .notes-content {
        color: #666;
        line-height: 1.6;
    }
</style>

<div class="order-detail-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black mb-2">Order #{{ $order->id }}</h1>
        <p class="text-amber-100">{{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="order-detail-container py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Order Status Card -->
            <div class="detail-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <h2 class="detail-title" style="margin: 0;">Order Status</h2>
                    <span class="status-badge status-{{ $order->status ?? 'pending' }}">
                        {{ ucfirst($order->status ?? 'pending') }}
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value">{{ ucfirst($order->payment_status ?? 'unpaid') }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="detail-card">
                <h2 class="detail-title">Order Items</h2>

                <div class="items-list">
                    <div class="item-row header">
                        <div>Product</div>
                        <div class="item-qty">Qty</div>
                        <div class="item-price">Price</div>
                        <div class="item-subtotal">Subtotal</div>
                    </div>

                    @foreach($order->items as $item)
                        <div class="item-row">
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-qty">{{ $item->qty }}</div>
                            <div class="item-price">Rp {{ number_format($item->base_price, 0, ',', '.') }}</div>
                            <div class="item-subtotal">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                        </div>
                    @endforeach

                    <div class="total-section">
                        <div class="total-final">
                            <span>Grand Total:</span>
                            <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="notes-section">
                    <div class="notes-title">Order Notes:</div>
                    <div class="notes-content">{{ $order->notes }}</div>
                </div>
            @endif

            <!-- Order Proof QR -->
            <div class="detail-card">
                <h2 class="detail-title">Order Proof (QR)</h2>
                <p class="text-sm text-gray-600 mb-3">Scan to verify order ID and owner.</p>
                <div class="flex justify-center">
                    {!! $orderQr !!}
                </div>
            </div>

            <!-- Actions -->
            <div class="action-buttons">
                <a href="{{ route('orders.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>

                @if($order->status === 'pending')
                    <a href="{{ route('orders.payment', $order) }}" class="btn-primary">
                        <i class="fas fa-credit-card"></i> Make Payment
                    </a>
                @endif

                <form action="{{ route('orders.reorder', $order) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-redo"></i> Reorder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

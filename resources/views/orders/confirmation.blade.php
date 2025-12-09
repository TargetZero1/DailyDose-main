@extends('layouts.app')

@section('content')
<style>
    .confirmation-container {
        background: linear-gradient(135deg, #f5f3f0 0%, #eae6e0 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .confirmation-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        padding: 40px;
        max-width: 550px;
        width: 100%;
        text-align: center;
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 40px;
        animation: scaleIn 0.6s ease-out;
    }

    @keyframes scaleIn {
        from {
            transform: scale(0.5);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .confirmation-title {
        color: #352b06;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .confirmation-subtitle {
        color: #666;
        font-size: 16px;
        margin-bottom: 30px;
    }

    .order-details {
        background: #f8f7f5;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e0d5c7;
        font-size: 14px;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        color: #352b06;
        font-weight: 700;
    }

    .items-preview {
        background: #faf8f5;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 20px;
        max-height: 200px;
        overflow-y: auto;
        text-align: left;
    }

    .item-preview {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e0d5c7;
        font-size: 14px;
    }

    .item-preview:last-child {
        border-bottom: none;
    }

    .item-name {
        color: #352b06;
        font-weight: 600;
    }

    .item-price {
        color: #d4a574;
        font-weight: 700;
    }

    .qr-code-container {
        background: white;
        border: 2px solid #e0d5c7;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .qr-code-container svg {
        max-width: 250px;
        height: auto;
    }

    .qr-label {
        color: #666;
        font-size: 12px;
        margin-top: 10px;
        text-align: center;
    }

    .timer {
        color: #d4a574;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .whatsapp-button {
        background: linear-gradient(135deg, #25D366 0%, #1ea852 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 28px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
    }

    .whatsapp-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
        color: white;
    }

    .back-button {
        background: #6b7280;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px 28px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .back-button:hover {
        background: #4b5563;
        transform: translateY(-2px);
    }

    .countdown {
        font-size: 48px;
        color: #d4a574;
        font-weight: 700;
        margin: 20px 0;
        font-variant-numeric: tabular-nums;
    }

    .order-id-badge {
        display: inline-block;
        background: #e0d5c7;
        color: #352b06;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 18px;
        margin-bottom: 12px;
    }
</style>

<div class="confirmation-container">
    <div class="confirmation-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <!-- Order ID Badge -->
        <div class="order-id-badge">Order #{{ $order->id }}</div>

        <!-- Title -->
        <h1 class="confirmation-title">Order Confirmed!</h1>
        <p class="confirmation-subtitle">Your order has been placed successfully</p>

        <!-- Order Items Preview -->
        <div class="items-preview">
            @foreach($order->items as $item)
                <div class="item-preview">
                    <div>
                        <div class="item-name">{{ $item->name }}</div>
                        <div style="color: #999; font-size: 12px;">Qty: {{ $item->qty }}</div>
                    </div>
                    <div class="item-price">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>

        <!-- Order Details -->
        <div class="order-details">
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-calendar mr-2"></i>Order Date</span>
                <span class="detail-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-tag mr-2"></i>Status</span>
                <span class="detail-value">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="fas fa-money-bill mr-2"></i>Total Amount</span>
                <span class="detail-value">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- QR Code -->
        <div class="qr-code-container">
            <div style="text-align: center;">
                @if(isset($orderQr) && $orderQr)
                    {!! $orderQr !!}
                    <div class="qr-label" style="margin-top: 12px;">Scan to verify order</div>
                @else
                    <div style="padding: 40px; color: #999;">
                        <i class="fas fa-qrcode" style="font-size: 48px; margin-bottom: 12px;"></i>
                        <p>QR Code unavailable</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Timer -->
        <div class="timer">
            Redirecting to WhatsApp in: <span class="countdown" id="countdown">3</span>
        </div>

        <!-- WhatsApp Button -->
        <a href="{{ $whatsappLink }}" target="_blank" class="whatsapp-button" id="whatsappBtn">
            <i class="fab fa-whatsapp"></i> Complete Payment via WhatsApp
        </a>

        <!-- Back Button -->
        <a href="{{ route('orders.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> View All Orders
        </a>
    </div>
</div>

<script>
    let countdown = 3;
    const countdownEl = document.getElementById('countdown');
    const whatsappBtn = document.getElementById('whatsappBtn');

    // Countdown timer
    const timer = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;

        if (countdown === 0) {
            clearInterval(timer);
            // Redirect to WhatsApp
            whatsappBtn.click();
        }
    }, 1000);

    // Allow manual click before countdown finishes
    whatsappBtn.addEventListener('click', (e) => {
        clearInterval(timer);
    });
</script>

@include('partials.footer')

@endsection

@extends('layouts.app')

@section('content')
<style>
    .payment-hero {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
    }
    
    .payment-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .payment-method {
        border: 3px solid transparent;
        border-radius: 12px;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .payment-method:hover {
        border-color: #d4a574;
        background: #f9f7f4;
    }
    
    .payment-method.selected {
        border-color: #d4a574;
        background: #f5f1e8;
    }
    
    .payment-method i {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
        color: #d4a574;
    }
    
    .btn-payment {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 1.2rem;
        font-size: 1.1rem;
        font-weight: bold;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .btn-payment:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(212, 165, 116, 0.3);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .summary-row.total {
        font-weight: bold;
        font-size: 1.2rem;
        border-bottom: 2px solid #d4a574;
        padding: 1rem 0;
        color: #d4a574;
    }
    
    .summary-row.total span:last-child {
        color: #d4a574;
    }
    
    .info-box {
        background: linear-gradient(135deg, #f5f1e8 0%, #ebe5d9 100%);
        border-left: 4px solid #d4a574;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .success-badge {
        background: #d1fae5;
        color: #065f46;
        padding: 0.75rem 1.5rem;
        border-radius: 20px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<!-- Payment Hero Section -->
<section class="payment-hero text-white py-12">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl md:text-5xl font-black mb-2">Order Confirmation & Payment</h1>
        <p class="text-amber-100 text-lg">Review your order and complete payment via WhatsApp</p>
    </div>
</section>

<!-- Payment Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Payment Content -->
            <div class="lg:col-span-2">
                <!-- Info Box -->
                <div class="info-box mb-6">
                    <h3 class="font-bold text-amber-900 mb-2">
                        <i class="fas fa-check-circle mr-2"></i>Order #{{ $order->id }}
                    </h3>
                    <p class="text-sm text-gray-700">Thank you for your order! Please proceed with payment via WhatsApp.</p>
                </div>
                
                <!-- Order Items -->
                <div class="payment-card mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-list text-amber-700 mr-2"></i>Order Items
                    </h2>
                    
                    @foreach($order->items as $item)
                        <div class="order-item">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $item->name }}</h4>
                                <p class="text-sm text-gray-600">Qty: {{ $item->qty }}</p>
                                @if($item->options)
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ json_encode($item->options) }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-700">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Rp {{ number_format($item->base_price, 0, ',', '.') }} each
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Special Notes -->
                @if($order->notes)
                    <div class="payment-card mb-6">
                        <h3 class="font-bold text-gray-900 mb-2">
                            <i class="fas fa-sticky-note text-amber-700 mr-2"></i>Special Notes
                        </h3>
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                @endif
                
                <!-- Payment Method Selection -->
                <div class="payment-card mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-credit-card text-amber-700 mr-2"></i>Payment Method
                    </h2>
                    
                    <p class="text-gray-700 mb-4">Choose your preferred payment method:</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- WhatsApp Payment -->
                        <div class="payment-method selected" onclick="selectPayment('whatsapp', this)">
                            <i class="fab fa-whatsapp text-green-500"></i>
                            <h3 class="font-bold text-gray-900">WhatsApp Payment</h3>
                            <p class="text-sm text-gray-600 mt-1">Quick & Easy</p>
                        </div>
                        
                        <!-- Bank Transfer -->
                        <div class="payment-method" onclick="selectPayment('bank', this)">
                            <i class="fas fa-university text-blue-600"></i>
                            <h3 class="font-bold text-gray-900">Bank Transfer</h3>
                            <p class="text-sm text-gray-600 mt-1">Coming Soon</p>
                        </div>
                    </div>
                    
                    <input type="hidden" id="selected-payment" value="whatsapp">
                </div>
                
                <!-- Payment Button -->
                <a href="{{ route('orders.pay.whatsapp', $order) }}" class="btn-payment inline-block text-center">
                    <i class="fab fa-whatsapp mr-2"></i>Pay with WhatsApp
                </a>
                
                <div class="text-center mt-4">
                    <a href="{{ route('menu') }}" class="text-amber-700 hover:text-amber-900 transition">
                        <i class="fas fa-arrow-left mr-1"></i>Continue Shopping
                    </a>
                </div>
            </div>
            
            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="payment-card sticky top-24">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-receipt text-amber-700 mr-2"></i>Order Summary
                    </h3>
                    
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Tax (10%):</span>
                        <span>Rp {{ number_format($order->total * 0.1, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Delivery:</span>
                        <span>Free</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total Amount:</span>
                        <span>Rp {{ number_format($order->total * 1.1, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Payment Info -->
                    <div class="mt-6 pt-6 border-t-2 border-gray-200">
                        <div class="mb-4">
                            <h4 class="font-bold text-gray-900 mb-2">
                                <i class="fab fa-whatsapp text-green-500 mr-2"></i>WhatsApp Payment
                            </h4>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                You'll be redirected to WhatsApp to complete the payment. Our team will respond to confirm your order.
                            </p>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded p-3">
                            <p class="text-xs text-blue-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Tip:</strong> Have your order number ready when contacting us.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="mt-6 pt-6 border-t-2 border-gray-200">
                        <h4 class="font-bold text-gray-900 mb-3">Need Help?</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-phone text-amber-700 mr-2"></i>
                                +62 882-009-759-102
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-clock text-amber-700 mr-2"></i>
                                7 AM - 9 PM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Security & Trust Section -->
<section class="bg-white border-t py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900">Secure Payment</h3>
                <p class="text-gray-600 text-sm mt-2">Your payment information is secure</p>
            </div>
            
            <div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900">Protected</h3>
                <p class="text-gray-600 text-sm mt-2">Verified by WhatsApp Business</p>
            </div>
            
            <div>
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-amber-700 text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900">24/7 Support</h3>
                <p class="text-gray-600 text-sm mt-2">We're here to help anytime</p>
            </div>
        </div>
    </div>
</section>

<script>
    function selectPayment(method, element) {
        // Remove selected class from all payment methods
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Add selected class to clicked element
        element.classList.add('selected');
        
        // Update hidden input
        document.getElementById('selected-payment').value = method;
    }
</script>

@include('partials.footer')

@endsection

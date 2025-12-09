@extends('layouts.app')

@section('content')

<style>
    * {
        box-sizing: border-box;
    }

    .checkout-container {
        background: linear-gradient(135deg, #fef3e2 0%, #fae9d0 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .checkout-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .checkout-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 24px;
        border-radius: 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .checkout-header h1 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .checkout-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 14px;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    @media (min-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1fr 400px;
        }
    }

    .checkout-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #352b06;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #d4a574;
    }

    .order-item {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: #fafafa;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 2px solid #f0f0f0;
        transition: all 0.3s;
    }

    .order-item:hover {
        border-color: #d4a574;
    }

    .order-item-img {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .order-item-details {
        flex: 1;
        min-width: 0;
    }

    .order-item-name {
        font-weight: 700;
        color: #352b06;
        font-size: 16px;
        margin: 0 0 6px 0;
        word-wrap: break-word;
    }

    .order-item-qty {
        color: #666;
        font-size: 14px;
        margin: 0 0 6px 0;
    }

    .order-item-price {
        color: #d4a574;
        font-weight: 700;
        font-size: 18px;
    }

    .order-item-remove {
        align-self: flex-start;
        background: #fee;
        color: #991b1b;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .order-item-remove:hover {
        background: #fcc;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #352b06;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-label i {
        color: #d4a574;
        margin-right: 6px;
        width: 16px;
    }

    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #d4a574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .terms-container {
        background: #fff8f0;
        border: 2px solid #d4a574;
        border-radius: 10px;
        padding: 16px;
        display: flex;
        gap: 12px;
        align-items: start;
    }

    .terms-checkbox {
        width: 20px;
        height: 20px;
        accent-color: #d4a574;
        cursor: pointer;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .terms-label {
        font-size: 13px;
        color: #666;
        line-height: 1.5;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
        font-weight: 500;
    }

    .summary-value {
        font-weight: 700;
        color: #352b06;
    }

    .summary-total {
        padding: 16px 0;
        border-top: 2px solid #e0e0e0;
        margin-top: 12px;
    }

    .summary-total .summary-label {
        font-size: 18px;
        font-weight: 700;
        color: #352b06;
    }

    .summary-total .summary-value {
        font-size: 24px;
        color: #d4a574;
    }

    .discount-box {
        background: #fff8f0;
        border: 2px solid #d4a574;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .discount-title {
        font-weight: 700;
        color: #352b06;
        margin: 0 0 12px 0;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .discount-input-group {
        display: flex;
        gap: 8px;
    }

    .discount-input {
        flex: 1;
        padding: 10px 12px;
        border: 2px solid #d4a574;
        border-radius: 8px;
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .discount-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .discount-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.4);
    }

    .discount-message {
        margin-top: 10px;
        font-size: 13px;
        font-weight: 500;
    }

    .remove-discount {
        margin-top: 8px;
        background: none;
        border: none;
        color: #991b1b;
        font-size: 13px;
        cursor: pointer;
        text-decoration: underline;
    }

    .payment-info {
        background: #f0fdf4;
        border: 2px solid #86efac;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }

    .payment-info-title {
        font-weight: 700;
        color: #166534;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
    }

    .payment-info-text {
        font-size: 13px;
        color: #166534;
        line-height: 1.5;
        margin: 0;
    }

    .submit-btn {
        width: 100%;
        padding: 16px 24px;
        background: linear-gradient(135deg, #25d366 0%, #1ea852 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }

    .submit-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .secure-text {
        text-align: center;
        color: #999;
        font-size: 12px;
        margin-top: 12px;
    }

    .empty-cart {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-cart i {
        font-size: 64px;
        color: #e0e0e0;
        margin-bottom: 16px;
    }

    .empty-cart p {
        color: #999;
        font-size: 16px;
        margin: 0 0 20px 0;
    }

    .back-btn {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.4);
    }

    @media (max-width: 640px) {
        .checkout-header h1 {
            font-size: 22px;
        }

        .order-item {
            flex-direction: column;
            align-items: start;
        }

        .order-item-img {
            width: 100%;
            height: 180px;
        }

        .order-item-remove {
            align-self: flex-end;
        }

        .discount-input-group {
            flex-direction: column;
        }

        .discount-btn {
            width: 100%;
        }
    }
</style>

<div class="checkout-container">
    <div class="checkout-wrapper">
        <!-- Header -->
        <div class="checkout-header">
            <h1>
                <i class="fas fa-shopping-bag"></i>
                Checkout
            </h1>
            <p>Complete your order and payment details</p>
        </div>

        <div class="checkout-grid">
            <!-- Left Column: Order Items & Customer Form -->
            <div>
                <!-- Order Items -->
                <div class="checkout-card">
                    <h2 class="card-title">
                        <i class="fas fa-list-ul"></i>
                        Order Items
                    </h2>
                    <div id="order-items-list">
                        <!-- Items populated by JavaScript -->
                    </div>
                </div>

                <!-- Customer Information Form -->
                <div class="checkout-card" style="margin-top: 20px;">
                    <h2 class="card-title">
                        <i class="fas fa-user-circle"></i>
                        Your Information
                    </h2>

                    <form id="checkout-form">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user"></i>
                                Full Name *
                            </label>
                            <input
                                type="text"
                                id="customer_name"
                                class="form-input"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Phone Number *
                            </label>
                            <input
                                type="tel"
                                id="customer_phone"
                                class="form-input"
                                placeholder="08XXXXXXXXXX"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Delivery Address *
                            </label>
                            <textarea
                                id="customer_address"
                                class="form-textarea"
                                placeholder="Enter complete delivery address"
                                required
                            ></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sticky-note"></i>
                                Special Notes (Optional)
                            </label>
                            <textarea
                                id="notes"
                                class="form-textarea"
                                placeholder="Any special requests?"
                            ></textarea>
                        </div>

                        <div class="terms-container">
                            <input type="checkbox" id="terms" class="terms-checkbox" required>
                            <label for="terms" class="terms-label">
                                I agree to the terms and conditions. Payment will be completed via WhatsApp with customer service.
                            </label>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Order Summary (Sticky on Desktop) -->
            <div>
                <div class="checkout-card" style="position: sticky; top: 20px;">
                    <h3 class="card-title">
                        <i class="fas fa-receipt"></i>
                        Order Summary
                    </h3>

                    <!-- Summary Rows -->
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value" id="sidebar-subtotal">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Tax (11%):</span>
                        <span class="summary-value" id="sidebar-tax">Rp 0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Delivery:</span>
                        <span class="summary-value" style="color: #10b981;">Free</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label"><i class="fas fa-gift" style="color: #d4a574; margin-right: 4px;"></i>Discount:</span>
                        <span class="summary-value" id="sidebar-discount" style="color: #10b981;">- Rp 0</span>
                    </div>

                    <div class="summary-row summary-total">
                        <span class="summary-label">Total Due:</span>
                        <span class="summary-value" id="sidebar-total">Rp 0</span>
                    </div>

                    <!-- Discount Code Section -->
                    <div class="discount-box">
                        <h4 class="discount-title">
                            <i class="fas fa-tag"></i>
                            Have a Discount Code?
                        </h4>
                        <div class="discount-input-group">
                            <input
                                type="text"
                                id="discount-code"
                                class="discount-input"
                                placeholder="ENTER CODE"
                            >
                            <button type="button" onclick="applyDiscount()" class="discount-btn">
                                Apply
                            </button>
                        </div>
                        <div id="discount-message" class="discount-message"></div>
                        <button type="button" onclick="removeDiscount()" id="remove-discount-btn" class="remove-discount" style="display: none;">
                            <i class="fas fa-times"></i> Remove discount
                        </button>
                    </div>

                    <!-- Payment Method Info -->
                    <div class="payment-info">
                        <h4 class="payment-info-title">
                            <i class="fab fa-whatsapp"></i>
                            Payment Method
                        </h4>
                        <p class="payment-info-text">
                            You'll be redirected to WhatsApp to complete your order with our customer service.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button
                        id="submit-order-btn"
                        type="button"
                        onclick="submitOrder()"
                        class="submit-btn"
                    >
                        <i class="fab fa-whatsapp" style="font-size: 24px;"></i>
                        Complete Order via WhatsApp
                    </button>

                    <p class="secure-text">
                        <i class="fas fa-lock"></i> Secure checkout
                    </p>
                </div>
            </div>
        </div>
            </div>
        </div>

        <script>
    let cart = [];
    let appliedDiscount = null;

    // Format Rupiah
    function formatRupiah(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Load and display cart
    function loadCheckout() {
        cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const itemsList = document.getElementById('order-items-list');

        if (cart.length === 0) {
            itemsList.innerHTML = `
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty</p>
                    <a href="{{ route('menu') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i> Back to Menu
                    </a>
                </div>
            `;
            document.getElementById('submit-order-btn').disabled = true;
            return;
        }

        itemsList.innerHTML = '';

        cart.forEach((item, index) => {
            const itemTotal = (item.harga || 0) * (item.qty || 0);

            const itemDiv = document.createElement('div');
            itemDiv.className = 'order-item';
            const defaultImg = '{{ asset("img/default-product.svg") }}';
            itemDiv.innerHTML = `
                <img src="${item.gambar || defaultImg}" alt="${item.namaProduct}" class="order-item-img" onerror="this.src='${defaultImg}'" >
                <div class="order-item-details">
                    <h4 class="order-item-name">${item.namaProduct}</h4>
                    <p class="order-item-qty">Quantity: ${item.qty}</p>
                    <p class="order-item-price">${formatRupiah(itemTotal)}</p>
                </div>
                <button onclick="removeItem(${index})" class="order-item-remove">
                    <i class="fas fa-trash"></i> Remove
                </button>
            `;
            itemsList.appendChild(itemDiv);
        });

        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        cart.forEach(item => {
            subtotal += (item.harga || 0) * (item.qty || 0);
        });

        const tax = Math.round(subtotal * 0.11);
        let discountAmount = 0;
        
        if (appliedDiscount) {
            discountAmount = appliedDiscount.amount;
        }

        const total = subtotal + tax - discountAmount;

        document.getElementById('sidebar-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('sidebar-tax').textContent = formatRupiah(tax);
        document.getElementById('sidebar-discount').textContent = '- ' + formatRupiah(discountAmount);
        document.getElementById('sidebar-total').textContent = formatRupiah(total);
    }

    function removeItem(index) {
        if (confirm('Remove this item from cart?')) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCheckout();
            
            if (window.updateGlobalCart) {
                window.updateGlobalCart();
            }
        }
    }

    async function applyDiscount() {
        const code = document.getElementById('discount-code').value.trim().toUpperCase();
        const messageEl = document.getElementById('discount-message');
        
        if (!code) {
            messageEl.innerHTML = '<span style="color: #991b1b;"><i class="fas fa-exclamation-circle"></i> Please enter a discount code</span>';
            return;
        }

        let subtotal = 0;
        cart.forEach(item => {
            subtotal += (item.harga || 0) * (item.qty || 0);
        });

        try {
            const response = await fetch('{{ route("discounts.validate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    subtotal: subtotal,
                    type: 'products'
                })
            });

            const data = await response.json();

            if (data.success) {
                appliedDiscount = data.discount;
                messageEl.innerHTML = `<span style="color: #10b981;"><i class="fas fa-check-circle"></i> ${data.message} - You saved ${data.discount.formatted_amount}!</span>`;
                document.getElementById('remove-discount-btn').style.display = 'block';
                document.getElementById('discount-code').disabled = true;
                updateTotals();
            } else {
                messageEl.innerHTML = `<span style="color: #991b1b;"><i class="fas fa-exclamation-circle"></i> ${data.message}</span>`;
            }
        } catch (error) {
            messageEl.innerHTML = '<span style="color: #991b1b;"><i class="fas fa-exclamation-circle"></i> Error applying discount</span>';
            console.error('Discount error:', error);
        }
    }

    function removeDiscount() {
        appliedDiscount = null;
        document.getElementById('discount-code').value = '';
        document.getElementById('discount-code').disabled = false;
        document.getElementById('discount-message').innerHTML = '';
        document.getElementById('remove-discount-btn').style.display = 'none';
        updateTotals();
    }

    function submitOrder() {
        const form = document.getElementById('checkout-form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const name = document.getElementById('customer_name').value;
        const phone = document.getElementById('customer_phone').value;
        const address = document.getElementById('customer_address').value;
        const notes = document.getElementById('notes').value;

        if (!document.getElementById('terms').checked) {
            alert('Please agree to the terms and conditions');
            return;
        }

        // Disable button to prevent double submission
        const submitBtn = document.getElementById('submit-order-btn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Order...';

        const orderData = {
            name: name,
            phone: phone,
            address: address,
            notes: notes,
            items: cart,
            discount: appliedDiscount
        };

        console.log('Submitting order with data:', orderData);

        fetch('{{ route("orders.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(orderData)
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            console.log('Order response:', data);
            if (data.status === 'ok' && data.redirect) {
                localStorage.removeItem('cart');
                if (window.updateGlobalCart) {
                    window.updateGlobalCart();
                }
                window.location.href = data.redirect;
            } else {
                alert('Error creating order: ' + (data.error || 'Unknown error'));
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error('Order error:', err);
            alert('Failed to create order: ' + err.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        loadCheckout();
        
        const discountInput = document.getElementById('discount-code');
        if (discountInput) {
            discountInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    applyDiscount();
                }
            });
        }
    });
</script>

@endsection


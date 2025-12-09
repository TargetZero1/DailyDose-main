<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DailyDose</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @keyframes slideUp {
            from { transform: translateY(100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .cart-modal-enter { animation: slideUp 0.3s ease; }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body class="bg-amber-50">
    @include('partials.header')
    @include('partials.promotion-banner')
    @include('partials.toast')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Floating Cart Button (Global) - Hidden for Admins -->
    @if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik'))
        <!-- Admin users cannot make purchases -->
    @else
    <button
        id="global-cart-btn"
        style="position: fixed; bottom: 30px; right: 30px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; width: 50px; height: 50px; border-radius: 50%; border: none; cursor: pointer; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transition: all 0.3s ease; font-size: 20px; display: flex; align-items: center; justify-content: center; z-index: 40;"
        onclick="toggleCartModal()"
        onmouseover="this.style.boxShadow='0 12px 32px rgba(212, 165, 116, 0.5)'; this.style.transform='scale(1.1)';"
        onmouseout="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.2)'; this.style.transform='scale(1)';"
    >
        <i class="fas fa-shopping-cart"></i>
        <span id="global-cart-count" style="position: absolute; top: -8px; right: -8px; background: #ff4757; color: white; font-size: 11px; font-weight: 700; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">0</span>
    </button>
    @endif

    <!-- Cart Modal (Global) -->
    <div id="global-cart-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto cart-modal-enter">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white p-6 sticky top-0 z-10">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold">
                        <i class="fas fa-shopping-cart mr-2"></i>Your Cart
                    </h2>
                    <button onclick="toggleCartModal()" class="text-2xl hover:text-amber-200 transition">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Cart Items -->
            <div class="p-6">
                <div id="global-cart-items" class="space-y-4 mb-6">
                    <!-- Will be populated by JavaScript -->
                </div>

                <!-- Total -->
                <div class="border-t-2 border-gray-200 pt-4">
                    <h3 id="global-cart-total" class="text-2xl font-bold text-gray-800 text-right mb-6">
                        Total: Rp 0
                    </h3>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button
                            onclick="toggleCartModal()"
                            class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 font-bold rounded-lg hover:border-amber-700 hover:text-amber-700 transition"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                        </button>
                        <a
                            href="{{ route('checkout') }}"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition text-center"
                        >
                            <i class="fas fa-credit-card mr-2"></i>Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/app.js')

    <script>
        function toggleCartModal() {
            const modal = document.getElementById('global-cart-modal');
            modal.classList.toggle('hidden');
            if (!modal.classList.contains('hidden')) {
                updateGlobalCart();
            }
        }

        function updateGlobalCart() {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const cartItems = document.getElementById('global-cart-items');
            const cartCount = document.getElementById('global-cart-count');
            const cartTotal = document.getElementById('global-cart-total');

            // Update count
            const totalItems = cart.reduce((sum, item) => sum + (item.qty || 0), 0);
            cartCount.textContent = totalItems;

            // Update items display
            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="text-center text-gray-500 py-8"><i class="fas fa-inbox text-4xl mb-2"></i><p>Your cart is empty</p></div>';
                cartTotal.textContent = 'Total: Rp 0';
            } else {
                let total = 0;
                cartItems.innerHTML = '';

                cart.forEach((item, index) => {
                    const itemTotal = (item.harga || 0) * (item.qty || 0);
                    total += itemTotal;

                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center gap-4 p-4 bg-gray-50 rounded-lg';
                    itemDiv.innerHTML = `
                        <img src="${item.gambar || ''}" alt="${item.namaProduct}" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-800">${item.namaProduct}</h4>
                            <p class="text-sm text-gray-600">Rp ${(item.harga || 0).toLocaleString('id-ID')} × ${item.qty}</p>
                            <p class="font-bold text-amber-700">Rp ${itemTotal.toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 p-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    `;
                    cartItems.appendChild(itemDiv);
                });

                cartTotal.textContent = `Total: Rp ${total.toLocaleString('id-ID')}`;
            }
        }

        function removeFromCart(index) {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            updateGlobalCart();
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', () => {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + (item.qty || 0), 0);
            document.getElementById('global-cart-count').textContent = totalItems;
        });

        // Listen for cart updates from other scripts
        window.addEventListener('storage', updateGlobalCart);
    </script>
    @vite('resources/js/app.js')
</body>
</html>

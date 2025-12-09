<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * OrderManagementController - Unified order, cart, and checkout management
 * Consolidates: OrderController + CheckoutController + CartController
 * Handles all order lifecycle: cart → checkout → order → payment
 */
class OrderManagementController extends BaseAdminController
{
    // Override authorization - allow regular users
    protected string $requiredRole = 'user';

    protected function authorize()
    {
        // Allow authenticated users, no role restriction needed
        return Auth::check();
    }

    // ============ CART MANAGEMENT ============

    /**
     * Ambil keranjang user
     */
    public function getCart()
    {
        $cart = $this->getUserCart();
        return response()->json(['success' => true, 'cart' => $cart->load('items')]);
    }

    /**
     * Tampilkan halaman keranjang
     */
    public function showCart()
    {
        $cart = $this->getUserCart();
        return view('cart', ['cart' => $cart->load('items')]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'customization_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'base_price' => 'required|integer|min:0',
            'qty' => 'required|integer|min:1|max:999',
            'subtotal' => 'required|integer|min:0',
            'options' => 'nullable|array',
        ]);

        $cart = $this->getUserCart();

        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $validated['product_id'] ?? null,
            'customization_id' => $validated['customization_id'] ?? null,
            'name' => $validated['name'],
            'base_price' => $validated['base_price'],
            'qty' => $validated['qty'],
            'subtotal' => $validated['subtotal'],
            'options' => $validated['options'] ?? null,
        ]);

        $this->logAction('Item Added to Cart', [
            'item_id' => $item->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['qty'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart!',
            'item' => $item,
            'cart_count' => $cart->items()->count(),
        ]);
    }

    /**
     * Update cart item quantity/details
     */
    public function updateCartItem(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'qty' => 'required|integer|min:1|max:999',
            'subtotal' => 'required|integer|min:0',
        ]);

        $item->update($validated);

        $this->logAction('Cart Item Updated', [
            'item_id' => $item->id,
            'new_quantity' => $validated['qty'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item updated!',
            'item' => $item,
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(CartItem $item)
    {
        $cartId = $item->cart_id;
        $item->delete();

        $this->logAction('Item Removed from Cart', ['item_id' => $item->id]);

        $cart = Cart::find($cartId);
        return response()->json([
            'success' => true,
            'message' => 'Item removed!',
            'cart_count' => $cart->items()->count(),
        ]);
    }

    /**
     * Sync guest cart items to authenticated user's cart
     */
    public function syncGuestCart(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $cart = $this->getUserCart();

        foreach ($validated['items'] as $item) {
            CartItem::updateOrCreate(
                ['cart_id' => $cart->id, 'product_id' => $item['product_id']],
                ['qty' => ($item['qty'] ?? 1)]
            );
        }

        $this->logAction('Guest Cart Synced', ['items_count' => count($validated['items'])]);

        return response()->json([
            'success' => true,
            'message' => 'Cart synced!',
            'cart_count' => $cart->items()->count(),
        ]);
    }

    // ============ CHECKOUT MANAGEMENT ============

    /**
     * Show checkout page
     */
    public function showCheckout()
    {
        return view('checkout');
    }

    /**
     * Proses checkout dan buat pesanan
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart' => 'required|json',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Parse cart JSON
        $cart = json_decode($validated['cart'], true) ?? [];

        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Your cart is empty.']);
        }

        $total = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));

        if ($total <= 0) {
            return back()->withErrors(['cart' => 'Cart total must be greater than 0.']);
        }

        DB::beginTransaction();

        try {
            // Buat pesanan
            $order = Order::create([
                'uuid' => Str::uuid(),
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total' => $total,
                'tax' => 0,
                'discount' => 0,
                'payment_status' => 'pending',
                'customer_name' => $validated['customer_name'] ?? Auth::user()->username,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create order items
            foreach ($cart as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem['product_id'] ?? null,
                    'quantity' => $cartItem['quantity'] ?? 1,
                    'unit_price' => $cartItem['price'] ?? 0,
                ]);
            }

            // Clear user's cart
            $userCart = $this->getUserCart();
            $userCart->items()->delete();

            DB::commit();

            $this->logAction('Order Created from Checkout', [
                'order_id' => $order->id,
                'total' => $total,
                'items_count' => count($cart),
            ]);

            return redirect()->route('orders.confirmation', $order)->with('success', 'Pesanan berhasil ditempatkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logAction('Checkout Failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['checkout' => 'Failed to create order: ' . $e->getMessage()]);
        }
    }

    // ============ ORDER MANAGEMENT ============

    /**
     * List user's orders
     */
    public function listOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('orders.list', compact('orders'));
    }

    /**
     * Show single order details
     */
    public function showOrder(Order $order)
    {
        // Verify user owns this order
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.product', 'payments']);
        return view('orders.show', compact('order'));
    }

    /**
     * Tampilkan halaman konfirmasi pesanan
     */
    public function confirmation(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.product', 'payments']);
        return view('orders.confirmation', compact('order'));
    }

    /**
     * Tampilkan halaman pembayaran untuk pesanan
     */
    public function payment(Order $order)
    {
        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $order->load(['items.product', 'payments']);
        return view('orders.payment', compact('order'));
    }

    /**
     * Generate WhatsApp payment message
     */
    public function generatePaymentMessage(Order $order)
    {
        $message = "Halo, saya ingin membayar pesanan #" . $order->id . "\n";
        $message .= "Total: Rp " . number_format($order->total, 0) . "\n";
        $message .= "Produk: " . $order->items->count() . " item";

        return response()->json([
            'success' => true,
            'message' => $message,
            'whatsapp_link' => 'https://api.whatsapp.com/send?phone=62&text=' . urlencode($message),
        ]);
    }

    /**
     * Initiate WhatsApp payment
     */
    public function payViaWhatsapp(Order $order)
    {
        $message = "Halo, saya ingin membayar pesanan #" . $order->id . " sebesar Rp " . number_format($order->total, 0);
        return redirect('https://api.whatsapp.com/send?phone=62&text=' . urlencode($message));
    }

    /**
     * Reorder from previous order
     */
    public function reorderFromPrevious(Order $previousOrder)
    {
        if ($previousOrder->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $cart = $this->getUserCart();

        // Add all previous items to cart
        foreach ($previousOrder->items as $item) {
            CartItem::updateOrCreate(
                [
                    'cart_id' => $cart->id,
                    'product_id' => $item->product_id,
                ],
                [
                    'name' => $item->product->name,
                    'base_price' => $item->unit_price,
                    'qty' => $item->quantity,
                    'subtotal' => $item->unit_price * $item->quantity,
                ]
            );
        }

        $this->logAction('Reorder Initiated', [
            'previous_order_id' => $previousOrder->id,
            'items_count' => $previousOrder->items->count(),
        ]);

        return redirect()->route('cart.show')->with('success', 'Items from previous order added to cart!');
    }

    /**
     * Mark payment as complete
     */
    public function markPaymentComplete(Payment $payment)
    {
        $this->authorize();

        $order = $payment->order;

        if ($order->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $payment->update([
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);

        $order->update(['payment_status' => 'confirmed']);

        $this->logAction('Payment Marked Complete', [
            'payment_id' => $payment->id,
            'order_id' => $order->id,
        ]);

        return back()->with('success', 'Payment confirmed!');
    }

    // ============ HELPER METHODS ============

    /**
     * Ambil atau buat keranjang user baru
     */
    private function getUserCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }
}

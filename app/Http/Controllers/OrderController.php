<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    private $whatsappNumber = '0882009759102'; // WhatsApp Business Number

    // Create an order from checkout form
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $items = $request->input('items', []);
        
        \Log::info('Order store request', [
            'user_id' => $user->id,
            'items_count' => count($items),
            'items' => $items,
        ]);
        
        if (empty($items)) {
            return response()->json(['status' => 'error', 'error' => 'Cart is empty'], 422);
        }

        $total = 0;
        foreach ($items as $item) {
            $total += ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
        }

        // Add tax
        $tax = intval($total * 0.11);
        $total_with_tax = $total + $tax;
        
        // Deduct discount if applied
        $discountAmount = 0;
        $discountId = null;
        $discount = $request->input('discount');
        if ($discount && isset($discount['amount'])) {
            $discountAmount = $discount['amount'];
            $discountId = $discount['id'] ?? null;
            $total_with_tax -= $discountAmount;
        }

        try {
            $orderData = [
                'user_id' => $user->id,
                'customer_name' => $request->input('name', ''),
                'customer_phone' => $request->input('phone', ''),
                'customer_address' => $request->input('address', ''),
                'total' => (float)$total_with_tax,
                'tax' => (float)$tax,
                'discount' => (float)$discountAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->input('notes', ''),
            ];

            // Only add discount_id if it's valid
            if ($discountId) {
                $orderData['discount_id'] = $discountId;
            }

            $order = Order::create($orderData);

            // Create order items
            foreach ($items as $item) {
                $item_subtotal = ($item['harga'] ?? 0) * ($item['qty'] ?? 0);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'] ?? null,
                    'name' => $item['namaProduct'] ?? 'Product',
                    'price' => $item['harga'] ?? 0,
                    'quantity' => $item['qty'] ?? 1,
                    'subtotal' => $item_subtotal,
                ]);
            }

            // Record discount usage if a discount was applied
            if ($discountId) {
                $discountModel = \App\Models\Discount::find($discountId);
                if ($discountModel) {
                    $discountModel->recordUsage($user->id);
                }
            }

            // Return confirmation page redirect
            return response()->json([
                'status' => 'ok',
                'order_id' => $order->id,
                'redirect' => route('orders.confirmation', $order)
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Order creation error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            // Ambil pesanan user yang login dengan item terkaitnya
            $orders = Order::where('user_id', Auth::id())->with('items')->latest()->get();
            // Ambil reservasi user yang login
            $reservations = \App\Models\Reservasi::where('user_id', Auth::id())->latest()->get();
            return view('orders.index', compact('orders', 'reservations'));
        } catch (\Exception $e) {
            \Log::error('Gagal memuat pesanan: ' . $e->getMessage());
            return view('orders.index', ['orders' => collect(), 'reservations' => collect()]);
        }
    }

    public function show(Order $order)
    {
        try {
            // Verifikasi user hanya bisa melihat pesanan mereka sendiri
            if ($order->user_id !== Auth::id()) {
                abort(403, 'Tidak diizinkan');
            }

            $order->load('items');

            // Buat QR code untuk pesanan
            $qrPayload = json_encode([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'total' => $order->total,
                'status' => $order->status,
                'created_at' => $order->created_at->toDateTimeString(),
            ]);

            $orderQr = QrCode::format('svg')
                ->size(280)
                ->errorCorrection('H')
                ->margin(1)
                ->generate($qrPayload);

            return view('orders.show', [
                'order' => $order,
                'orderQr' => $orderQr,
            ]);
        } catch (\Exception $e) {
            \Log::error('Gagal menampilkan pesanan: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'Gagal memuat detail pesanan');
        }
    }

    /**
     * Tampilkan halaman konfirmasi pesanan setelah dibuat
     */
    public function confirmation(Order $order)
    {
        // Ensure user can only see their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $order->load('items');

        // Generate WhatsApp payment message
        $items = $order->items->map(function ($item) {
            return "{$item->qty}x {$item->name} - Rp " . number_format($item->subtotal, 0, ',', '.');
        })->implode("\n");

        $message = "🎉 *Order Confirmed!*\n\n";
        $message .= "📦 *Order ID:* #{$order->id}\n";
        $message .= "💰 *Total:* Rp " . number_format($order->total, 0, ',', '.') . "\n\n";
        $message .= "📝 *Items:*\n";
        $message .= $items . "\n\n";
        
        if ($order->notes) {
            $message .= "📌 *Notes:* {$order->notes}\n\n";
        }
        
        $message .= "Thank you for your order! 🙏";

        $whatsappLink = "https://wa.me/62882009759102?text=" . urlencode($message);

        // Generate QR code with order details
        $qrPayload = json_encode([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'total' => $order->total,
            'status' => $order->status,
            'created_at' => $order->created_at->toDateTimeString(),
        ]);

        $orderQr = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($qrPayload);

        return view('orders.confirmation', [
            'order' => $order,
            'orderQr' => $orderQr,
            'whatsappLink' => $whatsappLink,
        ]);
    }
    
    /**
     * Show payment page for an order
     */
    public function showPayment(Order $order)
    {
        return view('orders.payment', ['order' => $order]);
    }
    
    /**
     * Generate WhatsApp payment message
     */
    public function generateWhatsappPaymentMessage(Order $order)
    {
        $items = $order->items->map(function ($item) {
            return "{$item->qty}x {$item->name} - Rp " . number_format($item->subtotal, 0, ',', '.');
        })->implode("\n");
        
        $message = "Halo! 👋\n\n";
        $message .= "Saya ingin melakukan pembayaran untuk pesanan #" . $order->id . "\n\n";
        $message .= "📦 *Detail Pesanan:*\n";
        $message .= $items . "\n\n";
        $message .= "💰 *Total: Rp " . number_format($order->total, 0, ',', '.') . "*\n\n";
        
        if ($order->notes) {
            $message .= "📝 *Catatan:*\n{$order->notes}\n\n";
        }
        
        $message .= "Mohon di-confirm. Terima kasih! 🙏";
        
        return $message;
    }
    
    /**
     * Get WhatsApp payment link for order
     */
    public function getWhatsappPaymentLink(Order $order)
    {
        $message = $this->generateWhatsappPaymentMessage($order);
        
        // Encode message for URL
        $encodedMessage = urlencode($message);
        
        // Format phone number (remove leading 0 and add +62)
        $formattedNumber = str_replace('0', '62', $this->whatsappNumber);
        
        // Create WhatsApp link
        $whatsappLink = "https://wa.me/{$formattedNumber}?text={$encodedMessage}";
        
        return $whatsappLink;
    }
    
    /**
     * Redirect to WhatsApp payment
     */
    public function payWithWhatsapp(Order $order)
    {
        $whatsappLink = $this->getWhatsappPaymentLink($order);
        return redirect()->away($whatsappLink);
    }
    
    /**
     * Reorder - Create a new order from a previous order
     */
    public function reorder(Order $previousOrder)
    {
        $user = Auth::user();
        
        // Verify the previous order belongs to the user
        if ($previousOrder->user_id !== $user->id) {
            return redirect()->route('menu')->with('error', 'Unauthorized access');
        }
        
        // Create a new order with same items
        $newOrder = Order::create([
            'user_id' => $user->id,
            'total' => $previousOrder->total,
            'status' => 'pending',
            'notes' => $previousOrder->notes,
        ]);
        
        // Copy items to new order
        foreach ($previousOrder->items as $item) {
            OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $item->product_id,
                'customization_id' => $item->customization_id,
                'name' => $item->name,
                'base_price' => $item->base_price,
                'qty' => $item->qty,
                'subtotal' => $item->subtotal,
                'options' => $item->options,
            ]);
        }
        
        return redirect()->route('orders.payment', $newOrder)->with('success', 'Order reordered successfully!');
    }
}

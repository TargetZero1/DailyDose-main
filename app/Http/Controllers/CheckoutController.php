<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout');
    }

    public function process(Request $request)
    {
        // Accept cart as JSON string or array (client may send JSON string)
        $rawCart = $request->input('cart');
        if (is_string($rawCart)) {
            $cart = json_decode($rawCart, true) ?? [];
        } elseif (is_array($rawCart)) {
            $cart = $rawCart;
        } else {
            $cart = [];
        }

        $data = $request->validate([
            'notes' => 'nullable|string',
            'customer_name' => 'nullable|string',
        ]);

        if (empty($cart)) {
            return redirect()->back()->withErrors(['cart' => 'Your cart is empty.']);
        }
        $total = collect($cart)->sum(function ($i) { return ($i['price'] ?? 0) * ($i['quantity'] ?? 1); });

        $order = Order::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => $total,
            'tax' => 0,
            'discount' => 0,
            'payment_status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'name' => $item['name'] ?? 'Item',
                'quantity' => $item['quantity'] ?? 1,
                'price' => $item['price'] ?? 0,
                'subtotal' => ($item['price'] ?? 0) * ($item['quantity'] ?? 1),
                'options' => $item['options'] ?? null,
            ]);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => 'whatsapp',
            'amount' => $order->total,
            'currency' => 'IDR',
            'status' => 'pending',
            'metadata' => [
                'customer_name' => $data['customer_name'] ?? null,
            ],
        ]);

        // Build WhatsApp URL
        $phone = config('services.whatsapp.phone') ?? env('WHATSAPP_PHONE', '+6280000000000');
        $message = "Order%20#".$order->uuid."%0AAmount:%20RP%20".number_format($order->total,0,',','.');
        if (!empty($data['customer_name'])) {
            $message .= "%0AName:%20".urlencode($data['customer_name']);
        }
        $message .= "%0A%0APlease%20reply%20with%20payment%20proof%20or%20type%20PAID.";

        $waUrl = 'https://api.whatsapp.com/send?phone=' . urlencode($phone) . '&text=' . urlencode($message) . '&app_absent=0';

        // store link in metadata for audit
        $payment->update(['metadata' => array_merge($payment->metadata ?? [], ['wa_link' => $waUrl])]);

        return redirect()->away($waUrl);
    }

    // Admin action to mark payment paid
    public function markPaid(Payment $payment)
    {
        // In production protect this route with auth/roles. Mark payment as paid.
        $payment->update(['status' => 'paid']);
        $payment->order->update(['payment_status' => 'paid']);
        return redirect()->back()->with('status','Payment marked as paid.');
    }
}

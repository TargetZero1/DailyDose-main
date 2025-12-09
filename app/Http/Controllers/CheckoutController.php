<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function show()
    {
        return view('checkout');
    }

    public function process(Request $request)
    {
        try {
            // Terima keranjang sebagai JSON string atau array (client mungkin mengirim JSON string)
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
                return redirect()->back()->withErrors(['cart' => 'Keranjang Anda kosong.']);
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

            // Buat URL WhatsApp
            $phone = config('services.whatsapp.phone') ?? env('WHATSAPP_PHONE', '+6280000000000');
            $message = "Pesanan%20#".$order->uuid."%0AJumlah:%20RP%20".number_format($order->total,0,',','.');
            if (!empty($data['customer_name'])) {
                $message .= "%0ANama:%20".urlencode($data['customer_name']);
            }
            $message .= "%0A%0AMohon balas dengan bukti pembayaran atau ketik PAID.";

            $waUrl = 'https://api.whatsapp.com/send?phone=' . urlencode($phone) . '&text=' . urlencode($message) . '&app_absent=0';

            // simpan link dalam metadata untuk audit
            $payment->update(['metadata' => array_merge($payment->metadata ?? [], ['wa_link' => $waUrl])]);

            return redirect()->away($waUrl);
        } catch (\Exception $e) {
            Log::error('Gagal proses checkout: ' . $e->getMessage());
            return redirect()->back()->withErrors(['checkout' => 'Gagal memproses checkout: ' . $e->getMessage()]);
        }
    }

    // Aksi admin untuk menandai pembayaran terbayar
    public function markPaid(Payment $payment)
    {
        try {
            // Dalam produksi lindungi rute ini dengan auth/roles. Tandai pembayaran sebagai terbayar.
            $payment->update(['status' => 'paid']);
            $payment->order->update(['payment_status' => 'paid']);
            return redirect()->back()->with('status','Pembayaran ditandai sebagai terbayar.');
        } catch (\Exception $e) {
            Log::error('Gagal tandai pembayaran: ' . $e->getMessage());
            return back()->with('error', 'Gagal menandai pembayaran');
        }
    }
}

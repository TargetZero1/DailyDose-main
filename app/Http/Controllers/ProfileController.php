<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user
     */
    public function show()
    {
        $user = Auth::user();
        $ordersCount = Order::where('user_id', $user->id)->count();
        $latestOrders = Order::where('user_id', $user->id)->latest()->limit(5)->get();
        $reservationsCount = \App\Models\Reservasi::where('user_id', $user->id)->count();
        $reviewsCount = \App\Models\Review::where('user_id', $user->id)->count();

        return view('profile', compact('user', 'ordersCount', 'latestOrders', 'reservationsCount', 'reviewsCount'));
    }

    /**
     * Tampilkan form edit profil user
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa edit profil mereka sendiri
        if ($user->id != $id) {
            return redirect()->route('profile.show')->with('error', 'Akses tidak diizinkan');
        }
        
        return view('profile-edit', compact('user'));
    }

    /**
     * Perbarui profil user
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Pastikan user hanya bisa update profil mereka sendiri
        if ($user->id != $id) {
            return redirect()->route('profile.show')->with('error', 'Akses tidak diizinkan');
        }
        
        $validated = $request->validate([
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);
        
        // Filter nilai null dan update
        $updateData = array_filter($validated, fn($value) => !is_null($value));
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }
        
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Daftar pesanan user
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->paginate(12);
        return view('orders', compact('orders'));
    }

    /**
     * Buat ulang pesanan lama (membuat pesanan baru dengan copy item)
     */
    public function reorder(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return back()->with('error', 'Tidak diizinkan');
        }

        // Buat pesanan baru (tanpa proses pembayaran)
        $newOrder = Order::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'user_id' => $user->id,
            'status' => 'pending',
            'total' => $order->total,
            'tax' => $order->tax ?? 0,
            'discount' => $order->discount ?? 0,
            'payment_status' => 'unpaid',
            'notes' => 'Re-order of #' . $order->id,
        ]);

        foreach ($order->items as $item) {
            OrderItem::create([
                'order_id' => $newOrder->id,
                'product_id' => $item->product_id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'options' => $item->options,
            ]);
        }

        return redirect()->route('orders.show', ['order' => $newOrder->id])->with('success', 'Order placed from previous order.');
    }

    /**
     * Show a single order details (optional route target for redirect).
     */
    public function showOrder(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized');
        }

        $order->load('items');
        return view('order-show', compact('order'));
    }
}

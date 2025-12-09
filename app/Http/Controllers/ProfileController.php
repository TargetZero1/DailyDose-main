<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class ProfileController extends Controller
{
    /**
     * Show the user's profile page.
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
     * Show the form for editing the user's profile.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        // Ensure user can only edit their own profile
        if ($user->id != $id) {
            return redirect()->route('profile.show')->with('error', 'Unauthorized access');
        }
        
        return view('profile-edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Ensure user can only update their own profile
        if ($user->id != $id) {
            return redirect()->route('profile.show')->with('error', 'Unauthorized access');
        }
        
        $validated = $request->validate([
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);
        
        // Filter out null values and update
        $updateData = array_filter($validated, fn($value) => !is_null($value));
        
        if (!empty($updateData)) {
            $user->update($updateData);
        }
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

    /**
     * List user's orders.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('items')->latest()->paginate(12);
        return view('orders', compact('orders'));
    }

    /**
     * Re-order a past order (creates a new order copying items).
     */
    public function reorder(Order $order)
    {
        $user = Auth::user();
        if ($order->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized');
        }

        // Create a new order (basic, without payment processing)
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

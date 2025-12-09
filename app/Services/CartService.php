<?php

namespace App\Services;

use App\Models\{Cart, CartItem};
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Sinkronisasi item keranjang localStorage dengan keranjang database
     * Dipanggil saat user login untuk menggabungkan keranjang guest
     * 
     * @param array $guestCartData Data keranjang dari localStorage
     * @return void
     */
    public static function syncGuestCartToUser(array $guestCartData = []): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Tambahkan item dari keranjang guest ke keranjang server
        if (empty($guestCartData)) {
            return;
        }

        foreach ($guestCartData as $item) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $item['product_id'] ?? null,
                'customization_id' => $item['customization_id'] ?? null,
                'name' => $item['name'] ?? 'Unnamed Item',
                'base_price' => $item['base_price'] ?? 0,
                'qty' => $item['qty'] ?? 1,
                'subtotal' => $item['subtotal'] ?? 0,
                'options' => $item['options'] ?? null,
            ]);
        }
    }
}

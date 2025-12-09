<?php

namespace App\Http\Controllers;

use App\Models\{Cart, CartItem};
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Dapatkan keranjang belanja pengguna yang sedang login
     * 
     * @return Cart
     */
    protected function getUserCart(): Cart
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    /**
     * Tampilkan isi keranjang belanja
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $cart = $this->getUserCart();
        return response()->json(['cart' => $cart->load('items')]);
    }

    /**
     * Tambah item ke keranjang belanja
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItem(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'customization_id' => 'nullable|integer',
            'name' => 'required|string',
            'base_price' => 'required|integer',
            'qty' => 'required|integer|min:1',
            'subtotal' => 'required|integer',
            'options' => 'nullable|array',
        ]);

        $cart = $this->getUserCart();

        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $data['product_id'] ?? null,
            'customization_id' => $data['customization_id'] ?? null,
            'name' => $data['name'],
            'base_price' => $data['base_price'],
            'qty' => $data['qty'],
            'subtotal' => $data['subtotal'],
            'options' => $data['options'] ?? null,
        ]);

        return response()->json(['status' => 'ok', 'item' => $item], 201);
    }

    /**
     * Update jumlah item di keranjang
     * 
     * @param Request $request
     * @param CartItem $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateItem(Request $request, CartItem $item)
    {
        // Verifikasi item milik pengguna
        $cart = $this->getUserCart();
        if ($item->cart_id !== $cart->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $data = $request->validate(['qty' => 'required|integer|min:1']);
        $item->update([
            'qty' => $data['qty'],
            'subtotal' => $item->base_price * $data['qty']
        ]);
        
        return response()->json(['status' => 'ok', 'item' => $item]);
    }

    /**
     * Hapus item dari keranjang
     * 
     * @param CartItem $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItem(CartItem $item)
    {
        // Verifikasi item milik pengguna
        $cart = $this->getUserCart();
        if ($item->cart_id !== $cart->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $item->delete();
        return response()->json(['status' => 'deleted']);
    }

    /**
     * Sinkronisasi keranjang guest dengan user yang login
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncGuest(Request $request)
    {
        $guestCart = $request->validate(['items' => 'nullable|array']);
        CartService::syncGuestCartToUser($guestCart['items'] ?? []);
        return response()->json(['status' => 'synced']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle status favorit untuk produk
     */
    public function toggle(Product $product)
    {
        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Dihapus dari favorit',
            ]);
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Ditambahkan ke favorit',
        ]);
    }

    /**
     * Cek apakah produk sudah di-favorit
     */
    public function checkFavorite(Product $product)
    {
        $isFavorited = Favorite::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }

    /**
     * Ambil favorit produk user
     */
    public function getUserFavorites()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->get();

        return response()->json([
            'favorites' => $favorites,
            'count' => $favorites->count(),
        ]);
    }

    /**
     * Tampilkan halaman wishlist user
     */
    public function show()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('favorites', compact('favorites'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk dengan paginasi
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $products = Product::paginate(12);
        return view('products.index', compact('products'));
    }

    /**
     * Tampilkan detail produk dengan produk terkait
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $relatedProducts = Product::where('category', $product->category)
                ->where('id', '!=', $product->id)
                ->take(4)
                ->get();

            return view('products.show', compact('product', 'relatedProducts'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat produk: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', 'Produk tidak ditemukan');
        }
    }

    /**
     * Dapatkan produk unggulan untuk halaman utama
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeatured()
    {
        $featured = Product::getFeatured(6);
        return response()->json($featured);
    }

    /**
     * Dapatkan produk baru untuk halaman utama
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNew()
    {
        $new = Product::getNew(10);
        return response()->json($new);
    }

    /**
     * Dapatkan produk terlaris untuk halaman utama
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBestSellers()
    {
        $bestSellers = Product::getBestSellers(10);
        return response()->json($bestSellers);
    }

    /**
     * Cari produk berdasarkan kata kunci
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('category', 'like', "%{$search}%")
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    /**
     * API: Daftar produk untuk menu frontend dalam format JSON
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiList(Request $request)
    {
        $products = Product::all()->map(fn($p) => [
            'id' => $p->id,
            'namaProduct' => $p->name,
            'category' => $p->category,
            'harga' => $p->price,
            'gambar' => $p->getImageUrl(),
            'stock' => $p->stock ?? 'Tersedia',
            'description' => $p->description ?? '',
            'is_new' => (bool) ($p->is_new ?? false),
        ]);

        return response()->json($products);
    }
}

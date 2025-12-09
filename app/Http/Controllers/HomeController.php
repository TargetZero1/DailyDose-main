<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman utama dengan produk unggulan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Ambil produk unggulan yang masih memiliki stok
            $featuredProducts = Product::where('is_featured', true)
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->take(4)
                ->get();

            return view('home', compact('featuredProducts'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat halaman utama: ' . $e->getMessage());
            return view('home', ['featuredProducts' => collect()]);
        }
    }
}

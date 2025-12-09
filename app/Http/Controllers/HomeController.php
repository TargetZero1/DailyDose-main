<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman beranda dengan produk unggulan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil produk unggulan dari database yang masih memiliki stok
        $featuredProducts = Product::where('is_featured', true)
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('featuredProducts'));
    }
}

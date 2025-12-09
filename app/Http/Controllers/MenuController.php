<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // Pencarian produk
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter kategori
            if ($request->has('category') && $request->category !== 'all') {
                $query->where('category', $request->category);
            }

            // Filter harga minimum
            if ($request->has('min_price') && $request->min_price) {
                $query->where('price', '>=', $request->min_price);
            }
            // Filter harga maksimum
            if ($request->has('max_price') && $request->max_price) {
                $query->where('price', '<=', $request->max_price);
            }

            // Sorting produk
            $sortBy = $request->get('sort_by', 'latest');
            switch ($sortBy) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('view_count', 'desc');
                    break;
                case 'rating':
                    $query->withAvg('reviews', 'rating')
                        ->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'latest':
                default:
                    $query->latest();
            }

            $products = $query->paginate(12);
            
            // Ambil kategori unik untuk filter
            $categories = Product::distinct()->pluck('category')->sort();

            return view('menu', [
                'products' => $products,
                'categories' => $categories,
                'search' => $request->get('search', ''),
                'category' => $request->get('category', 'all'),
                'sort_by' => $sortBy,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal memuat menu: ' . $e->getMessage());
            return view('menu', [
                'products' => collect()->paginate(12),
                'categories' => collect(),
                'search' => '',
                'category' => 'all',
                'sort_by' => 'latest',
            ])->with('error', 'Gagal memuat item menu');
        }
    }
}

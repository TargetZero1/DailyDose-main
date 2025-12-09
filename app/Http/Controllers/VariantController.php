<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class VariantController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $products = Product::with('variants')->get();
        return view('admin.variants.index', compact('products'));
    }

    public function store(Request $request)
    {
        try {
            $this->authorizeAdmin();
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'type' => 'required|string',
                'value' => 'required|string',
                'price_modifier' => 'nullable|integer',
                'stock' => 'nullable|integer',
            ]);

            ProductVariant::create([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'value' => $request->value,
                'price_modifier' => $request->price_modifier ?? 0,
                'stock' => $request->stock ?? 0,
            ]);

            return back()->with('success', 'Varian berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Gagal tambah varian: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah varian');
        }
    }

    public function destroy(ProductVariant $variant)
    {
        $this->authorizeAdmin();
        $variant->delete();
        return back()->with('success', 'Variant removed');
    }

    public function edit(ProductVariant $variant)
    {
        $this->authorizeAdmin();
        $products = Product::select('id', 'name', 'price')->limit(100)->get();
        return view('admin.variants.edit', compact('variant', 'products'));
    }

    public function update(Request $request, ProductVariant $variant)
    {
        try {
            $this->authorizeAdmin();
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'type' => 'required|string',
                'value' => 'required|string',
                'price_modifier' => 'nullable|integer',
                'stock' => 'nullable|integer',
            ]);

            $variant->update([
                'product_id' => $request->product_id,
                'type' => $request->type,
                'value' => $request->value,
                'price_modifier' => $request->price_modifier ?? 0,
                'stock' => $request->stock ?? 0,
            ]);

            return redirect()->route('admin.variants.index')->with('success', 'Varian berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal update varian: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui varian');
        }
    }

    protected function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'pemilik'])) {
            abort(403);
        }
    }
}

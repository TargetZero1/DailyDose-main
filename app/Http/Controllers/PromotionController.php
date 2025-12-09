<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Tampilkan semua promosi
     */
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    /**
     * Tampilkan form membuat promosi baru
     */
    public function create()
    {
        $products = Product::select('id', 'name', 'price')->limit(100)->get();
        return view('admin.promotions.create', compact('products'));
    }

    /**
     * Simpan promosi baru yang dibuat
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,buy_one_get_one',
            'value' => 'required|integer|min:1',
            'product_id' => 'nullable|exists:products,id',
            'is_active' => 'boolean',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'min_quantity' => 'integer|min:1',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Tangani upload gambar banner
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('promotions', 'public');
            $data['banner_image'] = $path;
        }

        Promotion::create($data);

        return redirect()->route('promotions.index')
            ->with('success', 'Promosi berhasil dibuat!');
    }

    /**
     * Tampilkan form edit promosi
     */
    public function edit(Promotion $promotion)
    {
        $products = Product::select('id', 'name', 'price')->limit(100)->get();
        return view('admin.promotions.edit', compact('promotion', 'products'));
    }

    /**
     * Perbarui promosi yang ditentukan
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed,buy_one_get_one',
            'value' => 'required|integer|min:1',
            'product_id' => 'nullable|exists:products,id',
            'is_active' => 'boolean',
            'start_date' => 'required|date_format:Y-m-d H:i',
            'end_date' => 'required|date_format:Y-m-d H:i|after:start_date',
            'min_quantity' => 'integer|min:1',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        // Tangani upload gambar banner
        if ($request->hasFile('banner_image')) {
            $path = $request->file('banner_image')->store('promotions', 'public');
            $data['banner_image'] = $path;
        }

        $promotion->update($data);

        return redirect()->route('promotions.index')
            ->with('success', 'Promosi berhasil diperbarui!');
    }

    /**
     * Hapus promosi yang ditentukan
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();
        return back()->with('success', 'Promosi berhasil dihapus!');
    }

    /**
     * Ambil promosi aktif untuk ditampilkan
     */
    public function getActive()
    {
        $promotions = Promotion::getActive();
        return response()->json($promotions);
    }

    /**
     * Toggle status promosi aktif
     */
    public function toggleActive(Promotion $promotion)
    {
        $promotion->is_active = !$promotion->is_active;
        $promotion->save();

        return back()->with('success', 'Promotion status updated!');
    }
}

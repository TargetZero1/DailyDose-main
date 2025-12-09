<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AdminProductController extends Controller
{
    /**
     * Verify admin access
     */
    private function authorize()
    {
        abort_if(Auth::user()->role !== 'admin', 403, 'Unauthorized');
    }

    /**
     * Apply inventory filters
     */
    private function applyFilters($query, Request $request)
    {
        return $query
            ->when($request->filter, function ($q) use ($request) {
                if ($request->filter === 'low') {
                    return $q->whereRaw('stock < low_stock_threshold');
                } elseif ($request->filter === 'out') {
                    return $q->where('stock', 0);
                } elseif ($request->filter === 'adequate') {
                    return $q->whereRaw('stock >= low_stock_threshold');
                }
            })
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->sort === 'stock_high', fn($q) => $q->orderBy('stock', 'desc'))
            ->when($request->sort === 'stock_low', fn($q) => $q->orderBy('stock', 'asc'))
            ->orderBy('name', 'asc');
    }

    /**
     * Get cached inventory stats
     */
    private function getInventoryStats()
    {
        return Cache::remember('inventory_stats', 300, function () {
            return [
                'total' => Product::count(),
                'low_stock' => Product::whereRaw('stock < low_stock_threshold AND stock > 0')->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
                'total_value' => Product::selectRaw('SUM(stock * price) as value')->first()?->value ?? 0,
            ];
        });
    }

    /**
     * Inventory management page
     */
    public function inventory(Request $request)
    {
        $this->authorize();

        $products = $this->applyFilters(Product::query(), $request)->paginate(20);
        $stats = $this->getInventoryStats();

        return view('admin.inventory.index', compact('products', 'stats'));
    }

    /**
     * Update individual product stock
     */
    public function updateStock(Request $request, Product $product)
    {
        $this->authorize();

        $validated = $request->validate([
            'new_stock' => 'required|integer|min:0',
            'new_price' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'low_stock_threshold' => 'nullable|integer|min:1',
        ]);

        $oldStock = $product->stock;
        $oldPrice = $product->price;
        $product->update([
            'stock' => $validated['new_stock'],
            'price' => $validated['new_price'],
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? $product->low_stock_threshold,
        ]);

        Cache::forget('inventory_stats');

        $changes = [];
        if ($oldStock != $validated['new_stock']) {
            $changes[] = "Stock: {$oldStock} → {$validated['new_stock']}";
        }
        if ($oldPrice != $validated['new_price']) {
            $changes[] = "Price: Rp" . number_format($oldPrice, 0, ',', '.') . " → Rp" . number_format($validated['new_price'], 0, ',', '.');
        }
        $changeMessage = implode(', ', $changes);

        \Log::info('Inventory updated', [
            'product_id' => $product->id,
            'old_stock' => $oldStock,
            'new_stock' => $validated['new_stock'],
            'old_price' => $oldPrice,
            'new_price' => $validated['new_price'],
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', "Updated: {$changeMessage} ({$validated['reason']})");
    }

    /**
     * Bulk update stock for multiple products
     */
    public function bulkUpdateStock(Request $request)
    {
        $this->authorize();

        $validated = $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'adjustment' => 'required|integer',
            'reason' => 'required|string|max:255',
        ]);

        $updated = 0;
        foreach ($validated['product_ids'] as $id) {
            $product = Product::find($id);
            if ($product) {
                $oldStock = $product->stock;
                $newStock = max(0, $oldStock + $validated['adjustment']);
                $product->update(['stock' => $newStock]);
                $updated++;
            }
        }

        Cache::forget('inventory_stats');

        \Log::info('Bulk stock update', [
            'count' => $updated,
            'adjustment' => $validated['adjustment'],
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', "{$updated} products updated by {$validated['adjustment']} units");
    }

    /**
     * Set low-stock threshold for a product
     */
    public function setLowStockAlert(Request $request, Product $product)
    {
        $this->authorize();

        $validated = $request->validate(['low_stock_threshold' => 'required|integer|min:1']);

        $product->update($validated);
        Cache::forget('inventory_stats');

        return back()->with('success', "Low stock threshold set to {$validated['low_stock_threshold']} units");
    }

    /**
     * Get products needing restock
     */
    public function getRestockNeeded()
    {
        $this->authorize();

        $products = Product::whereRaw('stock < low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->get(['id', 'name', 'stock', 'low_stock_threshold']);

        return response()->json($products);
    }

    /**
     * Export inventory to CSV
     */
    public function exportInventory()
    {
        $this->authorize();

        $products = Product::select('id', 'name', 'stock', 'price', 'low_stock_threshold')->latest()->get();

        $headers = ["Product ID", "Product Name", "Current Stock", "Unit Price", "Threshold", "Total Value"];
        $filename = "inventory-" . date('Y-m-d-His') . ".csv";

        $handle = fopen('php://memory', 'r+');
        fputcsv($handle, $headers);

        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->stock,
                $product->price,
                $product->low_stock_threshold,
                $product->stock * $product->price,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }
}

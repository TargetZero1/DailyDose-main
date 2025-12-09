<?php

namespace App\Http\Controllers;

use App\Models\Customization;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomizationController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['store']),
        ];
    }

    // Simpan kustomisasi untuk produk (user harus autentikasi)
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'product_id' => 'required|exists:products,id',
                'name' => 'nullable|string|max:191',
                'options' => 'nullable|array',
            ]);

            $cust = Customization::create([
                'product_id' => $data['product_id'],
                'user_id' => Auth::id(),
                'name' => $data['name'] ?? null,
                'options' => $data['options'] ?? null,
                'is_template' => false,
            ]);

            return response()->json(['status' => 'ok', 'customization' => $cust], 201);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan kustomisasi: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menyimpan kustomisasi'], 500);
        }
    }

    // Admin: daftar kustomisasi
    public function index()
    {
        try {
            $this->authorizeAdmin();
            $items = Customization::with('product','user')->latest()->paginate(50);
            return view('admin.customizations.index', compact('items'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat kustomisasi: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat daftar kustomisasi');
        }
    }

    // Admin: toggle template status
    public function toggleTemplate(Customization $customization)
    {
        $this->authorizeAdmin();
        $customization->is_template = !$customization->is_template;
        $customization->save();
        return back();
    }

    // Admin: impor kustomisasi (template) ke varian produk
    public function importToVariants(Customization $customization)
    {
        try {
            $this->authorizeAdmin();

            $options = $customization->options ?? [];
            if (!is_array($options)) {
                return back()->with('error', 'Opsi kustomisasi tidak dalam format yang diharapkan');
            }

            $created = 0;
            foreach ($options as $opt) {
                // Harapkan setiap opsi adalah objek/array dengan type, value, dan optional price_modifier
                $type = $opt['type'] ?? ($opt->type ?? null);
                $value = $opt['value'] ?? ($opt->value ?? null);
                $price_modifier = $opt['price_modifier'] ?? ($opt->price_modifier ?? 0);

                if (!$type || !$value) continue;

                // Cegah duplikat
                $exists = ProductVariant::where('product_id', $customization->product_id)
                    ->where('type', $type)
                    ->where('value', $value)
                    ->exists();

                if ($exists) continue;

                ProductVariant::create([
                    'product_id' => $customization->product_id,
                    'type' => $type,
                    'value' => $value,
                    'price_modifier' => intval($price_modifier ?? 0),
                    'stock' => 10,
                ]);
                $created++;
            }

            return back()->with('status', "Impor {$created} varian dari kustomisasi #{$customization->id}");
        } catch (\Exception $e) {
            Log::error('Gagal impor varian: ' . $e->getMessage());
            return back()->with('error', 'Gagal impor varian dari kustomisasi');
        }
    }

    protected function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role ?? 'user', ['admin','pemilik'])) {
            abort(403);
        }
    }
}

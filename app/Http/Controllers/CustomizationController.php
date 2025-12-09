<?php

namespace App\Http\Controllers;

use App\Models\Customization;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;

class CustomizationController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['store']),
        ];
    }

    // Store a customization for a product (user must be authenticated)
    public function store(Request $request)
    {
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
    }

    // Admin: list customizations
    public function index()
    {
        $this->authorizeAdmin();
        $items = Customization::with('product','user')->latest()->paginate(50);
        return view('admin.customizations.index', compact('items'));
    }

    // Admin: toggle template status
    public function toggleTemplate(Customization $customization)
    {
        $this->authorizeAdmin();
        $customization->is_template = !$customization->is_template;
        $customization->save();
        return back();
    }

    // Admin: import a customization (template) into product variants
    public function importToVariants(Customization $customization)
    {
        $this->authorizeAdmin();

        $options = $customization->options ?? [];
        if (!is_array($options)) {
            return back()->with('error', 'Opsi kustomisasi tidak dalam format yang diharapkan');
        }

        $created = 0;
        foreach ($options as $opt) {
            // Expect each option to be an object/array with type, value, and optional price_modifier
            $type = $opt['type'] ?? ($opt->type ?? null);
            $value = $opt['value'] ?? ($opt->value ?? null);
            $price_modifier = $opt['price_modifier'] ?? ($opt->price_modifier ?? 0);

            if (!$type || !$value) continue;

            // Prevent duplicates
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

        return back()->with('status', "Imported {$created} variant(s) from customization #{$customization->id}");
    }

    protected function authorizeAdmin()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role ?? 'user', ['admin','pemilik'])) {
            abort(403);
        }
    }
}

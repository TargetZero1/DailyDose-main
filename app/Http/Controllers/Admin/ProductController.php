<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Routing\Controllers\Middleware;

#[Middleware('auth')]
class ProductController extends Controller
{

    public function index()
    {
        $products = Product::orderBy('id','desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'stock' => 'nullable|integer',
            'image' => 'nullable|string',
        ]);

        $data['slug'] = 
            
            str_replace(' ', '-', strtolower($data['name']));

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'stock' => 'nullable|integer',
            'image' => 'nullable|string',
        ]);

        $data['slug'] = str_replace(' ', '-', strtolower($data['name']));
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Product deleted');
    }
}

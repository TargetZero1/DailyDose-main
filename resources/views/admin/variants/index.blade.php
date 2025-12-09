@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-6xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Product Variants</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-2">Add Variant</h3>
                <form action="{{ route('admin.variants.store') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label class="block font-medium">Product</label>
                        <select name="product_id" class="w-full p-2 border rounded" required>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block font-medium">Type</label>
                        <input name="type" class="w-full p-2 border rounded" placeholder="e.g. size, flavor" required>
                    </div>
                    <div class="mb-2">
                        <label class="block font-medium">Value</label>
                        <input name="value" class="w-full p-2 border rounded" placeholder="e.g. small, chocolate" required>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input name="price_modifier" type="number" class="p-2 border rounded" placeholder="Price modifier">
                        <input name="stock" type="number" class="p-2 border rounded" placeholder="Stock">
                    </div>
                    <div class="mt-2">
                        <button class="px-4 py-2 bg-amber-700 text-white rounded">Add Variant</button>
                    </div>
                </form>
            </div>

            <div>
                <h3 class="font-semibold mb-2">Existing Variants</h3>
                @foreach($products as $p)
                    <div class="mb-4 border p-3 rounded">
                        <div class="font-bold">{{ $p->name }}</div>
                        <div class="mt-2">
                            @foreach($p->variants as $v)
                                <div class="flex justify-between items-center border-t py-2">
                                    <div>{{ $v->type }}: {{ $v->value }} <span class="text-sm text-gray-500">(+Rp. {{ number_format($v->price_modifier) }})</span></div>
                                    <form method="POST" action="{{ route('admin.variants.destroy', $v->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 border rounded text-sm">Remove</button>
                                    </form>
                                </div>
                            @endforeach
+                        </div>
+                    </div>
+                @endforeach
+            </div>
+        </div>
+    </div>
+</div>
+
+@include('partials.footer')
+
+@endsection
+
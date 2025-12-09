@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Variant</h1>

        <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-2">
                <label class="block font-medium">Product</label>
                <select name="product_id" class="w-full p-2 border rounded" required>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $variant->product_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block font-medium">Type</label>
                <input name="type" value="{{ $variant->type }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-2">
                <label class="block font-medium">Value</label>
                <input name="value" value="{{ $variant->value }}" class="w-full p-2 border rounded" required>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <input name="price_modifier" type="number" value="{{ $variant->price_modifier }}" class="p-2 border rounded" placeholder="Price modifier">
                <input name="stock" type="number" value="{{ $variant->stock }}" class="p-2 border rounded" placeholder="Stock">
            </div>
            <div class="mt-2">
                <button class="px-4 py-2 bg-amber-700 text-white rounded">Update Variant</button>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

@endsection

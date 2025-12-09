@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Create Promotion</h1>

        <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="block font-medium">Title</label>
                <input type="text" name="title" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Type</label>
                <select name="type" class="w-full p-2 border rounded">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                    <option value="buy_one_get_one">Buy 1 Get 1</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Value</label>
                <input type="number" name="value" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Product (optional)</label>
                <select name="product_id" class="w-full p-2 border rounded">
                    <option value="">-- Global --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Start</label>
                <input type="datetime-local" name="start_date" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">End</label>
                <input type="datetime-local" name="end_date" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium">Banner Image</label>
                <input type="file" name="banner_image" class="w-full">
            </div>
            <div>
                <button class="px-4 py-2 bg-amber-700 text-white rounded">Create</button>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

@endsection

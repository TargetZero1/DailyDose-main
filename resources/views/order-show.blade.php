@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

        <div class="mb-4">
            <div class="text-sm text-gray-600">Placed: {{ $order->created_at->format('d M Y') }}</div>
            <div class="font-bold">Status: {{ ucfirst($order->status) }}</div>
            <div class="text-amber-700 font-semibold">Total: Rp. {{ number_format($order->total) }}</div>
        </div>

        <div class="border-t pt-4">
            <h3 class="font-semibold mb-2">Items</h3>
            <ul class="space-y-3">
                @foreach($order->items as $item)
                    <li class="flex justify-between items-center">
                        <div>
                            <div class="font-bold">{{ $item->name }}</div>
                            <div class="text-sm text-gray-600">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div class="text-amber-700 font-semibold">Rp. {{ number_format($item->subtotal) }}</div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

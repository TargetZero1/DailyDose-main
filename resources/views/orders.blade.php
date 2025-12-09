@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4"><i class="fas fa-shopping-bag mr-2 text-amber-700"></i>My Orders</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        @if($orders->count())
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="border rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <div class="text-sm text-gray-600">Order #{{ $order->id }} • {{ $order->created_at->format('d M Y') }}</div>
                            <div class="font-bold text-gray-800">Status: {{ ucfirst($order->status) }}</div>
                            <div class="text-amber-700 font-semibold">Total: Rp. {{ number_format($order->total) }}</div>
                        </div>
                        <div class="flex gap-2">
                            <a href="#" class="px-3 py-2 border rounded text-sm" onclick="event.preventDefault(); document.getElementById('reorder-{{ $order->id }}').submit();">Re-order</a>
                            <form id="reorder-{{ $order->id }}" action="/profile/orders/{{ $order->id }}/reorder" method="POST" class="hidden">
                                @csrf
                            </form>
                            <a href="/orders/{{ $order->id }}" class="px-3 py-2 bg-amber-700 text-white rounded text-sm">View</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center text-gray-500 py-12">You have no orders yet.</div>
        @endif
    </div>
</div>

@include('partials.footer')

@endsection

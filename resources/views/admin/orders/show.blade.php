@extends('layouts.app')

@section('content')
<div class="bg-amber-50 min-h-screen">
    <div class="bg-gradient-to-r from-amber-600 to-yellow-700 text-white py-8 mb-8">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Order #{{ $order->id }}</h1>
            <a href="{{ route('admin.orders.index') }}" class="bg-white text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-100 font-semibold">← Back to Orders</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 pb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Info -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h5 class="font-semibold text-gray-800">Order Information</h5>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600"><strong>Order ID:</strong> {{ $order->id }}</p>
                                <p class="text-gray-600 mt-2">
                                    <strong>Status:</strong>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </p>
                                <p class="text-gray-600 mt-2">
                                    <strong>Payment Status:</strong>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $order->payment_status === 'confirmed' ? 'bg-green-100 text-green-800' : ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600"><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                                <p class="text-gray-600 mt-2"><strong>Total:</strong> <span class="text-2xl font-bold text-amber-600">Rp{{ number_format($order->total, 0, ',', '.') }}</span></p>
                            </div>
                        </div>

                        <hr class="my-6">

                        <!-- Customer Info -->
                        <h6 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <p class="text-gray-600"><strong>Name:</strong> {{ $order->customer_name }}</p>
                                <p class="text-gray-600 mt-2"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                <p class="text-gray-600 mt-2"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600"><strong>Address:</strong> {{ $order->customer_address }}</p>
                                <p class="text-gray-600 mt-2"><strong>City:</strong> {{ $order->customer_city ?? 'N/A' }}</p>
                                <p class="text-gray-600 mt-2"><strong>Notes:</strong> {{ $order->notes ?? 'None' }}</p>
                            </div>
                        </div>

                        <hr class="my-6">

                        <!-- Order Items -->
                        <h6 class="text-lg font-semibold text-gray-800 mb-4">Order Items</h6>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-100 border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Qty</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Price</th>
                                        <th class="px-4 py-3 text-right font-semibold text-gray-700">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $item)
                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                            <td class="px-4 py-3">{{ $item->product_name }}</td>
                                            <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right font-semibold">Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">No items</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($order->discount_code)
                            <p class="text-gray-500 text-sm mt-4">Discount Applied: <strong>{{ $order->discount_code }}</strong> (-Rp{{ number_format($order->discount_amount, 0, ',', '.') }})</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div>
                <!-- Status Update -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h5 class="font-semibold text-gray-800">Update Status</h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Order Status</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-500">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="refunded" {{ $order->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">Update Order Status</button>
                        </form>
                    </div>
                </div>

                <!-- Payment Status Update -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h5 class="font-semibold text-gray-800">Update Payment</h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Payment Status</label>
                                <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-500">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->payment_status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold">Update Payment Status</button>
                        </form>
                    </div>
                </div>

                <!-- WhatsApp Notification -->
                <div class="bg-white shadow rounded-lg mb-6">
                    <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                        <h5 class="font-semibold text-gray-800">Send Notification</h5>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.orders.whatsapp', $order) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 font-semibold mb-2">Message</label>
                                <textarea name="message" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-amber-500" rows="3" placeholder="Enter custom message...">Your order #{{ $order->id }} has been updated. Status: {{ ucfirst($order->status) }}</textarea>
                                <small class="text-gray-500 mt-1">Customer phone: {{ $order->customer_phone }}</small>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-semibold">Send via WhatsApp</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

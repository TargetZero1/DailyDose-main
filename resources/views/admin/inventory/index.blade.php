@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#f5eee6] via-white to-[#f5eee6]">
    <div class="border-b border-[#e8d4c0] bg-gradient-to-r from-[#9d7e54] via-[#8b6f47] to-[#7a5f3f] text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-10 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-widest text-[#e8d4c0]">Inventory</p>
                <h1 class="mt-2 text-3xl font-bold leading-tight">Inventory and Price Management</h1>
                <p class="mt-2 max-w-2xl text-sm text-[#e8d4c0]">Manage stock levels and product pricing efficiently.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.inventory.export') }}" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm font-semibold text-[#8b6f47] shadow hover:bg-[#f5eee6] focus:outline-none focus:ring-2 focus:ring-white/60">
                    <span class="rounded-full bg-[#f5eee6] px-2 py-1 text-[11px] font-bold text-[#8b6f47]">CSV</span> Export
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-8 space-y-8">
        <!-- Stat cards -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-[#e8d4c0] bg-white/90 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-[#8b6f47]">Total Products</p>
                <p class="mt-3 text-3xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-red-100 bg-white/90 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">Low Stock</p>
                <p class="mt-3 text-3xl font-bold text-red-600">{{ $stats['low_stock'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-red-100 bg-white/90 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-500">Out of Stock</p>
                <p class="mt-3 text-3xl font-bold text-red-600">{{ $stats['out_of_stock'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-[#e8d4c0] bg-white/90 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-[#8b6f47]">Total Value</p>
                <p class="mt-3 text-2xl font-bold text-gray-900">Rp{{ number_format($stats['total_value'] ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="rounded-2xl border border-[#e8d4c0] bg-white/90 p-5 shadow-sm">
            <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Search</label>
                    <div class="relative mt-2">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm focus:border-[#8b6f47] focus:bg-white focus:outline-none" placeholder="Find by name or SKU">
                        <span class="pointer-events-none absolute right-3 top-2.5 text-gray-400">⌕</span>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Filter</label>
                    <select name="filter" class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm focus:border-[#8b6f47] focus:bg-white focus:outline-none">
                        <option value="">All Products</option>
                        <option value="low" {{ request('filter') === 'low' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out" {{ request('filter') === 'out' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="adequate" {{ request('filter') === 'adequate' ? 'selected' : '' }}>Adequate Stock</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Sort</label>
                    <select name="sort" class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm focus:border-[#8b6f47] focus:bg-white focus:outline-none">
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                        <option value="stock_low" {{ request('sort') === 'stock_low' ? 'selected' : '' }}>Stock: Low to High</option>
                        <option value="stock_high" {{ request('sort') === 'stock_high' ? 'selected' : '' }}>Stock: High to Low</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-[#8b6f47] px-4 py-2.5 text-sm font-semibold text-white shadow hover:bg-[#7a5f3f] focus:outline-none focus:ring-2 focus:ring-[#d4a574]">Apply</button>
                </div>
            </form>
        </div>

        <!-- Bulk update -->
        <div class="rounded-2xl border border-[#e8d4c0] bg-white/90 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-[#8b6f47] to-[#7a5f3f] px-6 py-4">
                <p class="text-lg font-bold text-white">Bulk Stock Update</p>
                <p class="text-sm text-[#e8d4c0]">Adjust multiple products at once with a single reason log.</p>
            </div>
            <div class="p-6">
                <button onclick="openBulkModal()" class="w-full rounded-xl border-2 border-dashed border-[#d4a574] bg-[#f5eee6] px-6 py-8 text-center hover:border-[#c49566] hover:bg-[#e8d4c0] transition">
                    <div class="mx-auto w-12 h-12 rounded-full bg-[#e8d4c0] flex items-center justify-center mb-3">
                        <span class="text-2xl">📦</span>
                    </div>
                    <p class="text-sm font-semibold text-[#5a4a35]">Select Products to Update</p>
                    <p class="text-xs text-[#8b6f47] mt-1">Click to choose items and adjust quantities</p>
                </button>
            </div>
        </div>

        <!-- Bulk Update Modal -->
        <div id="bulkModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
            <div class="w-full max-w-4xl rounded-2xl bg-white shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#8b6f47]">Bulk Update</p>
                        <h3 class="text-lg font-bold text-gray-900">Select Products & Adjust Stock</h3>
                    </div>
                    <button onclick="closeBulkModal()" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <form action="{{ route('admin.inventory.bulkUpdate') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600">Stock Adjustment</label>
                                <input type="number" name="adjustment" required class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-[#8b6f47] focus:bg-white focus:outline-none" placeholder="e.g., +10 or -5">
                                <p class="text-xs text-gray-500 mt-1">Use + to add, - to subtract</p>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600">Reason for Change</label>
                                <input type="text" name="reason" required class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm focus:border-[#8b6f47] focus:bg-white focus:outline-none" placeholder="e.g., Restock, Audit, Damage">
                                <p class="text-xs text-gray-500 mt-1">Brief description of the change</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto px-6 py-4">
                        <label class="text-xs font-semibold text-gray-600 block mb-3">Select Products ({{ $products->count() }} available)</label>
                        <div class="space-y-2 max-h-96">
                            @foreach($products as $product)
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 hover:border-[#d4a574] hover:bg-[#f5eee6] cursor-pointer transition">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="w-4 h-4 rounded border-gray-300 text-[#8b6f47] focus:ring-[#8b6f47]">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">Current: <span class="font-medium text-gray-700">{{ $product->stock }} units</span> · SKU: {{ $product->id }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $product->stock > $product->low_stock_threshold ? 'bg-green-100 text-green-800' : ($product->stock === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $product->stock === 0 ? 'Out' : ($product->stock <= $product->low_stock_threshold ? 'Low' : 'OK') }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <button type="button" onclick="closeBulkModal()" class="rounded-full border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancel</button>
                            <button type="submit" class="rounded-full bg-[#8b6f47] px-6 py-2 text-sm font-semibold text-white shadow hover:bg-[#7a5f3f] focus:outline-none focus:ring-2 focus:ring-[#d4a574]">Apply Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openBulkModal() {
                document.getElementById('bulkModal').classList.remove('hidden');
                document.getElementById('bulkModal').classList.add('flex');
                document.body.classList.add('overflow-hidden');
            }

            function closeBulkModal() {
                document.getElementById('bulkModal').classList.add('hidden');
                document.getElementById('bulkModal').classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            document.getElementById('bulkModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeBulkModal();
                }
            });
        </script>

        <!-- Products table -->
        <div class="overflow-hidden rounded-2xl border border-[#e8d4c0] bg-white/90 shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#e8d4c0]">
                    <thead class="bg-[#f5eee6]/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Product</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Stock</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Threshold</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Unit Price</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Stock Value</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-[#8b6f47]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#f5eee6]">
                        @forelse($products as $product)
                            <tr class="hover:bg-[#f5eee6]/60">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">SKU: {{ $product->id }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center rounded-full px-3 py-1 text-sm font-semibold {{ $product->stock > $product->low_stock_threshold ? 'bg-green-100 text-green-800' : ($product->stock === 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $product->stock }} units
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700">{{ $product->low_stock_threshold }} units</td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->stock === 0)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700"> Out</span>
                                    @elseif($product->stock <= $product->low_stock_threshold)
                                        <span class="inline-flex items-center gap-2 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-800"> Low</span>
                                    @else
                                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700"> Adequate</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800">Rp{{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" onclick="openModal('{{ $product->id }}')" class="inline-flex items-center gap-2 rounded-full bg-[#8b6f47] px-4 py-2 text-sm font-semibold text-white shadow hover:bg-[#7a5f3f] focus:outline-none focus:ring-2 focus:ring-[#d4a574]">
                                         Update
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products instanceof \Illuminate\Pagination\Paginator && $products->hasPages())
                <div class="border-t border-[#e8d4c0] bg-white/70 px-6 py-4">
                    <div class="flex justify-center">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Update modals -->
@forelse($products as $product)
    <div id="modal-{{ $product->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4" style="display: none;">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[#8b6f47]">Update Inventory</p>
                    <h3 class="text-lg font-bold text-gray-900">{{ $product->name }}</h3>
                </div>
                <button type="button" onclick="closeModal('{{ $product->id }}')" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('admin.inventory.updateStock', $product) }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Current Stock</label>
                        <input type="number" value="{{ $product->stock }}" disabled class="mt-2 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">New Stock</label>
                        <input type="number" name="new_stock" value="{{ $product->stock }}" required class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-[#8b6f47] focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Current Price</label>
                        <div class="relative mt-2">
                            <span class="absolute left-3 top-2.5 text-sm text-gray-500">Rp</span>
                            <input type="number" value="{{ $product->price }}" disabled class="w-full rounded-xl border border-gray-200 bg-gray-50 pl-10 pr-3 py-2.5 text-sm text-gray-700">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">New Price</label>
                        <div class="relative mt-2">
                            <span class="absolute left-3 top-2.5 text-sm text-gray-500">Rp</span>
                            <input type="number" name="new_price" value="{{ $product->price }}" required min="0" class="w-full rounded-xl border border-gray-200 bg-white pl-10 pr-3 py-2.5 text-sm focus:border-[#8b6f47] focus:outline-none">
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600">Reason for Change</label>
                        <input type="text" name="reason" required placeholder="e.g., Restock, price adjustment, promotion" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-[#8b6f47] focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Low Stock Threshold</label>
                        <input type="number" name="low_stock_threshold" value="{{ $product->low_stock_threshold }}" min="1" class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:border-[#8b6f47] focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Quick Adjust</label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach([+5, +10, -5, -10] as $delta)
                                <button type="button" onclick="adjustStock('{{ $product->id }}', {{ $delta }})" class="rounded-full border border-[#e8d4c0] bg-[#f5eee6] px-3 py-1 text-xs font-semibold text-[#8b6f47] hover:border-[#d4a574] hover:bg-[#e8d4c0]">{{ $delta > 0 ? '+' : '' }}{{ $delta }}</button>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal('{{ $product->id }}')" class="rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="rounded-full bg-[#8b6f47] px-5 py-2 text-sm font-semibold text-white shadow hover:bg-[#7a5f3f] focus:outline-none focus:ring-2 focus:ring-[#d4a574]">Save</button>
                </div>
            </form>
        </div>
    </div>
@empty
@endforelse

<script>
    function openModal(productId) {
        const modal = document.getElementById('modal-' + productId);
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.remove('hidden');
        }
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(productId) {
        const modal = document.getElementById('modal-' + productId);
        if (modal) {
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }
        document.body.classList.remove('overflow-hidden');
    }

    function adjustStock(productId, delta) {
        const input = document.querySelector(`#modal-${productId} input[name="new_stock"]`);
        if (!input) return;
        const current = parseInt(input.value || '0', 10);
        const next = Math.max(0, current + delta);
        input.value = next;
    }

    // Close modals on backdrop click
    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                this.style.display = 'none';
                this.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        });
    });
</script>

@include('partials.footer')

@endsection

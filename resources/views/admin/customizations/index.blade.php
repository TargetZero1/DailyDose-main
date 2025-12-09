@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold mb-4">Saved Customizations</h1>
    @if(session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded p-4">
        <table class="w-full table-auto">
            <thead>
                <tr class="text-left">
                    <th class="p-2">ID</th>
                    <th class="p-2">Product</th>
                    <th class="p-2">User</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Options</th>
                    <th class="p-2">Template</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $it)
                    <tr class="border-t">
                        <td class="p-2">{{ $it->id }}</td>
                        <td class="p-2">{{ $it->product->name ?? '—' }}</td>
                        <td class="p-2">{{ $it->user->username ?? 'Guest' }}</td>
                        <td class="p-2">{{ $it->name ?? '-' }}</td>
                        <td class="p-2"><pre class="text-sm">{{ json_encode($it->options, JSON_PRETTY_PRINT) }}</pre></td>
                        <td class="p-2">{{ $it->is_template ? 'Yes' : 'No' }}</td>
                        <td class="p-2 flex gap-2">
                            <form action="{{ route('admin.customizations.toggle', $it) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 bg-amber-700 text-white rounded text-sm">Toggle Template</button>
                            </form>

                            <form action="{{ route('admin.customizations.import', $it) }}" method="POST" onsubmit="return confirm('Import options as product variants? This will create variant rows for the product.');">
                                @csrf
                                <button class="px-2 py-1 bg-blue-600 text-white rounded text-sm">Import as Variants</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

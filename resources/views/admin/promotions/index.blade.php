@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Promotions</h1>
            <a href="{{ route('promotions.create') }}" class="px-4 py-2 bg-amber-700 text-white rounded">Create Promotion</a>
        </div>

        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 text-left">Title</th>
                    <th class="p-2">Type</th>
                    <th class="p-2">Value</th>
                    <th class="p-2">Active</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($promotions as $promo)
                    <tr class="border-t">
                        <td class="p-2">{{ $promo->title }}</td>
                        <td class="p-2">{{ $promo->type }}</td>
                        <td class="p-2">{{ $promo->value }}</td>
                        <td class="p-2">{{ $promo->is_active ? 'Yes' : 'No' }}</td>
                        <td class="p-2">
                            <a href="{{ route('promotions.edit', $promo->id) }}" class="px-2 py-1 border rounded">Edit</a>
                            <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-1 border rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $promotions->links() }}
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

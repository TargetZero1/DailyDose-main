@extends('layouts.app')

@section('content')
<style>
    .form-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
        padding: 32px 16px;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 32px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-label {
        display: block;
        font-weight: 600;
        color: #352b06;
        margin-bottom: 8px;
    }
    
    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        border: 2px solid #e0d5c7;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        border-color: #d4a574;
        background-color: #fffbf7;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
        outline: none;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }
    
    .checkbox-group {
        display: flex;
        gap: 16px;
        margin-top: 8px;
    }
    
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .checkbox-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    
    .error-message {
        color: #dc2626;
        font-size: 14px;
        margin-top: 6px;
    }
    
    .button-group {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }
    
    .btn-submit,
    .btn-cancel {
        padding: 12px 32px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212, 165, 116, 0.3);
    }
    
    .btn-cancel {
        background: #e5e7eb;
        color: #374151;
    }
    
    .btn-cancel:hover {
        background: #d1d5db;
    }
    
    .product-preview {
        border-radius: 8px;
        padding: 12px;
        background: #f3f4f6;
        margin-top: 8px;
    }
    
    .product-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 6px;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h1 class="text-3xl font-black text-gray-900 mb-8">Edit Product</h1>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                <ul class="text-red-700">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <div class="form-group">
                <label class="form-label">Product Name <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    name="name" 
                    class="form-input @error('name') border-red-500 @enderror"
                    placeholder="e.g., Cappuccino"
                    value="{{ old('name', $product->name) }}"
                    required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label class="form-label">Description <span class="text-red-500">*</span></label>
                <textarea 
                    name="description" 
                    class="form-textarea @error('description') border-red-500 @enderror"
                    placeholder="Describe your product..."
                    required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category & Price Row -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="form-select @error('category') border-red-500 @enderror" required>
                        <option value="">Select a category...</option>
                        <option value="Coffee" {{ old('category', $product->category) === 'Coffee' ? 'selected' : '' }}>Coffee</option>
                        <option value="Tea" {{ old('category', $product->category) === 'Tea' ? 'selected' : '' }}>Tea</option>
                        <option value="Pastry" {{ old('category', $product->category) === 'Pastry' ? 'selected' : '' }}>Pastry</option>
                        <option value="Cake" {{ old('category', $product->category) === 'Cake' ? 'selected' : '' }}>Cake</option>
                        <option value="Icecream" {{ old('category', $product->category) === 'Icecream' ? 'selected' : '' }}>Ice Cream</option>
                        <option value="Snack" {{ old('category', $product->category) === 'Snack' ? 'selected' : '' }}>Snack</option>
                        <option value="Other" {{ old('category', $product->category) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Price (Rp) <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        name="price" 
                        class="form-input @error('price') border-red-500 @enderror"
                        placeholder="25000"
                        value="{{ old('price', $product->price) }}"
                        min="0"
                        required>
                    @error('price')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Stock -->
            <div class="form-group">
                <label class="form-label">Stock <span class="text-red-500">*</span></label>
                <input 
                    type="number" 
                    name="stock" 
                    class="form-input @error('stock') border-red-500 @enderror"
                    placeholder="50"
                    value="{{ old('stock', $product->stock) }}"
                    min="0"
                    required>
                @error('stock')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                @if($product->image)
                    <div class="product-preview">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Current Image:</p>
                        <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect width=%22200%22 height=%22200%22 fill=%22%23e5e7eb%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2212%22 fill=%22%236b7280%22%3ENo Image%3C/text%3E%3C/svg%3E'">>
                    </div>
                @endif
                <input 
                    type="file" 
                    name="image" 
                    class="form-input @error('image') border-red-500 @enderror"
                    accept="image/*">
                <p class="text-sm text-gray-600 mt-2">Max file size: 2MB. Supported formats: JPG, PNG, GIF (Leave empty to keep current image)</p>
                @error('image')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Flags -->
            <div class="form-group">
                <label class="form-label">Product Flags</label>
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input 
                            type="checkbox" 
                            id="is_featured" 
                            name="is_featured" 
                            {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label for="is_featured" class="text-gray-700 cursor-pointer">
                            <i class="fas fa-star text-amber-500 mr-2"></i> Featured Item
                        </label>
                    </div>
                    <div class="checkbox-item">
                        <input 
                            type="checkbox" 
                            id="is_new" 
                            name="is_new" 
                            {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                        <label for="is_new" class="text-gray-700 cursor-pointer">
                            <i class="fas fa-badge text-blue-500 mr-2"></i> New Item
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-cancel">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@include('partials.footer')

@endsection

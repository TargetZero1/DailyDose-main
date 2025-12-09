@extends('layouts.app')

@section('content')
<style>
    .edit-container {
        background: #f5f5f5;
        min-height: calc(100vh - 80px);
        padding: 32px 0;
    }

    .edit-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
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
        font-size: 14px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #d4a574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }

    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 32px;
    }

    .btn-primary {
        flex: 1;
        padding: 14px 24px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-secondary {
        flex: 1;
        padding: 14px 24px;
        background: #f0f0f0;
        color: #352b06;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
        border-color: #d4a574;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #10b981;
    }

    .alert-error {
        background: #fee;
        color: #991b1b;
        border: 1px solid #ef4444;
    }
</style>

<div class="edit-header">
    <div class="max-w-6xl" style="max-width: 1200px; margin: 0 auto; padding: 0 16px;">
        <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-user-edit" style="margin-right: 12px;"></i>Edit Profile
        </h1>
        <p style="font-size: 16px; opacity: 0.95;">Update your account information</p>
    </div>
</div>

<div class="edit-container">
    <div class="form-card">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-user mr-2" style="color: #d4a574;"></i>Username
                </label>
                <input 
                    type="text" 
                    name="username" 
                    value="{{ old('username', $user->username) }}" 
                    class="form-input"
                    placeholder="Enter your username"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone mr-2" style="color: #d4a574;"></i>Phone Number
                </label>
                <input 
                    type="text" 
                    name="no_hp" 
                    value="{{ old('no_hp', $user->no_hp) }}" 
                    class="form-input"
                    placeholder="Enter your phone number"
                >
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-map-marker-alt mr-2" style="color: #d4a574;"></i>Address
                </label>
                <textarea 
                    name="alamat" 
                    rows="4" 
                    class="form-input"
                    placeholder="Enter your address"
                >{{ old('alamat', $user->alamat ?? '') }}</textarea>
            </div>

            <div class="btn-group">
                <a href="{{ route('profile.show') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

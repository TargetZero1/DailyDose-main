@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        background: #f5f5f5;
        min-height: calc(100vh - 80px);
        padding: 32px 0;
    }

    .profile-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 32px;
    }

    .profile-banner {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
    }

    .profile-avatar-section {
        display: flex;
        gap: 24px;
        margin-bottom: 32px;
        padding: 0 16px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        color: #d4a574;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .profile-info {
        flex: 1;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        color: #352b06;
        margin-bottom: 4px;
    }

    .profile-role {
        color: #999;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .profile-email {
        color: #666;
        font-size: 14px;
    }

    .max-w-6xl {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .stat-icon {
        font-size: 32px;
        color: #d4a574;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #352b06;
    }

    .stat-label {
        color: #999;
        font-size: 13px;
        margin-top: 4px;
    }

    .content-section {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #352b06;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title i {
        color: #d4a574;
        font-size: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #999;
        font-size: 14px;
        font-weight: 600;
    }

    .info-value {
        color: #352b06;
        font-size: 14px;
        font-weight: 600;
    }

    .recent-orders-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .order-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid #d4a574;
    }

    .order-info {
        flex: 1;
    }

    .order-id {
        font-weight: 700;
        color: #352b06;
        margin-bottom: 4px;
    }

    .order-status {
        font-size: 12px;
        color: #999;
    }

    .order-amount {
        font-weight: 700;
        color: #d4a574;
        font-size: 16px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .btn-primary {
        flex: 1;
        min-width: 140px;
        padding: 12px 20px;
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-secondary {
        flex: 1;
        min-width: 140px;
        padding: 12px 20px;
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
        gap: 8px;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
        border-color: #d4a574;
    }
</style>

<div class="profile-header">
    <div class="max-w-6xl">
        <h1 style="font-size: 32px; font-weight: 700; margin-bottom: 8px;">
            <i class="fas fa-user-circle" style="margin-right: 12px;"></i>My Profile
        </h1>
        <p style="font-size: 16px; opacity: 0.95;">Manage your account and preferences</p>
    </div>
</div>

<div class="profile-container">
    <div class="max-w-6xl">
        <!-- Profile Card -->
        <div class="profile-avatar-section">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <div class="profile-name">{{ $user->username ?? $user->name }}</div>
                <div class="profile-role"><i class="fas fa-badge mr-1" style="color: #d4a574;"></i>{{ ucfirst($user->role ?? 'customer') }}</div>
                <div class="profile-email" style="margin-top: 8px;">
                    <i class="fas fa-phone mr-2" style="color: #d4a574;"></i>
                    {{ $user->no_hp ?? 'Not provided' }}
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-value">{{ $ordersCount ?? 0 }}</div>
                <div class="stat-label">Total Orders</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div class="stat-value">{{ $user->favorites->count() ?? 0 }}</div>
                <div class="stat-label">Favorites</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $reservationsCount ?? 0 }}</div>
                <div class="stat-label">Reservations</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $reviewsCount ?? 0 }}</div>
                <div class="stat-label">Reviews</div>
            </div>
        </div>

        <!-- Account Details -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>Account Information
            </div>
            <div class="info-row">
                <span class="info-label">Username</span>
                <span class="info-value">{{ $user->username }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone Number</span>
                <span class="info-value">{{ $user->no_hp ?? 'Not provided' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Account Type</span>
                <span class="info-value">{{ ucfirst($user->role ?? 'customer') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Member Since</span>
                <span class="info-value">{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('profile.edit', $user->id) }}" class="btn-primary">
                    <i class="fas fa-edit"></i>Edit Profile
                </a>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="content-section">
            <div class="section-title">
                <i class="fas fa-history"></i>Recent Orders
            </div>
            <div class="recent-orders-list">
                @if(isset($latestOrders) && $latestOrders->count() > 0)
                    @foreach($latestOrders as $order)
                        <div class="order-item">
                            <div class="order-info">
                                <div class="order-id">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                <div class="order-status">
                                    {{ $order->created_at->format('d M Y, H:i') }} — 
                                    <span style="color: #d4a574; font-weight: 600;">{{ ucfirst($order->status ?? 'pending') }}</span>
                                </div>
                            </div>
                            <div class="order-amount">
                                Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 24px; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                        <p>No orders yet</p>
                    </div>
                @endif
            </div>
            <div style="margin-top: 16px;">
                <a href="{{ route('orders.index') }}" class="btn-primary">
                    <i class="fas fa-list"></i>View All Orders
                </a>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="content-section">
            <div class="button-group">
                <a href="{{ route('home') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i>Back to Home
                </a>
                <form action="{{ route('logout') }}" method="POST" style="flex: 1; min-width: 140px;">
                    @csrf
                    <button type="submit" class="btn-secondary" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

@endsection

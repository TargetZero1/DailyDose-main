@extends('layouts.app')

@section('content')
<style>
    .admin-container {
        background: #f8f7f5;
        min-height: calc(100vh - 100px);
    }
    
    .admin-header {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 32px 0;
        margin-bottom: 32px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #d4a574;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
        font-weight: 500;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #352b06;
        margin-top: 8px;
    }

    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .filter-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        align-items: flex-end;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #d4a574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter:hover {
        box-shadow: 0 4px 12px rgba(212, 165, 116, 0.3);
    }

    .btn-filter-reset {
        background: #6b7280;
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-filter-reset:hover {
        background: #4b5563;
    }
    
    .reservation-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #d4a574;
    }

    .reservation-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .reservation-header {
        font-weight: 700;
        color: #352b06;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .reservation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 12px;
    }

    .reservation-detail {
        background: #f8f7f5;
        padding: 12px;
        border-radius: 8px;
    }

    .detail-label {
        font-size: 12px;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 600;
        color: #352b06;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef08a;
        color: #854d0e;
    }

    .reservation-notes {
        background: #efe8df;
        padding: 12px;
        border-radius: 8px;
        border-left: 3px solid #d4a574;
        margin-top: 12px;
        font-size: 14px;
        color: #352b06;
    }

    .reservation-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .btn-action {
        padding: 8px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-confirm {
        background: #10b981;
        color: white;
    }

    .btn-confirm:hover {
        background: #059669;
    }

    .btn-cancel {
        background: #ef4444;
        color: white;
    }

    .btn-cancel:hover {
        background: #dc2626;
    }
</style>

<div class="admin-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black mb-2">Reservations Management</h1>
        <p class="text-amber-100">Manage table reservations with advanced filtering and analytics</p>
    </div>
</div>

<div class="admin-container py-12">
    <div class="container mx-auto px-4">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-calendar-check mr-2"></i>Total Reservations</div>
                <div class="stat-value">{{ $reservations->total() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <div class="stat-label"><i class="fas fa-check-circle mr-2"></i>Confirmed</div>
                <div class="stat-value">{{ collect($reservations->items())->where('status', 'confirmed')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-label"><i class="fas fa-clock mr-2"></i>Pending</div>
                <div class="stat-value">{{ collect($reservations->items())->where('status', 'pending')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #3b82f6;">
                <div class="stat-label"><i class="fas fa-users mr-2"></i>Total Guests</div>
                <div class="stat-value">{{ collect($reservations->items())->sum('jumlah') }}</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fas fa-sliders-h mr-2"></i>Filter Reservations</h3>
            <form method="GET" action="{{ route('admin.reservations') }}" class="space-y-4">
                <div class="filter-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by name or phone</label>
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Area</label>
                        <select name="area">
                            <option value="">All Areas</option>
                            <option value="indoor" {{ request('area') == 'indoor' ? 'selected' : '' }}>Indoor</option>
                            <option value="outdoor" {{ request('area') == 'outdoor' ? 'selected' : '' }}>Outdoor</option>
                            <option value="vip" {{ request('area') == 'vip' ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                        <select name="sort">
                            <option value="">Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="earliest_date" {{ request('sort') == 'earliest_date' ? 'selected' : '' }}>Earliest Date</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.reservations') }}" class="btn-filter-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Reservations List -->
        <div>
            @forelse($reservations as $reservation)
                <div class="reservation-card" data-reservation-id="{{ $reservation->id }}">
                    <div class="reservation-header">
                        {{ $reservation->nama }}
                        <span class="status-badge status-{{ $reservation->status ?? 'pending' }} float-right">
                            {{ ucfirst($reservation->status ?? 'pending') }}
                        </span>
                    </div>

                    <div class="reservation-grid">
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-phone mr-1"></i> Phone</div>
                            <div class="detail-value">{{ $reservation->no_hp }}</div>
                        </div>
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-calendar mr-1"></i> Date</div>
                            <div class="detail-value">{{ $reservation->tanggal ? $reservation->tanggal->format('d M Y') : 'N/A' }}</div>
                        </div>
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-clock mr-1"></i> Time</div>
                            <div class="detail-value">{{ $reservation->waktu ?? 'N/A' }}</div>
                        </div>
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-users mr-1"></i> Guests</div>
                            <div class="detail-value">{{ $reservation->jumlah }} people</div>
                        </div>
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-chair mr-1"></i> Area</div>
                            <div class="detail-value">{{ $reservation->area ?? 'N/A' }}</div>
                        </div>
                        <div class="reservation-detail">
                            <div class="detail-label"><i class="fas fa-calendar-plus mr-1"></i> Booked</div>
                            <div class="detail-value">{{ $reservation->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    @if($reservation->notes)
                        <div class="reservation-notes">
                            <i class="fas fa-sticky-note mr-2"></i><strong>Notes:</strong> {{ $reservation->notes }}
                        </div>
                    @endif

                    <div class="reservation-actions">
                        @if($reservation->status !== 'confirmed')
                            <button class="btn-action btn-confirm" onclick="confirmReservation('{{ $reservation->id }}')">
                                <i class="fas fa-check"></i> Confirm
                            </button>
                        @endif
                        @if($reservation->status !== 'cancelled')
                            <button class="btn-action btn-cancel" onclick="cancelReservation('{{ $reservation->id }}')">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-calendar text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-600 text-lg">No reservations found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reservations->hasPages())
            <div class="mt-12">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    function confirmReservation(id) {
        const card = document.querySelector(`[data-reservation-id="${id}"]`);
        
        fetch(`/admin/reservations/${id}/confirm`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show toast notification
                if (typeof Toast !== 'undefined') {
                    Toast.success(data.message);
                }
                
                // Update UI in real-time
                updateReservationCard(id, 'confirmed');
                
                // Update stats counter
                updateStatsCounters();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Toast !== 'undefined') {
                Toast.error('Failed to confirm reservation');
            } else {
                alert('Failed to confirm reservation');
            }
        });
    }

    function cancelReservation(id) {
        if (!confirm('Cancel this reservation? This action cannot be undone.')) {
            return;
        }
        
        const card = document.querySelector(`[data-reservation-id="${id}"]`);
        
        fetch(`/admin/reservations/${id}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show toast notification
                if (typeof Toast !== 'undefined') {
                    Toast.success(data.message);
                }
                
                // Update UI in real-time
                updateReservationCard(id, 'cancelled');
                
                // Update stats counter
                updateStatsCounters();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof Toast !== 'undefined') {
                Toast.error('Failed to cancel reservation');
            } else {
                alert('Failed to cancel reservation');
            }
        });
    }

    function updateReservationCard(id, newStatus) {
        const card = document.querySelector(`[data-reservation-id="${id}"]`);
        if (!card) return;

        // Update status badge
        const statusBadge = card.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'status-badge status-' + newStatus;
            statusBadge.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
        }

        // Update action buttons
        const confirmBtn = card.querySelector('.btn-confirm');
        const cancelBtn = card.querySelector('.btn-cancel');
        
        if (newStatus === 'confirmed' && confirmBtn) {
            confirmBtn.remove();
        }
        
        if (newStatus === 'cancelled' && cancelBtn) {
            cancelBtn.remove();
        }

        // Add animation
        card.style.transition = 'all 0.3s ease';
        card.style.transform = 'scale(0.98)';
        setTimeout(() => {
            card.style.transform = 'scale(1)';
        }, 150);
    }

    function updateStatsCounters() {
        // Fetch updated stats from server
        fetch(window.location.href, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the HTML and update stat values
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const statsCards = ['Total Reservations', 'Confirmed', 'Pending', 'Total Guests'];
            statsCards.forEach(label => {
                const newStat = doc.querySelector(`.stat-label:contains("${label}")`);
                const currentStat = document.querySelector(`.stat-label:contains("${label}")`);
                
                if (newStat && currentStat) {
                    const newValue = newStat.nextElementSibling?.textContent;
                    const currentValue = currentStat.nextElementSibling;
                    if (newValue && currentValue) {
                        currentValue.textContent = newValue;
                    }
                }
            });
        })
        .catch(error => console.error('Error updating stats:', error));
    }
</script>

@include('partials.footer')

@endsection

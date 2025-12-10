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
    
    .user-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #d4a574;
    }

    .user-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .user-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    
    .user-name {
        font-weight: 700;
        color: #352b06;
        font-size: 18px;
    }

    .user-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        margin-bottom: 12px;
    }

    .user-detail {
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
        font-size: 14px;
        font-weight: 600;
        color: #352b06;
    }
    
    .role-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .role-admin {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .role-pemilik {
        background: #fef3c7;
        color: #92400e;
    }
    
    .role-user {
        background: #d1fae5;
        color: #065f46;
    }

    .status-online {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        margin-right: 4px;
    }

    .user-actions {
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

    .btn-edit {
        background: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background: #2563eb;
    }

    .btn-view {
        background: #8b5cf6;
        color: white;
    }

    .btn-view:hover {
        background: #7c3aed;
    }
</style>

<div class="admin-header">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black mb-2">Users Management</h1>
        <p class="text-amber-100">Manage registered users with detailed analytics and filtering</p>
    </div>
</div>

<div class="admin-container py-12">
    <div class="container mx-auto px-4">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-users mr-2"></i>Total Users</div>
                <div class="stat-value">{{ $users->total() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #ef4444;">
                <div class="stat-label"><i class="fas fa-crown mr-2"></i>Admins</div>
                <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <div class="stat-label"><i class="fas fa-user-tie mr-2"></i>Pemilik</div>
                <div class="stat-value">{{ $users->where('role', 'pemilik')->count() }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <div class="stat-label"><i class="fas fa-user-check mr-2"></i>Regular Users</div>
                <div class="stat-value">{{ $users->where('role', 'user')->count() }}</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h3 class="font-bold text-lg mb-4 text-gray-800"><i class="fas fa-sliders-h mr-2"></i>Filter Users</h3>
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="filter-group">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by name or email</label>
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                        <select name="sort">
                            <option value="">Newest First</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                        </select>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn-filter-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users List -->
        <div>
            @forelse($users as $user)
                <div class="user-card">
                    <div class="user-header">
                        <div>
                            <div class="user-name">
                                <i class="fas fa-user-circle mr-2 text-amber-700"></i>{{ $user->username }}
                            </div>
                            <span class="role-badge {{ 'role-' . $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->is_banned ?? false)
                                <span class="role-badge" style="background: #fee2e2; color: #991b1b; margin-left: 8px;">
                                    <i class="fas fa-ban mr-1"></i>Banned
                                </span>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($user->is_banned ?? false)
                                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #ef4444; margin-right: 4px;"></span>
                                <span style="font-size: 12px; color: #ef4444; font-weight: 600;">Banned</span>
                            @else
                                <span class="status-online"></span>
                                <span style="font-size: 12px; color: #666;">Active</span>
                            @endif
                        </div>
                    </div>

                    <div class="user-grid">
                        <div class="user-detail">
                            <div class="detail-label"><i class="fas fa-envelope mr-1"></i> Email</div>
                            <div class="detail-value text-sm">{{ $user->email ?? 'N/A' }}</div>
                        </div>
                        <div class="user-detail">
                            <div class="detail-label"><i class="fas fa-phone mr-1"></i> Phone</div>
                            <div class="detail-value text-sm">{{ $user->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="user-detail">
                            <div class="detail-label"><i class="fas fa-calendar mr-1"></i> Joined</div>
                            <div class="detail-value">{{ $user->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="user-detail">
                            <div class="detail-label"><i class="fas fa-history mr-1"></i> Last Activity</div>
                            <div class="detail-value">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div class="user-actions">
                        <button class="btn-action btn-view" onclick="openViewModal({{ $user->id }}, '{{ $user->username }}', '{{ $user->email }}', '{{ $user->phone }}', '{{ $user->role }}', '{{ $user->created_at->format('d M Y') }}', '{{ $user->updated_at->diffForHumans() }}')">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                        @if($user->is_banned ?? false)
                            <button class="btn-action" style="background: #10b981; color: white;" onclick="toggleBan({{ $user->id }}, false)">
                                <i class="fas fa-unlock"></i> Unban User
                            </button>
                        @else
                            <button class="btn-action" style="background: #ef4444; color: white;" onclick="toggleBan({{ $user->id }}, true)">
                                <i class="fas fa-ban"></i> Ban User
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-600 text-lg">No users found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-12">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

<!-- View User Modal -->
<div id="viewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-500">User Details</p>
                <h3 id="modalUsername" class="text-lg font-bold text-gray-900"></h3>
            </div>
            <button onclick="closeViewModal()" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Email</p>
                    <p id="modalEmail" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Phone</p>
                    <p id="modalPhone" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Role</p>
                    <p id="modalRole" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Joined Date</p>
                    <p id="modalJoined" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 md:col-span-2">
                    <p class="text-xs font-semibold text-gray-600 mb-1">Last Activity</p>
                    <p id="modalLastActivity" class="text-sm font-medium text-gray-900"></p>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button onclick="closeViewModal()" class="rounded-full bg-amber-700 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-amber-800">Close</button>
        </div>
    </div>
</div>

<script>
    function openViewModal(userId, username, email, phone, role, joined, lastActivity) {
        document.getElementById('modalUsername').textContent = username;
        document.getElementById('modalEmail').textContent = email || 'N/A';
        document.getElementById('modalPhone').textContent = phone || 'N/A';
        document.getElementById('modalRole').textContent = role.charAt(0).toUpperCase() + role.slice(1);
        document.getElementById('modalJoined').textContent = joined;
        document.getElementById('modalLastActivity').textContent = lastActivity;
        document.getElementById('viewModal').classList.remove('hidden');
        document.getElementById('viewModal').classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
        document.getElementById('viewModal').classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleBan(userId, shouldBan) {
        if (!confirm(shouldBan ? 'Are you sure you want to ban this user?' : 'Are you sure you want to unban this user?')) {
            return;
        }

        fetch(`/admin/users/${userId}/ban`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ ban: shouldBan })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }

    // Close modal on backdrop click
    document.getElementById('viewModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
        }
    });
</script>

@include('partials.footer')

@endsection

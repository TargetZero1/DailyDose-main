<!-- Navbar & Header -->
<nav style="background: white; border-bottom: 1px solid #f0f0f0; position: sticky; top: 0; z-index: 50;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 40px; display: flex; justify-content: space-between; align-items: center; height: 70px;">
        <!-- Logo -->
        <a href="/" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
            <i class="fas fa-coffee" style="color: #d4a574; font-size: 28px;"></i>
            <span style="font-size: 24px; font-weight: 900; color: #2d2d2d;">DailyDose</span>
        </a>

        <!-- Navigation Links -->
        <div style="display: flex; gap: 40px; align-items: center;">
            <a href="/" style="color: #666; text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#d4a574'" onmouseout="this.style.color='#666'">Home</a>
            <a href="{{ route('menu') }}" style="color: #666; text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#d4a574'" onmouseout="this.style.color='#666'">Menu</a>
            <a href="{{ route('reservasi.create') }}" style="color: #666; text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#d4a574'" onmouseout="this.style.color='#666'">Reservations</a>
            <a href="{{ route('about') }}" style="color: #666; text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#d4a574'" onmouseout="this.style.color='#666'">About</a>
            <a href="{{ route('contact') }}" style="color: #666; text-decoration: none; font-weight: 500; transition: color 0.3s;" onmouseover="this.style.color='#d4a574'" onmouseout="this.style.color='#666'">Contact</a>
        </div>

            <!-- Profile Dropdown & Auth -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="relative">
                        <button onclick="toggleDropdown(event)" class="flex items-center gap-2 bg-amber-100 px-4 py-2 rounded-lg hover:bg-amber-200 transition">
                            <i class="fas fa-user-circle text-amber-700"></i>
                            <span class="text-gray-800 font-medium hidden sm:inline">{{ Auth::user()->username }}</span>
                            <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu">
                            <a href="/profile" class="block px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition rounded-t-lg border-b border-gray-100">
                                <i class="fas fa-user mr-2"></i> My Profile
                            </a>
                            <a href="{{ route('favorites.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition border-b border-gray-100">
                                <i class="fas fa-heart mr-2" style="color: #ff4757;"></i> My Favorites
                            </a>
                            @if(Auth::user()->role !== 'admin' && Auth::user()->role !== 'pemilik')
                            <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition border-b border-gray-100">
                                <i class="fas fa-shopping-bag mr-2"></i> My Orders
                            </a>
                            @endif
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')
                                <div class="border-t border-gray-200 pt-2">
                                    <div class="px-4 py-2 text-xs font-bold uppercase text-amber-700">Admin Panel</div>
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-chart-line mr-2"></i> Dashboard
                                    </a>
                                    <a href="{{ route('admin.products.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-cube mr-2"></i> Products
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-receipt mr-2"></i> Orders
                                    </a>
                                    <a href="{{ route('admin.analytics.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-chart-bar mr-2"></i> Analytics
                                    </a>
                                    <a href="{{ route('admin.inventory.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-warehouse mr-2"></i> Inventory
                                    </a>
                                    <a href="{{ route('admin.reservations.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-calendar-check mr-2"></i> Reservations
                                    </a>
                                    <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition text-sm">
                                        <i class="fas fa-users mr-2"></i> Users
                                    </a>
                                </div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="block border-t border-gray-200 mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition rounded-b-lg">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-amber-700 border border-amber-700 rounded-lg hover:bg-amber-50 transition font-medium">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-medium">
                        <i class="fas fa-user-plus mr-2"></i> Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
    #dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        margin-top: 0.5rem;
        width: 16rem;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        z-index: 1000;
    }

    #dropdown-menu.show {
        display: block;
    }
</style>

<script>
    let dropdownOpen = false;
    const dropdownMenu = document.getElementById('dropdown-menu');
    const profileButton = document.querySelector('button[onclick*="toggleDropdown"]');

    function toggleDropdown(e) {
        e.stopPropagation();
        dropdownOpen = !dropdownOpen;
        
        if (dropdownOpen) {
            dropdownMenu.classList.add('show');
        } else {
            dropdownMenu.classList.remove('show');
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (dropdownOpen && !event.target.closest('.relative')) {
            dropdownMenu.classList.remove('show');
            dropdownOpen = false;
        }
    });

    // Close dropdown on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && dropdownOpen) {
            dropdownMenu.classList.remove('show');
            dropdownOpen = false;
        }
    });

    // Prevent dropdown from closing when clicking inside it
    dropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

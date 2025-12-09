<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DailyDose - Cafe & Bakery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        .dropdown-menu {
            display: none;
        }
        .dropdown-menu.show {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header & Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2">
                    <i class="fas fa-coffee text-amber-700 text-2xl"></i>
                    <span class="text-2xl font-bold text-gray-800">DailyDose</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:flex gap-8">
                    <a href="/" class="text-gray-700 hover:text-amber-700 font-medium transition">Home</a>
                    <a href="{{ route('menu') }}" class="text-gray-700 hover:text-amber-700 font-medium transition">Menu</a>
                    <a href="#about" class="text-gray-700 hover:text-amber-700 font-medium transition">About</a>
                    <a href="#contact" class="text-gray-700 hover:text-amber-700 font-medium transition">Contact</a>
                </div>

                <!-- Profile Dropdown & Auth -->
                <div class="flex items-center gap-4">
                    @auth
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="flex items-center gap-2 bg-amber-100 px-4 py-2 rounded-lg hover:bg-amber-200 transition">
                                <i class="fas fa-user-circle text-amber-700"></i>
                                <span class="text-gray-800 font-medium">{{ Auth::user()->username }}</span>
                                <i class="fas fa-chevron-down text-gray-600 text-sm"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdown-menu" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200">
                                <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition rounded-t-lg">
                                    <i class="fas fa-user mr-2"></i> My Profile
                                </a>
                                <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    <i class="fas fa-shopping-bag mr-2"></i> My Orders
                                </a>
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')
                                    <a href="/admin" class="block px-4 py-2 text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition border-t border-gray-200">
                                        <i class="fas fa-cog mr-2"></i> Admin Panel
                                    </a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition rounded-b-lg border-t border-gray-200">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="/login" class="px-4 py-2 text-amber-700 border border-amber-700 rounded-lg hover:bg-amber-50 transition font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                        <a href="/register" class="px-4 py-2 bg-amber-700 text-white rounded-lg hover:bg-amber-800 transition font-medium">
                            <i class="fas fa-user-plus mr-2"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">Welcome to DailyDose</h1>
            <p class="text-xl mb-8 text-amber-100">Delicious Cakes, Pastries & Coffee - Your Daily Treat</p>
            <div class="flex gap-4 justify-center">
                <a href="{{ route('menu') }}" class="px-8 py-3 bg-white text-amber-700 font-bold rounded-lg hover:bg-amber-50 transition shadow-lg">
                    <i class="fas fa-shopping-cart mr-2"></i> Order Now
                </a>
                <a href="#about" class="px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-amber-700 transition">
                    <i class="fas fa-info-circle mr-2"></i> Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Our Featured Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($featuredProducts->take(3) as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition transform hover:scale-105">
                        <a href="{{ route('products.show', $product->id) }}" class="block">
                            <div class="h-48 flex items-center justify-center" style="background: linear-gradient(135deg, {{ ['#fbbf24, #f59e0b', '#ec4899, #be185d', '#fbbf24, #d97706'][($loop->index) % 3] }});">
                                <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                            </div>
                        </a>
                        <div class="p-6">
                            <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none;">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-amber-700 transition">{{ $product->name }}</h3>
                            </a>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->description ?? 'Delicious premium quality item made with the finest ingredients', 60) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-amber-700 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('products.show', $product->id) }}" class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-4 py-2 rounded-lg hover:from-amber-700 hover:to-amber-800 transition">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500 text-lg">No featured products available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">About DailyDose</h2>
                    <p class="text-gray-600 mb-4 text-lg">
                        Since 2025, DailyDose has been serving the finest cakes, pastries, and ice cream in the community. 
                        We believe in using only the freshest ingredients and traditional baking methods.
                    </p>
                    <p class="text-gray-600 mb-6 text-lg">
                        Our mission is to provide delicious treats that make every day special. Whether it's a celebration, 
                        a casual meet-up, or just treating yourself, we have something perfect for you.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-700"></i>
                            <span>Premium Quality Ingredients</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-700"></i>
                            <span>Made Fresh Daily</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-700"></i>
                            <span>Custom Orders Available</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-amber-700"></i>
                            <span>Fast & Reliable Delivery</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg p-12 text-white shadow-xl">
                        <i class="fas fa-store text-6xl mb-6"></i>
                        <h3 class="text-3xl font-bold mb-4">Visit Us Today</h3>
                        <p class="text-lg mb-6">
                            Experience the taste of freshness and quality at our cozy cafe.
                        </p>
                        <div class="space-y-3">
                            <p><i class="fas fa-clock mr-3"></i> Mon - Sun: 7:00 AM - 9:00 PM</p>
                            <p><i class="fas fa-phone mr-3"></i> +63 910 123 4567</p>
                            <p><i class="fas fa-map-marker-alt mr-3"></i> 123 Cafe Street, City</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-12">Get In Touch</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <i class="fas fa-phone text-amber-700 text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Call Us</h3>
                    <p class="text-gray-600">+63 910 123 4567</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <i class="fas fa-envelope text-amber-700 text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-600">info@dailydose.com</p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-md">
                    <i class="fas fa-map-marker-alt text-amber-700 text-4xl mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Location</h3>
                    <p class="text-gray-600">123 Cafe Street, City</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-xl font-bold mb-4 text-amber-400">DailyDose</h4>
                    <p class="text-gray-400">Your daily dose of deliciousness</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-amber-400 transition">Home</a></li>
                        <li><a href="{{ route('menu') }}" class="hover:text-amber-400 transition">Menu</a></li>
                        <li><a href="#about" class="hover:text-amber-400 transition">About</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Follow Us</h4>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition text-xl"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition text-xl"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-amber-400 transition text-xl"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Hours</h4>
                    <p class="text-gray-400 text-sm">Mon - Sun<br>7:00 AM - 9:00 PM</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown-menu');
            const button = event.target.closest('button');
            if (!button && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        });
    </script>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>

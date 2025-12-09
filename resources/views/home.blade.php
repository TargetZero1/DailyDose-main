@extends('layouts.app')

@section('content')
<style>
    .hero-gradient {
        background: linear-gradient(120deg, rgba(10, 7, 5, 0.58) 0%, rgba(26, 18, 13, 0.42) 100%),
            url('{{ asset('img/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .feature-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .feature-card:hover {
        transform: translateY(-8px);
        border-color: #d4a574;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(212, 165, 116, 0.4);
    }
    
    .product-preview {
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .stat-box {
        text-align: center;
        padding: 2rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 900;
        color: #d4a574;
    }
    
    .scroll-smooth {
        scroll-behavior: smooth;
    }
</style>

<!-- Hero Section with Full Background -->
<section class="hero-gradient text-white py-32 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 1200 600">
            <defs>
                <pattern id="dots" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
                    <circle cx="25" cy="25" r="2" fill="white"/>
                </pattern>
            </defs>
            <rect width="1200" height="600" fill="url(#dots)"/>
        </svg>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div class="animate-fade-in">
                <h1 class="text-6xl md:text-7xl font-black mb-8 leading-tight">
                    Welcome to<br><span class="text-yellow-300">DailyDose</span>
                </h1>
                <p class="text-lg md:text-xl text-amber-50 mb-12 leading-relaxed max-w-md">
                    Experience the finest coffee, pastries, and delicacies. Your daily dose of deliciousness awaits you.
                </p>
                <div class="flex flex-col sm:flex-row gap-5">
                    <a href="{{ route('menu') }}" class="btn-primary text-white px-7 py-3 rounded-lg font-bold text-base hover:text-white inline-flex items-center justify-center shadow-lg">
                        <i class="fas fa-utensils mr-2"></i>Explore Menu
                    </a>
                    <a href="{{ route('reservasi.create') }}" class="bg-white text-amber-700 px-7 py-3 rounded-lg font-bold text-base hover:bg-amber-50 inline-flex items-center justify-center transition shadow-lg">
                        <i class="fas fa-calendar-alt mr-2"></i>Book a Table
                    </a>
                </div>
            </div>
            
            <!-- Right Image -->
            <div class="hidden md:flex justify-end items-center">
                <div class="relative w-96 h-80 bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-white">
                    <img src="{{ asset('img/slide2.jpg') }}" alt="DailyDose Signature" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white py-16 border-b">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="stat-box">
                <div class="stat-number">500+</div>
                <p class="text-gray-600 text-lg font-semibold">Happy Customers</p>
            </div>
            <div class="stat-box">
                <div class="stat-number">50+</div>
                <p class="text-gray-600 text-lg font-semibold">Menu Items</p>
            </div>
            <div class="stat-box">
                <div class="stat-number">15+</div>
                <p class="text-gray-600 text-lg font-semibold">Years Experience</p>
            </div>
            <div class="stat-box">
                <div class="stat-number">4.9★</div>
                <p class="text-gray-600 text-lg font-semibold">Average Rating</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">Why Choose DailyDose?</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Premium quality products with exceptional service</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-card p-8 bg-white rounded-2xl">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-leaf text-amber-700 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Premium Quality</h3>
                <p class="text-gray-600 leading-relaxed">
                    We use only the finest ingredients sourced locally and internationally to ensure exceptional taste and quality.
                </p>
            </div>
            
            <div class="feature-card p-8 bg-white rounded-2xl">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-clock text-amber-700 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Fast Service</h3>
                <p class="text-gray-600 leading-relaxed">
                    Quick preparation and efficient service. Your orders are ready when you need them, every single time.
                </p>
            </div>
            
            <div class="feature-card p-8 bg-white rounded-2xl">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-hand-holding-heart text-amber-700 text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Customer First</h3>
                <p class="text-gray-600 leading-relaxed">
                    Your satisfaction is our priority. We're committed to delivering exceptional experience every visit.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Preview -->
<section class="py-20">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-gray-900">Featured Products</h2>
                <p class="text-gray-600 text-lg mt-2">Discover our most loved and premium products</p>
            </div>
            <a href="{{ route('menu') }}" class="text-amber-700 font-bold hover:text-amber-900 flex items-center gap-2 transition">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <!-- Product Grid - Rectangle Images -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($featuredProducts as $product)
                <div class="product-preview shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
                    <a href="{{ route('products.show', $product->id) }}" class="block" style="text-decoration: none;">
                        <div style="position: relative; width: 100%; height: 240px; background: linear-gradient(135deg, #fef3e2 0%, #fae9d0 100%); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                            <img src="{{ $product->getImageUrl() }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; object-position: center;" onerror="this.src='https://via.placeholder.com/400x300?text=No+Image'">
                            <div style="position: absolute; top: 12px; right: 12px; background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                <i class="fas fa-star" style="margin-right: 4px;"></i>Featured
                            </div>
                        </div>
                    </a>
                    <div class="p-6">
                        <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none;">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1 hover:text-amber-700 transition">{{ $product->name }}</h3>
                        </a>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 min-h-10">{{ $product->description ?? 'Delicious premium quality item made with the finest ingredients' }}</p>
                        
                        <!-- Rating -->
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 14px; color: #d4a574;">
                            @php
                                $avgRating = $product->reviews->avg('rating') ?? 5;
                                $reviewCount = $product->reviews->count();
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($avgRating) ? '' : '-o' }}"></i>
                            @endfor
                            <span style="color: #999;">({{ $reviewCount }})</span>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="font-size: 20px; font-weight: 900; color: #d4a574;">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                            <a href="{{ route('products.show', $product->id) }}" style="background: linear-gradient(135deg, #d4a574 0%, #8b6f47 100%); color: white; padding: 10px 14px; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s;">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12">
                    <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">No featured products available at the moment</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="hero-gradient text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl md:text-5xl font-black mb-6">Ready for Your Daily Dose?</h2>
        <p class="text-xl text-amber-100 mb-8 max-w-2xl mx-auto">
            Browse our extensive menu and place your order today. We're open 7:00 AM - 9:00 PM, Monday to Sunday.
        </p>
        <a href="{{ route('menu') }}" class="bg-white text-amber-700 px-10 py-4 rounded-lg font-bold text-lg hover:bg-amber-50 inline-flex items-center gap-2 transition">
            <i class="fas fa-shopping-bag"></i>Start Ordering Now
        </a>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 text-center mb-12">What Our Customers Say</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-md">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    "The best coffee in town! Amazing atmosphere and friendly staff. I visit almost every day."
                </p>
                <div class="font-bold text-gray-900">Sarah Johnson</div>
                <div class="text-gray-600 text-sm">Regular Customer</div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-md">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    "Perfect place for meetings. Great pastries and excellent service. Highly recommended!"
                </p>
                <div class="font-bold text-gray-900">Michael Chen</div>
                <div class="text-gray-600 text-sm">Frequent Visitor</div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-md">
                <div class="flex gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-yellow-400"></i>
                    @endfor
                </div>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    "Fantastic reservations experience. All details were handled perfectly. Will definitely come again!"
                </p>
                <div class="font-bold text-gray-900">Emma Williams</div>
                <div class="text-gray-600 text-sm">Special Events</div>
            </div>
        </div>
    </div>
</section>

@endsection

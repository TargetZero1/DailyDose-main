<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - DailyDose</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        .dropdown-menu { display: none; }
        .dropdown-menu.show { display: block; }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>
<body class="bg-gray-50">

@include('partials.header')

<main>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">
            <i class="fas fa-heart mr-3"></i>About DailyDose
        </h1>
        <p class="text-xl text-[#e8d4c0] mb-3">Bringing joy through delicious treats since 2025</p>
        <p class="text-sm text-[#e8d4c0] opacity-90">
            <i class="fas fa-graduation-cap mr-2"></i>Tugas Akhir Mata Kuliah Rekayasa Perangkat Lunak
        </p>
        <p class="text-sm text-[#e8d4c0] opacity-90">
            Pendidikan Teknik Informatika © 2025
        </p>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 py-12 max-w-6xl">
    <!-- Our Story -->
    <section class="mb-16">
        <h2 class="text-4xl font-bold text-gray-800 mb-8 text-center">
            <i class="fas fa-book text-[#8b6f47] mr-3"></i>Our Story
        </h2>
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <p class="text-gray-700 text-lg mb-4">
                    DailyDose is a comprehensive cafe and bakery management system developed in 2025 as a final project for the Software Engineering course (Rekayasa Perangkat Lunak) in the Informatics Engineering Education program (Pendidikan Teknik Informatika).
                </p>
                <p class="text-gray-700 text-lg mb-4">
                    This web-based application demonstrates modern software development practices, featuring a complete ordering system, reservation management, inventory tracking, and administrative dashboard to showcase real-world e-commerce and restaurant management solutions.
                </p>
                <p class="text-gray-700 text-lg">
                    Built with Laravel framework and following best practices in software engineering, DailyDose represents our commitment to creating practical, user-friendly solutions that bridge the gap between technology and daily business operations.
                </p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="mb-16">
        <h2 class="text-4xl font-bold text-gray-800 mb-12 text-center">
            <i class="fas fa-star text-[#8b6f47] mr-3"></i>Why Choose Us
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-leaf text-[#8b6f47] text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Premium Quality</h3>
                <p class="text-gray-600">
                    We use only the finest, freshest ingredients sourced from trusted suppliers.
                </p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-clock text-[#8b6f47] text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Made Fresh Daily</h3>
                <p class="text-gray-600">
                    Everything is baked fresh daily to ensure maximum freshness and taste.
                </p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 text-center hover:shadow-xl transition">
                <i class="fas fa-heart text-[#8b6f47] text-4xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Made with Love</h3>
                <p class="text-gray-600">
                    Every creation is made with passion and attention to detail.
                </p>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="max-w-4xl mx-auto mb-16 bg-white rounded-xl shadow-lg p-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-8 text-center">
            <i class="fas fa-users text-[#8b6f47] mr-3"></i>Meet Our Team
        </h2>
        <p class="text-gray-700 text-lg text-center mb-8">
            Our dedicated team of passionate bakers and café staff work tirelessly to bring you the best experience possible. Each member brings their own unique skills and creativity to make DailyDose special.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#d4a574] to-[#8b6f47] rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-user-chef text-white text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Master Baker</h3>
                <p class="text-gray-600">Expert in traditional baking techniques</p>
            </div>
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#d4a574] to-[#8b6f47] rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-person text-white text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Pastry Chef</h3>
                <p class="text-gray-600">Creative dessert specialist</p>
            </div>
            <div class="text-center">
                <div class="w-32 h-32 bg-gradient-to-br from-[#d4a574] to-[#8b6f47] rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-person-hiking text-white text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Service Team</h3>
                <p class="text-gray-600">Dedicated customer service</p>
            </div>
        </div>
    </section>

    <!-- Our Values -->
    <section class="max-w-4xl mx-auto">
        <h2 class="text-4xl font-bold text-gray-800 mb-12 text-center">
            <i class="fas fa-compass text-[#8b6f47] mr-3"></i>Our Values
        </h2>
        <div class="space-y-6">
            <div class="bg-white rounded-lg p-6 border-l-4 border-[#8b6f47] hover:shadow-lg transition">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Quality</h3>
                <p class="text-gray-700">We never compromise on quality in any aspect of our business.</p>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-[#8b6f47] hover:shadow-lg transition">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Customer Focus</h3>
                <p class="text-gray-700">Your satisfaction is our top priority, and we go the extra mile for you.</p>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-[#8b6f47] hover:shadow-lg transition">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Innovation</h3>
                <p class="text-gray-700">We constantly innovate and introduce new flavors and products.</p>
            </div>
            <div class="bg-white rounded-lg p-6 border-l-4 border-[#8b6f47] hover:shadow-lg transition">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Community</h3>
                <p class="text-gray-700">We believe in giving back to the community that supports us.</p>
            </div>
        </div>
    </section>
</main>

@include('partials.footer')

</body>
</html>

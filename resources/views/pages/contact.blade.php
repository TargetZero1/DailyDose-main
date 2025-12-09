<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - DailyDose</title>
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
            <i class="fas fa-envelope mr-3"></i>Get In Touch
        </h1>
        <p class="text-xl text-[#e8d4c0]">We'd love to hear from you! Contact us anytime.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Contact Info -->
        <div class="space-y-6">
            <!-- Phone -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-center w-12 h-12 bg-[#f5eee6] rounded-full mb-4">
                    <i class="fas fa-phone text-[#8b6f47] text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Phone</h3>
                <p class="text-gray-700 mb-2">0882009759102</p>
                <p class="text-gray-600 text-sm">Mon-Sun: 7:00 AM - 9:00 PM</p>
            </div>

            <!-- Email -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-center w-12 h-12 bg-[#f5eee6] rounded-full mb-4">
                    <i class="fas fa-envelope text-[#8b6f47] text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Email</h3>
                <p class="text-gray-700 mb-2">vijaybrara@gmail.com</p>
                <p class="text-gray-600 text-sm">We respond within 24 hours</p>
            </div>

            <!-- Address -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-center w-12 h-12 bg-[#f5eee6] rounded-full mb-4">
                    <i class="fas fa-map-marker-alt text-[#8b6f47] text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Location</h3>
                <p class="text-gray-700">Universitas Negeri Malang</p>
            </div>

            <!-- Hours -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <div class="flex items-center justify-center w-12 h-12 bg-[#f5eee6] rounded-full mb-4">
                    <i class="fas fa-clock text-[#8b6f47] text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Hours</h3>
                <ul class="text-gray-700 space-y-1">
                    <li><span class="font-bold">Mon-Fri:</span> 7:00 AM - 9:00 PM</li>
                    <li><span class="font-bold">Sat:</span> 8:00 AM - 10:00 PM</li>
                    <li><span class="font-bold">Sun:</span> 8:00 AM - 9:00 PM</li>
                </ul>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-pencil text-[#8b6f47] mr-2"></i>Send us a Message
                </h2>

                <form class="space-y-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Your Name</label>
                        <input
                            type="text"
                            placeholder="Enter your name"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#8b6f47] transition"
                            required
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Your Email</label>
                        <input
                            type="email"
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#8b6f47] transition"
                            required
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Phone Number</label>
                        <input
                            type="tel"
                            placeholder="+62 8xx xxxx xxxx"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#8b6f47] transition"
                            required
                        >
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Subject</label>
                        <input
                            type="text"
                            placeholder="What is this about?"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#8b6f47] transition"
                            required
                        >
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Message</label>
                        <textarea
                            placeholder="Tell us what you think..."
                            rows="6"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#8b6f47] transition resize-none"
                            required
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold py-3 rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition shadow-lg"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>Send Message
                    </button>
                </form>
            </div>

            <!-- Social Media -->
            <div class="bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white rounded-xl shadow-lg p-8 mt-8 text-center">
                <h3 class="text-2xl font-bold mb-4">Follow Us On Social Media</h3>
                <div class="flex justify-center gap-6">
                    <a href="#" class="text-4xl hover:text-[#e8d4c0] transition">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" class="text-4xl hover:text-[#e8d4c0] transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-4xl hover:text-[#e8d4c0] transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-4xl hover:text-[#e8d4c0] transition">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

@include('partials.footer')

</body>
</html>

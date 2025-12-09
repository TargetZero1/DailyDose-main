<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations - DailyDose</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        .dropdown-menu { display: none; }
        .dropdown-menu.show { display: block; }
    </style>
</head>
<body class="bg-gray-50">

@include('partials.header')

<div class="bg-gradient-to-r from-amber-700 to-orange-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">
            <i class="fas fa-calendar-check mr-3"></i>Make a Reservation
        </h1>
        <p class="text-xl text-amber-100">Reserve your table at DailyDose Cafe</p>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Reservation Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-edit text-amber-700 mr-2"></i>Reservation Details
                </h2>

                <form class="space-y-6">
                    <!-- Date -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-calendar text-amber-700 mr-2"></i>Reservation Date
                        </label>
                        <input
                            type="date"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            required
                        >
                    </div>

                    <!-- Time -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-clock text-amber-700 mr-2"></i>Reservation Time
                        </label>
                        <input
                            type="time"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            required
                        >
                    </div>

                    <!-- Number of Guests -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-users text-amber-700 mr-2"></i>Number of Guests
                        </label>
                        <input
                            type="number"
                            min="1"
                            max="20"
                            value="2"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            required
                        >
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-user text-amber-700 mr-2"></i>Your Name
                        </label>
                        <input
                            type="text"
                            placeholder="Enter your full name"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            required
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-phone text-amber-700 mr-2"></i>Phone Number
                        </label>
                        <input
                            type="tel"
                            placeholder="+62 8xx xxxx xxxx"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            required
                        >
                    </div>

                    <!-- Special Requests -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">
                            <i class="fas fa-comment text-amber-700 mr-2"></i>Special Requests
                        </label>
                        <textarea
                            placeholder="Any special requests or dietary restrictions?"
                            rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition resize-none"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold py-3 rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition shadow-lg"
                    >
                        <i class="fas fa-check mr-2"></i>Confirm Reservation
                    </button>
                </form>
            </div>

            <!-- Info Section -->
            <div>
                <div class="bg-white rounded-xl shadow-lg p-8 mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-amber-700 mr-2"></i>Our Hours
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <div class="flex justify-between">
                            <span class="font-bold">Monday - Friday:</span>
                            <span>7:00 AM - 9:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold">Saturday:</span>
                            <span>8:00 AM - 10:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold">Sunday:</span>
                            <span>8:00 AM - 9:00 PM</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt text-amber-700 mr-2"></i>Location
                    </h3>
                    <p class="text-gray-700 mb-4">
                        123 Cafe Street<br>
                        City, State 12345
                    </p>
                    <p class="text-gray-600 mb-4">
                        <i class="fas fa-phone text-amber-700 mr-2"></i>
                        +63 910 123 4567
                    </p>
                    <p class="text-gray-600">
                        <i class="fas fa-envelope text-amber-700 mr-2"></i>
                        reservations@dailydose.com
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

</body>
</html>

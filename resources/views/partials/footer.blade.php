<!-- Footer -->
<footer class="w-full bg-gradient-to-b from-[#2c3e50] to-[#1a252f] text-white py-16 mt-0 mb-0">
    <div class="w-full max-w-7xl mx-auto px-4">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Brand Section -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-coffee text-[#d4a574] text-2xl"></i>
                    <h3 class="text-2xl font-bold text-white" style="color: #d4a574;">DailyDose</h3>
                </div>
                <p class="text-white text-sm leading-relaxed mb-4">Your daily dose of deliciousness. Premium coffee, fresh pastries, and unforgettable moments.</p>
                <p class="text-white text-xs leading-relaxed opacity-90 mb-1">Tugas Akhir Mata Kuliah Rekayasa Perangkat Lunak</p>
                <p class="text-white text-xs leading-relaxed opacity-90">Pendidikan Teknik Informatika © 2025</p>
                <div class="mt-6 flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-[#d4a574] bg-opacity-20 flex items-center justify-center text-white hover:text-[#d4a574] hover:bg-opacity-30 transition relative z-10">
                        <i class="fab fa-facebook-f text-base"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-[#d4a574] bg-opacity-20 flex items-center justify-center text-white hover:text-[#d4a574] hover:bg-opacity-30 transition relative z-10">
                        <i class="fab fa-twitter text-base"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-[#d4a574] bg-opacity-20 flex items-center justify-center text-white hover:text-[#d4a574] hover:bg-opacity-30 transition relative z-10">
                        <i class="fab fa-instagram text-base"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2 text-white">
                    <i class="fas fa-link text-[#d4a574] text-sm"></i>Quick Links
                </h4>
                <ul class="space-y-3 text-white text-sm">
                    <li><a href="/" class="hover:text-[#d4a574] transition duration-300 flex items-center gap-2">
                        <i class="fas fa-chevron-right text-[#d4a574] text-xs"></i>Home
                    </a></li>
                    <li><a href="{{ route('menu') }}" class="hover:text-[#d4a574] transition duration-300 flex items-center gap-2">
                        <i class="fas fa-chevron-right text-[#d4a574] text-xs"></i>Menu
                    </a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-[#d4a574] transition duration-300 flex items-center gap-2">
                        <i class="fas fa-chevron-right text-[#d4a574] text-xs"></i>About Us
                    </a></li>
                    <li><a href="#" class="hover:text-[#d4a574] transition duration-300 flex items-center gap-2">
                        <i class="fas fa-chevron-right text-[#d4a574] text-xs"></i>Reservations
                    </a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2 text-white">
                    <i class="fas fa-phone text-[#d4a574] text-sm"></i>Contact Us
                </h4>
                <div class="space-y-4 text-white text-sm">
                    <div class="flex gap-3">
                        <i class="fas fa-map-marker-alt text-[#d4a574] mt-1 text-xs"></i>
                        <p>Jl. Raden Fatih No.112A<br>Singaraja, Bali 81118</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-phone text-[#d4a574] text-xs"></i>
                        <p>+62 882-0097-59102</p>
                    </div>
                    <div class="flex gap-3">
                        <i class="fas fa-envelope text-[#d4a574] text-xs"></i>
                        <p>info@dailydose.id</p>
                    </div>
                </div>
            </div>

            <!-- Hours Section -->
            <div>
                <h4 class="text-lg font-bold mb-6 flex items-center gap-2 text-white">
                    <i class="fas fa-clock text-[#d4a574] text-sm"></i>Business Hours
                </h4>
                <div class="space-y-3 text-white text-sm">
                    <div class="flex justify-between">
                        <span>Monday - Friday</span>
                        <span class="text-[#d4a574]">7:00 AM - 9:00 PM</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Saturday - Sunday</span>
                        <span class="text-[#d4a574]">8:00 AM - 10:00 PM</span>
                    </div>
                    <div class="mt-4 p-3 bg-[#d4a574] bg-opacity-10 rounded-lg border border-[#d4a574] border-opacity-20">
                        <p class="text-xs text-white">Holidays may have special hours</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 my-8"></div>

        <!-- Bottom Footer -->
        <div class="flex flex-col md:flex-row justify-between items-center text-white text-sm">
            <div class="mb-4 md:mb-0">
                <p class="text-center md:text-left">Made with <i class="fas fa-heart text-[#d4a574]"></i> in Bali</p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-white hover:text-[#d4a574] transition">Privacy Policy</a>
                <a href="#" class="text-white hover:text-[#d4a574] transition">Terms of Service</a>
                <a href="#" class="text-white hover:text-[#d4a574] transition">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DailyDose</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen flex flex-col">
    <div class="flex-1 flex items-center justify-center">
        <div class="w-full max-w-md px-4">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-block mb-4">
                <i class="fas fa-coffee text-amber-700 text-5xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">DailyDose</h1>
            <p class="text-gray-600">Your Daily Treat</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-xl shadow-2xl p-8 border-t-4 border-amber-700">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Login</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" id="login-form" onsubmit="handleSubmit(event)">
                @csrf

                <!-- Username -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="username">
                        <i class="fas fa-user mr-2 text-amber-700"></i>Username
                    </label>
                    <input
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                        id="username"
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="password">
                        <i class="fas fa-lock mr-2 text-amber-700"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-4 top-3 text-gray-600 hover:text-amber-700 transition"
                        >
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-6 flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 accent-amber-700 rounded"
                    >
                    <label for="remember" class="ml-2 text-gray-700 font-medium">
                        Remember me
                    </label>
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    id="login-btn"
                    class="w-full bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold py-3 rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition shadow-lg mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="btn-text">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </span>
                    <span id="btn-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Logging in...
                    </span>
                </button>
            </form>

            <!-- Register Link -->
            <div class="text-center mt-6 pt-6 border-t border-gray-200">
                <p class="text-gray-700 mb-2">
                    Don't have an account?
                </p>
                <a
                    href="{{ route('register') }}"
                    class="text-amber-700 font-bold hover:text-amber-800 transition"
                >
                    <i class="fas fa-user-plus mr-2"></i>Register here
                </a>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>
                <i class="fas fa-shield-alt mr-2 text-amber-700"></i>
                Your data is secure with us
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
        
        function handleSubmit(event) {
            const btn = document.getElementById('login-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            // Form will submit naturally, no need to prevent default
        }
    </script>

</body>
</html>

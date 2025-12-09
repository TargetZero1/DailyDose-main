<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DailyDose</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen flex items-center justify-center py-12">
    <div class="w-full max-w-2xl px-4">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="inline-block mb-4">
                <i class="fas fa-coffee text-amber-700 text-5xl"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 mb-2">DailyDose</h1>
            <p class="text-gray-600">Join Our Community</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-xl shadow-2xl p-8 border-t-4 border-amber-700">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Create Your Account</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" id="register-form" onsubmit="handleSubmit(event)">
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
                        placeholder="Choose a username"
                        required
                    >
                </div>

                <!-- Two-column layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2" for="password">
                            <i class="fas fa-lock mr-2 text-amber-700"></i>Password
                        </label>
                        <div class="relative">
                            <input
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Create a password"
                                required
                                minlength="8"
                                oninput="checkPasswordStrength()"
                            >
                            <button
                                type="button"
                                onclick="togglePassword('password', 'eye-icon-1')"
                                class="absolute right-4 top-3 text-gray-600 hover:text-amber-700 transition"
                            >
                                <i class="fas fa-eye" id="eye-icon-1"></i>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div id="strength-bar-1" class="h-1 w-1/4 bg-gray-200 rounded transition-all"></div>
                                <div id="strength-bar-2" class="h-1 w-1/4 bg-gray-200 rounded transition-all"></div>
                                <div id="strength-bar-3" class="h-1 w-1/4 bg-gray-200 rounded transition-all"></div>
                                <div id="strength-bar-4" class="h-1 w-1/4 bg-gray-200 rounded transition-all"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-gray-500">Password strength: None</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>At least 8 characters, including uppercase, lowercase, and number
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-gray-700 font-bold mb-2" for="password_confirmation">
                            <i class="fas fa-lock mr-2 text-amber-700"></i>Confirm Password
                        </label>
                        <div class="relative">
                            <input
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm password"
                                required
                                oninput="checkPasswordMatch()"
                            >
                            <button
                                type="button"
                                onclick="togglePassword('password_confirmation', 'eye-icon-2')"
                                class="absolute right-4 top-3 text-gray-600 hover:text-amber-700 transition"
                            >
                                <i class="fas fa-eye" id="eye-icon-2"></i>
                            </button>
                        </div>
                        <p id="match-text" class="text-xs mt-1">&nbsp;</p>
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="no_hp">
                        <i class="fas fa-phone mr-2 text-amber-700"></i>Phone Number
                    </label>
                    <input
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-amber-700 transition"
                        id="no_hp"
                        type="text"
                        name="no_hp"
                        placeholder="+62 8xx xxxx xxxx"
                        required
                    >
                </div>

                <!-- Register Button -->
                <button
                    type="submit"
                    id="submit-btn"
                    class="w-full bg-gradient-to-r from-[#d4a574] to-[#8b6f47] text-white font-bold py-3 rounded-lg hover:from-[#c49566] hover:to-[#7a5f3f] transition shadow-lg mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span id="btn-text">
                        <i class="fas fa-user-plus mr-2"></i>Create Account
                    </span>
                    <span id="btn-loading" class="hidden">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Creating account...
                    </span>
                </button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6 pt-6 border-t border-gray-200">
                <p class="text-gray-700 mb-2">
                    Already have an account?
                </p>
                <a
                    href="{{ route('login') }}"
                    class="text-amber-700 font-bold hover:text-amber-800 transition"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Login here
                </a>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>
                <i class="fas fa-shield-alt mr-2 text-amber-700"></i>
                Your information is safe and secure
            </p>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(iconId);
            
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

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const bars = [
                document.getElementById('strength-bar-1'),
                document.getElementById('strength-bar-2'),
                document.getElementById('strength-bar-3'),
                document.getElementById('strength-bar-4')
            ];
            const strengthText = document.getElementById('strength-text');
            
            // Reset bars
            bars.forEach(bar => {
                bar.className = 'h-1 w-1/4 bg-gray-200 rounded transition-all';
            });
            
            let strength = 0;
            let strengthLabel = 'None';
            let color = 'gray';
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            // Cap strength at 4 for UI purposes
            strength = Math.min(strength, 4);
            
            switch(strength) {
                case 0:
                    strengthLabel = 'None';
                    color = 'gray';
                    break;
                case 1:
                    strengthLabel = 'Weak';
                    color = 'red';
                    break;
                case 2:
                    strengthLabel = 'Fair';
                    color = 'orange';
                    break;
                case 3:
                    strengthLabel = 'Good';
                    color = 'yellow';
                    break;
                case 4:
                    strengthLabel = 'Strong';
                    color = 'green';
                    break;
            }
            
            // Update bars
            for (let i = 0; i < strength; i++) {
                bars[i].className = `h-1 w-1/4 bg-${color}-500 rounded transition-all`;
            }
            
            // Update text
            strengthText.textContent = `Password strength: ${strengthLabel}`;
            strengthText.className = `text-xs text-${color}-600 font-medium`;
            
            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchText = document.getElementById('match-text');
            
            if (confirmation === '') {
                matchText.innerHTML = '&nbsp;';
                return;
            }
            
            if (password === confirmation) {
                matchText.innerHTML = '<i class="fas fa-check-circle text-green-600 mr-1"></i><span class="text-green-600">Passwords match</span>';
            } else {
                matchText.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-1"></i><span class="text-red-600">Passwords do not match</span>';
            }
        }
        
        function handleSubmit(event) {
            const btn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoading = document.getElementById('btn-loading');
            
            btn.disabled = true;
            btnText.classList.add('hidden');
            btnLoading.classList.remove('hidden');
            
            // Form will submit naturally
        }
    </script>

</body>
</html>

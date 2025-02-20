<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Password')" />

            <!-- Password Input -->
            <x-text-input id="password" class="block mt-1 w-full pr-10"
                        type="password"
                        name="password"
                        required autocomplete="current-password" />

            <!-- Show/Hide Password Icon -->
            <button type="button" id="togglePassword" 
                    class="absolute inset-y-0 right-3 flex items-center justify-center w-8">
                <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825C12.685 19.295 11.375 19.5 10 19.5c-4.97 0-9-3.76-9-7.5S5.03 4.5 10 4.5c1.375 0 2.685.205 3.875.675m4.3 1.6C21.36 7.74 23 9.5 23 12s-1.64 4.26-4.825 5.225M15 12a3 3 0 11-6 0 3 3 0 016 0zM6 18L18 6" />
                </svg>
                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="hidden h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0c0 2.76 4.03 5 9 5s9-2.24 9-5-4.03-5-9-5-9 2.24-9 5z" />
                </svg>
            </button>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('register') }}">
               {{ __('Haven\'t registered? Click Here!') }}
            </a>
        </div>
    </form>
 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            //Show/Hide Password
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', () => {
                // Toggle input type
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';

                // Toggle icons
                eyeOffIcon.classList.toggle('hidden', isPassword);
                eyeIcon.classList.toggle('hidden', !isPassword);
            });
        });
    </script>
</x-guest-layout>

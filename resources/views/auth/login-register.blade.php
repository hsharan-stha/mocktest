<x-guest2-layout>
    <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <!-- Title -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">{{ __('auth.welcomeLogin') }}</h1>
            <p class="mt-2 text-sm text-gray-600">{{ __('auth.loginInfo') }}</p>
        </div>

        <!-- Grid: Login | Register -->
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Login Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 sm:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">{{ __('auth.welcomeLogin') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ __('auth.loginInfo') }}</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <input class="cart_items" type="hidden" value="" name="cart_items" />

                        <!-- Email -->
                        <div class="mt-2">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input
                                id="email"
                                class="block mt-1 w-full"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div  class="mt-2">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input
                                id="password"
                                class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                    name="remember"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    href="{{ route('password.request') }}"
                                >
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <div class="pt-2">
                            <x-primary-button class=" justify-center">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Register Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 sm:p-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">{{ __('auth.createAccount') }}</h2>
                        <p class="mt-1 text-sm text-gray-600">{{ __('auth.registerInfo') }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf
                        <input class="cart_items" type="hidden" value="" name="cart_items" />

                        <!-- Name -->
                        <div  class="mt-2">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input
                                id="name"
                                class="block mt-1 w-full"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div  class="mt-2">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input
                                id="email"
                                class="block mt-1 w-full"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autocomplete="username"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div  class="mt-2">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input
                                id="password"
                                class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div  class="mt-2">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input
                                id="password_confirmation"
                                class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Already Registered + Submit -->
                        <div class="flex items-center justify-between pt-2">
                            <a
                                class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                href="{{ route('login') }}"
                            >
                                {{ __('Already registered?') }}
                            </a>

                            <x-primary-button>
                                {{ __('Register') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Optional: bottom note -->
        <p class="mt-10 text-center text-xs text-gray-500">
            {{ config('app.name') }} &middot; © {{ now()->year }}
        </p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cart_items = localStorage.getItem("cart_items");
            document.querySelectorAll(".cart_items").forEach(el => {
                el.value = cart_items;
            });

            // Disable submit buttons on submit for both forms
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", () => {
                    const btn = form.querySelector("button[type='submit'], [type='submit']");
                    if (btn) {
                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        // Keep button height stable
                        const current = btn.innerHTML;
                        btn.innerHTML = `<span>${current}</span>`;
                    }
                });
            });
        });
    </script>
</x-guest2-layout>

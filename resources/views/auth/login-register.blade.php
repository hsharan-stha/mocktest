<x-guest2-layout>
    <div class="mx-auto flex min-h-[calc(100vh-5rem)] max-w-7xl items-center">
        <div class="grid w-full gap-8 xl:grid-cols-[0.95fr_1.05fr] xl:items-center">
            <section class="hidden xl:block">
                <span class="eyebrow">{{ app()->getLocale() === 'jp' ? '学習スタート' : 'Start Learning' }}</span>
                <h1 class="mt-6 text-5xl font-semibold tracking-tight text-white">{{ __('auth.welcomeLogin') }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">{{ __('auth.loginInfo') }}</p>
                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-sm text-slate-300">{{ app()->getLocale() === 'jp' ? 'マイライブラリ' : 'Your Library' }}</p>
                        <p class="mt-2 text-sm font-medium text-white">{{ app()->getLocale() === 'jp' ? '購入した本を整理し、すぐ読める状態に保ちます。' : 'Keep purchased books organized and reading-ready.' }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-sm text-slate-300">{{ app()->getLocale() === 'jp' ? '模擬試験' : 'Mock Tests' }}</p>
                        <p class="mt-2 text-sm font-medium text-white">{{ app()->getLocale() === 'jp' ? '集中できる時間制の試験画面で練習できます。' : 'Practice in a focused timed exam experience.' }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-sm text-slate-300">{{ app()->getLocale() === 'jp' ? '進捗' : 'Progress' }}</p>
                        <p class="mt-2 text-sm font-medium text-white">{{ app()->getLocale() === 'jp' ? '読書、購入、受験履歴を1か所で確認できます。' : 'Track reading, purchases, and attempts in one place.' }}</p>
                    </div>
                </div>
            </section>

            <section class="surface overflow-hidden">
                <div class="grid lg:grid-cols-2">
                    <div class="border-b border-slate-200 p-6 sm:p-8 lg:border-b-0 lg:border-r">
                        <div class="mb-6">
                            <span class="ui-badge ui-badge-brand">{{ __('auth.welcomeLogin') }}</span>
                            <h2 class="mt-4 text-2xl font-semibold text-slate-900">{{ __('auth.welcomeLogin') }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('auth.loginInfo') }}</p>
                        </div>

                        <x-auth-session-status class="mb-4 ui-alert-success" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <input class="cart_items" type="hidden" value="" name="cart_items" />

                            <div>
                                <x-input-label for="login_email" :value="__('Email')" class="ui-label" />
                                <x-text-input id="login_email" class="ui-input mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="ui-error" />
                            </div>

                            <div>
                                <x-input-label for="login_password" :value="__('Password')" class="ui-label" />
                                <x-text-input id="login_password" class="ui-input mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="ui-error" />
                            </div>

                            <div class="flex flex-col gap-3">
                                <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                                    <span class="ml-2">{{ __('Remember me') }}</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-medium text-blue-700 underline-offset-4 hover:text-blue-800 hover:underline" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="ui-button-primary w-full justify-center">
                                    {{ __('Log in') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-slate-50/80 p-6 sm:p-8">
                        <div class="mb-6">
                            <span class="ui-badge ui-badge-accent">{{ __('auth.createAccount') }}</span>
                            <h2 class="mt-4 text-2xl font-semibold text-slate-900">{{ __('auth.createAccount') }}</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ __('auth.registerInfo') }}</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf
                            <input class="cart_items" type="hidden" value="" name="cart_items" />

                            <div>
                                <x-input-label for="register_name" :value="__('Name')" class="ui-label" />
                                <x-text-input id="register_name" class="ui-input mt-1 block w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="ui-error" />
                            </div>

                            <div>
                                <x-input-label for="register_email" :value="__('Email')" class="ui-label" />
                                <x-text-input id="register_email" class="ui-input mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="ui-error" />
                            </div>

                            <div>
                                <x-input-label for="register_password" :value="__('Password')" class="ui-label" />
                                <x-text-input id="register_password" class="ui-input mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="ui-error" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="ui-label" />
                                <x-text-input id="password_confirmation" class="ui-input mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="ui-error" />
                            </div>

                            <div class="pt-2">
                                <x-primary-button class="ui-button-primary w-full justify-center">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartItems = localStorage.getItem("cart_items");
            document.querySelectorAll(".cart_items").forEach(el => {
                el.value = cartItems;
            });
        });
    </script>
</x-guest2-layout>

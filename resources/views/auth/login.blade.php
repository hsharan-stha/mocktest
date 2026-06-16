<x-guest-layout>
    <div class="mb-8">
        <span class="ui-badge ui-badge-brand">Welcome Back</span>
        <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ __('Log in') }}</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            {{ app()->getLocale() === 'jp'
                ? 'サインインして、ライブラリ、購入履歴、模擬試験の進捗にアクセスしてください。'
                : 'Sign in to access your library, purchases, and mock exam progress.' }}
        </p>
    </div>

    <x-auth-session-status class="mb-4 ui-alert-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="ui-label" />
            <x-text-input id="email" class="ui-input mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="ui-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="ui-label" />
            <x-text-input id="password" class="ui-input mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="ui-error" />
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
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

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a href="{{ route('register') }}" class="text-sm font-medium text-slate-600 underline-offset-4 hover:text-slate-900 hover:underline">
                {{ app()->getLocale() === 'jp' ? 'アカウントをお持ちでないですか？' : 'Need an account?' }}
            </a>
            <x-primary-button class="ui-button-primary justify-center sm:min-w-[140px]">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

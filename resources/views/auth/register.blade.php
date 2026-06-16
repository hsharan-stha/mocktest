<x-guest-layout>
    <div class="mb-8">
        <span class="ui-badge ui-badge-accent">Create Account</span>
        <h2 class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ __('Register') }}</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            {{ app()->getLocale() === 'jp'
                ? '学習用アカウントを作成して、教材の購入、ライブラリの保存、模擬試験の開始を行いましょう。'
                : 'Create your learner account to purchase materials, save your library, and start mock exams.' }}
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="ui-label" />
            <x-text-input id="name" class="ui-input mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="ui-error" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="ui-label" />
            <x-text-input id="email" class="ui-input mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="ui-error" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="ui-label" />
            <x-text-input id="password" class="ui-input mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="ui-error" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="ui-label" />
            <x-text-input id="password_confirmation" class="ui-input mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="ui-error" />
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a class="text-sm font-medium text-slate-600 underline-offset-4 hover:text-slate-900 hover:underline" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ui-button-primary justify-center sm:min-w-[140px]">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell overflow-hidden">
    <div class="admin-shell h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        @include('layouts.navigation')

        <div class="admin-main flex h-screen flex-1 flex-col overflow-hidden">
            <header class="admin-topbar">
                <div class="app-container flex items-center justify-between gap-4 py-4">
                    <div class="flex items-center gap-3">
                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm lg:hidden"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ app()->getLocale() === 'jp' ? '管理画面' : 'Admin workspace' }}</p>
                            <h1 class="text-lg font-semibold text-slate-900">{{ isset($header) ? trim(strip_tags($header)) : config('app.name', 'Laravel') }}</h1>
                        </div>
                    </div>
                    <div class="hidden items-center gap-3 md:flex">
                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-right shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Signed in</p>
                            <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name ?? 'User' }}</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="app-container flex-1 overflow-y-auto py-6 lg:py-8">
                <div class="page-stack">
                    @if (isset($header))
                        <section class="page-hero">
                            <div class="relative z-10">
                                <span class="eyebrow">Operations</span>
                                <div class="page-title">{!! $header !!}</div>
                                <p class="page-copy">Manage books, exams, purchases, and master data with a cleaner workflow built for daily operations.</p>
                            </div>
                        </section>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</body>
</html>

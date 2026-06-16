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
<body class="app-shell">
    <div class="relative min-h-screen overflow-hidden bg-slate-950 text-slate-100">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.28),_transparent_28%),radial-gradient(circle_at_bottom_right,_rgba(245,158,11,0.22),_transparent_24%),linear-gradient(135deg,#020617,#0f172a_45%,#172554)]"></div>
        <div class="relative z-10 mx-auto flex min-h-screen max-w-7xl items-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="grid w-full gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                <div class="hidden lg:block">
                    <span class="eyebrow">Account Access</span>
                    <h1 class="mt-6 text-5xl font-semibold tracking-tight text-white">Study, purchase, and take mock exams from one calm workspace.</h1>
                    <p class="mt-5 max-w-2xl text-base leading-8 text-slate-300">Sign in to continue reading, manage your cart, and jump back into your learning flow with the redesigned platform experience.</p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Reader Mode</p>
                            <p class="mt-2 text-sm font-medium text-white">Focused reading with direct exam access.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Mock Exams</p>
                            <p class="mt-2 text-sm font-medium text-white">Launch timed practice from your library.</p>
                        </div>
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                            <p class="text-sm text-slate-300">Purchase Flow</p>
                            <p class="mt-2 text-sm font-medium text-white">Clean checkout and purchase tracking.</p>
                        </div>
                    </div>
                </div>

                <div class="w-full">
                    <div class="surface mx-auto w-full max-w-xl p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll("form").forEach(function(form) {
            form.addEventListener("submit", function() {
                const submitBtn = form.querySelector("button[type='submit']");
                if (submitBtn) {
                    submitBtn.disabled = true;
                }
            });
        });
    });
</script>
</html>

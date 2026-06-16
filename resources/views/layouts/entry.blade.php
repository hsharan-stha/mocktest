<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/swiper/swiper.bundle.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('js/swiper/swiper.bundle.min.js') }}"></script>
    <script>
        function cartCountdisplay(cartCount) {
            cartCount = parseInt(cartCount) || 0;
            ['cart-count', 'cart-count-sidebar', 'guest-cart-count', 'guest-cart-count-sidebar'].forEach((id) => {
                const node = document.getElementById(id);
                if (!node) return;
                if (cartCount > 0) {
                    node.innerText = cartCount;
                    node.classList.remove('hidden');
                } else {
                    node.classList.add('hidden');
                }
            });
        }

        function loggedInDevicesCount(count) {
            const loggedInDevices = document.getElementById('loggedInDevices');
            if (loggedInDevices) {
                loggedInDevices.innerText = ``;
            }
        }
    </script>
</head>
<body class="app-shell overflow-hidden">
    <div class="flex h-screen overflow-hidden">
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 flex h-screen w-72 -translate-x-full flex-col overflow-hidden border-r border-slate-200 bg-white/95 shadow-2xl backdrop-blur transition-transform duration-300 lg:static lg:translate-x-0">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-6">
                <a href="/" class="flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">{{ app()->getLocale() === 'jp' ? '模擬試験プラットフォーム' : 'Mock Test Platform' }}</p>
                        <p class="text-lg font-semibold text-slate-900">{{ __('home.online_exam') }}</p>
                    </div>
                </a>
                <button onclick="toggleSidebar()" class="rounded-xl p-2 text-slate-500 hover:bg-slate-100 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-2 overflow-y-auto px-4 py-6">
                <a href="/" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    <span>{{ app()->getLocale() === 'jp' ? 'ホーム' : 'Home' }}</span>
                </a>
                @if (Auth::check())
                    <a href="/library" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        <span>{{ app()->getLocale() === 'jp' ? 'ライブラリ' : 'Library' }}</span>
                    </a>
                    <a href="/cart-web" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        <span>{{ __('home.cart') }}</span>
                        <span class="hidden rounded-full bg-red-500 px-2 py-0.5 text-xs text-white" id="cart-count-sidebar"></span>
                    </a>
                @else
                    <a href="/cart-web" class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        <span>{{ __('home.cart') }}</span>
                        <span class="hidden rounded-full bg-red-500 px-2 py-0.5 text-xs text-white" id="guest-cart-count-sidebar"></span>
                    </a>
                @endif

                <div class="surface-muted mt-4 p-3">
                    <button id="langToggleBtn" class="flex w-full items-center justify-between rounded-2xl px-3 py-2 text-left text-sm font-semibold text-slate-700 hover:bg-white">
                        <span>{{ strtoupper(app()->getLocale()) }}</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="langDropdown" class="mt-2 hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-lg">
                        <a href="{{ route('lang.switch', 'en') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">English</a>
                        <a href="{{ route('lang.switch', 'jp') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">Japanese</a>
                    </div>
                </div>
            </nav>

            <div class="border-t border-slate-200 p-4">
                @if (Auth::check())
                    <div class="surface-muted p-4">
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ Str::limit(auth()->user()->email, 24) }}</p>
                        <p id="loggedInDevices" class="mt-3 text-xs text-slate-500"></p>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <button type="submit" class="ui-button-secondary w-full">{{ __('home.logout') }}</button>
                        </form>
                    </div>
                @else
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="ui-button-primary w-full">{{ __('home.signin') }}</a>
                        <a href="{{ route('register') }}" class="ui-button-secondary w-full">{{ __('home.register') }}</a>
                    </div>
                @endif
            </div>
        </aside>

        <div id="mobileMenuOverlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 backdrop-blur-sm lg:hidden" onclick="toggleSidebar()"></div>

        <div class="flex h-screen flex-1 flex-col overflow-hidden">
            <div class="border-b border-slate-200 bg-white/90 backdrop-blur lg:hidden">
                <div class="app-container flex items-center justify-between py-4">
                    <button onclick="toggleSidebar()" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="/" class="text-base font-semibold text-slate-900">{{ __('home.online_exam') }}</a>
                    <a href="/cart-web" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272" />
                        </svg>
                        <span class="hidden absolute right-1 top-1 rounded-full bg-red-500 px-1.5 text-[10px] font-semibold text-white" id="{{ Auth::check() ? 'cart-count' : 'guest-cart-count' }}"></span>
                    </a>
                </div>
            </div>

            <main class="app-container flex-1 overflow-y-auto py-6 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div id="infoToast" class="fixed bottom-5 right-5 z-[111111] hidden">
        <div class="surface flex items-start gap-3 px-4 py-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a7 7 0 107 7H9V2z" />
                    <path d="M13 13H7v2h6v-2z" />
                </svg>
            </div>
            <p id="toastMessage" class="text-sm font-medium text-slate-700">This is your message</p>
        </div>
    </div>

    <div id="authModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 backdrop-blur-sm">
        <div class="surface max-w-md p-6">
            <h3 class="text-xl font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? 'サインインが必要です' : 'Sign in required' }}</h3>
            <p class="mt-2 text-sm text-slate-600">{{ app()->getLocale() === 'jp' ? '購入やライブラリ利用にはアカウントが必要です。' : 'You need an account to purchase items and access your library.' }}</p>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('register') }}" class="ui-button-secondary flex-1">{{ __('home.register') }}</a>
                <a href="{{ route('login') }}" class="ui-button-primary flex-1">{{ __('home.signin') }}</a>
            </div>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4 backdrop-blur-sm">
        <div class="surface max-w-sm p-6 text-center">
            <h2 class="text-lg font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? '購入確認' : 'Confirm Purchase' }}</h2>
            <p class="mt-2 text-sm text-slate-600">{{ app()->getLocale() === 'jp' ? '購入手続きに進みますか？' : 'Are you sure you want to proceed to buy?' }}</p>
            <div class="mt-6 flex justify-center gap-3">
                <button onclick="confirmProceed()" class="ui-button-primary">{{ app()->getLocale() === 'jp' ? 'はい' : 'Yes' }}</button>
                <button onclick="closeBuyModal()" class="ui-button-secondary">{{ __('cart.cancel') }}</button>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileMenuOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }

        const langBtn = document.getElementById('langToggleBtn');
        const langDropdown = document.getElementById('langDropdown');
        if (langBtn && langDropdown) {
            langBtn.addEventListener('click', () => langDropdown.classList.toggle('hidden'));
            document.addEventListener('click', function(e) {
                if (!langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
                    langDropdown.classList.add('hidden');
                }
            });
        }

        function showToast(message, duration = 3000) {
            const toast = document.getElementById('infoToast');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), duration);
        }

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

        function addToCart(button, book, quantity = 1) {
            const isLoggedIn = @json(Auth::check());
            const isEmailVerified = isLoggedIn ? @json(Auth::check() && Auth::user()->hasVerifiedEmail()) : false;
            if (isLoggedIn) {
                if (isEmailVerified) {
                    const textSpan = button.querySelector('.button-text');
                    const loadingSpan = button.querySelector('.loading');
                    button.setAttribute("disabled", true);
                    if (textSpan) textSpan.classList.add('hidden');
                    if (loadingSpan) loadingSpan.classList.remove('hidden');

                    fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ book_id: book?.id, quantity: quantity })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && typeof cartCountdisplay === 'function' && data.cartCount !== undefined) {
                            cartCountdisplay(data.cartCount);
                        }
                        showToast(data.message || (data.success ? 'Item added to cart successfully' : 'Failed to add item to cart'));
                        if (textSpan) textSpan.classList.remove('hidden');
                        if (loadingSpan) loadingSpan.classList.add('hidden');
                        button.removeAttribute("disabled");
                    })
                    .catch(() => {
                        if (textSpan) textSpan.classList.remove('hidden');
                        if (loadingSpan) loadingSpan.classList.add('hidden');
                        button.removeAttribute("disabled");
                    });
                } else {
                    showToast("Please verify your email before adding items to the cart.");
                }
            } else {
                showToast('Please login to add to cart', 4000);
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            }
        }

        function updateQuantity(bookId, quantity = 0) {
            if (quantity == 0) return;
            fetch('/cart/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ book_id: bookId, quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false && data.cartCount !== undefined && typeof cartCountdisplay === 'function') {
                    cartCountdisplay(data.cartCount);
                }
            });
        }

        function deleteCart(bookId) {
            fetch('/cart/delete-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ book_id: bookId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false && data.cartCount !== undefined && typeof cartCountdisplay === 'function') {
                    cartCountdisplay(data.cartCount);
                }
            });
        }

        function openAuthModal() {
            document.getElementById('authModal').classList.remove('hidden');
            document.getElementById('authModal').classList.add('flex');
        }

        function closeAuthModal() {
            document.getElementById('authModal').classList.add('hidden');
            document.getElementById('authModal').classList.remove('flex');
        }

        function proceedToBuy() {
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                window.location.href = "/cart-web";
            } else {
                window.location.href = "/login";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                fetch('/cart')
                    .then(response => response.json())
                    .then(data => {
                        const cartCount = data.cartList?.length || 0;
                        if (typeof cartCountdisplay === 'function') {
                            cartCountdisplay(cartCount);
                        }
                    });
            } else {
                const cart = JSON.parse(localStorage.getItem('cart_items')) || [];
                if (typeof cartCountdisplay === 'function') {
                    cartCountdisplay(cart.length);
                }
            }
        });

        function confirmToBuy() {
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeBuyModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
        }

        function confirmProceed() {
            closeBuyModal();
            document.getElementById("proceedToBuy").setAttribute("disabled", true);
            document.getElementById("proceedToBuy").innerText = "{{ __('home.loading') }}";
            fetch('/purchases', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ books: payloadOfCart })
            })
            .then(response => response.json())
            .then(data => {
                window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
            })
            .catch(() => {
                document.getElementById("proceedToBuy").removeAttribute("disabled");
                document.getElementById("proceedToBuy").innerText = "Proceed to Buy";
            });
        }
    </script>
</body>
</html>

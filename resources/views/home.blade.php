<x-entry-layout>
    <div class="page-stack">
        <section class="page-hero">
            <div class="relative z-10 grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
                <div>
                    <span class="eyebrow">{{ app()->getLocale() === 'jp' ? '学習・ライブラリ・模擬試験' : 'Learning • Library • Mock Exam' }}</span>
                    <h1 class="page-title">{{ app()->getLocale() === 'jp' ? '洗練された学習と模擬試験の画面で、より効率よく準備しましょう。' : 'Prepare smarter with a polished learning and mock test workspace.' }}</h1>
                    <p class="page-copy">{{ app()->getLocale() === 'jp' ? '厳選された試験教材を探し、カートに追加し、ライブラリで学習し、そのまま集中できる模擬試験へ進めます。' : 'Browse curated exam books, build your cart, study from your library, and move into a focused mock-exam flow without losing momentum.' }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#catalog" class="ui-button-primary">{{ __('cart.browseBooks') }}</a>
                        @auth
                            <a href="/library" class="ui-button-secondary border-white/20 bg-white/10 text-white hover:bg-white/20">{{ app()->getLocale() === 'jp' ? 'ライブラリを開く' : 'Open My Library' }}</a>
                        @else
                            <a href="{{ route('register') }}" class="ui-button-secondary border-white/20 bg-white/10 text-white hover:bg-white/20">{{ app()->getLocale() === 'jp' ? 'アカウント作成' : 'Create Account' }}</a>
                        @endauth
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-sm text-blue-100">{{ app()->getLocale() === 'jp' ? 'カテゴリー' : 'Categories' }}</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $categoryList->count() }}</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-sm text-blue-100">{{ app()->getLocale() === 'jp' ? '利用可能な書籍' : 'Books Ready' }}</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $categories->sum(fn($category) => $category->books->count()) }}</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-sm text-blue-100">{{ app()->getLocale() === 'jp' ? '体験' : 'Experience' }}</p>
                        <p class="mt-2 text-base font-medium">{{ app()->getLocale() === 'jp' ? '学習、購入、復習を1つの場所で快適に行えます。' : 'Responsive learning, secure checkout, and exam review in one place.' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface p-5 sm:p-6" id="catalog">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '最適な学習コースを見つける' : 'Find the right learning path' }}</h2>
                    <p class="section-copy mt-2">{{ app()->getLocale() === 'jp' ? 'カテゴリーやタイトルで絞り込み、試験計画に合う教材をすぐに見つけられます。' : 'Filter by category, search by title, and jump directly into the material that fits your exam plan.' }}</p>
                </div>
                <form class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4" method="GET" action="{{ route('store') }}">
                    <select id="category" name="category_id" class="ui-select">
                        <option value="">{{ __('home.category') }}</option>
                        @forelse ($categoryList as $category)
                            <option {{ isset($filteredData['category_id']) && $category->id == $filteredData['category_id'] ? 'selected' : '' }} value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @empty
                            <option disabled>{{ __('home.noCategoriesAvailable') }}</option>
                        @endforelse
                    </select>
                    <input type="text" placeholder="{{ __('home.book_name') }}" name="book_name" value="{{ $filteredData['book_name'] ?? '' }}" class="ui-input" />
                    <button type="submit" class="ui-button-primary">{{ __('home.search') }}</button>
                    <a href="{{ route('index') }}" class="ui-button-secondary">{{ __('home.clear') }}</a>
                </form>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-3">
            <div class="surface p-6">
                <span class="ui-badge ui-badge-brand">{{ app()->getLocale() === 'jp' ? '注目の書籍' : 'Featured Books' }}</span>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? '見つけやすい書籍表示' : 'Visual book discovery' }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ app()->getLocale() === 'jp' ? '表紙を大きく見せ、情報の優先順位を整理することで、次に学ぶ本を選びやすくします。' : 'Larger covers, stronger hierarchy, and quicker calls to action make it easier to decide what to study next.' }}</p>
            </div>
            <div class="surface p-6">
                <span class="ui-badge ui-badge-success">{{ app()->getLocale() === 'jp' ? '模擬試験フロー' : 'Mock Test Flow' }}</span>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? '試験対策に最適' : 'Exam-ready preparation' }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ app()->getLocale() === 'jp' ? '購入から読書、時間制限のある問題演習まで、集中しやすい画面でスムーズに進めます。' : 'Move from purchase to reader to timed questions with a clearer interface designed for sustained focus.' }}</p>
            </div>
            <div class="surface p-6">
                <span class="ui-badge ui-badge-accent">{{ app()->getLocale() === 'jp' ? '使い方' : 'How It Works' }}</span>
                <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? '進歩へのシンプルな流れ' : 'Simple path to progress' }}</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ app()->getLocale() === 'jp' ? '書籍を選び、カートに追加し、ライブラリで利用可能にした後、読書と演習を始められます。' : 'Choose a title, add it to your cart, unlock it in your library, then read and practice when you are ready.' }}</p>
            </div>
        </section>

        @php
            $hasAnyBooks = $categories->contains(fn ($category) => $category->books->count() > 0);
        @endphp

        @if (! $hasAnyBooks)
            <section class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13" />
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">No books available yet</h3>
                <p class="mt-2 max-w-xl text-sm text-slate-600">Once books are added to the catalog, they will appear here in organized sections with search and purchase actions.</p>
            </section>
        @endif

        @forelse ($categories as $category)
            @if (count($category->books) > 0)
                <section class="space-y-4">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <span class="ui-badge ui-badge-brand">{{ $category->books->count() }} books</span>
                            <h2 class="section-title mt-3 capitalize">{{ $category->name }}</h2>
                        </div>
                    </div>

                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @forelse ($category->books as $book)
                                <div class="swiper-slide py-2">
                                    <article class="book-card h-full">
                                        <div class="skeleton-loader absolute inset-0 z-10 animate-pulse rounded-[1.5rem] bg-blue-50"></div>
                                        <div class="book-cover">
                                            <a href="{{ route('detail.view', $book->id) }}">
                                                <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover" class="book-image opacity-0 transition-opacity duration-500">
                                            </a>
                                        </div>
                                        <div class="book-body">
                                            <div>
                                                <h3 class="line-clamp-2 text-lg font-semibold text-slate-900">{{ $book->name }}</h3>
                                                <p class="mt-2 line-clamp-2 text-sm text-slate-600">{{ $book->description }}</p>
                                            </div>
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="price-chip">Rs.{{ number_format($book->price) }}</span>
                                                <button type="button" onclick="addToCart(this, {{ $book }}, 1)" class="ui-button-primary px-4 py-2 text-xs">
                                                    <span class="button-text">{{ __('home.addToCart') }}</span>
                                                    <span class="loading hidden">{{ __('home.loading') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @empty
                                <p class="text-slate-500">No books in this category.</p>
                            @endforelse
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-pagination relative mt-8"></div>
                    </div>
                </section>
            @endif
        @empty
        @endforelse
    </div>

    <script>
        const swipers = document.querySelectorAll('.mySwiper');
        swipers.forEach(container => {
            new Swiper(container, {
                lazy: true,
                spaceBetween: 16,
                freeMode: true,
                navigation: {
                    nextEl: container.querySelector('.swiper-button-next'),
                    prevEl: container.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: container.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                breakpoints: {
                    0: { slidesPerView: 1.15 },
                    640: { slidesPerView: 2.2 },
                    1024: { slidesPerView: 4.2 },
                },
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const bookImages = document.querySelectorAll(".book-image");
            bookImages.forEach((img) => {
                if (img.complete) {
                    handleImageLoad(img);
                } else {
                    img.addEventListener("load", () => handleImageLoad(img));
                    img.addEventListener("error", () => handleImageLoad(img));
                }
            });

            function handleImageLoad(img) {
                const wrapper = img.closest(".swiper-slide");
                const skeleton = wrapper?.querySelector(".skeleton-loader");
                if (skeleton) skeleton.remove();
                img.classList.remove("opacity-0");
                img.classList.add("opacity-100");
            }
        });
    </script>

    <script>
        function addToCartBulk(payload) {
            const isLoggedIn = @json(Auth::check());
            const isEmailVerified = isLoggedIn ? @json(Auth::check() && Auth::user()->hasVerifiedEmail()) : false;

            if (isLoggedIn && isEmailVerified) {
                fetch('/cart-bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ items: JSON.parse(payload) })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.removeItem("cart_items");
                        window.location.href = "/cart-web";
                    }
                });
            } else if (isLoggedIn) {
                showToast("Please verify your email before adding items to the cart.");
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isLoggedIn = @json(Auth::check());
            const cartCount = {{ isset($cartCount) ? $cartCount : 0 }};
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }
            if (typeof loggedInDevicesCount === 'function') {
                loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }});
            }
            if (isLoggedIn) {
                if (typeof renderCartFromApi === 'function') renderCartFromApi();
            } else {
                if (typeof renderGuestCart === 'function') renderGuestCart();
            }
        });
    </script>
</x-entry-layout>

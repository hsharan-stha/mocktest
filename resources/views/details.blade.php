<x-entry-layout>
    <div class="page-stack">
        @if (empty($bookDetails))
            <section class="surface p-6">
                <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '書籍が見つかりません' : 'Book not found' }}</h2>
                <p class="section-copy mt-2">{{ app()->getLocale() === 'jp' ? '要求された書籍は利用できません。' : 'The requested book is not available.' }}</p>
            </section>
        @else
        <section class="page-hero">
            <div class="relative z-10">
                <span class="eyebrow">{{ app()->getLocale() === 'jp' ? '書籍詳細' : 'Book Detail' }}</span>
                <h1 class="page-title">{{ $bookDetails->name }}</h1>
                <p class="page-copy">{{ app()->getLocale() === 'jp' ? '学習計画に追加する前に、プレビュー、価格、購入方法を確認できます。' : 'Review the preview pages, pricing, and purchase actions before adding this title to your study plan.' }}</p>
            </div>
        </section>

        <div class="grid gap-6">
            <section class="space-y-6">
                <div class="surface p-6">
                    <div class="flex flex-col gap-6 sm:flex-row">
                        <div class="w-full max-w-[210px] overflow-hidden rounded-[1.75rem] bg-slate-100 shadow-lg">
                            <img src="{{ asset($bookDetails->images) }}" alt="{{ $bookDetails->name }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1">
                            <span class="ui-badge ui-badge-brand">{{ $bookDetails->category->name ?? 'Learning Book' }}</span>
                            <h2 class="mt-4 text-2xl font-semibold text-slate-900">{{ $bookDetails->name }}</h2>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $bookDetails->description ?: 'A guided study title designed to support practice, review, and exam readiness.' }}</p>
                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <span class="price-chip text-base">Rs.{{ $bookDetails->price }}</span>
                                <span class="ui-badge ui-badge-success">{{ __('details.taxIncluded') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? '購入方法' : 'Purchase Options' }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ app()->getLocale() === 'jp' ? 'カートに追加するか、このページから直接購入に進めます。' : 'Add to cart for later or go directly to checkout from this page.' }}</p>
                    <div class="mt-5 grid gap-3">
                        <button type="button" onclick="addToCart(this,{{ $bookDetails }}, 1)" class="ui-button-primary w-full">
                            <span class="button-text">{{ __('details.addToCart') }}</span>
                            <span class="loading hidden">{{ __('details.loading') }}</span>
                        </button>
                        <button onclick="buyNow({{ $bookDetails }})" id="buyNow" class="ui-button-secondary w-full">{{ __('details.buyNow') }}</button>
                    </div>
                </div>

                <div class="surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900">{{ app()->getLocale() === 'jp' ? 'この教材でできること' : 'What you can expect' }}</h3>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                        <li>Preview pages that match the actual learning and exam format.</li>
                        <li>Library access after purchase with reading and exam actions.</li>
                        <li>A mobile-friendly experience for browsing, purchasing, and studying.</li>
                    </ul>
                </div>
            </section>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartCount = {{ isset($cartCount) ? $cartCount : 0 }};
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }
        });

        function buyNow(details) {
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                document.getElementById("buyNow").setAttribute("disabled", true);
                document.getElementById("buyNow").innerText = "{{ __('home.loading') }}";
                fetch('/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ book_id: details.id, quantity: 1 })
                })
                .then(response => response.json())
                .then(() => {
                    window.location.href = `/cart-web`;
                })
                .catch(() => {
                    document.getElementById("buyNow").removeAttribute("disabled");
                    document.getElementById("buyNow").innerText = "Proceed to Buy";
                });
            } else {
                item = { ...details, qty: 1 };
                let cart = JSON.parse(localStorage.getItem("cart_items")) || [];
                const existing = cart.find(i => i.id === item.id);
                if (!existing) {
                    cart.push(item);
                }
                localStorage.setItem("cart_items", JSON.stringify(cart));
                window.location.href = "/login";
            }
        }
    </script>
</x-entry-layout>

<x-entry-layout>

    <form class="w-full flex justify-center" method="GET" action="{{ route('store') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-center py-4 w-full">

            <!-- Category Select -->
            <select id="category" name="category_id"
                class="bg-white text-slate-700 border border-primary-300 px-4 py-2 text-sm rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-full transition-all">
                <option value="">{{ __('home.category') }}</option>
                @forelse ($categoryList as $category)
                    <option
                        {{ isset($filteredData['category_id']) && $category->id == $filteredData['category_id'] ? 'selected' : '' }}
                        value="{{ $category->id }}">{{ $category->name }}</option>
                @empty
                    <option disabled>{{ __('home.noCategoriesAvailable') }}</option>
                @endforelse
            </select>

            <!-- Search Box -->
            <div class="w-full">
                <input type="text" placeholder="{{ __('home.book_name') }}" name="book_name"
                    value="{{ isset($filteredData['book_name']) ? $filteredData['book_name'] : '' }}"
                    class="w-full pr-3 py-2 text-sm text-slate-700 border border-primary-300 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all" />
            </div>

            <!-- Buttons Group -->
            <div class="flex gap-2 ">
                <button type="submit"
                    class="flex items-center gap-2 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-medium px-4 py-2 text-sm rounded-lg transition-all shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17.65 17.65A7.5 7.5 0 1 0 5.2 5.2a7.5 7.5 0 0 0 10.6 10.6z" />
                    </svg>
                    {{ __('home.search') }}
                </button>

                <a href="{{ route('index') }}"
                    class="flex items-center gap-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-medium px-4 py-2 text-sm rounded-lg transition-all shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    {{ __('home.clear') }}
                </a>
            </div>

        </div>
    </form>

    @forelse ($categories as $category)
        @if (count($category->books) > 0)
            <div class="flex flex-col gap-8">
                <h2 class="text-2xl font-bold text-primary-700 capitalize mt-8">{{ $category->name }}</h2>

                <div>
                    <div class="swiper mySwiper">

                        <div class="swiper-wrapper">
                            @forelse ($category->books as $book)
                                <div class="swiper-slide">
                                    <div
                                        class="bg-white shadow-md hover:shadow-xl transition-all overflow-hidden flex flex-col rounded-lg border border-slate-100">

                                        <!-- Skeleton Loader -->
                                        <div class="skeleton-loader absolute inset-0 bg-primary-100 animate-pulse z-10 rounded-lg">
                                        </div>

                                        <!-- Book Image -->
                                        <div class="aspect-[2/3] overflow-hidden">
                                            <a href="{{ route('detail.view', $book->id) }}">
                                                <img loading="lazy" src="{{ asset($book->images) }}" alt="Book cover"
                                                    class="book-image w-full h-full transition-opacity duration-500 opacity-0">
                                            </a>
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-4 flex flex-col justify-between gap-3 flex-1">
                                            <!-- Title & Description -->
                                            <div>
                                                <h3
                                                    class="text-xl text-slate-900 text-base leading-tight h-[3rem] overflow-hidden line-clamp-2 font-semibold">
                                                    {{ $book->name }}
                                                </h3>
                                                <p class="text-sm text-slate-600 mt-1 line-clamp-2 hidden">
                                                    {{ $book->description }}
                                                </p>
                                            </div>

                                            <!-- Price & Button -->
                                            <div class="mt-2 flex flex-col gap-3">
                                                <div class="text-primary-700 font-bold text-lg">
                                                    Rs.{{ number_format($book->price) }}
                                                </div>

                                                <!-- Button -->
                                                <button type="button"
                                                    onclick="addToCart(this, {{ $book }}, 1)"
                                                    class="bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-semibold py-2 rounded-lg transition-all shadow-md hover:shadow-lg w-full block opacity-100 opacity-0 mtransition-opacity">
                                                    <span class="button-text">{{ __('home.addToCart') }}</span>
                                                    <span class="loading hidden">{{ __('home.loading') }}</span>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500">No books in this category.</p>
                            @endforelse
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-pagination relative mt-8"></div>

                    </div>
                </div>
            </div>
        @endif
    @empty
    @endforelse

    <script>
        const swipers = document.querySelectorAll('.mySwiper');
        swipers.forEach(container => {
            new Swiper(container, {
                lazy: true,
                spaceBetween: 12,
                freeMode: true,
                navigation: {
                    nextEl: container.querySelector('.swiper-button-next'),
                    prevEl: container.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    0: { slidesPerView: 1 },
                    640: { slidesPerView: 3 },
                    1024: { slidesPerView: 5 },
                },
            });
        });
    </script>

    <script>
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
                    })
                    .catch(error => console.error('Error:', error));
            } else if (isLoggedIn) {
                showToast("'Please verify your email before adding items to the cart.");
            }
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isLoggedIn = @json(Auth::check());
            const cartCount = {{ isset($cartCount) ? $cartCount : 0 }};
            
            // Update cart count display
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }
            
            // Update logged in devices count
            if (typeof loggedInDevicesCount === 'function') {
                loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }});
            }
            
            // Initialize cart display based on login status
            if (isLoggedIn) {
                if (typeof renderCartFromApi === 'function') {
                    renderCartFromApi();
                }
            } else {
                if (typeof renderGuestCart === 'function') {
                    renderGuestCart();
                }
            }
        });
    </script>

</x-entry-layout>

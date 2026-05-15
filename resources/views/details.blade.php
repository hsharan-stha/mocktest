<x-entry-layout>

    <script src="{{ asset('js/extras/jquery.min.1.7.js') }}"></script>

    <script src="{{ asset('js/lib/turn.min.js') }}"></script>


    <div class="w-full mx-auto flex flex-col lg:flex-row gap-8">

        <!-- Product Images + Flipbook -->
        <div class="w-full md:w-1/2 lg:w-1/4 flex flex-col items-center gap-8">

            <!-- Flipbook -->
            <div class="w-full aspect-[3/4] border shadow" id="flipbook">

                <div class="page bg-white flex justify-center items-center text-2xl font-bold"><img loading="lazy"
                        src="{{ asset($bookDetails->images) }}" alt="Page {{ 0 }}" class="w-full h-full"></div>
                @foreach ($bookDetails->pages as $page)
                    <div class="page bg-white flex flex-col justify-center items-center text-2xl font-bold">
                       {{-- Question --}}
        <p class="mb-4 text-center">{{ $page->question }}</p>

        {{-- Options --}}
        <div class="space-y-2 w-full max-w-md text-lg font-normal">
            <div class="p-3 bg-gray-100 rounded">{{ $page->option1 }}</div>
            <div class="p-3 bg-gray-100 rounded">{{ $page->option2 }}</div>
            <div class="p-3 bg-gray-100 rounded">{{ $page->option3 }}</div>
            <div class="p-3 bg-gray-100 rounded">{{ $page->option4 }}</div>
        </div>
                    </div>
                @endforeach

                <div class="page bg-white h-full w-full flex justify-center items-center text-2xl font-bold relative">
                    <div
                        class="h-full w-full bg-gradient-to-br from-primary-600 via-primary-700 to-accent-600 text-white flex flex-col items-center justify-center text-center px-6 py-8">
                        <h3 class="text-2xl font-bold mb-4">Unlock Full Access</h3>
                        <p class="text-sm md:text-base opacity-95">
                            You're viewing a preview. Purchase the full version to read the entire book. Once purchased,
                            the
                            full version will be available in your library for unlimited access.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Product Details -->
        <div class="w-full md:w-1/2 lg:w-1/4">
            <div class="flex-grow w-full space-y-4">
                <h1 class="text-2xl font-semibold text-gray-800">
                    {{ $bookDetails->name }}
                </h1>

                <!-- Ratings and badges -->
                <div class="flex items-center space-x-2 hidden">
                    <div class="flex items-center text-yellow-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.062 3.275a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.062 3.275c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.062-3.275a1 1 0 00-.364-1.118L2.447 8.702c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.062-3.275z" />
                        </svg>
                        <span class="ml-1 text-gray-700">4.1</span>
                    </div>
                    <!-- <span class="text-sm text-gray-500">(12,826 ratings)</span> -->
                    <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">#1
                        {{ __('details.bestSeller') }}</span>
                </div>

                <!-- Sold info -->
                <!--  <p class="text-sm text-gray-500">20K+ bought in the past month</p> -->

                <!-- Price -->
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-bold text-primary-700">Rs.{{ $bookDetails->price }}</span>
                    <span class="text-sm text-slate-500">({{ __('details.taxIncluded') }})</span>
                </div>


                <!-- Delivery info -->
                <p class="text-sm text-gray-700 hidden">
                    <span class="font-bold text-green-600">{{ __('details.delivery') }}</span>
                    {{ __('details.stuffCheck') }}
                </p>
            </div>

            <!-- Buy Box -->
            <div class="w-full border border-slate-200 rounded-xl p-6 space-y-4 shadow-lg mt-4 bg-gradient-to-br from-white to-slate-50">

                <button type="button" onclick="addToCart(this,{{ $bookDetails }}, 1)"
                    class="w-full bg-gradient-to-r from-accent-500 to-accent-600 hover:from-accent-600 hover:to-accent-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg">
                    <span class="button-text">{{ __('details.addToCart') }}</span>
                    <span class="loading hidden">{{ __('details.loading') }}</span>
                </button>
                <button onclick="buyNow({{ $bookDetails }})" id="buyNow"
                    class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg">{{ __('details.buyNow') }}</button>
                <div class="text-xs text-slate-500 hidden">{{ __('details.shipInfo') }}</div>
            </div>
        </div>
    </div>

    <style>
        #flipbook .page {
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#flipbook').turn({
                width: "100%",
                height: "100%",
                autoCenter: true,
                acceleration: false,
                elevation: 100,
                duration: 800,
                display: 'single',
                gradients: true
            });
            const isLoggedIn = @json(Auth::check());
            
            // Cart count is updated via cartCountdisplay function, no need to render sidebar
            
            // Update cart count display
            const cartCount = {{ isset($cartCount) ? $cartCount : 0 }};
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }

            // Orientation change handler
            window.addEventListener("orientationchange", () => {
              window.location.reload()
            });
        });
    </script>

    <script>
        function buyNow(details) {
            const isLoggedIn = @json(Auth::check());
            if (isLoggedIn) {
                document.getElementById("buyNow").setAttribute("disabled", true);
                document.getElementById("buyNow").innerText = "{{ __('home.loading') }}"
                fetch('/cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')


                        },
                        body: JSON.stringify({
                            book_id: details.id,
                            quantity: 1
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        window.location.href = `/cart-web`;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById("buyNow").removeAttribute("disabled");
                        document.getElementById("buyNow").innerText = "Proceed to Buy"
                    });
            } else {
                item = {
                    ...details,
                    qty: 1
                }
                let cart = JSON.parse(localStorage.getItem("cart_items")) || [];

                const existing = cart.find(i => i.id === item.id);
                if (!existing) {
                    cart.push(item);
                }

                localStorage.setItem("cart_items", JSON.stringify(cart));
                window.location.href = "/login-register"
            }
        }
    </script>



</x-entry-layout>

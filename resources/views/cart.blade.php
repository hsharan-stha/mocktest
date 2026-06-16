<x-entry-layout>
    <div class="page-stack">
        <section class="page-hero">
            <div class="relative z-10">
                <span class="eyebrow">{{ __('home.cart') }}</span>
                <h1 class="page-title">{{ __('cart.cartOverview') }}</h1>
                <p class="page-copy">{{ app()->getLocale() === 'jp' ? '数量を確認・更新し、見やすい概要で購入手続きを進められます。' : 'Review quantities, update your selection, and complete checkout with a cleaner summary layout.' }}</p>
            </div>
        </section>

        @if ($cartList->isEmpty())
            <section class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75" />
                    </svg>
                </div>
                <h3 class="mt-5 text-xl font-semibold text-slate-900">{{ __('cart.empty') }}</h3>
                    <p class="mt-2 text-sm text-slate-600">{{ app()->getLocale() === 'jp' ? 'カタログから本を追加して購入リストを作成しましょう。' : 'Browse the catalog and add books to start building your purchase list.' }}</p>
                <a href="/" class="ui-button-primary mt-6">{{ __('cart.browseBooks') }}</a>
            </section>
        @else
            <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                <section class="surface overflow-hidden">
                    <div class="border-b border-slate-200 px-6 py-5">
                        <h2 class="section-title">{{ app()->getLocale() === 'jp' ? 'カート内商品' : 'Cart Items' }}</h2>
                        <p class="section-copy mt-2">{{ __('cart.info') }}</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('cart.bookName') }}</th>
                                    <th class="text-center">{{ __('cart.quantity') }}</th>
                                    <th class="text-right">{{ __('cart.price') }}</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cart-items-body">
                                @foreach ($cartList as $detail)
                                    <tr id="cart-row-{{ $detail->book_id }}">
                                        <td>
                                            <div class="font-semibold text-slate-900">{{ $detail->book->name ?? 'Unknown' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="mx-auto flex w-fit items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-2 py-1">
                                                <button onclick="decreaseQuantity({{ $detail->book_id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-700 hover:bg-white disabled:cursor-not-allowed disabled:opacity-50" id="decrease-btn-{{ $detail->book_id }}" {{ $detail->quantity <= 1 ? 'disabled' : '' }}>
                                                    -
                                                </button>
                                                <span id="quantity-{{ $detail->book_id }}" class="min-w-[2rem] text-center text-sm font-semibold text-slate-900">{{ $detail->quantity }}</span>
                                                <button onclick="increaseQuantity({{ $detail->book_id }})" class="inline-flex h-9 w-9 items-center justify-center rounded-xl text-slate-700 hover:bg-white" id="increase-btn-{{ $detail->book_id }}">
                                                    +
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-right font-semibold text-slate-900">
                                            <span id="item-total-{{ $detail->book_id }}">Rs.{{ number_format($detail->book->price * $detail->quantity) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button onclick="removeCartItem({{ $detail->book_id }})" class="inline-flex rounded-xl bg-red-50 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-100" id="remove-btn-{{ $detail->book_id }}">
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>

                <aside class="surface p-6">
                    <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '概要' : 'Summary' }}</h2>
                    <p class="section-copy mt-2">{{ __('cart.finalize') }}</p>
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-4">
                            <span class="text-sm font-medium text-slate-500">{{ __('cart.totalEstimated') }}</span>
                            <span class="text-2xl font-semibold text-slate-900" id="cart-total-price">Rs.{{ number_format($totalPrice) }}</span>
                        </div>
                        <a id="proceedToCheckout" href="#" onclick="confirmProceedToCheckout()" class="ui-button-primary w-full">{{ __('cart.proceedToCheckout') }}</a>
                        <a href="/" class="ui-button-secondary w-full">{{ __('cart.cancel') }}</a>
                    </div>
                </aside>
            </div>
        @endif
    </div>

    <script>
        localStorage.removeItem("cart_items");
        const bookPrices = {};
        @foreach ($cartList as $detail)
            bookPrices[{{ $detail->book_id }}] = {{ $detail->book->price }};
        @endforeach

        let payload = @json($cartList).map(i => ({
            book_id: i.book_id,
            quantity: i.quantity,
            per_price: parseFloat(i.book.price),
            price: parseFloat(i.book.price * i.quantity)
        }));

        function updatePayload() {
            payload = [];
            document.querySelectorAll('[id^="quantity-"]').forEach(quantityEl => {
                const bookId = parseInt(quantityEl.id.replace('quantity-', ''));
                const quantity = parseInt(quantityEl.innerText);
                if (bookPrices[bookId] && quantity > 0) {
                    payload.push({
                        book_id: bookId,
                        quantity: quantity,
                        per_price: bookPrices[bookId],
                        price: bookPrices[bookId] * quantity
                    });
                }
            });
        }

        function updateTotal() {
            updatePayload();
            const total = payload.reduce((sum, item) => sum + item.price, 0);
            const totalElement = document.getElementById('cart-total-price');
            if (totalElement) totalElement.innerText = `Rs.${total.toLocaleString('en-US')}`;
        }

        function getCurrentQuantity(bookId) {
            const quantityEl = document.getElementById(`quantity-${bookId}`);
            return quantityEl ? parseInt(quantityEl.innerText) || 1 : 1;
        }

        function increaseQuantity(bookId) {
            updateCartQuantity(bookId, getCurrentQuantity(bookId) + 1);
        }

        function decreaseQuantity(bookId) {
            updateCartQuantity(bookId, getCurrentQuantity(bookId) - 1);
        }

        function updateCartQuantity(bookId, newQuantity) {
            if (newQuantity < 1) {
                if (confirm('Remove this item from cart?')) removeCartItem(bookId);
                return;
            }
            const decreaseBtn = document.getElementById(`decrease-btn-${bookId}`);
            const increaseBtn = document.getElementById(`increase-btn-${bookId}`);
            const removeBtn = document.getElementById(`remove-btn-${bookId}`);
            if (decreaseBtn) decreaseBtn.disabled = true;
            if (increaseBtn) increaseBtn.disabled = true;
            if (removeBtn) removeBtn.disabled = true;

            fetch('/cart/update-quantity', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ book_id: bookId, quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    const quantityEl = document.getElementById(`quantity-${bookId}`);
                    if (quantityEl) quantityEl.innerText = newQuantity;
                    const itemTotalEl = document.getElementById(`item-total-${bookId}`);
                    if (itemTotalEl && bookPrices[bookId]) {
                        itemTotalEl.innerText = `Rs.${(bookPrices[bookId] * newQuantity).toLocaleString('en-US')}`;
                    }
                    if (decreaseBtn) decreaseBtn.disabled = newQuantity <= 1;
                    if (data.cartCount !== undefined && typeof cartCountdisplay === 'function') cartCountdisplay(data.cartCount);
                    updateTotal();
                    if (typeof showToast === 'function') showToast('Quantity updated successfully');
                } else if (typeof showToast === 'function') {
                    showToast(data.message || 'Failed to update quantity');
                }
                if (decreaseBtn) decreaseBtn.disabled = false;
                if (increaseBtn) increaseBtn.disabled = false;
                if (removeBtn) removeBtn.disabled = false;
            })
            .catch(() => {
                if (decreaseBtn) decreaseBtn.disabled = false;
                if (increaseBtn) increaseBtn.disabled = false;
                if (removeBtn) removeBtn.disabled = false;
            });
        }

        function removeCartItem(bookId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) return;
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
                if (data.success !== false) {
                    const row = document.getElementById(`cart-row-${bookId}`);
                    if (row) {
                        row.remove();
                        const tbody = document.getElementById('cart-items-body');
                        if (tbody && tbody.children.length === 0) {
                            window.location.reload();
                        } else {
                            updateTotal();
                        }
                    }
                    if (data.cartCount !== undefined && typeof cartCountdisplay === 'function') cartCountdisplay(data.cartCount);
                    if (typeof showToast === 'function') showToast('Item removed from cart');
                }
            });
        }

        function confirmProceedToCheckout() {
            updatePayload();
            if (payload.length === 0) {
                if (typeof showToast === 'function') showToast('Your cart is empty');
                return;
            }
            const proceedBtn = document.getElementById("proceedToCheckout");
            proceedBtn.setAttribute("disabled", true);
            proceedBtn.innerText = "{{__('cart.loading')}}";
            fetch('/purchases', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ books: payload })
            })
            .then(response => response.json())
            .then(data => {
                window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
            })
            .catch(() => {
                proceedBtn.removeAttribute("disabled");
                proceedBtn.innerText = "{{__('cart.proceedToCheckout')}}";
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            const cartCount = {{ $cartCount ?? 0 }};
            if (typeof cartCountdisplay === 'function') cartCountdisplay(cartCount);
            if (typeof loggedInDevicesCount === 'function') loggedInDevicesCount({{ $loggedInDevices ?? 0 }});
        });
    </script>
</x-entry-layout>

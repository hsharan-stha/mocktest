<x-entry-layout>
    <div class="bg-white p-8 rounded-2xl max-w-2xl mx-auto shadow-xl space-y-6 text-slate-800 border border-slate-200">

        @if ($cartList->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-600 text-xl font-semibold">{{ __('cart.empty') }}</p>
                <a href="/"
                    class="mt-4 inline-block bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all font-medium">
                    {{ __('cart.browseBooks') }}
                </a>
            </div>
        @else
            <div class="text-center">
                <p class="text-primary-700 font-bold text-2xl"> {{ __('cart.cartOverview') }}</p>
                <p class="mt-2 text-slate-600 font-medium">
                    {{ __('cart.summary') }}

                </p>
            </div>

            <div>
                <p>{{ __('cart.info') }}</p>

              

                <p class="mt-4 font-semibold text-lg"> {{ __('cart.cartDetails') }}
                </p>
                <table class="w-full border-collapse border border-slate-200 text-left rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gradient-to-r from-primary-50 to-slate-50">
                            <th class="border border-slate-200 px-4 py-3 text-primary-700 font-bold">{{ __('cart.bookName') }}</th>
                            <th class="border border-slate-200 px-4 py-3 text-primary-700 font-bold">{{ __('cart.quantity') }}</th>
                            <th class="border border-slate-200 px-4 py-3 text-primary-700 font-bold">{{ __('cart.price') }}</th>
                            <th class="border border-slate-200 px-4 py-3 text-primary-700 font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-body">
                        @foreach ($cartList as $detail)
                            <tr id="cart-row-{{ $detail->book_id }}" class="hover:bg-slate-50 transition-colors">
                                <td class="border border-slate-200 px-4 py-3">{{ $detail->book->name ?? 'Unknown' }}</td>
                                <td class="border border-slate-200 px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="decreaseQuantity({{ $detail->book_id }})" 
                                                class="p-1.5 border border-slate-300 rounded-lg hover:bg-primary-50 hover:border-primary-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                id="decrease-btn-{{ $detail->book_id }}"
                                                {{ $detail->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <span id="quantity-{{ $detail->book_id }}" class="text-primary-700 text-sm w-8 text-center font-semibold w-full"   contenteditable="true"
  inputmode="numeric"
  onbeforeinput="if (event.data && !/^[0-9]$/.test(event.data)) event.preventDefault()">{{ $detail->quantity }}</span>
                                        
                                        <button onclick="increaseQuantity({{ $detail->book_id }})" 
                                                class="p-1.5 border border-slate-300 rounded-lg hover:bg-primary-50 hover:border-primary-300 transition-colors"
                                                id="increase-btn-{{ $detail->book_id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="border border-slate-200 px-4 py-3 text-right font-semibold text-primary-700">
                                    <span id="item-total-{{ $detail->book_id }}">Rs.{{ number_format($detail->book->price * $detail->quantity) }}</span>
                                </td>
                                <td class="border border-slate-200 px-4 py-3 text-center">
                                    <button onclick="removeCartItem({{ $detail->book_id }})" 
                                            class="p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all"
                                            id="remove-btn-{{ $detail->book_id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="bg-gradient-to-r from-primary-50 to-slate-50">
                    <td class="border border-slate-200 px-4 py-3 font-bold text-slate-900" colspan="3"> {{ __('cart.totalEstimated') }}</td>
                    <td class="border border-slate-200 px-4 py-3 font-bold text-right text-primary-700 text-lg" id="cart-total-price">Rs.{{ number_format($totalPrice) }} </td>
                    </tr>
                    </tfoot>
                </table>
                  
            </div>

            <div class="mt-6">
                <p>{{ __('cart.finalize') }}</p>
            </div>

            <div class="text-center mt-6 flex justify-center gap-4">
                <a id="proceedToCheckout" href="#" onclick="confirmProceedToCheckout()"
                    class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white py-3 px-8 rounded-lg shadow-md hover:shadow-lg inline-block font-semibold transition-all">
                    {{ __('cart.proceedToCheckout') }}
                </a>
                <a href="/"
                    class="bg-slate-200 hover:bg-slate-300 text-slate-700 py-3 px-8 rounded-lg shadow-md hover:shadow-lg inline-block font-semibold transition-all">
                    {{ __('cart.cancel') }}
                </a>
            </div>

        @endif

    </div>

    <script>
        localStorage.removeItem("cart_items");
        
        // Store book prices for dynamic calculations
        const bookPrices = {};
        @foreach ($cartList as $detail)
            bookPrices[{{ $detail->book_id }}] = {{ $detail->book->price }};
        @endforeach
        
        // Initialize payload
        let payload = @json($cartList).map(i => {
            return {
                book_id: i.book_id,
                quantity: i.quantity,
                per_price: parseFloat(i.book.price),
                price: parseFloat(i.book.price * i.quantity)
            }
        });
        
        // Function to update payload
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
        
        // Function to calculate and update total
        function updateTotal() {
            updatePayload();
            const total = payload.reduce((sum, item) => sum + item.price, 0);
            const totalElement = document.getElementById('cart-total-price');
            if (totalElement) {
                totalElement.innerText = `Rs.${total.toLocaleString('en-US')}`;
            }
        }
        
        // Helper functions to get current quantity and update
        function getCurrentQuantity(bookId) {
            const quantityEl = document.getElementById(`quantity-${bookId}`);
            return quantityEl ? parseInt(quantityEl.innerText) || 1 : 1;
        }
        
        function increaseQuantity(bookId) {
            const currentQty = getCurrentQuantity(bookId);
            updateCartQuantity(bookId, currentQty + 1);
        }
        
        function decreaseQuantity(bookId) {
            const currentQty = getCurrentQuantity(bookId);
            updateCartQuantity(bookId, currentQty - 1);
        }
        
        // Function to update cart quantity
        function updateCartQuantity(bookId, newQuantity) {
            if (newQuantity < 1) {
                if (confirm('Remove this item from cart?')) {
                    removeCartItem(bookId);
                }
                return;
            }
            
            // Disable buttons during update
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
                body: JSON.stringify({
                    book_id: bookId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    // Update quantity display
                    const quantityEl = document.getElementById(`quantity-${bookId}`);
                    if (quantityEl) {
                        quantityEl.innerText = newQuantity;
                    }
                    
                    // Update item total
                    const itemTotalEl = document.getElementById(`item-total-${bookId}`);
                    if (itemTotalEl && bookPrices[bookId]) {
                        const itemTotal = bookPrices[bookId] * newQuantity;
                        itemTotalEl.innerText = `Rs.${itemTotal.toLocaleString('en-US')}`;
                    }
                    
                    // Update decrease button state
                    if (decreaseBtn) {
                        decreaseBtn.disabled = newQuantity <= 1;
                    }
                    
                    // Update cart count
                    if (data.cartCount !== undefined && typeof cartCountdisplay === 'function') {
                        cartCountdisplay(data.cartCount);
                    }
                    
                    // Update total
                    updateTotal();
                    
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('Quantity updated successfully');
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Failed to update quantity');
                    }
                }
                
                // Re-enable buttons
                if (decreaseBtn) decreaseBtn.disabled = false;
                if (increaseBtn) increaseBtn.disabled = false;
                if (removeBtn) removeBtn.disabled = false;
            })
            .catch(error => {
                console.error('Error updating quantity:', error);
                if (typeof showToast === 'function') {
                    showToast('Error updating quantity. Please try again.');
                }
                
                // Re-enable buttons
                if (decreaseBtn) decreaseBtn.disabled = false;
                if (increaseBtn) increaseBtn.disabled = false;
                if (removeBtn) removeBtn.disabled = false;
            });
        }
        
        // Function to remove cart item
        function removeCartItem(bookId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            // Disable buttons during deletion
            const decreaseBtn = document.getElementById(`decrease-btn-${bookId}`);
            const increaseBtn = document.getElementById(`increase-btn-${bookId}`);
            const removeBtn = document.getElementById(`remove-btn-${bookId}`);
            
            if (decreaseBtn) decreaseBtn.disabled = true;
            if (increaseBtn) increaseBtn.disabled = true;
            if (removeBtn) removeBtn.disabled = true;
            
            fetch('/cart/delete-cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    book_id: bookId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success !== false) {
                    // Remove row from table
                    const row = document.getElementById(`cart-row-${bookId}`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if cart is empty
                            const tbody = document.getElementById('cart-items-body');
                            if (tbody && tbody.children.length === 0) {
                                // Reload page to show empty cart message
                                window.location.reload();
                            } else {
                                // Update total
                                updateTotal();
                            }
                        }, 300);
                    }
                    
                    // Update cart count
                    if (data.cartCount !== undefined && typeof cartCountdisplay === 'function') {
                        cartCountdisplay(data.cartCount);
                    }
                    
                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('Item removed from cart');
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Failed to remove item');
                    }
                    
                    // Re-enable buttons
                    if (decreaseBtn) decreaseBtn.disabled = false;
                    if (increaseBtn) increaseBtn.disabled = false;
                    if (removeBtn) removeBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                if (typeof showToast === 'function') {
                    showToast('Error removing item. Please try again.');
                }
                
                // Re-enable buttons
                if (decreaseBtn) decreaseBtn.disabled = false;
                if (increaseBtn) increaseBtn.disabled = false;
                if (removeBtn) removeBtn.disabled = false;
            });
        }

        function confirmProceedToCheckout() {
            // Update payload before checkout to ensure latest quantities
            updatePayload();
            
            if (payload.length === 0) {
                if (typeof showToast === 'function') {
                    showToast('Your cart is empty');
                }
                return;
            }
            
            const proceedBtn = document.getElementById("proceedToCheckout");
            proceedBtn.setAttribute("disabled", true);
            proceedBtn.innerText = "{{__("cart.loading")}}";
            
            fetch('/purchases', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        books: payload
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    window.location.href = `/purchases?purchase_id=${data?.purchase_id}`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    proceedBtn.removeAttribute("disabled");
                    proceedBtn.innerText = "{{__('cart.proceedToCheckout')}}";
                    if (typeof showToast === 'function') {
                        showToast('Error processing checkout. Please try again.');
                    }
                });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const isLoggedIn = @json(Auth::check());
            const cartCount = {{ $cartCount ?? 0 }};
            
            // Update cart count display
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }
            
            // Update logged in devices count
            if (typeof loggedInDevicesCount === 'function') {
                loggedInDevicesCount({{ $loggedInDevices ?? 0 }});
            }
            
            // Cart is rendered server-side on this page, no need to render via JavaScript
        });
    </script>
</x-entry-layout>

<x-entry-layout>
    <div class="page-stack">
        <section class="page-hero">
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <span class="eyebrow">{{ app()->getLocale() === 'jp' ? 'マイライブラリ' : 'My Library' }}</span>
                    <h1 class="page-title">{{ __('library.bookLibrary') }}</h1>
                    <p class="page-copy">{{ app()->getLocale() === 'jp' ? '購入した教材を整理し、すっきりしたダッシュボードからすぐ読書を再開できます。' : 'Keep your purchased titles organized and jump back into reading from a cleaner, more focused dashboard.' }}</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-sm text-blue-100">{{ app()->getLocale() === 'jp' ? '総書籍数' : 'Total Books' }}</p>
                        <p class="mt-2 text-3xl font-semibold" id="totalBooksCount">0</p>
                    </div>
                    <div class="rounded-3xl bg-white/10 p-5 backdrop-blur">
                        <p class="text-sm text-blue-100">{{ app()->getLocale() === 'jp' ? '読書準備完了' : 'Reading Ready' }}</p>
                        <p class="mt-2 text-base font-medium">{{ app()->getLocale() === 'jp' ? '前回の続きからすぐに読書を開始できます。' : 'Continue where you left off and launch the reader in one tap.' }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface p-5 sm:p-6">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '購入済み書籍' : 'Purchased Books' }}</h2>
                    <p class="section-copy mt-2">{{ app()->getLocale() === 'jp' ? '利用可能な書籍が残り利用回数と読書アクション付きで表示されます。' : 'Your available library titles appear here with remaining access counts and direct reading actions.' }}</p>
                </div>
            </div>
            <div id="booksContainer" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"></div>
        </section>
    </div>

    <script>
        const books = @json($purchasesList);

        function renderBooks() {
            const container = document.getElementById('booksContainer');
            container.innerHTML = '';
            document.getElementById('totalBooksCount').textContent = books.length;

            if (books.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A3.375 3.375 0 0011.25 11.625v2.625" />
                                </svg>
                            </div>
                            <h3 class="mt-5 text-xl font-semibold text-slate-900">Your library is empty</h3>
                            <p class="mt-2 text-sm text-slate-600">Purchase books from the catalog and they will appear here with direct reading access.</p>
                        </div>
                    </div>
                `;
                return;
            }

            books.forEach(book => {
                const div = document.createElement('div');
                div.className = "book-card library-book-card";
                div.innerHTML = `
                    <a href="/reader/${book.id}/reading" class="book-anchor block h-full">
                        <div class="book-cover relative">
                            <img loading="lazy" src="${book.src}" alt="${book.name}" class="h-full w-full object-cover" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 200 300%22%3E%3Crect fill=%22%23e2e8f0%22 width=%22200%22 height=%22300%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%2394a3b8%22 font-family=%22Arial%22 font-size=%2216%22%3EBook%3C/text%3E%3C/svg%3E';" />
                            <div class="absolute inset-x-3 bottom-3 rounded-2xl bg-slate-950/70 px-3 py-2 text-xs font-medium text-white backdrop-blur">
                                <div class="flex items-center justify-between gap-3">
                                    <span>Total: ${book.total_quantity || 0}</span>
                                    <span>Remaining: ${book.remaining_quantity || 0}</span>
                                </div>
                            </div>
                        </div>
                        <div class="book-body">
                            <div>
                                <h3 class="line-clamp-2 text-lg font-semibold text-slate-900">${book.name}</h3>
                                <p class="mt-2 text-sm text-slate-500">Continue reading from your purchased collection.</p>
                            </div>
                            <span class="ui-button-primary w-full text-center">Continue Reading</span>
                        </div>
                    </a>
                `;
                container.appendChild(div);
            });
        }

        renderBooks();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cartCount = {{ isset($cartCount) ? $cartCount : 0 }};
            if (typeof cartCountdisplay === 'function') {
                cartCountdisplay(cartCount);
            }
            if (typeof loggedInDevicesCount === 'function') {
                loggedInDevicesCount({{ isset($loggedInDevices) ? $loggedInDevices : 0 }});
            }
        });
    </script>
    <script>
        window.addEventListener("pageshow", function() {
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                anchor.disabled = false;
                anchor.innerHTML = anchor.dataset.originalText;
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                anchor.dataset.originalText = anchor.innerHTML;
                anchor.addEventListener("click", function() {
                    anchor.disabled = true;
                    anchor.innerHTML = '<div class="book-body"><span class="ui-button-secondary w-full">Loading...</span></div>';
                });
            });
        });
    </script>
</x-entry-layout>

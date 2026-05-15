<x-entry-layout>
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-primary-600 to-primary-800 bg-clip-text text-transparent mb-2">
                    📚 {{__("library.bookLibrary")}}
                </h1>
                <p class="text-slate-600">Manage and organize your purchased books</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-8">
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-6 border border-primary-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-700 mb-1">Total Books</p>
                        <p class="text-3xl font-bold text-primary-900" id="totalBooksCount">0</p>
                    </div>
                    <div class="w-12 h-12 bg-primary-200 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Section -->
    <div id="booksSection">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <span class="text-3xl">📖</span>
                All Books
            </h2>
        </div>
        <div id="booksContainer"
            class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            <!-- Books will be rendered here -->
        </div>
    </div>


    <script>
        const books = @json($purchasesList);

        function renderBooks() {
            const container = document.getElementById('booksContainer');
            container.innerHTML = '';
            
            // Update total books count
            document.getElementById('totalBooksCount').textContent = books.length;
            
            if (books.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full flex flex-col items-center justify-center py-16 px-4">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 text-lg font-medium mb-2">No books available</p>
                        <p class="text-slate-400 text-sm">Purchase books to add them to your library</p>
                    </div>
                `;
                return;
            }
            
            books.forEach(book => {
                const div = document.createElement('div');
                div.className = "group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-slate-200 cursor-pointer";
                div.setAttribute('data-id', book.id);

                div.innerHTML = `
                    <a href="/reader/${book.id}/reading" class="book-anchor block">
                        <div class="aspect-[2/3] overflow-hidden bg-gradient-to-br from-slate-50 to-slate-100 relative">
                            <img loading="lazy" 
                                src="${book.src}" 
                                alt="${book.name}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' viewBox=\\'0 0 200 300\\'%3E%3Crect fill=\\'%23e2e8f0\\' width=\\'200\\' height=\\'300\\'/%3E%3Ctext x=\\'50%25\\' y=\\'50%25\\' text-anchor=\\'middle\\' dy=\\'.3em\\' fill=\\'%2394a3b8\\' font-family=\\'Arial\\' font-size=\\'16\\'%3EBook%3C/text%3E%3C/svg%3E';" />
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                                <div class="text-white text-xs font-semibold">
                                    <div>Total: ${book.total_quantity || 0}</div>
                                    <div>Remaining: ${book.remaining_quantity || 0}</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-slate-900 text-sm line-clamp-2 group-hover:text-primary-600 transition-colors">
                                ${book.name}
                            </h3>
                        </div>
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="bg-white/90 backdrop-blur-sm rounded-lg p-1.5 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
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
            
            // Cart count is updated via cartCountdisplay function, no need to render sidebar
        });
    </script>
    <script>
        window.addEventListener("pageshow", function(event) {
            // This will run on both normal and bfcache restores
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                anchor.disabled = false;
                anchor.innerHTML = anchor.dataset.originalText;
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            DOMContentLoaded()
        });

        function DOMContentLoaded() {
            document.querySelectorAll(".book-anchor").forEach(function(anchor) {
                // Save original button text
                anchor.dataset.originalText = anchor.innerHTML;

                anchor.addEventListener("click", function(e) {
                    anchor.disabled = true;
                    anchor.innerHTML = '<span class="ml-2">Loading...</span>';
                });
            });
        }
    </script>
</x-entry-layout>

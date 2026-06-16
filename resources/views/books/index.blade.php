<x-app-layout>
    <x-slot name="header">
        Books
    </x-slot>

    <div class="page-stack">
        @if (session('success'))
            <div class="ui-alert-success">{{ session('success') }}</div>
        @endif

        <section class="data-card">
            <div class="data-card-header">
                <div>
                    <h2 class="section-title">Book Catalog</h2>
                    <p class="section-copy mt-2">Filter your catalog, open page management, and keep the content library tidy.</p>
                </div>
                <a href="{{ route('books.create') }}" class="ui-button-primary">Add Book</a>
            </div>

            <div class="border-b border-slate-200 px-5 py-4">
                <form method="GET" class="grid gap-3 md:grid-cols-5">
                    <input type="text" name="name" value="{{ request('name') }}" class="ui-input" placeholder="Search by book name">
                    <input type="text" name="category" value="{{ request('category') }}" class="ui-input" placeholder="Search category">
                    <input type="text" name="company" value="{{ request('company') }}" class="ui-input" placeholder="Search company">
                    <input type="text" name="price" value="{{ request('price') }}" class="ui-input" placeholder="Search price">
                    <div class="flex gap-3">
                        <button type="submit" class="ui-button-primary w-full">Filter</button>
                        <a href="{{ route('books.index') }}" class="ui-button-secondary w-full">Reset</a>
                    </div>
                </form>
            </div>

            <div class="data-grid">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Company</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $book->name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($book->description, 70) }}</div>
                                </td>
                                <td>{{ $book->category->name ?? '-' }}</td>
                                <td>{{ $book->company->name ?? '-' }}</td>
                                <td><span class="price-chip">Rs.{{ $book->price }}</span></td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('books.edit', $book) }}" class="ui-button-secondary px-4 py-2 text-xs">Edit</a>
                                        <a href="{{ route('books.pages.index', $book) }}" class="ui-button-primary px-4 py-2 text-xs">Pages</a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete it?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ui-button-danger px-4 py-2 text-xs">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="py-10 text-center">
                                        <p class="text-lg font-semibold text-slate-900">No books found</p>
                                        <p class="mt-2 text-sm text-slate-500">Create a new book or adjust your filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>

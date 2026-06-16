<x-app-layout>
    <x-slot name="header">
        Categories
    </x-slot>

    <div class="page-stack">
        @if (session('success'))
            <div class="ui-alert-success">{{ session('success') }}</div>
        @endif

        <section class="data-card">
            <div class="data-card-header">
                <div>
                    <h2 class="section-title">Category List</h2>
                    <p class="section-copy mt-2">Maintain a clean taxonomy for books and question types.</p>
                </div>
                <a href="{{ route('categories.create') }}" class="ui-button-primary">Add Category</a>
            </div>
            <div class="divide-y divide-slate-200">
                @forelse($categories as $category)
                    <div class="flex items-center justify-between gap-4 px-6 py-5">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $category->name }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="ui-button-secondary px-4 py-2 text-xs">Edit</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete it?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ui-button-danger px-4 py-2 text-xs">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-slate-500">No categories yet.</div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>

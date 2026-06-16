<x-app-layout>
    <x-slot name="header">
        New Category
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <section class="form-card">
            <h2 class="section-title">Category Details</h2>
            <p class="section-copy mt-2">Create a new category while keeping the existing resource workflow intact.</p>

            <form action="{{ route('categories.store') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label for="name" class="ui-label">Category name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="ui-input" placeholder="Example: History">
                    @error('name')
                        <p class="ui-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 pt-6">
                    <a href="{{ route('categories.index') }}" class="ui-button-secondary">Cancel</a>
                    <button type="submit" class="ui-button-primary">Save</button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>

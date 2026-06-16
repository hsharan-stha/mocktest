<x-app-layout>
    <x-slot name="header">
        New Book
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[1fr_0.72fr]">
        <section class="form-card">
            <h2 class="section-title">Book Information</h2>
            <p class="section-copy mt-2">Add a new title without changing any of the underlying resource or upload behavior.</p>

            <form id="form1" action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="name" class="ui-label">Book name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="ui-input" placeholder="Example: JLPT N5 Mastery">
                    @error('name') <p class="ui-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="ui-label">Description</label>
                    <textarea name="description" id="description" rows="5" class="ui-textarea" placeholder="Introduce the book, learning goals, and what the reader will find inside.">{{ old('description') }}</textarea>
                    @error('description') <p class="ui-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-grid">
                    @if(Auth::user()->id==1)
                        <div>
                            <label for="company_id" class="ui-label">Company</label>
                            <select name="company_id" id="company_id" class="ui-select">
                                <option value="">Select a company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @error('company_id') <p class="ui-error">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div>
                        <label for="category_id" class="ui-label">Category</label>
                        <select name="category_id" id="category_id" class="ui-select">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="ui-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="price" class="ui-label">Price</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" class="ui-input" placeholder="Example: 6500">
                        @error('price') <p class="ui-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="image" class="ui-label">Cover image</label>
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg" class="ui-input">
                        @error('image') <p class="ui-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-wrap justify-end gap-3 border-t border-slate-200 pt-6">
                    <a href="{{ route('books.index') }}" class="ui-button-secondary">Cancel</a>
                    <button id="sendButton" type="submit" class="ui-button-primary">Save Book</button>
                </div>
            </form>
        </section>

        <aside class="surface p-6">
            <h3 class="text-lg font-semibold text-slate-900">Publishing Tips</h3>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                <li>Use a clear book title that matches the exam or course level.</li>
                <li>Write a concise description so learners understand the value quickly.</li>
                <li>Choose the correct category and company to keep search and reporting clean.</li>
            </ul>
            <div id="filelist" class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-500"></div>
        </aside>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('New Sub-Section') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form action="{{ route('question-type-sub-sections.store') }}" method="POST">
                    @csrf

                    <div class="space-y-4">
                        <!-- Question Type Field -->
                        <div>
                            <label for="question_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Question Type <span class="text-red-500">*</span>
                            </label>
                            <select
                                name="question_type_id"
                                id="question_type_id"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Select Question Type</option>
                                @foreach($questionTypes as $qt)
                                    <option value="{{ $qt->id }}" {{ (old('question_type_id', $questionTypeId) == $qt->id) ? 'selected' : '' }}>
                                        {{ $qt->type }} ({{ $qt->typecode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('question_type_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                placeholder="e.g., Part 1, Section A, etc."
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Count Field -->
                        <div>
                            <label for="count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Count
                            </label>
                            <input
                                type="number"
                                name="count"
                                id="count"
                                value="{{ old('count', 0) }}"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Number of questions to display for this sub-section</p>
                            @error('count')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Field -->
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Order
                            </label>
                            <input
                                type="number"
                                name="order"
                                id="order"
                                value="{{ old('order', 0) }}"
                                min="0"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                placeholder="0"
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Display order (lower numbers appear first)</p>
                            @error('order')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Enter description for this sub-section..."
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <a href="{{ route('question-type-sub-sections.index', ['question_type_id' => $questionTypeId]) }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-all">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                            Create Sub-Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sub-Section Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $questionTypeSubSection->name }}
                    </h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('question-type-sub-sections.edit', $questionTypeSubSection) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-all">
                            Edit
                        </a>
                        <form action="{{ route('question-type-sub-sections.destroy', $questionTypeSubSection) }}" 
                              method="POST" 
                              class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this sub-section?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-all">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Question Type
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">
                            <a href="{{ route('question-type-sub-sections.index', ['question_type_id' => $questionTypeSubSection->question_type_id]) }}" 
                               class="text-primary-600 hover:text-primary-800">
                                {{ $questionTypeSubSection->questionType->type }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Order
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionTypeSubSection->order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Count
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionTypeSubSection->count ?? 0 }}</p>
                    </div>

                    @if($questionTypeSubSection->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Description
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionTypeSubSection->description }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Number of Questions
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionTypeSubSection->pages->count() }}</p>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('question-type-sub-sections.index', ['question_type_id' => $questionTypeSubSection->question_type_id]) }}" 
                       class="text-primary-600 hover:text-primary-800">
                        ← Back to Sub-Sections
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

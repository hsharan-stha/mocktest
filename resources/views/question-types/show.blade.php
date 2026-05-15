<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Question Type Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Type
                        </label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $questionType->type }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Type Code
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">
                            <span class="px-2 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $questionType->typecode }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Count
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionType->count }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Order
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionType->order }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Description
                        </label>
                        <p class="text-gray-900 dark:text-gray-100">{{ $questionType->description ?? 'N/A' }}</p>
                    </div>

                    <div class="flex justify-end mt-6 space-x-3">
                        <a href="{{ route('question-types.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-all">
                            Back to List
                        </a>
                        <a href="{{ route('question-types.edit', $questionType) }}" 
                           class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


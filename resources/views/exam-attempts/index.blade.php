<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exam Attempts') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search and Filter Form -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg p-6 mb-4">
                <form method="GET" action="{{ route('exam-attempts.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by name or email..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <select name="book_id" class="px-4 py-2 border border-gray-300 rounded-md">
                            <option value="">All Books</option>
                            @foreach(\App\Models\Book::all() as $book)
                                <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                                    {{ $book->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Filter
                        </button>
                        <a href="{{ route('exam-attempts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto text-gray-900 dark:text-gray-100">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-800">
                                <th class="px-4 py-3 text-left">ID</th>
                                <th class="px-4 py-3 text-left">Examinee Name</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-left">Book</th>
                                <th class="px-4 py-3 text-center">Score</th>
                                <th class="px-4 py-3 text-center">Correct/Total</th>
                                <th class="px-4 py-3 text-left">Started At</th>
                                <th class="px-4 py-3 text-left">Completed At</th>
                                <th class="px-4 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($examAttempts as $attempt)
                            <tr class="border-t hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3">#{{ $attempt->id }}</td>
                                <td class="px-4 py-3">{{ $attempt->examinee_name }}</td>
                                <td class="px-4 py-3">{{ $attempt->examinee_email }}</td>
                                <td class="px-4 py-3">{{ $attempt->book->title ?? 'N/A' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-bold {{ $attempt->score_percentage >= 70 ? 'text-green-600' : ($attempt->score_percentage >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($attempt->score_percentage, 2) }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}
                                </td>
                                <td class="px-4 py-3">{{ $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i') : 'In Progress' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('exam-attempts.show', $attempt) }}" 
                                       class="text-indigo-600 hover:text-indigo-800" 
                                       title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                    No exam attempts found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t">
                    {{ $examAttempts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

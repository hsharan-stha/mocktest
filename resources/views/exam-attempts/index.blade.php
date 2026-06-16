<x-app-layout>
    <x-slot name="header">
        Exam Attempts
    </x-slot>

    <div class="page-stack">
        @if (session('success'))
            <div class="ui-alert-success">{{ session('success') }}</div>
        @endif

        <section class="data-card">
            <div class="data-card-header">
                <div>
                    <h2 class="section-title">Attempt Overview</h2>
                    <p class="section-copy mt-2">Filter by learner or book, review results quickly, and drill into the full attempt detail page.</p>
                </div>
            </div>

            <div class="border-b border-slate-200 px-5 py-4">
                <form method="GET" action="{{ route('exam-attempts.index') }}" class="grid gap-3 md:grid-cols-[1.4fr_1fr_auto]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="ui-input">
                    <select name="book_id" class="ui-select">
                        <option value="">All Books</option>
                        @foreach(\App\Models\Book::all() as $book)
                            <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->name ?? $book->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="flex gap-3">
                        <button type="submit" class="ui-button-primary">Filter</button>
                        <a href="{{ route('exam-attempts.index') }}" class="ui-button-secondary">Clear</a>
                    </div>
                </form>
            </div>

            <div class="data-grid">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Examinee</th>
                            <th>Book</th>
                            <th>Score</th>
                            <th>Correct / Total</th>
                            <th>Started</th>
                            <th>Completed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examAttempts as $attempt)
                            <tr>
                                <td>#{{ $attempt->id }}</td>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $attempt->examinee_name }}</div>
                                    <div class="mt-1 text-xs text-slate-500">{{ $attempt->examinee_email }}</div>
                                </td>
                                <td>{{ $attempt->book->name ?? $attempt->book->title ?? 'N/A' }}</td>
                                <td>
                                    <span class="ui-badge {{ ($attempt->score_percentage ?? 0) >= 70 ? 'ui-badge-success' : (($attempt->score_percentage ?? 0) >= 50 ? 'ui-badge-accent' : 'ui-badge-danger') }}">
                                        {{ number_format($attempt->score_percentage, 2) }}%
                                    </span>
                                </td>
                                <td>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td>
                                <td>{{ $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>{{ $attempt->completed_at ? $attempt->completed_at->format('Y-m-d H:i') : 'In Progress' }}</td>
                                <td>
                                    <a href="{{ route('exam-attempts.show', $attempt) }}" class="ui-button-secondary px-4 py-2 text-xs">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="py-10 text-center text-slate-500">No exam attempts found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $examAttempts->links() }}
            </div>
        </section>
    </div>
</x-app-layout>

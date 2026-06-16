<x-app-layout>
    <x-slot name="header">
        {{ app()->getLocale() === 'jp' ? 'ダッシュボード' : 'Dashboard' }}
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="space-y-6">
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="stat-card">
                    <p class="stat-label">{{ app()->getLocale() === 'jp' ? '総書籍数' : 'Total Books' }}</p>
                    <p class="stat-value">{{ \App\Models\Book::count() }}</p>
                    <p class="stat-meta">Published learning resources in your catalog.</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">{{ app()->getLocale() === 'jp' ? '総購入数' : 'Total Purchases' }}</p>
                    <p class="stat-value">{{ \App\Models\Purchase::count() }}</p>
                    <p class="stat-meta">Orders placed across the platform.</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">{{ app()->getLocale() === 'jp' ? '総ユーザー数' : 'Total Users' }}</p>
                    <p class="stat-value">{{ \App\Models\User::count() }}</p>
                    <p class="stat-meta">Registered learners and admins.</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">{{ app()->getLocale() === 'jp' ? '受験回数' : 'Exam Attempts' }}</p>
                    <p class="stat-value">{{ \App\Models\ExamAttempt::count() }}</p>
                    <p class="stat-meta">Started mock exam sessions.</p>
                </div>
            </div>

            <div class="data-card">
                <div class="data-card-header">
                    <div>
                        <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '最近の購入' : 'Recent Purchases' }}</h2>
                        <p class="section-copy mt-2">{{ app()->getLocale() === 'jp' ? '最新の注文と支払い状況をすばやく確認できます。' : 'A quick view into the latest orders and payment activity.' }}</p>
                    </div>
                    <a href="{{ route('purchase.list') }}" class="ui-button-secondary">{{ app()->getLocale() === 'jp' ? '購入管理' : 'Manage Purchases' }}</a>
                </div>
                <div class="data-grid">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Purchase ID</th>
                                <th>User</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse (\App\Models\Purchase::with('user')->latest()->take(5)->get() as $purchase)
                                <tr>
                                    <td>{{ $purchase->purchase_date }}</td>
                                    <td>{{ $purchase->user->name ?? '-' }}</td>
                                    <td>Rs.{{ number_format($purchase->total_amount) }}</td>
                                    <td>
                                        <span class="ui-badge {{ $purchase->is_paid ? 'ui-badge-success' : 'ui-badge-accent' }}">
                                            {{ $purchase->is_paid ? 'Paid' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $purchase->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="py-8 text-center text-slate-500">No purchases yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <div class="surface p-6">
                <h2 class="section-title">{{ app()->getLocale() === 'jp' ? 'クイック操作' : 'Quick Actions' }}</h2>
                <div class="mt-5 grid gap-3">
                    <a href="{{ route('books.create') }}" class="ui-button-primary w-full">{{ app()->getLocale() === 'jp' ? '書籍追加' : 'Add Book' }}</a>
                    <a href="{{ route('books.index') }}" class="ui-button-secondary w-full">{{ app()->getLocale() === 'jp' ? 'ページ管理' : 'Manage Pages' }}</a>
                    <a href="{{ route('question-types.index') }}" class="ui-button-secondary w-full">{{ app()->getLocale() === 'jp' ? '問題タイプ' : 'Question Types' }}</a>
                    @if (Auth::user() && Auth::user()->role_id == 1)
                        <a href="{{ route('categories.index') }}" class="ui-button-secondary w-full">{{ app()->getLocale() === 'jp' ? 'カテゴリー管理' : 'Manage Categories' }}</a>
                        <a href="{{ route('users.index') }}" class="ui-button-secondary w-full">{{ app()->getLocale() === 'jp' ? 'ユーザー管理' : 'Manage Users' }}</a>
                    @endif
                </div>
            </div>

            <div class="surface p-6">
                <h2 class="section-title">{{ app()->getLocale() === 'jp' ? '最近の受験履歴' : 'Recent Exam Attempts' }}</h2>
                <div class="mt-4 space-y-4">
                    @forelse (\App\Models\ExamAttempt::with('book')->latest()->take(5)->get() as $attempt)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $attempt->examinee_name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $attempt->book->name ?? 'Book N/A' }}</p>
                                </div>
                                <span class="ui-badge ui-badge-brand">{{ number_format($attempt->score_percentage ?? 0, 1) }}%</span>
                            </div>
                            <p class="mt-3 text-xs text-slate-500">{{ $attempt->created_at }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No exam attempts recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</x-app-layout>

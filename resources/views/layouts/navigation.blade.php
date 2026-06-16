<aside
    class="admin-sidebar h-screen -translate-x-full overflow-hidden lg:translate-x-0"
    :class="{ 'translate-x-0': sidebarOpen }"
>
    <div class="flex items-center justify-between border-b border-white/10 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-white shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3.75 3v18h18M7.5 14.25l3-3 2.25 2.25L16.5 9" />
                </svg>
            </div>
            <div>
                <p class="text-xs uppercase tracking-[0.25em] text-blue-200">Learning SaaS</p>
                <p class="text-lg font-semibold text-white">{{ config('app.name', 'Laravel') }}</p>
            </div>
        </a>
        <button type="button" @click="sidebarOpen = false" class="rounded-xl p-2 text-slate-300 hover:bg-white/10 hover:text-white lg:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div class="flex-1 space-y-6 overflow-y-auto px-4 py-6">
        <div class="space-y-2">
            <p class="px-4 text-xs font-semibold uppercase tracking-[0.22em] text-blue-200/80">Overview</p>
            <a href="{{ route('dashboard') }}" class="admin-sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? 'ダッシュボード' : 'Dashboard' }}</span>
            </a>
            <a href="{{ route('books.index') }}" class="admin-sidebar-link {{ request()->routeIs('books.*') || request()->routeIs('pages.*') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? '書籍とページ' : 'Books & Pages' }}</span>
            </a>
            <a href="{{ route('purchase.list') }}" class="admin-sidebar-link {{ request()->routeIs('purchase.*') || request()->routeIs('purchases.*') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? '購入管理' : 'Purchases' }}</span>
            </a>
            <a href="{{ route('exam-attempts.index') }}" class="admin-sidebar-link {{ request()->routeIs('exam-attempts.*') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? '受験履歴' : 'Exam Attempts' }}</span>
            </a>
        </div>

        <div class="space-y-2">
            <p class="px-4 text-xs font-semibold uppercase tracking-[0.22em] text-blue-200/80">Catalog</p>
            <a href="{{ route('question-types.index') }}" class="admin-sidebar-link {{ request()->routeIs('question-types.*') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? '問題タイプ' : 'Question Types' }}</span>
            </a>
            <a href="{{ route('question-type-sub-sections.index') }}" class="admin-sidebar-link {{ request()->routeIs('question-type-sub-sections.*') ? 'active' : '' }}">
                <span>{{ app()->getLocale() === 'jp' ? 'サブセクション' : 'Sub Sections' }}</span>
            </a>
            @if (Auth::user() && Auth::user()->role_id == 1)
                <a href="{{ route('categories.index') }}" class="admin-sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <span>{{ app()->getLocale() === 'jp' ? 'カテゴリー' : 'Categories' }}</span>
                </a>
                <a href="{{ route('companies.index') }}" class="admin-sidebar-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                    <span>{{ app()->getLocale() === 'jp' ? '会社' : 'Companies' }}</span>
                </a>
                <a href="{{ route('users.index') }}" class="admin-sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span>{{ app()->getLocale() === 'jp' ? 'ユーザー' : 'Users' }}</span>
                </a>
            @endif
        </div>
    </div>

    <div class="border-t border-white/10 p-4">
        <div class="rounded-3xl bg-white/10 p-4">
            <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'User' }}</p>
            <p class="mt-1 text-xs text-blue-100">{{ Auth::user()->email ?? '' }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('profile.edit') }}" class="inline-flex rounded-xl bg-white/10 px-3 py-2 text-xs font-semibold text-white hover:bg-white/20">
                    {{ app()->getLocale() === 'jp' ? 'プロフィール' : 'Profile' }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex rounded-xl bg-red-500/20 px-3 py-2 text-xs font-semibold text-red-100 hover:bg-red-500/30">
                        {{ app()->getLocale() === 'jp' ? 'ログアウト' : 'Log Out' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<div
    class="fixed inset-0 z-30 hidden bg-slate-950/40 backdrop-blur-sm lg:hidden"
    :class="{ 'block': sidebarOpen }"
    @click="sidebarOpen = false"
></div>

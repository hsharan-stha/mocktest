<x-app-layout>
    <x-slot name="header">
        Purchases
    </x-slot>

    <div class="page-stack">
        @if (session('success'))
            <div class="ui-alert-success">{{ session('success') }}</div>
        @endif

        <section class="data-card">
            <div class="data-card-header">
                <div>
                    <h2 class="section-title">Purchase Management</h2>
                    <p class="section-copy mt-2">Search by purchase, customer, or date, then update payment status without changing the existing backend flow.</p>
                </div>
            </div>

            <div class="border-b border-slate-200 px-5 py-4">
                <form action="{{ route('purchase.list') }}" method="GET" class="grid gap-3 md:grid-cols-4">
                    <input type="text" name="purchase_id" value="{{ request('purchase_id') }}" placeholder="Search by purchase ID" class="ui-input" />
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="Search by user name" class="ui-input" />
                    <input type="text" name="email" value="{{ request('email') }}" placeholder="Search by email" class="ui-input" />
                    <input type="date" name="created_date" value="{{ request('created_date') }}" class="ui-input" />
                    <div class="flex gap-3 md:col-span-4">
                        <button type="submit" class="ui-button-primary">Search</button>
                        <a href="{{ route('purchase.list') }}" class="ui-button-secondary">Clear</a>
                    </div>
                </form>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($purchases as $purchase)
                    <div
                        x-data="{ original: {{ $purchase->is_paid ? 'true' : 'false' }}, current: {{ $purchase->is_paid ? 'true' : 'false' }}, expanded: false }"
                        class="px-5 py-5"
                    >
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div class="grid flex-1 gap-4 md:grid-cols-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Purchase</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $purchase->purchase_date }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Customer</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $purchase->user->name ?? '-' }}</p>
                                    <p class="text-sm text-slate-500">{{ $purchase->user->email ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Total</p>
                                    <p class="mt-1"><span class="price-chip">Rs.{{ number_format($purchase->total_amount) }}</span></p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Created</p>
                                    <p class="mt-1 text-sm text-slate-700">{{ $purchase->created_at }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <button @click="expanded = !expanded" class="ui-button-secondary px-4 py-2 text-xs" x-text="expanded ? 'Hide Details' : 'Show Details'"></button>
                                <span class="ui-badge" :class="current ? 'ui-badge-success' : 'ui-badge-accent'" x-text="current ? 'Paid' : 'Unpaid'"></span>
                                <label class="relative inline-flex cursor-pointer items-center">
                                    <input type="checkbox" x-model="current" class="peer sr-only">
                                    <div class="h-7 w-12 rounded-full bg-slate-300 transition peer-checked:bg-blue-600"></div>
                                    <div class="absolute left-1 top-1 h-5 w-5 rounded-full bg-white transition peer-checked:translate-x-5"></div>
                                </label>
                                <form action="{{ route('purchase.update') }}" method="POST" x-show="original !== current">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_paid" :value="current ? 1 : 0">
                                    <input type="hidden" name="id" value="{{ $purchase->id }}">
                                    @foreach (request()->query() as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <button type="submit" class="ui-button-primary px-4 py-2 text-xs">Save</button>
                                </form>
                            </div>
                        </div>

                        <div x-show="expanded" class="mt-5 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <h3 class="text-lg font-semibold text-slate-900">Purchase Details</h3>
                            <div class="mt-4 space-y-3">
                                @foreach ($purchase->details as $detail)
                                    <div class="flex items-center justify-between gap-4 rounded-2xl bg-white px-4 py-3">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $detail->book->name ?? 'Unknown Book' }}</p>
                                            <p class="mt-1 text-sm text-slate-500">Quantity: {{ number_format($detail->quantity) }}</p>
                                        </div>
                                        <span class="price-chip">Rs.{{ number_format($detail->price) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-slate-500">No purchases found.</div>
                @endforelse
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $purchases->onEachSide(1)->links('vendor.pagination.tailwind') }}
            </div>
        </section>
    </div>
</x-app-layout>

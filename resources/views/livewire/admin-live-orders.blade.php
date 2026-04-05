<div class="min-h-dvh gradient-dark text-white/95" wire:poll.4s>

    {{-- ── Header ── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-brand flex items-center justify-center">
                    <span class="text-sm font-bold font-[Outfit]">K</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">
                        <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">{{ __('Live Orders') }}</span>
                    </h1>
                    <p class="text-xs text-white/70 flex items-center gap-1.5">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        {{ __('Auto-refreshes every 4 seconds') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-sm font-medium transition-all">
                ← {{ __('Back to Dashboard') }}
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 pb-24 space-y-6">

        {{-- ── KPI Strip ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="glass rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-orange-400">{{ $counts['pending'] }}</div>
                <div class="text-xs text-white/70 mt-1">🕐 {{ __('Pending Dispatch') }}</div>
            </div>
            <div class="glass rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-violet-500">{{ $counts['active'] }}</div>
                <div class="text-xs text-white/70 mt-1">⚡ {{ __('Active') }}</div>
            </div>
            <div class="glass rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-green-400">{{ $counts['completed'] }}</div>
                <div class="text-xs text-white/70 mt-1">✅ {{ __('Completed') }}</div>
            </div>
            <div class="glass rounded-2xl p-4 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-white/60">{{ $counts['total'] }}</div>
                <div class="text-xs text-white/70 mt-1">📋 {{ __('Total Orders') }}</div>
            </div>
        </div>

        {{-- ── Filter Tabs ── --}}
        <div class="flex items-center gap-1 glass rounded-xl p-1 w-fit">
            @foreach(['pending' => '🕐 Pending', 'active' => '⚡ Active', 'completed' => '✅ Completed', 'all' => '📋 All'] as $val => $label)
                <button wire:click="$set('filter', '{{ $val }}')"
                    class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all
                    {{ $filter === $val ? 'bg-violet-600 text-white shadow' : 'text-white/60 hover:text-white' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- ── Live Orders Feed ── --}}
        @if($orders->isEmpty())
            <div class="glass rounded-3xl p-16 text-center">
                <div class="text-5xl mb-4">🎉</div>
                <p class="text-white/60">{{ __('No orders in this category.') }}</p>
            </div>
        @else
        <div class="space-y-3">
            @foreach($orders as $order)
            @php
                $statusColors = [
                    'pending'     => 'border-yellow-400 bg-yellow-500/5',
                    'confirmed'   => 'border-blue-400 bg-blue-500/5',
                    'assigned'    => 'border-indigo-400 bg-indigo-500/5',
                    'en_route'    => 'border-cyan-400 bg-cyan-500/5',
                    'in_progress' => 'border-orange-400 bg-orange-500/5',
                    'completed'   => 'border-green-400 bg-green-500/5',
                    'cancelled'   => 'border-red-400 bg-red-500/5',
                ];
                $badgeColors = [
                    'pending'     => 'bg-yellow-500/10 text-yellow-400',
                    'confirmed'   => 'bg-blue-500/10 text-blue-400',
                    'assigned'    => 'bg-indigo-500/10 text-indigo-400',
                    'en_route'    => 'bg-cyan-500/10 text-cyan-400',
                    'in_progress' => 'bg-orange-500/10 text-orange-400',
                    'completed'   => 'bg-green-500/10 text-green-400',
                    'cancelled'   => 'bg-red-500/10 text-red-400',
                ];
                $statusVal = $order->status->value;
                $elapsed = $order->created_at->diffForHumans();
            @endphp
            <div class="glass bg-white/95 rounded-2xl border-l-4 {{ $statusColors[$statusVal] ?? 'border-slate-500' }} overflow-hidden transition-all shadow-md shadow-slate-200/50">
                <div class="px-5 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                    {{-- Left: Order Info --}}
                    <div class="flex items-start gap-4 min-w-0">
                        {{-- Status dot --}}
                        <div class="mt-1 shrink-0">
                            @if(in_array($statusVal, ['assigned', 'in_progress', 'en_route']))
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
                                        {{ $statusVal === 'in_progress' ? 'bg-orange-400' : 'bg-indigo-400' }}"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3
                                        {{ $statusVal === 'in_progress' ? 'bg-orange-500' : 'bg-indigo-500' }}"></span>
                                </span>
                            @else
                                <span class="inline-flex h-3 w-3 rounded-full
                                    {{ $statusVal === 'completed' ? 'bg-green-500' : ($statusVal === 'pending' ? 'bg-yellow-400' : 'bg-red-500') }}"></span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-900 font-[Outfit]">{{ $order->order_number }}</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-lg font-bold {{ $badgeColors[$statusVal] ?? '' }}">
                                    {{ $order->status->label() }}
                                </span>
                                @if($order->urgency?->value === 'urgent')
                                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-600 font-bold animate-pulse border border-red-200">⚡ URGENT</span>
                                @endif
                            </div>

                            {{-- Service Name --}}
                            @if($order->items->count())
                                <div class="text-sm text-violet-700 font-bold mt-1">
                                    🔧 {{ app()->getLocale() === 'ar'
                                        ? ($order->items->first()->serviceOption?->name_ar ?? '—')
                                        : ($order->items->first()->serviceOption?->name_en ?? '—') }}
                                    @if($order->items->count() > 1)
                                        <span class="text-slate-400 text-[10px] font-medium">+{{ $order->items->count() - 1 }} more</span>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center gap-3 mt-2 flex-wrap text-[11px] font-semibold text-slate-500">
                                <span class="flex items-center gap-1">👤 <span class="text-slate-700">{{ $order->client?->name ?? 'Guest' }}</span></span>
                                <span class="flex items-center gap-1">📞 <span class="text-slate-700">{{ $order->client?->phone ?? '—' }}</span></span>
                                <span class="flex items-center gap-1">📍 <span class="text-slate-700">{{ Str::limit($order->address, 35) }}</span></span>
                                <span class="flex items-center gap-1">🕐 <span class="text-slate-700">{{ $elapsed }}</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Technician + Price + Actions --}}
                    <div class="flex items-center gap-3 shrink-0 flex-wrap">
                        {{-- Technician badge --}}
                        @if($order->technician)
                            <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-violet-500/10">
                                <div class="w-6 h-6 rounded-lg bg-violet-600 flex items-center justify-center text-white text-[10px] font-bold">
                                    {{ strtoupper(substr($order->technician->name, 0, 2)) }}
                                </div>
                                <span class="text-xs text-violet-400 font-semibold">{{ $order->technician->name }}</span>
                            </div>
                        @else
                            <span class="text-xs px-3 py-1.5 rounded-xl bg-yellow-500/10 text-yellow-400 font-semibold">
                                ⏳ {{ __('Unassigned') }}
                            </span>
                        @endif

                        {{-- Price --}}
                        <span class="text-base font-bold font-[Outfit] text-orange-400">
                            {{ number_format($order->total, 2) }} SAR
                        </span>

                        {{-- Action buttons --}}
                        <div class="flex items-center gap-1.5">
                            
                            {{-- View / Edit Button --}}
                            <a href="{{ route('admin.orders.detail', $order->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-blue-500/10 text-blue-500 hover:bg-blue-500/20 text-xs font-semibold transition-all shadow shadow-blue-500/10">
                                📝 {{ __('View / Edit') }}
                            </a>

                            @if($order->status === \App\Enums\OrderStatus::PENDING && !$order->technician_id)
                                <button wire:click="forceDispatch({{ $order->id }})"
                                    class="px-3 py-1.5 rounded-lg bg-green-500/10 text-green-400 hover:bg-green-500/20 text-xs font-semibold transition-all whitespace-nowrap">
                                    🚀 {{ __('Dispatch') }}
                                </button>
                            @endif
                            @if(!in_array($order->status->value, ['completed', 'cancelled']))
                                <button wire:click="cancelOrder({{ $order->id }})"
                                    wire:confirm="إلغاء هذا الطلب؟"
                                    class="px-3 py-1.5 rounded-lg bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-xs font-semibold transition-all">
                                    ✕ {{ __('Cancel') }}
                                </button>
                            @endif

                            {{-- Delete Button --}}
                            <button wire:click="deleteOrder({{ $order->id }})"
                                wire:confirm="{{ __('WARNING: This will permanently delete the order from the database. Are you sure?') }}"
                                class="px-3 py-1.5 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500/20 text-xs font-semibold transition-all">
                                🗑
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar for active orders --}}
                @if(in_array($statusVal, ['assigned', 'en_route', 'in_progress']))
                @php
                    $progress = match($statusVal) {
                        'assigned'    => 25,
                        'en_route'    => 55,
                        'in_progress' => 80,
                        default       => 0,
                    };
                @endphp
                <div class="h-1 bg-slate-700/50">
                    <div class="h-1 transition-all duration-1000
                        {{ $statusVal === 'in_progress' ? 'bg-orange-400' : 'bg-indigo-400' }}"
                        style="width: {{ $progress }}%"></div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $orders->links() }}
        </div>
        @endif

    </main>
</div>

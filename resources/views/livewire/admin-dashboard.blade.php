<div class="min-h-dvh gradient-dark text-white/95" wire:poll.10s>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 pb-24">
        {{-- ── Stats Grid ────────────────────────────── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            {{-- Revenue --}}
            <div class="glass rounded-2xl p-5 fade-in">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xs text-green-400 bg-green-500/10 px-2 py-0.5 rounded-lg">+12%</span>
                </div>
                <div class="text-2xl font-bold font-[Outfit] text-green-400 counter-spin">{{ number_format($totalRevenue, 0) }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Total Revenue') }} ({{ __('SAR') }})</div>
            </div>

            {{-- Total Orders --}}
            <div class="glass rounded-2xl p-5 fade-in" style="animation-delay: 100ms">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-bold font-[Outfit] text-violet-600 counter-spin">{{ number_format($totalOrders) }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Total Orders') }}</div>
            </div>

            {{-- Active Orders --}}
            <div class="glass rounded-2xl p-5 fade-in" style="animation-delay: 200ms">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    @if($activeOrders > 0)
                        <span class="badge-pulse text-xs text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded-lg">{{ __('Live') }}</span>
                    @endif
                </div>
                <div class="text-2xl font-bold font-[Outfit] text-orange-400 counter-spin">{{ number_format($activeOrders) }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Active Orders') }}</div>
            </div>

            {{-- Users --}}
            <div class="glass rounded-2xl p-5 fade-in" style="animation-delay: 300ms">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-bold font-[Outfit] text-purple-400 counter-spin">
                    {{ $totalTechnicians + $totalClients }}
                </div>
                <div class="text-xs text-white/70 mt-1">{{ $totalTechnicians }} {{ __('technicians') }} · {{ $totalClients }} {{ __('clients') }}</div>
            </div>
        </div>

        {{-- ── Pending Orders Alert Badge ── --}}
        @if($pendingOrders > 0)
        <div class="mb-6 p-4 rounded-2xl bg-orange-500/10 border border-orange-500/30 flex items-center gap-3">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
            </span>
            <p class="text-orange-400 font-semibold text-sm">
                <span class="font-bold">{{ $pendingOrders }}</span> {{ __('orders waiting for technician dispatch') }}
            </p>
        </div>
        @endif

        {{-- ── Quick Actions ─────────────────────────── --}}
        <div class="glass rounded-2xl p-5 mb-8 fade-in" style="animation-delay: 350ms">
            <h3 class="text-sm font-semibold text-white/80 mb-4">{{ __('Quick Actions') }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 p-3 rounded-xl bg-violet-500/10 text-violet-700 hover:bg-orange-500/10 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('Terms & Policies') }}
                </a>
                <button class="flex items-center gap-2 p-3 rounded-xl bg-green-500/10 text-green-300 hover:bg-green-500/20 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('Analytics') }}
                </button>
                <a href="{{ route('admin.services') }}" class="flex items-center gap-2 p-3 rounded-xl bg-orange-500/10 text-orange-400 font-bold hover:bg-orange-500/20 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Manage Services') }}
                </a>
                <a href="{{ route('admin.agents') }}" class="flex items-center gap-2 p-3 rounded-xl bg-red-500/10 text-red-300 hover:bg-red-500/20 transition-all text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Agent Management') }}
                </a>
                <a href="{{ route('admin.orders.live') }}" class="flex items-center gap-2 p-3 rounded-xl bg-green-500/10 text-green-400 font-bold hover:bg-green-500/20 transition-all text-sm relative">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('Live Orders') }}
                    @if($pendingOrders > 0)
                        <span class="ml-auto px-1.5 py-0.5 bg-orange-500 text-white text-[10px] rounded-full font-bold animate-pulse">{{ $pendingOrders }}</span>
                    @endif
                </a>
            </div>
        </div>

        {{-- ── Recent Orders (clickable) ── --}}
        <div class="glass rounded-2xl p-5 mb-8 fade-in" style="animation-delay: 400ms">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white/80">{{ __('Recent Orders') }}</h3>
                <a href="{{ route('admin.orders.live') }}" class="text-xs text-violet-500 hover:text-violet-700 font-semibold">
                    {{ __('View all') }} →
                </a>
            </div>
            @if($recentOrders->isEmpty())
                <p class="text-white/70 text-sm text-center py-8">{{ __('No orders yet') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.detail', $order->id) }}"
                           class="flex items-center justify-between p-3 rounded-xl bg-white/80 border border-slate-100/50 hover:bg-violet-50 hover:border-violet-200 transition-all group cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-violet-500/10 flex items-center justify-center text-xs font-bold text-violet-600">
                                    #{{ $order->id }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium">{{ $order->order_number }}</div>
                                    <div class="text-xs text-white/70">{{ $order->client->name ?? 'N/A' }} · {{ $order->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <div class="text-sm font-semibold">{{ number_format($order->total, 0) }} {{ __('SAR') }}</div>
                                    <span class="inline-block text-[10px] px-2 py-0.5 rounded-lg
                                        {{ $order->status === \App\Enums\OrderStatus::COMPLETED ? 'bg-green-500/10 text-green-400' :
                                           ($order->status === \App\Enums\OrderStatus::PENDING ? 'bg-yellow-500/10 text-yellow-400' :
                                           ($order->status === \App\Enums\OrderStatus::CANCELLED ? 'bg-red-500/10 text-red-400' :
                                           'bg-violet-500/10 text-violet-600')) }}">
                                        {{ $order->status->label() }}
                                    </span>
                                </div>
                                <svg class="w-4 h-4 text-white/50 group-hover:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Audit Logs ─────────────────────────── --}}
        <div class="glass rounded-2xl p-5 fade-in" style="animation-delay: 450ms">
            <h3 class="text-sm font-semibold text-white/80 mb-4">{{ __('Audit Trail') }}</h3>
            @if($recentLogs->isEmpty())
                <p class="text-white/70 text-sm text-center py-8">{{ __('No audit logs yet') }}</p>
            @else
                <div class="space-y-3">
                    @foreach($recentLogs as $log)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-white/80 border border-slate-100/50">
                            <div class="w-8 h-8 rounded-lg bg-white/50/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium">{{ $log->action }}</div>
                                <div class="text-xs text-white/70">{{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
</div>

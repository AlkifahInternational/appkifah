<div class="min-h-dvh gradient-dark text-white/95">
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center text-sm font-bold text-cyan-400">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">{{ __('My Bookings') }}</h1>
                    <p class="text-xs text-white/70">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-sm font-semibold transition-all hover:scale-105 active:scale-95">
                    + {{ __('New Booking') }}
                </a>
                <button wire:click="logout" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-300 hover:bg-red-500/20 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-lg mx-auto px-4 py-6 pb-24">
        {{-- Active Orders --}}
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-white/80 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                {{ __('Active Orders') }}
            </h3>
            @forelse($activeOrders as $order)
                <div class="glass rounded-2xl p-4 mb-3 fade-in">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold">{{ $order->order_number }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-lg bg-violet-500/10 text-violet-600">{{ $order->status->label() }}</span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="flex items-center gap-1 mb-3">
                        @php
                            $steps = ['confirmed', 'assigned', 'en_route', 'in_progress'];
                            $currentStep = array_search($order->status->value, $steps);
                        @endphp
                        @foreach($steps as $i => $step)
                            <div class="flex-1 h-1.5 rounded-full {{ $i <= ($currentStep ?? -1) ? 'bg-violet-500' : 'bg-white/90 border border-slate-100' }} transition-all"></div>
                        @endforeach
                    </div>

                    @if($order->technician)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white/80 border border-slate-100/50">
                            <div class="w-9 h-9 rounded-lg gradient-brand flex items-center justify-center text-sm font-bold">
                                {{ substr($order->technician->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium">{{ $order->technician->name }}</div>
                                <div class="text-xs text-white/70">{{ __('Technician assigned') }}</div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-200">
                        <span class="text-xs text-white/70">{{ $order->created_at->diffForHumans() }}</span>
                        <span class="text-sm font-bold text-violet-600">{{ number_format($order->total, 0) }} {{ __('SAR') }}</span>
                    </div>
                </div>
            @empty
                <div class="glass rounded-2xl p-8 text-center">
                    <div class="text-4xl mb-3">📦</div>
                    <p class="text-sm text-white/70 mb-4">{{ __('No active orders') }}</p>
                    <a href="/" class="inline-block px-6 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-sm font-semibold transition-all hover:scale-105">
                        {{ __('Book a Service') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Past Orders --}}
        <div>
            <h3 class="text-sm font-semibold text-white/80 mb-4">{{ __('Past Orders') }}</h3>
            @forelse($pastOrders as $order)
                <div class="flex items-center justify-between p-3 rounded-xl bg-white/80 border border-slate-100/50 mb-2">
                    <div>
                        <div class="text-sm font-medium">{{ $order->order_number }}</div>
                        <div class="text-xs text-white/70">{{ $order->completed_at?->format('M d, Y') ?? $order->cancelled_at?->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right rtl:text-left">
                        <div class="text-sm font-semibold">{{ number_format($order->total, 0) }} {{ __('SAR') }}</div>
                        <span class="text-[10px] {{ $order->status->value === 'completed' ? 'text-green-400' : 'text-red-400' }}">
                            {{ $order->status->label() }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-white/70 text-center py-4">{{ __('No past orders') }}</p>
            @endforelse
        </div>
    </main>
</div>

<div class="min-h-dvh gradient-dark text-white/95">
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-6 0a1 1 0 001-1m-6 0a1 1 0 001 1"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">
                        <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">{{ __('Dispatch Center') }}</span>
                    </h1>
                    <p class="text-xs text-white/70">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" class="w-10 h-10 rounded-xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
                <button wire:click="logout" class="px-4 py-2 rounded-xl bg-red-500/10 text-red-300 hover:bg-red-500/20 text-sm font-medium transition-all">
                    {{ __('Logout') }}
                </button>
            </div>
        </div>
    </header>

    @if (session()->has('message'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
            <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-4">
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 pb-24">
        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="glass rounded-2xl p-5 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-yellow-400">{{ $pendingOrders->count() }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Pending') }}</div>
            </div>
            <div class="glass rounded-2xl p-5 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-violet-600">{{ $activeOrders->count() }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Active') }}</div>
            </div>
            <div class="glass rounded-2xl p-5 text-center">
                <div class="text-2xl font-bold font-[Outfit] text-green-400">{{ $availableTechnicians->count() }}</div>
                <div class="text-xs text-white/70 mt-1">{{ __('Available Techs') }}</div>
            </div>
        </div>

        {{-- Pending Orders (with urgent alert) --}}
        <div class="glass rounded-2xl p-5 mb-8">
            <h3 class="text-sm font-semibold text-white/80 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                {{ __('Pending Orders') }}
            </h3>
            @forelse($pendingOrders as $order)
                <div class="flex items-center justify-between p-4 rounded-xl {{ $order->isUrgent() ? 'bg-red-500/10 border border-red-500/20 urgent-flash' : 'bg-white/90 border border-slate-200' }} mb-3 transition-all">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold {{ $order->isUrgent() ? 'text-white' : 'text-slate-900' }}">{{ $order->order_number }}</span>
                            @if($order->isUrgent())
                                <span class="text-[10px] px-2 py-0.5 rounded-lg bg-red-500/20 text-red-300 font-semibold animate-pulse">⚡ {{ __('URGENT') }}</span>
                            @endif
                        </div>
                        <div class="text-xs font-medium {{ $order->isUrgent() ? 'text-white/70' : 'text-slate-500' }} mt-1">{{ $order->client->name }} · {{ $order->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right rtl:text-left">
                            <div class="text-lg font-black text-violet-700">{{ number_format($order->total, 0) }} {{ __('SAR') }}</div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                             {{-- Manual Assignment Dropdown --}}
                            <select 
                                x-on:change="$wire.manualDispatch({{ $order->id }}, $event.target.value)"
                                class="bg-slate-100 hover:bg-slate-200 border-slate-200 rounded-xl text-[10px] font-bold text-slate-700 py-1.5 px-2 focus:ring-violet-500 transition-all cursor-pointer">
                                <option value="" class="bg-white">{{ __('Assign Tech...') }}</option>
                                @foreach($availableTechnicians as $tech)
                                    <option value="{{ $tech->id }}" class="bg-white">
                                        {{ $tech->name }} (⭐{{ $tech->technicianProfile->rating ?? '?' }})
                                    </option>
                                @endforeach
                            </select>

                            <button 
                                wire:click="cancelOrder({{ $order->id }})"
                                wire:confirm="{{ __('Are you sure you want to cancel this order?') }}"
                                class="w-8 h-8 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 flex items-center justify-center transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-white/70 text-center py-8">{{ __('No pending orders') }} ✨</p>
            @endforelse
        </div>

        {{-- Active Jobs & Dispatch --}}
        <div class="glass rounded-2xl p-5 mb-8">
            <h3 class="text-sm font-semibold text-white/80 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-violet-600"></span>
                {{ __('Active Dispatches') }}
            </h3>
            <div class="grid grid-cols-1 gap-4">
                @forelse($activeOrders as $order)
                    <div class="rounded-2xl bg-white/5 border border-white/10 p-4 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-violet-600/20 text-violet-400 flex items-center justify-center font-bold">
                                    {{ substr($order->technician->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white">{{ $order->order_number }}</h4>
                                    <p class="text-[10px] text-white/40 font-bold uppercase tracking-widest">{{ $order->technician->name ?? 'Unknown' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold text-violet-400 mb-1">{{ $order->status->label() }}</div>
                                <a href="{{ route('admin.orders.detail', $order->id) }}" class="text-[10px] text-white/40 hover:text-white underline">
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>

                        {{-- The Uber-style Tracking Widget --}}
                        <x-tracking-status :order="$order" class="bg-black/20 rounded-xl" />

                    </div>
                @empty
                    <p class="text-sm text-white/40 text-center py-6 italic">{{ __('No active dispatches') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Available Technicians --}}
        <div class="glass rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white/80 mb-4">{{ __('Available Technicians') }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($availableTechnicians as $tech)
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-slate-200">
                        <div class="w-10 h-10 rounded-xl gradient-brand flex items-center justify-center text-sm font-bold text-white">
                            {{ substr($tech->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-slate-900">{{ $tech->name }}</div>
                            <div class="text-xs font-medium text-slate-500">
                                ⭐ {{ $tech->technicianProfile->rating ?? 'N/A' }}
                                · {{ $tech->technicianProfile->completed_jobs ?? 0 }} {{ __('jobs') }}
                            </div>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                    </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

@php
$statusVal = $order->status->value;
$statusColors = [
    'pending'     => ['dot' => 'bg-yellow-400',  'bar' => 'w-1/6',  'ring' => 'ring-yellow-400/30',  'text' => 'text-yellow-400',  'bg' => 'bg-yellow-500/10'],
    'confirmed'   => ['dot' => 'bg-blue-400',    'bar' => 'w-2/6',  'ring' => 'ring-blue-400/30',    'text' => 'text-blue-400',    'bg' => 'bg-blue-500/10'],
    'assigned'    => ['dot' => 'bg-indigo-400',  'bar' => 'w-3/6',  'ring' => 'ring-indigo-400/30',  'text' => 'text-indigo-400',  'bg' => 'bg-indigo-500/10'],
    'en_route'    => ['dot' => 'bg-cyan-400',    'bar' => 'w-4/6',  'ring' => 'ring-cyan-400/30',    'text' => 'text-cyan-400',    'bg' => 'bg-cyan-500/10'],
    'in_progress' => ['dot' => 'bg-orange-400',  'bar' => 'w-5/6',  'ring' => 'ring-orange-400/30',  'text' => 'text-orange-400',  'bg' => 'bg-orange-500/10'],
    'completed'   => ['dot' => 'bg-green-400',   'bar' => 'w-full', 'ring' => 'ring-green-400/30',   'text' => 'text-green-400',   'bg' => 'bg-green-500/10'],
    'cancelled'   => ['dot' => 'bg-red-400',     'bar' => 'w-0',    'ring' => 'ring-red-400/30',     'text' => 'text-red-400',     'bg' => 'bg-red-500/10'],
];
$sc = $statusColors[$statusVal] ?? $statusColors['pending'];
$steps = ['pending','assigned','in_progress','completed'];
$stepLabels = [
    'pending'     => __('Order Placed'),
    'assigned'    => __('Technician Assigned'),
    'in_progress' => __('Work In Progress'),
    'completed'   => __('Completed'),
];
$currentStep = array_search($statusVal, $steps);
if ($currentStep === false) $currentStep = -1;
@endphp

<div class="min-h-dvh gradient-dark text-white/95" wire:poll.5s>

    {{-- ── Header ── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.live') }}" class="w-9 h-9 rounded-xl bg-white/80 border border-slate-100 hover:bg-orange-50 flex items-center justify-center text-white/70 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-base font-bold font-[Outfit] text-white/95">{{ $order->order_number }}</h1>
                    <p class="text-xs text-white/70">{{ $order->created_at->format('d M Y · H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Live pulse if active --}}
                @if(in_array($statusVal, ['assigned','en_route','in_progress']))
                    <span class="flex items-center gap-1.5 text-xs text-green-400 font-semibold">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        {{ __('Live') }}
                    </span>
                @endif
                <span class="text-xs px-3 py-1.5 rounded-xl font-bold {{ $sc['text'] }} {{ $sc['bg'] }}">
                    {{ $order->status->label() }}
                </span>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 pb-24 space-y-5">

        {{-- ── Progress Stepper ── --}}
        <div class="glass rounded-3xl p-6">
            <h2 class="text-sm font-semibold text-white/70 mb-5">{{ __('Order Progress') }}</h2>

            {{-- Step track --}}
            <div class="relative flex items-center justify-between">
                {{-- Background line --}}
                <div class="absolute inset-x-0 top-4 h-1 bg-slate-200 rounded-full"></div>
                {{-- Filled line --}}
                <div class="absolute left-0 top-4 h-1 rounded-full bg-gradient-to-r from-violet-500 to-green-500 transition-all duration-700 {{ $sc['bar'] }}"></div>

                @foreach($steps as $i => $step)
                @php
                    $done    = $i < $currentStep;
                    $active  = $i === $currentStep;
                    $future  = $i > $currentStep;
                @endphp
                <div class="relative flex flex-col items-center z-10" style="width: {{ round(100 / count($steps)) }}%">
                    <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all
                        {{ $done   ? 'bg-green-500 border-green-500 text-white' : '' }}
                        {{ $active ? 'bg-violet-600 border-violet-600 text-white ring-4 ring-violet-400/30' : '' }}
                        {{ $future ? 'bg-white border-slate-300 text-white/60' : '' }}">
                        @if($done)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @elseif($active)
                            <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span></span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                        @endif
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-[10px] font-semibold {{ $active ? 'text-violet-600' : ($done ? 'text-green-500' : 'text-white/60') }}">
                            {{ $stepLabels[$step] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Timestamps --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6 pt-4 border-t border-slate-100">
                <div class="text-center">
                    <p class="text-xs text-white/60">{{ __('Placed') }}</p>
                    <p class="text-xs font-semibold text-white/80">{{ $order->created_at->format('H:i') }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-white/60">{{ __('Assigned') }}</p>
                    <p class="text-xs font-semibold text-white/80">{{ $order->technician_id ? '✓' : '—' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-white/60">{{ __('Started') }}</p>
                    <p class="text-xs font-semibold text-white/80">{{ $order->started_at?->format('H:i') ?? '—' }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-white/60">{{ __('Completed') }}</p>
                    <p class="text-xs font-semibold text-white/80">{{ $order->completed_at?->format('H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">

            {{-- ── Client Card ── --}}
            <div class="glass rounded-3xl p-5">
                <h3 class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-4">👤 {{ __('Client') }}</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-violet-500/10 flex items-center justify-center font-bold text-violet-600 text-lg">
                        {{ strtoupper(substr($order->client?->name ?? 'G', 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-bold text-white/95">{{ $order->client?->name ?? __('Guest') }}</p>
                        <p class="text-sm text-white/70">{{ $order->client?->phone ?? '—' }}</p>
                        <p class="text-xs text-white/60">{{ $order->client?->email }}</p>
                    </div>
                </div>
                <div class="bg-white/5 rounded-xl p-3 mt-3">
                    <p class="text-xs text-white/70">📍 {{ __('Address') }}</p>
                    @if($editMode)
                        <textarea wire:model="editAddress" rows="2" class="w-full mt-1 px-3 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500/30"></textarea>
                    @else
                        <p class="text-sm font-medium text-white/90 mt-0.5">{{ $order->address ?: '—' }}</p>
                    @endif
                    
                    @if(!$editMode && $order->latitude && $order->longitude)
                        <a href="https://maps.google.com/?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank"
                           class="mt-2 flex items-center gap-1 text-xs text-violet-500 font-semibold">
                            🗺 {{ __('View on Google Maps') }} →
                        </a>
                    @endif
                </div>
                
                <div class="bg-white/5 rounded-xl p-3 mt-3">
                    <p class="text-xs text-white/70">📝 {{ __('Notes') }}</p>
                    @if($editMode)
                        <textarea wire:model="editNotes" rows="2" class="w-full mt-1 px-3 py-2 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-violet-500/30"></textarea>
                    @else
                        <p class="text-sm font-medium text-white/90 mt-0.5">{{ $order->notes ?: '—' }}</p>
                    @endif
                </div>
            </div>

            {{-- ── Technician Card ── --}}
            <div class="glass rounded-3xl p-5">
                <h3 class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-4">🛠 {{ __('Technician') }}</h3>
                @if($order->technician)
                    @php $profile = $order->technician->technicianProfile; @endphp
                    <div class="flex items-center gap-3 mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-600 to-orange-500 flex items-center justify-center font-bold text-white text-lg">
                                {{ strtoupper(substr($order->technician->name, 0, 2)) }}
                            </div>
                            @if($profile?->is_available)
                                <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-green-400 rounded-full border-2 border-white"></span>
                            @endif
                        </div>
                        <div>
                            <p class="font-bold text-white/95">{{ $order->technician->name }}</p>
                            <p class="text-sm text-white/70">{{ $order->technician->phone }}</p>
                            @if($profile)
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="text-yellow-400 text-xs">⭐</span>
                                    <span class="text-xs font-semibold text-white/80">{{ number_format($profile->rating, 1) }}</span>
                                    <span class="text-xs text-white/60">· {{ $profile->completed_jobs }} {{ __('jobs') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Technician GPS live location --}}
                    @if($profile?->latitude && $profile?->longitude)
                        <a href="https://maps.google.com/?q={{ $profile->latitude }},{{ $profile->longitude }}" target="_blank"
                           class="flex items-center gap-2 p-3 rounded-xl bg-violet-500/5 border border-violet-500/20 hover:bg-violet-500/10 transition-all">
                            <span class="text-xl">📍</span>
                            <div>
                                <p class="text-xs font-semibold text-violet-500">{{ __('Live GPS Location') }}</p>
                                <p class="text-xs text-white/60">{{ number_format($profile->latitude, 4) }}, {{ number_format($profile->longitude, 4) }}</p>
                            </div>
                            <svg class="w-4 h-4 text-violet-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    @else
                        <div class="p-3 rounded-xl bg-white/5 text-center text-xs text-white/60">
                            {{ __('GPS location not yet available') }}
                        </div>
                    @endif
                @else
                    <div class="flex flex-col items-center justify-center py-8 gap-3">
                        <div class="text-4xl">⏳</div>
                        <p class="text-sm text-white/70 text-center">{{ __('No technician assigned yet') }}</p>
                        @if($statusVal === 'pending')
                            <button wire:click="forceDispatch"
                                class="px-4 py-2 rounded-xl bg-green-500 text-white text-sm font-bold hover:bg-green-600 transition-all shadow-lg shadow-green-500/30">
                                🚀 {{ __('Force Dispatch') }}
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Order Items ── --}}
        <div class="glass rounded-3xl p-5">
            <h3 class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-4">📦 {{ __('Order Items') }}</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/5">
                        <div>
                            <p class="text-sm font-semibold text-white/90">
                                {{ app()->getLocale() === 'ar' ? ($item->serviceOption?->name_ar ?? '—') : ($item->serviceOption?->name_en ?? '—') }}
                            </p>
                            <p class="text-xs text-white/60 mt-0.5">
                                {{ $item->serviceOption?->subService?->service?->name_en ?? '' }}
                                · {{ __('Qty') }}: {{ $item->quantity }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-orange-500">{{ number_format($item->total_price, 2) }} SAR</p>
                            <p class="text-xs text-white/60">{{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}</p>
                        </div>
                    </div>
                @endforeach
                <div class="flex items-center justify-between pt-3 border-t border-slate-100 px-3">
                    <span class="font-bold text-white/90">{{ __('Total') }}</span>
                    <span class="text-lg font-bold font-[Outfit] text-orange-500">{{ number_format($order->total, 2) }} SAR</span>
                </div>
            </div>
        </div>

        {{-- ── Admin Actions ── --}}
        <div class="glass rounded-3xl p-5 mt-5">
            <h3 class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-4">⚙️ {{ __('Admin Actions') }}</h3>
            
            @if(session()->has('success'))
                <div class="mb-4 p-3 rounded-xl bg-green-500/10 text-green-600 text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-wrap gap-3">
                @if($editMode)
                    <button wire:click="saveOrder"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 text-white hover:bg-violet-700 text-sm font-bold transition-all shadow-lg shadow-violet-500/30">
                        💾 {{ __('Save Changes') }}
                    </button>
                    <button wire:click="toggleEditMode"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 text-white/80 hover:bg-slate-200 text-sm font-bold transition-all">
                        ✕ {{ __('Cancel') }}
                    </button>
                @else
                    <button wire:click="toggleEditMode"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-500/10 text-blue-500 hover:bg-blue-500/20 text-sm font-bold transition-all">
                        ✏️ {{ __('Edit Order') }}
                    </button>

                    @if(!in_array($statusVal, ['completed', 'cancelled']))
                        @if($statusVal === 'pending' && !$order->technician_id)
                            <button wire:click="forceDispatch"
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-green-500/10 text-green-500 hover:bg-green-500/20 text-sm font-bold transition-all">
                                🚀 {{ __('Force Dispatch') }}
                            </button>
                        @endif
                        <button wire:click="cancelOrder"
                            wire:confirm="{{ __('Are you sure you want to cancel this order?') }}"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-sm font-bold transition-all">
                            ✕ {{ __('Cancel Order') }}
                        </button>
                    @endif

                    <button wire:click="deleteOrder"
                        wire:confirm="{{ __('WARNING: This will permanently delete the order from the database. Are you sure?') }}"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500/20 text-sm font-bold transition-all ml-auto">
                        🗑 {{ __('Delete Order') }}
                    </button>
                @endif
            </div>
        </div>

    </main>
</div>

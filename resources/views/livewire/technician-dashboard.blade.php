<div class="min-h-dvh gradient-dark text-white/95"
    x-data="techGps()"
    x-init="startGps()"
    wire:poll.10s>

    {{-- ── GPS JS ── --}}
    <script>
        function techGps() {
            return {
                startGps() {
                    if (!navigator.geolocation) return;
                    const ping = () => {
                        navigator.geolocation.getCurrentPosition(pos => {
                            @this.updateLocation(pos.coords.latitude, pos.coords.longitude);
                        });
                    };
                    ping();
                    setInterval(ping, 30000); // every 30s
                }
            }
        }
    </script>

    {{-- ── Header ── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-base font-bold font-[Outfit] text-white/95">{{ auth()->user()->name }}</h1>
                    <p class="text-xs text-white/70 flex items-center gap-1">
                        <span class="relative flex h-2 w-2">
                            @if($isAvailable)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $isAvailable ? 'bg-green-400' : 'bg-slate-400' }}"></span>
                        </span>
                        {{ __('Technician Portal') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="toggleAvailability" class="px-3 py-1.5 rounded-xl {{ $isAvailable ? 'bg-green-500/20 text-green-500' : 'bg-white/50/20 text-white/60' }} text-xs font-bold transition-all">
                    {{ $isAvailable ? '🟢 ' . __('Online') : '⚫ ' . __('Offline') }}
                </button>
                <button wire:click="logout" class="w-9 h-9 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-6 pb-32">

        @if(session('job_message'))
            <div class="mb-4 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
                {{ session('job_message') }}
            </div>
        @endif

        {{-- ── Wallet Card ── --}}
        <div class="rounded-3xl p-6 mb-6 relative overflow-hidden" style="background: linear-gradient(135deg, #47204d 0%, #2d1a4a 50%, #1a1230 100%);">
            <div class="absolute -top-8 -right-8 w-40 h-40 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -bottom-6 -left-6 w-28 h-28 rounded-full opacity-10 bg-white"></div>
            <div class="relative flex items-start justify-between">
                <div>
                    <p class="text-violet-300/80 text-xs mb-1">{{ __('Wallet Balance') }}</p>
                    <div class="text-4xl font-bold font-[Outfit] text-white">
                        {{ number_format($wallet?->balance ?? 0, 2) }}
                        <span class="text-lg text-violet-300/70">{{ __('SAR') }}</span>
                    </div>
                    <p class="text-violet-300/60 text-xs mt-2">{{ __('Total Earned') }}: <span class="text-white font-semibold">{{ number_format($wallet?->total_earned ?? 0, 0) }}</span> {{ __('SAR') }}</p>
                </div>
                @if($profile)
                <div class="text-right">
                    <div class="flex items-center gap-1 justify-end mb-1">
                        <span class="text-yellow-400 text-lg">⭐</span>
                        <span class="text-white font-bold font-[Outfit] text-xl">{{ number_format($profile->rating, 1) }}</span>
                    </div>
                    <p class="text-violet-300/60 text-xs">{{ $profile->completed_jobs }} {{ __('jobs done') }}</p>
                </div>
                @endif
            </div>
            <div class="grid grid-cols-3 gap-3 mt-5 pt-4 border-t border-white/10">
                <div class="text-center">
                    <div class="text-white font-bold font-[Outfit] text-lg">{{ $activeJobs->count() }}</div>
                    <div class="text-violet-300/60 text-xs">{{ __('Active') }}</div>
                </div>
                <div class="text-center border-x border-white/10">
                    <div class="text-orange-400 font-bold font-[Outfit] text-lg">{{ $pendingOrders->count() }}</div>
                    <div class="text-violet-300/60 text-xs">{{ __('Pending') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-white font-bold font-[Outfit] text-lg">{{ $profile?->total_jobs ?? 0 }}</div>
                    <div class="text-violet-300/60 text-xs">{{ __('Total Jobs') }}</div>
                </div>
            </div>
        </div>

        {{-- ── Tabs ── --}}
        <div class="flex items-center gap-1 glass rounded-xl p-1 mb-6">
            <button wire:click="$set('tab', 'jobs')" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'jobs' ? 'bg-violet-600 text-white shadow' : 'text-white/70 hover:text-white/90' }}">
                🛠 {{ __('My Jobs') }}
                @if($activeJobs->count() > 0)
                    <span class="ml-1 px-1.5 py-0.5 bg-orange-500 text-white text-[10px] rounded-full">{{ $activeJobs->count() }}</span>
                @endif
            </button>
            <button wire:click="$set('tab', 'queue')" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'queue' ? 'bg-orange-500 text-white shadow' : 'text-white/70 hover:text-white/90' }}">
                📥 {{ __('Queue') }}
                @if($pendingOrders->count() > 0)
                    <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white text-[10px] rounded-full animate-pulse">{{ $pendingOrders->count() }}</span>
                @endif
            </button>
            <button wire:click="$set('tab', 'profile')" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'profile' ? 'bg-violet-600 text-white shadow' : 'text-white/70 hover:text-white/90' }}">
                👤 {{ __('Profile') }}
            </button>
            <button wire:click="$set('tab', 'earnings')" class="flex-1 py-2 rounded-lg text-sm font-semibold transition-all {{ $tab === 'earnings' ? 'bg-violet-600 text-white shadow' : 'text-white/70 hover:text-white/90' }}">
                💰 {{ __('Earnings') }}
            </button>
        </div>

        {{-- ══════ TAB: ACTIVE JOBS ══════ --}}
        @if($tab === 'jobs')
            @forelse($activeJobs as $job)
                @php $isInProgress = $job->status === \App\Enums\OrderStatus::IN_PROGRESS; @endphp
                <div class="glass rounded-2xl p-5 mb-4 border-l-4 {{ $isInProgress ? 'border-orange-500' : 'border-violet-500' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-white/95">{{ $job->order_number }}</span>
                        <span class="text-xs px-2 py-1 rounded-lg font-semibold
                            {{ $job->status === \App\Enums\OrderStatus::IN_PROGRESS ? 'bg-orange-500/10 text-orange-500' : 'bg-violet-500/10 text-violet-600' }}">
                            {{ $job->status->label() }}
                        </span>
                    </div>
                    <div class="text-sm text-white/80 mb-1">👤 {{ $job->client?->name ?? '—' }}</div>
                    <div class="text-sm text-white/70 mb-1">📍 {{ $job->address }}</div>
                    @if($job->latitude && $job->longitude)
                        <a href="https://maps.google.com/?q={{ $job->latitude }},{{ $job->longitude }}" target="_blank"
                           class="text-xs text-violet-500 underline mb-2 block">
                            🗺 {{ __('View on map') }}
                        </a>
                    @endif
                    <div class="text-sm font-bold text-orange-500 mb-3">{{ number_format($job->total, 2) }} {{ __('SAR') }}</div>

                    <div class="flex gap-2">
                        @if($job->status === \App\Enums\OrderStatus::ASSIGNED)
                            <button wire:click="startJob({{ $job->id }})" class="flex-1 py-2.5 rounded-xl bg-violet-500/10 text-violet-600 text-sm font-bold hover:bg-violet-500/20 transition-all">
                                🚗 {{ __('Start Job') }}
                            </button>
                        @elseif($job->status === \App\Enums\OrderStatus::IN_PROGRESS)
                            <button wire:click="completeJob({{ $job->id }})" wire:confirm="{{ __('Mark this job as completed?') }}"
                                class="flex-1 py-2.5 rounded-xl bg-green-500/10 text-green-500 text-sm font-bold hover:bg-green-500/20 transition-all">
                                ✅ {{ __('Complete & Collect') }}
                            </button>
                        @endif
                        @if($job->latitude && $job->longitude)
                            <a href="https://maps.google.com/?q={{ $job->latitude }},{{ $job->longitude }}" target="_blank"
                               class="px-4 py-2.5 rounded-xl bg-blue-500/10 text-blue-500 text-sm font-bold hover:bg-blue-500/20 transition-all text-center">
                                📍 {{ __('Navigate') }}
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="glass rounded-2xl p-10 text-center">
                    <div class="text-5xl mb-3">🎉</div>
                    <p class="text-white/70 text-sm">{{ __('No active jobs. Take a break!') }}</p>
                </div>
            @endforelse

            @if($completedJobs->count() > 0)
                <h3 class="text-sm font-semibold text-white/70 mt-6 mb-3">{{ __('Recently Completed') }}</h3>
                @foreach($completedJobs as $job)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/80 border border-slate-100 mb-2">
                        <div>
                            <div class="text-sm font-medium text-white/90">{{ $job->order_number }}</div>
                            <div class="text-xs text-white/60">{{ $job->completed_at?->diffForHumans() }}</div>
                        </div>
                        <span class="text-sm font-bold text-green-500">+{{ number_format($job->total, 0) }} {{ __('SAR') }}</span>
                    </div>
                @endforeach
            @endif

        {{-- ══════ TAB: QUEUE (Pending unassigned orders) ══════ --}}
        @elseif($tab === 'queue')
            <p class="text-xs text-white/70 mb-4">{{ __('These orders have not been assigned yet. They will be dispatched automatically.') }}</p>
            @forelse($pendingOrders as $order)
                <div class="glass rounded-2xl p-5 mb-4 border-l-4 {{ $order->urgency?->value === 'urgent' ? 'border-red-500' : 'border-orange-400' }}">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-white/95 font-[Outfit]">{{ $order->order_number }}</span>
                        @if($order->urgency?->value === 'urgent')
                            <span class="text-xs px-2.5 py-1 rounded-full bg-red-500/10 text-red-500 font-semibold animate-pulse">⚡ {{ __('Urgent') }}</span>
                        @else
                            <span class="text-xs px-2.5 py-1 rounded-full bg-white/10 text-white/70">🕐 {{ __('Scheduled') }}</span>
                        @endif
                    </div>

                    {{-- Service Info --}}
                    @if($order->items->count())
                        <div class="text-sm font-semibold text-violet-600 mb-1">
                            🔧 {{ app()->getLocale() === 'ar'
                                ? ($order->items->first()->serviceOption?->name_ar ?? '—')
                                : ($order->items->first()->serviceOption?->name_en ?? '—') }}
                        </div>
                    @endif

                    <div class="text-sm text-white/70 mb-1">📍 {{ $order->address ?: __('Address pending') }}</div>
                    <div class="text-sm text-white/60 mb-3">🕐 {{ $order->created_at->diffForHumans() }}</div>

                    {{-- Price + Accept --}}
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                        <span class="text-xl font-bold font-[Outfit] text-orange-500">
                            {{ number_format($order->total, 2) }}
                            <span class="text-sm text-white/60">{{ __('SAR') }}</span>
                        </span>
                        <button
                            wire:click="acceptOrder({{ $order->id }})"
                            wire:loading.attr="disabled"
                            wire:target="acceptOrder({{ $order->id }})"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-green-500 text-white text-sm font-bold hover:bg-green-600 active:scale-95 transition-all shadow-lg shadow-green-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Accept Order') }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="glass rounded-2xl p-10 text-center">
                    <div class="text-5xl mb-3">✅</div>
                    <p class="text-white/70 text-sm">{{ __('No pending orders in queue.') }}</p>
                </div>
            @endforelse

        {{-- ══════ TAB: PROFILE ══════ --}}
        @elseif($tab === 'profile')
            <div class="glass rounded-3xl p-6 space-y-5">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-orange-500 flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold font-[Outfit] text-white/95">{{ auth()->user()->name }}</h2>
                        <p class="text-sm text-white/70">{{ auth()->user()->email }}</p>
                        <p class="text-sm text-white/70">{{ auth()->user()->phone }}</p>
                    </div>
                </div>

                @if($profile)
                    <div>
                        <label class="text-xs font-semibold text-white/70 uppercase tracking-wide">{{ __('Bio') }}</label>
                        <p class="mt-1 text-sm text-white/90 bg-white/5 rounded-xl p-3">
                            {{ app()->getLocale() === 'ar' ? ($profile->bio_ar ?: '—') : ($profile->bio_en ?: '—') }}
                        </p>
                    </div>

                    @if($profile->specializations && count($profile->specializations) > 0)
                    <div>
                        <label class="text-xs font-semibold text-white/70 uppercase tracking-wide">{{ __('Specializations') }}</label>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($profile->specializations as $spec)
                                <span class="px-3 py-1 rounded-full bg-violet-500/10 text-violet-700 text-xs font-semibold">{{ $spec }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100">
                        <div class="bg-white/5 rounded-xl p-3 text-center">
                            <div class="text-base font-bold {{ $profile->is_verified ? 'text-green-500' : 'text-orange-400' }}">
                                {{ $profile->is_verified ? '✓ ' . __('Verified') : '⏳ ' . __('Pending') }}
                            </div>
                            <div class="text-xs text-white/60 mt-0.5">{{ __('Account Status') }}</div>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 text-center">
                            <div class="text-base font-bold {{ $profile->is_available ? 'text-green-500' : 'text-white/60' }}">
                                {{ $profile->is_available ? '🟢 ' . __('Online') : '⚫ ' . __('Offline') }}
                            </div>
                            <div class="text-xs text-white/60 mt-0.5">{{ __('Availability') }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-white/60 text-sm text-center py-4">{{ __('Profile not set up yet.') }}</p>
                @endif
            </div>

        {{-- ══════ TAB: EARNINGS ══════ --}}
        @elseif($tab === 'earnings')
            @forelse($recentTransactions as $tx)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white/80 border border-slate-100 mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm {{ $tx->type === 'credit' ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-400' }}">
                            {{ $tx->type === 'credit' ? '↑' : '↓' }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-white/90">{{ $tx->description }}</div>
                            <div class="text-xs text-white/60">{{ $tx->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span class="font-bold font-[Outfit] {{ $tx->type === 'credit' ? 'text-green-500' : 'text-red-400' }}">
                        {{ $tx->type === 'credit' ? '+' : '-' }}{{ number_format($tx->amount, 2) }}
                    </span>
                </div>
            @empty
                <div class="glass rounded-2xl p-10 text-center">
                    <div class="text-5xl mb-3">💳</div>
                    <p class="text-white/70 text-sm">{{ __('No transactions yet') }}</p>
                </div>
            @endforelse
        @endif

    </main>

    {{-- ── Bottom Nav ── --}}
    <nav class="fixed bottom-0 inset-x-0 glass-dark border-t border-slate-200/20 z-40">
        <div class="max-w-2xl mx-auto flex items-center justify-around py-3 px-4">
            <button wire:click="$set('tab', 'jobs')" class="flex flex-col items-center gap-0.5 {{ $tab === 'jobs' ? 'text-violet-600' : 'text-white/60' }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-[10px] font-medium">{{ __('Jobs') }}</span>
            </button>
            <button wire:click="$set('tab', 'queue')" class="flex flex-col items-center gap-0.5 {{ $tab === 'queue' ? 'text-orange-500' : 'text-white/60' }} transition-colors relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($pendingOrders->count() > 0)
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] rounded-full flex items-center justify-center font-bold animate-pulse">{{ $pendingOrders->count() }}</span>
                @endif
                <span class="text-[10px] font-medium">{{ __('Queue') }}</span>
            </button>
            <button wire:click="$set('tab', 'earnings')" class="flex flex-col items-center gap-0.5 {{ $tab === 'earnings' ? 'text-violet-600' : 'text-white/60' }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-[10px] font-medium">{{ __('Earnings') }}</span>
            </button>
            <button wire:click="$set('tab', 'profile')" class="flex flex-col items-center gap-0.5 {{ $tab === 'profile' ? 'text-violet-600' : 'text-white/60' }} transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-[10px] font-medium">{{ __('Profile') }}</span>
            </button>
        </div>
    </nav>

</div>

<div class="min-h-dvh gradient-dark text-white/95">
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center text-sm font-bold text-cyan-400">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">{{ app()->getLocale() === 'ar' ? 'حجوزاتي' : 'My Bookings' }}</h1>
                    <p class="text-xs text-white/70">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="/" class="px-4 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-sm font-semibold transition-all hover:scale-105 active:scale-95">
                    + {{ app()->getLocale() === 'ar' ? 'حجز جديد' : 'New Booking' }}
                </a>
                <button wire:click="logout" class="w-10 h-10 rounded-xl bg-red-500/10 text-red-300 hover:bg-red-500/20 flex items-center justify-center transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-6 pb-32">
        {{-- Active Orders --}}
        <div class="mb-8">
            <h3 class="text-sm font-bold text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-400 shadow-[0_0_10px_rgba(96,165,250,0.5)] animate-pulse"></span>
                {{ app()->getLocale() === 'ar' ? 'الطلبات النشطة' : 'Active Orders' }}
            </h3>
            @forelse($activeOrders as $order)
                <div class="glass-dark rounded-3xl p-5 mb-4 border border-white/5 hover:border-white/10 transition-all duration-300 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black text-white/90">#{{ $order->order_number }}</span>
                        </div>
                        <span class="text-[10px] uppercase font-black px-3 py-1 rounded-full bg-violet-500/20 text-violet-300 border border-violet-500/20">
                            {{ $order->status->label() }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="flex items-center gap-1.5 mb-5 px-1">
                        @php
                            $steps = ['confirmed', 'assigned', 'en_route', 'in_progress'];
                            $currentStep = array_search($order->status->value, $steps);
                        @endphp
                        @foreach($steps as $i => $step)
                            <div class="flex-1 h-1.5 rounded-full {{ $i <= ($currentStep ?? -1) ? 'bg-violet-500 shadow-[0_0_8px_rgba(139,92,246,0.3)]' : 'bg-white/5 border border-white/5' }} transition-all duration-500"></div>
                        @endforeach
                    </div>

                    @if($order->technician)
                        <div class="flex items-center gap-3 p-3.5 rounded-2xl bg-white/[0.03] border border-white/5 mb-2 hover:bg-white/[0.05] transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-violet-600 flex items-center justify-center text-sm font-bold shadow-lg">
                                {{ substr($order->technician->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white/90">{{ $order->technician->name }}</div>
                                <div class="text-[11px] text-white/50">
                                    @if($order->status->value === 'en_route' || $order->status->value === 'in_progress')
                                        {{ app()->getLocale() === 'ar' ? 'تم تعيين فني' : 'Technician assigned' }}
                                    @else
                                        {{ __('Waiting for Technician Assignment') }}
                                    @endif
                                </div>
                            </div>
                            <div class="mr-auto rtl:ml-auto">
                                <a href="tel:{{ $order->technician->phone }}" class="w-9 h-9 rounded-lg bg-green-500/20 text-green-400 flex items-center justify-center hover:bg-green-500/30 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-orange-500/5 border border-orange-500/10 mb-2 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500/20 flex items-center justify-center animate-pulse">
                                <div class="w-2.5 h-2.5 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.5)]"></div>
                            </div>
                            <span class="text-[11px] font-bold text-orange-200 uppercase tracking-widest">{{ __('Waiting for Technician Assignment') }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-white/5">
                        <span class="text-xs text-white/40 font-medium">{{ $order->created_at->diffForHumans() }}</span>
                        <div class="text-right rtl:text-left">
                            <span class="text-lg font-black font-[Outfit] text-transparent bg-clip-text bg-linear-to-r from-orange-400 to-violet-400">
                                {{ number_format($order->total, 0) }} 
                                <span class="text-xs font-bold text-white/40">{{ app()->getLocale() === 'ar' ? 'ر.س' : 'SAR' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-dark rounded-3xl p-10 text-center border border-white/5">
                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center mx-auto mb-5 text-3xl">📦</div>
                    <p class="text-sm font-medium text-white/60 mb-6">{{ app()->getLocale() === 'ar' ? 'لا توجد طلبات نشطة' : 'No active orders' }}</p>
                    <a href="/" class="inline-flex px-8 py-3 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-600 text-sm font-bold shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all">
                        {{ app()->getLocale() === 'ar' ? 'احجز خدمة جديدة' : 'Book a New Service' }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Past Orders --}}
        <div>
            <h3 class="text-sm font-bold text-white/40 uppercase tracking-widest mb-4">{{ app()->getLocale() === 'ar' ? 'الطلبات السابقة' : 'Past Orders' }}</h3>
            <div class="space-y-3">
                @forelse($pastOrders as $order)
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/[0.04] transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 group-hover:text-violet-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white/80">#{{ $order->order_number }}</div>
                                <div class="text-[10px] text-white/30 font-medium">{{ $order->completed_at?->format('Y/m/d') ?? $order->cancelled_at?->format('Y/m/d') }}</div>
                            </div>
                        </div>
                        <div class="text-right rtl:text-left">
                            <div class="text-sm font-black text-white/90">{{ number_format($order->total, 0) }} {{ app()->getLocale() === 'ar' ? 'ر.س' : 'SAR' }}</div>
                            <span class="text-[9px] uppercase font-black tracking-tighter {{ $order->status->value === 'completed' ? 'text-green-500' : 'text-red-500' }}">
                                {{ $order->status->label() }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-white/20 text-center py-8 font-medium italic border-2 border-dashed border-white/5 rounded-3xl">{{ app()->getLocale() === 'ar' ? 'لا توجد طلبات سابقة' : 'No past orders' }}</p>
                @endforelse
            </div>
        </div>
    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 w-[90%] sm:w-80 h-16 glass-dark rounded-full border border-white/10 shadow-2xl flex items-center justify-around px-4">
        <a href="/" class="flex flex-col items-center gap-1 text-white/40 hover:text-violet-400 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span class="text-[10px] font-bold">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</span>
        </a>
        <a href="{{ route('client.dashboard') }}" class="flex flex-col items-center gap-1 text-violet-400 scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span class="text-[10px] font-black">{{ app()->getLocale() === 'ar' ? 'حجوزاتي' : 'My Bookings' }}</span>
        </a>
        <button onclick="window.KifahTour?.launch('bookings')" class="flex flex-col items-center gap-1 text-white/40 hover:text-violet-400 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-[10px] font-bold">{{ app()->getLocale() === 'ar' ? 'مساعدة' : 'Support' }}</span>
        </button>
    </nav>
</div>

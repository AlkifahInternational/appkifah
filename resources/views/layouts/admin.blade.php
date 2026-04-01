<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0A1628">
    @auth
        @if(auth()->user()->role->value === 'technician')
            <meta name="technician-id" content="{{ auth()->id() }}">
        @elseif(auth()->user()->role->value === 'client')
            <meta name="client-id" content="{{ auth()->id() }}">
        @endif
    @endauth
    <title>{{ $title ?? 'Al-Kifah Admin' }}</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased min-h-dvh bg-white/5" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- SIDEBAR                                                         --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}

{{-- Overlay for mobile --}}
<div x-show="sidebarOpen && window.innerWidth < 1024"
     x-transition:enter="transition ease-in-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in-out duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-black/50 lg:hidden"
     style="display:none"></div>

<aside x-show="sidebarOpen"
       x-transition:enter="transition ease-in-out duration-300"
       x-transition:enter-start="-translate-x-full opacity-0"
       x-transition:enter-end="translate-x-0 opacity-100"
       x-transition:leave="transition ease-in-out duration-300"
       x-transition:leave-start="translate-x-0 opacity-100"
       x-transition:leave-end="-translate-x-full opacity-0"
       class="fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} z-50 w-64 flex flex-col"
       style="background: linear-gradient(180deg, #1a0a2e 0%, #2d1a4a 60%, #47204d 100%);">

    {{-- Logo --}}
    <div class="flex flex-col items-center px-5 pt-6 pb-5 border-b border-white/10 relative">
        {{-- Close button (mobile only) --}}
        <button @click="sidebarOpen = false"
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} text-white/40 hover:text-white transition-colors lg:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        {{-- Logo image --}}
        <img src="/images/logo.png" alt="Al-Kifah Logo"
             class="w-16 h-16 rounded-2xl object-cover shadow-lg shadow-violet-900/60 mb-3"
             style="object-position: center;">
        {{-- Brand name --}}
        <p class="text-white font-bold font-[Outfit] text-base tracking-wide">Al-Kifah</p>
        <span class="mt-0.5 text-[10px] px-2 py-0.5 rounded-full bg-violet-500/20 text-violet-300 font-medium">{{ __('Super Admin') }}</span>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-1">

        @php
            $navItems = [
                ['route' => 'admin.dashboard',   'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => __('Dashboard'),      'color' => 'violet'],
                ['route' => 'admin.orders.live', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => __('Live Orders'),    'color' => 'green'],
                ['route' => 'admin.agents',      'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => __('Agents'),         'color' => 'blue'],
                ['route' => 'admin.services',    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'label' => __('Services'),       'color' => 'orange'],
                ['route' => 'admin.payments',    'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => __('Payments'),       'color' => 'emerald'],
                ['route' => 'admin.settings',    'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4', 'label' => __('Settings'),       'color' => 'slate'],
            ];
        @endphp

        @foreach($navItems as $item)
        @php
            $isActive = request()->routeIs($item['route']) || (isset($item['routes']) && request()->routeIs($item['routes']));
            $colors = [
                'violet'  => ['active' => 'bg-violet-500/20 text-violet-200',  'hover' => 'hover:bg-violet-500/10 hover:text-violet-200',  'dot' => 'bg-violet-400'],
                'green'   => ['active' => 'bg-green-500/20 text-green-200',    'hover' => 'hover:bg-green-500/10 hover:text-green-200',    'dot' => 'bg-green-400'],
                'blue'    => ['active' => 'bg-blue-500/20 text-blue-200',      'hover' => 'hover:bg-blue-500/10 hover:text-blue-200',      'dot' => 'bg-blue-400'],
                'orange'  => ['active' => 'bg-orange-500/20 text-orange-200',  'hover' => 'hover:bg-orange-500/10 hover:text-orange-200',  'dot' => 'bg-orange-400'],
                'emerald' => ['active' => 'bg-emerald-500/20 text-emerald-200','hover' => 'hover:bg-emerald-500/10 hover:text-emerald-200','dot' => 'bg-emerald-400'],
                'slate'   => ['active' => 'bg-white/50/20 text-slate-200',    'hover' => 'hover:bg-slate-400/10 hover:text-slate-200',    'dot' => 'bg-slate-400'],
            ];
            $c = $colors[$item['color']] ?? $colors['violet'];
        @endphp
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
           {{ $isActive ? $c['active'] : 'text-white/50 ' . $c['hover'] }}">
            @if($isActive)
                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }} shrink-0"></span>
            @else
                <span class="w-1.5 h-1.5 rounded-full bg-transparent shrink-0"></span>
            @endif
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
            </svg>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- Bottom: user + logout --}}
    <div class="px-4 py-4 border-t border-white/10 pb-20 lg:pb-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-orange-400 flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-white/40 text-[10px] truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-red-300/70 hover:bg-red-500/10 hover:text-red-300 text-xs font-medium transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT                                                     --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div class="transition-all duration-300" :class="sidebarOpen && window.innerWidth >= 1024 ? '{{ app()->getLocale() === 'ar' ? 'mr-64' : 'ml-64' }}' : ''">

    {{-- Top bar --}}
    <header class="sticky top-0 z-30 glass-dark border-b border-white/5">
        <div class="px-4 sm:px-6 py-3 flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen"
                class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/60 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            {{-- Breadcrumb --}}
            @php
                $pageTitle = $title ?? 'Admin';
                $titleMap  = [
                    'Dashboard'     => __('Dashboard'),
                    'Live Orders'   => __('Live Orders'),
                    'Agents'        => __('Agents'),
                    'Services'      => __('Services'),
                    'Payments'      => __('Payments'),
                    'Settings'      => __('Settings'),
                    'Admin'         => __('Admin'),
                    'Order Detail'  => __('Orders'),
                ];
                $translatedTitle = $titleMap[$pageTitle] ?? $pageTitle;
            @endphp
            <div class="flex items-center gap-2 text-sm">
                <span class="text-white/70">{{ app()->getLocale() === 'ar' ? 'الكفاح' : 'Al-Kifah' }}</span>
                <span class="text-white/80">/</span>
                <span class="text-white/50 font-medium">{{ $translatedTitle }}</span>
            </div>

            <div class="ml-auto flex items-center gap-2">
                {{-- How it Works replay --}}
                <button onclick="window.KifahTour?.resetAll(); setTimeout(()=>window.KifahTour?.launch('admin'),100)"
                        data-tooltip="{{ app()->getLocale() === 'ar' ? 'كيف يعمل النظام؟' : 'How it Works' }}"
                        class="w-8 h-8 rounded-lg bg-violet-500/10 hover:bg-violet-500/20 flex items-center justify-center text-violet-400 hover:text-violet-300 transition-all"
                        style="background:none;border:none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </button>
                
                {{-- Notifications --}}
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/60 hover:text-white transition-all relative">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 flex h-2.5 w-2.5">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500 border border-[#1a0a2e]"></span>
                            </span>
                        @endif
                    </button>
                    
                    {{-- Notifications Dropdown --}}
                    <div x-show="open" @click.away="open = false" class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-80 bg-white rounded-xl shadow-2xl border border-slate-200 z-50 overflow-hidden" style="display: none;">
                        <div class="p-3 border-b border-slate-100 bg-white/5 flex items-center justify-between">
                            <span class="font-bold text-white/95">{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}</span>
                            @if($unreadCount > 0)
                                <span class="text-xs font-semibold bg-violet-100 text-violet-600 px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto p-2">
                            @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                <div class="p-3 {{ $notification->read_at ? 'opacity-60' : 'bg-orange-50/50' }} rounded-lg mb-1 flex gap-3 text-sm">
                                    <div class="w-2 h-2 mt-1.5 rounded-full {{ $notification->read_at ? 'bg-slate-300' : 'bg-orange-500' }} shrink-0"></div>
                                    <div>
                                        <p class="text-white/95 font-medium">{{ $notification->data['message'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-white/70 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-white/70 text-sm">
                                    {{ app()->getLocale() === 'ar' ? 'لا توجد إشعارات جديدة' : 'No new notifications' }}
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <a href="/" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/60 hover:text-white transition-all" title="{{ __('View Site') }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </a>
            </div>
        </div>
    </header>

    <div class="min-h-[calc(100dvh-57px)] bg-white/5/50 pb-20 lg:pb-0">
        {{ $slot }}
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{-- MOBILE BOTTOM NAVBAR (hidden on lg+)                           --}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<nav class="fixed bottom-0 inset-x-0 z-50 lg:hidden"
     style="background: linear-gradient(180deg, #2d1a4a 0%, #1a0a2e 100%);
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-bottom: env(safe-area-inset-bottom);">
    <div class="grid grid-cols-5 gap-0">

        @php
            $mobileNav = [
                ['route' => 'admin.dashboard',   'label' => __('Dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'admin.orders.live', 'label' => __('Live Orders'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'admin.agents',      'label' => __('Agents'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.settings',    'label' => __('Settings'), 'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'],
            ];
        @endphp

        @foreach($mobileNav as $nav)
            @php $isActive = request()->routeIs($nav['route']); @endphp
            <a href="{{ route($nav['route']) }}"
               class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 relative
                      {{ $isActive ? 'text-violet-300' : 'text-white/35 hover:text-white/70' }}">
                @if($isActive)
                    <span class="absolute top-1.5 w-1 h-1 rounded-full bg-violet-400"></span>
                @endif
                <svg class="w-5 h-5 mt-1 {{ $isActive ? 'stroke-[2.2]' : 'stroke-[1.7]' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/>
                </svg>
                <span class="text-[9px] mt-0.5 font-medium leading-tight text-center truncate w-full px-0.5">
                    {{ $nav['label'] }}
                </span>
            </a>
        @endforeach

        {{-- Logout tab --}}
        <form method="POST" action="{{ route('logout') }}" class="contents">
            @csrf
            <button type="submit"
                    class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 relative text-red-400/70 hover:text-red-300"
                    style="background:none;border:none;">
                <svg class="w-5 h-5 mt-1 stroke-[1.7]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="text-[9px] mt-0.5 font-medium leading-tight text-center">
                    {{ __('Logout') }}
                </span>
            </button>
        </form>

    </div>
</nav>

@livewireScripts
@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => window.KifahTour?.launch('admin'), 1000);
    });
</script>
</body>
</html>

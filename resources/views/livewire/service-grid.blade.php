<div x-data="{ mobileMenuOpen: false }" class="min-h-dvh gradient-dark text-white/95">
    {{-- ── Top Header Bar ────────────────────────────────────── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($activeServiceId)
                    <button wire:click="goBack"
                        class="w-10 h-10 rounded-xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ route('home') }}">
                        <img src="/images/logo.png" alt="Al-Kifah"
                             class="w-12 h-12 rounded-xl object-cover shrink-0 shadow-md shadow-violet-900/40">
                    </a>
                @endif
                <div>
                    <h1 class="text-lg sm:text-xl font-bold font-[Outfit] tracking-tight {{ $activeServiceId ? 'text-white' : 'text-white/90' }}">
                        @if($activeSubService)
                            {{ app()->getLocale() === 'ar' ? $activeSubService->name_ar : $activeSubService->name_en }}
                        @elseif($activeService)
                            {{ app()->getLocale() === 'ar' ? $activeService->name_ar : $activeService->name_en }}
                        @else
                            @if(app()->getLocale() === 'ar')
                                <span class="bg-gradient-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-sm">شركة الكفاح</span>
                                <span class="text-white/80 font-light ml-1">العالمية</span>
                            @else
                                <span class="bg-gradient-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-sm">AL-KIFAH</span>
                                <span class="text-white/80 font-light ml-1">Global</span>
                            @endif
                        @endif
                    </h1>
                    @if(!$activeServiceId)
                        <p class="text-xs text-white/60 mt-0.5">{{ __('Professional Services at Your Fingertips') }}</p>
                    @else
                        {{-- breadcrumb --}}
                        @if($activeSubService && $activeService)
                            <p class="text-xs text-white/50 mt-0.5">{{ app()->getLocale() === 'ar' ? $activeService->name_ar : $activeService->name_en }}</p>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-all shrink-0">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenuOpen" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            {{-- Navigation Buttons --}}
            <div :class="mobileMenuOpen ? 'flex flex-col absolute top-[100%] left-0 w-full p-4 bg-[#47204d] shadow-2xl border-b border-white/10 z-50 gap-4' : 'hidden sm:flex'" class="items-center gap-2">

                {{-- How it Works --}}
                <button onclick="window.KifahTour?.resetAll(); setTimeout(()=>window.KifahTour?.launch('home'),100)"
                        data-tooltip="{{ app()->getLocale() === 'ar' ? 'تعرف على كيفية استخدام التطبيق' : 'Learn how to use the app' }}"
                        class="flex w-full sm:w-auto items-center justify-center gap-1.5 px-4 py-2 bg-white/5 sm:bg-transparent rounded-xl transition-all text-sm sm:text-xs font-semibold {{ $activeServiceId ? 'text-white/80 border sm:border-white/20 hover:bg-white/10' : 'text-violet-300 border border-violet-400/20 hover:bg-violet-500/10' }}"
                        style="background:none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'كيف يعمل؟' : 'How it Works' }}
                </button>

                {{-- Language Toggle --}}
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                   data-tooltip="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التبديل للعربية' }}"
                   class="w-full sm:w-10 h-10 rounded-xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all text-sm font-semibold text-violet-600">
                    {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
                </a>

                {{-- Search Bar --}}
                <div class="relative w-full sm:w-auto">
                    <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3' : 'left-0 pl-3' }} flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input id="mobileSearchInput" wire:model.live.debounce.300ms="searchQuery" type="text" placeholder="{{ app()->getLocale() === 'ar' ? 'بحث عن خدمة...' : 'Search services...' }}" class="w-full sm:w-48 {{ app()->getLocale() === 'ar' ? 'pr-9 pl-3' : 'pl-9 pr-3' }} py-2 border border-slate-200 rounded-xl leading-5 bg-white/90 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-violet-500 focus:border-violet-500 sm:text-sm transition-all duration-300">
                </div>

                @auth
                    {{-- Notifications --}}
                    @php
                        $unreadCount = auth()->user()->unreadNotifications->count();
                    @endphp
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="w-10 h-10 rounded-xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all relative">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 flex h-3 w-3">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500 border-2 border-white"></span>
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
                                @if(auth()->user()->notifications()->count() > 5)
                                    <a href="{{ route('dashboard') }}" class="block text-center mt-2 text-violet-600 text-xs font-semibold py-1 hover:underline">
                                        {{ app()->getLocale() === 'ar' ? 'عرض الكل' : 'View all' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('dashboard') }}"
                       data-tooltip="{{ app()->getLocale() === 'ar' ? 'لوحة تحكمك' : 'Your dashboard' }}"
                       class="w-full sm:w-10 h-10 rounded-xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all text-violet-600 gap-2 font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="sm:hidden">{{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       data-tooltip="{{ app()->getLocale() === 'ar' ? 'سجّل دخولك لإجراء الحجز' : 'Sign in to book a service' }}"
                       class="w-full sm:w-auto px-4 py-3 sm:py-2 text-center rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-sm font-semibold hover:shadow-lg hover:shadow-orange-500/30 transition-all sm:hover:scale-105 sm:active:scale-95 text-white">
                        {{ __('Login') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ── Main Content ────────────────────────────────────── --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-5 pb-24">

        {{-- ── Level 1: Main Services Grid ────────────────── --}}
        @if(!$activeServiceId)
            {{-- ── Hero Slideshow Section ─────────────────────── --}}
            <div x-data="{
                    activeSlide: 1,
                    slides: [
                        {
                            id: 1,
                            title: '{{ app()->getLocale() === "ar" ? "مرحباً بكم في الكفاح العالمية" : "Welcome to AL-KIFAH Global" }}',
                            subtitle: '{{ app()->getLocale() === "ar" ? "شريكك الموثوق للخدمات المهنية المتكاملة" : "Your trusted partner for integrated professional services" }}',
                            image: '/images/slider/slide1.png'
                        },
                        {
                            id: 2,
                            title: '{{ app()->getLocale() === "ar" ? "التميز في الصيانة والمقاولات" : "Excellence in Maintenance & Contracting" }}',
                            subtitle: '{{ app()->getLocale() === "ar" ? "فنيون خبراء ومهندسون متاحون لتلبية كافة احتياجاتك" : "Expert technicians available 24/7 for all your needs" }}',
                            image: '/images/slider/slide2.png'
                        },
                        {
                            id: 3,
                            title: '{{ app()->getLocale() === "ar" ? "حلول برمجية حديثة" : "Modern Software Solutions" }}',
                            subtitle: '{{ app()->getLocale() === "ar" ? "تمكين أعمالك بأحدث تقنيات التكنولوجيا المتقدمة" : "Empowering your business with cutting edge tech" }}',
                            image: '/images/slider/slide3.png'
                        },
                        {
                            id: 4,
                            title: '{{ app()->getLocale() === "ar" ? "أنظمة المراقبة الأمنية" : "Advanced Security Systems" }}',
                            subtitle: '{{ app()->getLocale() === "ar" ? "تركيب وبرمجة كاميرات المراقبة لتأمين منشأتك بأعلى المعايير" : "Professional installation of CCTV systems to secure your business" }}',
                            image: '/images/slider/slide4.png'
                        }
                    ],
                    init() {
                        setInterval(() => {
                            this.activeSlide = this.activeSlide === this.slides.length ? 1 : this.activeSlide + 1;
                        }, 6000);
                    }
                }"
                class="relative w-full h-[400px] sm:h-[500px] rounded-[2rem] overflow-hidden mb-12 shadow-2xl group border border-slate-200/20"
            >
                <!-- Slides -->
                <template x-for="slide in slides" :key="slide.id">
                    <div x-show="activeSlide === slide.id"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-700 absolute inset-0"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full">
                        <img :src="slide.image" class="absolute inset-0 w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/60"></div>
                        <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-8 sm:px-16 text-white z-10 w-full max-w-4xl mx-auto">
                            <span class="text-orange-400 font-bold tracking-wider text-sm mb-3" x-text="'{{ app()->getLocale() === 'ar' ? 'خدمات متميزة' : 'PREMIUM SERVICES' }}'"></span>
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-black font-[Outfit] leading-tight mb-4" x-text="slide.title"></h1>
                            <p class="text-base sm:text-lg lg:text-xl text-slate-200 mb-8 leading-relaxed max-w-2xl" x-text="slide.subtitle"></p>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <button onclick="document.getElementById('services-grid').scrollIntoView({behavior: 'smooth'})"
                                    class="px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-600 text-white font-bold shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-105 active:scale-95 transition-all text-center">
                                    {{ app()->getLocale() === 'ar' ? 'تصفح خدماتنا' : 'Browse Our Services' }}
                                </button>
                                <button onclick="document.getElementById('about-us')?.scrollIntoView({behavior:'smooth'})"
                                    class="px-8 py-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 hover:scale-105 active:scale-95 transition-all text-center">
                                    {{ app()->getLocale() === 'ar' ? 'عن الشركة' : 'About Us' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Navigation Dots -->
                <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-3 z-10">
                    <template x-for="slide in slides" :key="slide.id">
                        <button @click="activeSlide = slide.id"
                            :class="activeSlide === slide.id ? 'w-8 bg-orange-400' : 'w-2 bg-white/50'"
                            class="h-2 rounded-full transition-all duration-300 hover:bg-white"></button>
                    </template>
                </div>
                
                <!-- Navigation Arrows -->
                <button @click="activeSlide = activeSlide === 1 ? slides.length : activeSlide - 1"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/20 text-white backdrop-blur-md border border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-black/40 transition-all z-10 hidden sm:flex">
                    <svg class="w-6 h-6 rtl:scale-x-[-1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="activeSlide = activeSlide === slides.length ? 1 : activeSlide + 1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-black/20 text-white backdrop-blur-md border border-white/10 flex items-center justify-center opacity-0 group-hover:opacity-100 hover:bg-black/40 transition-all z-10 hidden sm:flex">
                    <svg class="w-6 h-6 rtl:scale-x-[-1]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div id="services-grid" class="mb-8 scroll-mt-24">
                <h2 class="text-2xl sm:text-3xl font-bold font-[Outfit] mb-2">{{ __('Our Services') }}</h2>
                <p class="text-white/70">{{ __('Choose a service category to get started') }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:gap-6" wire:transition>
                @foreach($services as $index => $service)
                    <button wire:click="selectService({{ $service->id }})"
                        class="service-card text-center group"
                        data-tooltip="{{ app()->getLocale() === 'ar' ? 'اضغط لاستعراض خدمات ' . $service->name_ar : 'Tap to explore ' . $service->name_en . ' services' }}"
                        style="background: linear-gradient(135deg, {{ $service->color }} 0%, {{ $service->color }}dd 100%); border: 1px solid {{ $service->color }}; animation-delay: {{ $index * 100 }}ms"
                        wire:key="service-{{ $service->id }}">

                        {{-- Icon --}}
                        <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-2xl flex items-center justify-center mb-4 transition-transform duration-300 group-hover:scale-110 shadow-inner"
                             style="background: rgba(255,255,255,0.2);">
                            @php
                                // Icon map: supports both legacy text names and newer emojis
                                $svgIcons = [
                                    'building' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                                    'home'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                                    'shield'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                                    'code'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
                                ];
                                $iconVal = $service->icon;
                            @endphp
                            @if(isset($svgIcons[$iconVal]))
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $svgIcons[$iconVal] !!}
                                </svg>
                            @else
                                {{-- Emoji or any other character --}}
                                <span class="text-3xl leading-none">{{ $iconVal }}</span>
                            @endif
                        </div>

                        {{-- Text Button Shape --}}
                        <div class="inline-block px-4 py-1.5 rounded-full bg-white/20 border border-white/30 shadow-sm mb-2 text-white">
                            <h3 class="text-sm sm:text-base font-bold font-[Outfit]">
                                {{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}
                            </h3>
                        </div>
                        
                        <p class="text-xs sm:text-sm text-white/90 line-clamp-2">
                            {{ app()->getLocale() === 'ar' ? $service->description_ar : $service->description_en }}
                        </p>

                        {{-- Arrow --}}
                        <div class="mt-4 flex items-center justify-center text-xs font-medium opacity-80 group-hover:opacity-100 transition-all text-white">
                            <span>{{ __('Explore') }}</span>
                            <svg class="w-4 h-4 ml-1 rtl:mr-1 rtl:ml-0 rtl:rotate-180 group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </button>
                @endforeach
            </div>

            {{-- Stats Strip --}}
            <div class="mt-10 grid grid-cols-3 gap-4 glass rounded-2xl p-5">
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold font-[Outfit] text-white counter-spin">500+</div>
                    <div class="text-xs text-white/70 mt-1">{{ __('Projects Done') }}</div>
                </div>
                <div class="text-center border-x border-slate-200">
                    <div class="text-2xl sm:text-3xl font-bold font-[Outfit] text-green-400 counter-spin">50+</div>
                    <div class="text-xs text-white/70 mt-1">{{ __('Expert Technicians') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl sm:text-3xl font-bold font-[Outfit] text-gold counter-spin">4.9</div>
                    <div class="text-xs text-white/70 mt-1">{{ __('Avg. Rating') }}</div>
                </div>
            </div>
        @endif

        {{-- ── Level 2: Sub-Services Grid ────────────────── --}}
        @if($activeServiceId && !$activeSubServiceId)
            <div class="slide-enter">
                <div class="mb-6">
                    <p class="text-white/70 text-sm">{{ __('Select a service type') }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($subServices as $index => $sub)
                        @php
                            $bgColors = [
                                'snowflake' => 'from-blue-500 to-blue-600',
                                'droplet' => 'from-cyan-500 to-cyan-600',
                                'zap' => 'from-yellow-400 to-orange-500',
                                'paintbrush' => 'from-orange-500 to-red-500',
                                'camera' => 'from-red-500 to-rose-600',
                                'bell' => 'from-purple-500 to-fuchsia-600',
                                'hammer' => 'from-violet-500 to-purple-600',
                                'paint-roller' => 'from-green-500 to-emerald-600',
                                'globe' => 'from-indigo-500 to-blue-600',
                                'smartphone' => 'from-pink-500 to-rose-500'
                            ];
                            $cardBg = $bgColors[$sub->icon] ?? 'from-slate-500 to-slate-600';
                        @endphp
                        <button wire:click="selectSubService({{ $sub->id }})"
                            class="service-card text-center group bg-gradient-to-br {{ $cardBg }} border-0 border-white/10"
                            style="animation-delay: {{ $index * 80 }}ms"
                            wire:key="sub-{{ $sub->id }}">

                            {{-- Icon --}}
                            <div class="w-12 h-12 mx-auto rounded-xl bg-white/20 flex items-center justify-center mb-3 transition-colors shadow-inner">
                                @switch($sub->icon)
                                    @case('snowflake')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M19.07 4.93L4.93 19.07"/></svg>
                                        @break
                                    @case('droplet')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2C12 2 5 10 5 14a7 7 0 1014 0c0-4-7-12-7-12z"/></svg>
                                        @break
                                    @case('zap')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                                        @break
                                    @case('paintbrush')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-5 5M4 20l3.5-3.5M15 4l5 5-11 11H4v-5L15 4z"/></svg>
                                        @break
                                    @case('camera')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4" stroke-width="1.5"/></svg>
                                        @break
                                    @case('bell')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/></svg>
                                        @break
                                    @case('hammer')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        @break
                                    @case('paint-roller')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5h16a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1zM12 11v4M10 19h4a1 1 0 001-1v-3H9v3a1 1 0 001 1z"/></svg>
                                        @break
                                    @case('globe')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                        @break
                                    @case('smartphone')
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2" ry="2" stroke-width="1.5"/><path stroke-linecap="round" stroke-width="1.5" d="M12 18h.01"/></svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                @endswitch
                            </div>

                            <div class="inline-block px-3 py-1 rounded-full bg-white/20 border border-white/30 shadow-sm mb-1 text-white">
                                <h3 class="text-sm sm:text-base font-bold font-[Outfit]">
                                    {{ app()->getLocale() === 'ar' ? $sub->name_ar : $sub->name_en }}
                                </h3>
                            </div>

                            <div class="mt-2 flex items-center justify-center text-xs text-white/80 group-hover:text-white transition-colors">
                                <span>{{ $sub->serviceOptions()->count() }} {{ __('options') }}</span>
                                <svg class="w-3 h-3 ml-1 rtl:mr-1 rtl:ml-0 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ── Level 3: Service Options List ────────────────── --}}
        @if($activeSubServiceId)
            <div class="slide-enter">
                {{-- Service description banner --}}
                @if($activeService && ($activeService->description_en || $activeService->description_ar))
                <div class="mb-4 p-4 rounded-2xl bg-gradient-to-br from-violet-50 to-slate-50 border border-violet-100">
                    <div class="flex gap-3">
                        <div class="text-xl shrink-0">{{ $activeService->icon }}</div>
                        <p class="text-sm text-white/80 leading-relaxed">
                            {{ app()->getLocale() === 'ar' ? $activeService->description_ar : $activeService->description_en }}
                        </p>
                    </div>

                    {{-- Construction-specific pricing note --}}
                    @if($activeService->slug === 'construction-contracting')
                    <div class="mt-3 pt-3 border-t border-violet-100">
                        <p class="text-xs font-semibold text-violet-700 mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ app()->getLocale() === 'ar' ? 'كيف يُحسب السعر؟' : 'How is the price calculated?' }}
                        </p>
                        <ul class="space-y-1 text-xs text-white/70">
                            @foreach([
                                ['ar' => '📐 المخطط المعماري — نوع وتعقيد التصميم', 'en' => '📐 Architectural plan — type & complexity'],
                                ['ar' => '📏 المساحة الإجمالية — بالمتر المربع (م²)', 'en' => '📏 Total surface area — in square meters (m²)'],
                                ['ar' => '🧱 المكوّنات — أعمال التشطيب، والمواد، والمرافق', 'en' => '🧱 Components — finishing works, materials & utilities'],
                                ['ar' => '📋 السعر النهائي يُحدَّد بعد مراجعة المشروع', 'en' => '📋 Final price confirmed after project review'],
                            ] as $point)
                            <li>{{ app()->getLocale() === 'ar' ? $point['ar'] : $point['en'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                @endif

                <div class="mb-4">
                    <p class="text-white/70 text-sm">{{ __('Choose your service and set options') }}</p>
                </div>

                <div class="space-y-3">
                    @foreach($serviceOptions as $index => $option)
                        <button wire:click="openModal({{ $option->id }})"
                            class="w-full text-left p-4 sm:p-5 rounded-2xl bg-white/80 border border-slate-100/50 border border-slate-200 hover:bg-white/90 border border-slate-100 hover:border-violet-500/30 transition-all duration-300 group fade-in"
                            style="animation-delay: {{ $index * 100 }}ms"
                            wire:key="option-{{ $option->id }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm sm:text-base group-hover:text-violet-700 transition-colors">
                                        {{ app()->getLocale() === 'ar' ? $option->name_ar : $option->name_en }}
                                    </h4>
                                    <p class="text-xs text-white/70 mt-1">
                                        {{ __('Per') }} {{ app()->getLocale() === 'ar' ? $option->unit_label_ar : $option->unit_label_en }}
                                        · {{ $option->min_quantity }}-{{ $option->max_quantity }} {{ app()->getLocale() === 'ar' ? $option->unit_label_ar : $option->unit_label_en }}
                                    </p>
                                </div>
                                <div class="text-right rtl:text-left">
                                    <div class="text-lg sm:text-xl font-bold text-violet-600 font-[Outfit]">
                                        {{ number_format($option->base_price, 0) }}
                                        <span class="text-xs text-white/70 font-normal">{{ __('SAR') }}</span>
                                    </div>
                                    <div class="text-[10px] text-orange-400/80 mt-0.5">
                                        {{ __('Urgent') }}: {{ number_format($option->base_price * $option->urgent_multiplier, 0) }} {{ __('SAR') }}
                                    </div>
                                </div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    {{-- ── Action Modal (Level 3) ────────────────────────── --}}
    @if($showModal && $selectedOption)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center modal-overlay" wire:click.self="closeModal">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="relative w-full sm:max-w-lg mx-auto bg-white rounded-t-3xl sm:rounded-3xl border border-slate-200 modal-content max-h-[90dvh] overflow-y-auto">
                {{-- Modal Handle --}}
                <div class="flex justify-center pt-3 sm:hidden">
                    <div class="w-10 h-1 bg-orange-50 rounded-full"></div>
                </div>

                {{-- Modal Header --}}
                <div class="px-6 pt-5 pb-4 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold font-[Outfit]">
                                {{ app()->getLocale() === 'ar' ? $selectedOption->name_ar : $selectedOption->name_en }}
                            </h3>
                            <p class="text-xs text-white/70 mt-0.5">
                                {{ app()->getLocale() === 'ar' ? $selectedOption->subService->name_ar : $selectedOption->subService->name_en }}
                            </p>
                        </div>
                        <button wire:click="closeModal" class="w-8 h-8 rounded-lg bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Construction services: pricing explanation banner --}}
                    @if($activeService && $activeService->slug === 'construction-contracting')
                    <div class="mt-3 p-3 rounded-xl bg-orange-50 border border-orange-100">
                        <p class="text-xs text-orange-700 font-semibold mb-1">
                            {{ app()->getLocale() === 'ar' ? '⚠️ ملاحظة: هذا سعر تقديري' : '⚠️ Note: Estimated base price' }}
                        </p>
                        <p class="text-xs text-orange-600 leading-relaxed">
                            {{ app()->getLocale() === 'ar'
                                ? 'السعر المعروض هو تقدير أولي. يُحدَّد السعر النهائي بناءً على المخطط المعماري، المساحة الإجمالية (م²)، وطبيعة المكوّنات والتشطيبات. سيتواصل معك أحد مهندسينا لمراجعة تفاصيل مشروعك.'
                                : 'The displayed price is an initial estimate. The final cost is determined based on the architectural plan, total surface area (m²), and the nature of components & finishes. One of our engineers will contact you to review your project details.'
                            }}
                        </p>
                    </div>
                    @endif
                </div>

                <div class="px-5 sm:px-6 py-4 space-y-5">
                    {{-- Unit Counter --}}
                    <div>
                        <label class="text-sm font-medium text-white/80 block mb-3">
                            {{ __('How many') }} {{ app()->getLocale() === 'ar' ? $selectedOption->unit_label_ar : $selectedOption->unit_label_en }}?
                        </label>
                        <div class="flex items-center justify-center gap-6">
                            <button wire:click="decrementQuantity"
                                class="w-14 h-14 rounded-2xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center text-2xl font-bold transition-all hover:scale-105 active:scale-95 {{ $quantity <= $selectedOption->min_quantity ? 'opacity-30 cursor-not-allowed' : '' }}">
                                −
                            </button>
                            <div class="text-4xl font-bold font-[Outfit] min-w-[4rem] text-center text-violet-600 counter-spin">
                                {{ $quantity }}
                            </div>
                            <button wire:click="incrementQuantity"
                                class="w-14 h-14 rounded-2xl bg-white/90 border border-slate-100 hover:bg-orange-50 flex items-center justify-center text-2xl font-bold transition-all hover:scale-105 active:scale-95 {{ $quantity >= $selectedOption->max_quantity ? 'opacity-30 cursor-not-allowed' : '' }}">
                                +
                            </button>
                        </div>
                    </div>

                    {{-- Urgency Toggle --}}
                    <div>
                        <label class="text-sm font-medium text-white/80 block mb-3">{{ __('When do you need it?') }}</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button wire:click="$set('urgency', 'scheduled')"
                                class="p-4 rounded-2xl border-2 transition-all duration-300 text-center {{ $urgency === 'scheduled' ? 'border-blue-500 bg-violet-500/10' : 'border-slate-200 bg-white/80 border border-slate-100/50 hover:border-slate-300' }}">
                                <svg class="w-6 h-6 mx-auto mb-2 {{ $urgency === 'scheduled' ? 'text-violet-600' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-sm font-semibold {{ $urgency === 'scheduled' ? 'text-violet-700' : 'text-white/80' }}">{{ __('Scheduled') }}</div>
                                <div class="text-xs text-white/70 mt-0.5">{{ __('Pick a time') }}</div>
                            </button>
                            <button wire:click="$set('urgency', 'urgent')"
                                class="p-4 rounded-2xl border-2 transition-all duration-300 text-center {{ $urgency === 'urgent' ? 'border-red-500 bg-red-500/10 urgent-flash' : 'border-slate-200 bg-white/80 border border-slate-100/50 hover:border-slate-300' }}">
                                <svg class="w-6 h-6 mx-auto mb-2 {{ $urgency === 'urgent' ? 'text-red-400' : 'text-white/70' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div class="text-sm font-semibold {{ $urgency === 'urgent' ? 'text-red-300' : 'text-white/80' }}">{{ __('Urgent') }}</div>
                                <div class="text-xs text-white/70 mt-0.5">{{ __('Right Now') }}</div>
                            </button>
                        </div>
                    </div>

                    {{-- Address / Location --}}
                    <div>
                        <label class="text-sm font-medium text-white/80 block mb-3">{{ __('Your Location') }}</label>
                        <div class="relative">
                            <input type="text" wire:model="address" placeholder="{{ __('Enter your address or use GPS') }}"
                                class="w-full px-4 py-3.5 pl-12 rtl:pl-4 rtl:pr-12 rounded-2xl bg-white/80 border border-slate-100/50 border border-slate-200 text-sm text-white/95 placeholder-slate-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all">
                            <svg class="w-5 h-5 text-white/70 absolute left-4 rtl:right-4 rtl:left-auto top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <button type="button" onclick="getLocation()" class="mt-2 text-xs text-violet-600 hover:text-violet-700 flex items-center gap-1 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Use my current location') }}
                        </button>
                    </div>

                    {{-- Price Summary --}}
                    <div class="glass rounded-2xl p-5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-white/70">{{ __('Unit Price') }}</span>
                            <span class="text-sm">
                                @if($urgency === 'urgent')
                                    <span class="line-through text-white/70 mr-1">{{ number_format($selectedOption->base_price, 2) }}</span>
                                    {{ number_format($selectedOption->base_price * $selectedOption->urgent_multiplier, 2) }}
                                @else
                                    {{ number_format($selectedOption->base_price, 2) }}
                                @endif
                                {{ __('SAR') }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-white/70">{{ __('Quantity') }}</span>
                            <span class="text-sm">{{ $quantity }} {{ app()->getLocale() === 'ar' ? $selectedOption->unit_label_ar : $selectedOption->unit_label_en }}</span>
                        </div>
                        @if($urgency === 'urgent')
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-orange-400">{{ __('Urgent Fee') }}</span>
                                <span class="text-sm text-orange-400">{{ number_format(($selectedOption->urgent_multiplier - 1) * 100, 0) }}%</span>
                            </div>
                        @endif
                        <div class="border-t border-slate-200 my-3"></div>
                        <div class="flex items-center justify-between">
                            <span class="font-semibold">{{ __('Total') }}</span>
                            <span class="text-2xl font-bold font-[Outfit] text-violet-600">
                                {{ number_format($calculatedPrice, 2) }}
                                <span class="text-sm font-normal text-white/70">{{ __('SAR') }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="sticky bottom-0 bg-white/95 backdrop-blur-md pt-3 pb-6 mt-2 border-t border-slate-100/80 -mx-5 sm:-mx-6 px-5 sm:px-6 z-10">
                        @auth
                            <button wire:click="confirmBooking"
                                data-tooltip="{{ app()->getLocale() === 'ar' ? 'سيُرسَل فني معتمد إلى موقعك فوراً' : 'A verified technician will be dispatched to your location' }}"
                                class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold text-base shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                {{ __('Confirm Booking & Pay') }}
                            </button>
                        @else
                            @if($showOtpInput)
                                <div class="space-y-3 pb-2">
                                    <div class="relative">
                                        <input type="text" wire:model="otp" placeholder="{{ __('Enter OTP (Check Demo logs)') }}" class="w-full text-center tracking-[0.5em] text-xl font-bold px-4 py-3 rounded-2xl bg-white/5 border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all">
                                    </div>
                                    @error('otp') <p class="text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                                    <div class="grid grid-cols-2 gap-2">
                                        <button wire:click="$set('showOtpInput', false)" class="py-3.5 rounded-2xl bg-white/10 text-white/80 font-bold text-sm hover:bg-slate-200 transition-all">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button wire:click="verifyAndBook" class="py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold text-sm shadow-lg shadow-orange-500/20 transition-all active:scale-[0.98]">
                                            {{ __('Verify & Book') }}
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-3 pb-2">
                                    <p class="text-[11px] text-center text-white/70 font-medium">{{ __('No account needed! Just verify your phone.') }}</p>
                                    <div class="relative">
                                        <input type="tel" wire:model="phone" placeholder="{{ __('Phone Number') }}" class="w-full px-4 py-3.5 rtl:pr-4 pl-12 rounded-2xl bg-white/5 border border-slate-200 text-sm text-white/95 placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all" dir="ltr">
                                        <svg class="w-5 h-5 text-white/60 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    @error('phone') <p class="text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                                    <button wire:click="sendOtp" class="w-full py-3.5 rounded-2xl bg-slate-800 text-white font-bold text-sm shadow-lg shadow-slate-800/20 hover:shadow-slate-800/40 transition-all hover:scale-[1.02] active:scale-[0.98]">
                                        {{ __('Send OTP & Book') }}
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── About Us Section ─────────────────────────────── --}}
    @if(!$activeServiceId)
    <section id="about-us" class="max-w-7xl mx-auto px-4 sm:px-6 py-16 pb-8">

        {{-- Section Header --}}
        <div class="text-center mb-12 bg-[#1a0a2e]/60 backdrop-blur-xl border border-white/10 rounded-[2rem] p-8 sm:p-12 shadow-2xl max-w-4xl mx-auto ring-1 ring-white/5 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-violet-500/10 to-transparent"></div>
            <div class="relative z-10">
                <span class="inline-block px-4 py-1.5 rounded-full bg-violet-500/20 text-violet-300 border border-violet-500/20 text-xs font-bold tracking-widest uppercase mb-4">
                    {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black font-[Outfit] text-white mb-5 drop-shadow-md">
                    {{ app()->getLocale() === 'ar' ? 'شركة الكفاح العالمية' : 'Al Kifah International Company' }}
                </h2>
                <p class="text-white/80 max-w-2xl mx-auto text-base sm:text-lg leading-relaxed mix-blend-plus-lighter">
                    {{ app()->getLocale() === 'ar'
                        ? 'شركة سعودية رائدة في تقديم خدمات الصيانة المنزلية والمقاولات والخدمات التقنية المتخصصة، نسعى دائماً لتقديم أعلى مستويات الجودة والاحترافية لعملائنا.'
                        : 'A leading Saudi company specializing in home maintenance, contracting, and technical services — committed to delivering the highest quality and professionalism to our clients.' }}
                </p>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
            @php
                $stats = [
                    ['num' => '+15', 'en' => 'Years Experience', 'ar' => 'سنة خبرة'],
                    ['num' => '+500', 'en' => 'Projects Completed', 'ar' => 'مشروع منجز'],
                    ['num' => '+50', 'en' => 'Certified Technicians', 'ar' => 'فني معتمد'],
                    ['num' => '5★', 'en' => 'Client Rating', 'ar' => 'تقييم العملاء'],
                ];
            @endphp
            @foreach($stats as $stat)
            <div class="bg-white rounded-2xl p-5 text-center shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="text-3xl font-black font-[Outfit] bg-gradient-to-br from-orange-500 to-violet-600 bg-clip-text text-transparent">{{ $stat['num'] }}</div>
                <div class="text-xs text-slate-500 mt-1 font-medium">{{ app()->getLocale() === 'ar' ? $stat['ar'] : $stat['en'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Two-column info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- About card --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-violet-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 font-[Outfit]">
                        {{ app()->getLocale() === 'ar' ? 'رؤيتنا ومهمتنا' : 'Our Vision & Mission' }}
                    </h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ app()->getLocale() === 'ar'
                        ? 'نسعى لأن نكون الخيار الأول في مجال الخدمات المنزلية والتقنية في المملكة العربية السعودية، من خلال فريق من الفنيين المعتمدين والمدربين على أعلى مستوى، مع الالتزام بمعايير السلامة والجودة في كل مشروع.'
                        : 'We aim to be the first choice for home and technical services in Saudi Arabia, through a team of certified and highly trained technicians, committed to safety and quality standards in every project.' }}
                </p>
            </div>

            {{-- Registration info --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 font-[Outfit]">
                        {{ app()->getLocale() === 'ar' ? 'البيانات التجارية' : 'Commercial Registration' }}
                    </h3>
                </div>
                <ul class="space-y-2.5 text-sm">
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'اسم الشركة' : 'Company Name' }}</span>
                        <span class="font-semibold text-slate-800 text-end">{{ app()->getLocale() === 'ar' ? 'شركة الكفاح العالمية' : 'Al Kifah International Company' }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'السجل التجاري' : 'C.R' }}</span>
                        <span class="font-semibold text-slate-800 font-mono">1010186250</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'رقم ض.ق.م' : 'VAT Number' }}</span>
                        <span class="font-semibold text-slate-800 font-mono text-xs">301053406400003</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}</span>
                        <span class="font-semibold text-slate-800 text-end text-xs">{{ app()->getLocale() === 'ar' ? 'الرياض، ظهرة لبن، الشفاء' : 'Riyadh, Dhahrat Laban' }}</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'الرمز البريدي' : 'ZIP Code' }}</span>
                        <span class="font-semibold text-slate-800 font-mono">13782</span>
                    </li>
                    <li class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'الهاتف' : 'Phone' }}</span>
                        <a href="tel:114247888" class="font-semibold text-violet-600 hover:text-violet-800 transition-colors font-mono">114247888</a>
                    </li>
                    <li class="flex items-center justify-between py-2">
                        <span class="text-slate-500">{{ app()->getLocale() === 'ar' ? 'المدير العام' : 'Director' }}</span>
                        <span class="font-semibold text-slate-800 text-end text-xs">{{ app()->getLocale() === 'ar' ? 'مسفر بن عبدالله الهرجاني' : 'Musfer Al-Harjani' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    {{-- ── Footer ──────────────────────────────────────── --}}
    <footer class="mt-8 pb-28 sm:pb-8"
            style="background: linear-gradient(180deg, #0f0620 0%, #1a0a2e 100%); color: rgba(255,255,255,0.7);">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-14 pb-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-10 mb-10">

                {{-- Brand Column --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="/images/logo.png" alt="Al-Kifah Logo" class="w-12 h-12 rounded-xl object-cover">
                        <div>
                            <p class="text-white font-bold font-[Outfit] text-base">{{ app()->getLocale() === 'ar' ? 'الكفاح العالمية' : 'Al Kifah International' }}</p>
                            <p class="text-violet-300/60 text-xs">{{ app()->getLocale() === 'ar' ? 'خدمات منزلية متميزة' : 'Premium Home Services' }}</p>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed opacity-60">
                        {{ app()->getLocale() === 'ar'
                            ? 'شركة الكفاح العالمية — شريكك الموثوق في خدمات الصيانة والمقاولات بالمملكة العربية السعودية.'
                            : 'Al Kifah International Company — your trusted partner for maintenance and contracting services across Saudi Arabia.' }}
                    </p>
                    {{-- Stamp --}}
                    <div class="mt-4">
                        <img src="/AlkifahDocuments/alkifahstamp.JPG" alt="Company Stamp"
                             class="w-24 h-24 object-contain opacity-70 hover:opacity-100 transition-opacity">
                    </div>
                </div>

                {{-- Services Column --}}
                <div>
                    <h4 class="text-white font-bold font-[Outfit] mb-4">
                        {{ app()->getLocale() === 'ar' ? 'خدماتنا' : 'Our Services' }}
                    </h4>
                    <ul class="space-y-2 text-sm opacity-70">
                        @foreach($services->take(6) as $svc)
                        <li class="hover:opacity-100 transition-opacity cursor-pointer"
                            wire:click="selectService({{ $svc->id }})">
                            → {{ app()->getLocale() === 'ar' ? $svc->name_ar : $svc->name_en }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contact Column --}}
                <div>
                    <h4 class="text-white font-bold font-[Outfit] mb-4">
                        {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                    </h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5 opacity-70">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'الرياض، حي ظهرة لبن، الشفاء — ص.ب 3274، الرمز 13782' : 'Riyadh, Dhahrat Laban, Al-Shafa — P.O Box 3274, ZIP 13782' }}</span>
                        </li>
                        <li class="flex items-center gap-2.5 opacity-70">
                            <svg class="w-4 h-4 shrink-0 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <a href="tel:114247888" dir="ltr" class="hover:text-orange-400 transition-colors">114247888</a>
                        </li>
                        <li class="flex items-center gap-2.5 opacity-70">
                            <svg class="w-4 h-4 shrink-0 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'س.ت: ' : 'C.R: ' }}1010186250</span>
                        </li>
                        <li class="flex items-center gap-2.5 opacity-70">
                            <svg class="w-4 h-4 shrink-0 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'ض.ق.م: ' : 'VAT: ' }}301053406400003</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-white/10 pt-5 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs opacity-40">
                    &copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'شركة الكفاح العالمية. جميع الحقوق محفوظة.' : 'Al Kifah International Company. All rights reserved.' }}
                </p>
                <div class="flex items-center gap-3 text-xs opacity-40">
                    <a href="{{ route('locale.switch', 'ar') }}" class="hover:opacity-100 transition-opacity">العربية</a>
                    <span>·</span>
                    <a href="{{ route('locale.switch', 'en') }}" class="hover:opacity-100 transition-opacity">English</a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    {{-- ── Mobile Bottom Navigation ──────────────────────── --}}
    <nav class="bottom-nav border-t border-white/10 sm:hidden"
         style="background: linear-gradient(180deg, #2d1a4a 0%, #1a0a2e 100%);
                padding-bottom: env(safe-area-inset-bottom);">
        <div class="grid grid-cols-4 gap-0">

            {{-- Home --}}
            <a href="{{ route('home') }}"
               class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200
                      {{ !$activeServiceId ? 'text-violet-300' : 'text-white/40 hover:text-white/70' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ !$activeServiceId ? '2.2' : '1.7' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[9px] mt-0.5 font-medium">{{ __('Home') }}</span>
            </a>

            {{-- Search --}}
            <button @click="window.scrollTo({top:0,behavior:'smooth'}); mobileMenuOpen = true; setTimeout(() => { document.getElementById('mobileSearchInput').focus(); }, 300);"
                    class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70 cursor-pointer">
                <svg class="w-5 h-5 focus:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span class="text-[9px] mt-0.5 font-medium">{{ __('Search') }}</span>
            </button>

            @auth
                @php $role = auth()->user()->role->value; @endphp

                @if(in_array($role, ['super_admin', 'technical_manager']))
                    {{-- Admin / Manager: go to their dashboard --}}
                    <a href="{{ $role === 'super_admin' ? route('admin.dashboard') : route('manager.dashboard') }}"
                       class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-orange-300/80 hover:text-orange-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-[9px] mt-0.5 font-medium">{{ __('Dashboard') }}</span>
                    </a>

                    {{-- Logout for admin --}}
                    <form method="POST" action="{{ route('logout') }}" class="contents">
                        @csrf
                        <button type="submit" style="background:none;border:none;"
                                class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-red-400/70 hover:text-red-300 cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="text-[9px] mt-0.5 font-medium">{{ __('Logout') }}</span>
                        </button>
                    </form>

                @elseif($role === 'technician')
                    {{-- Technician: My Jobs --}}
                    <a href="{{ route('technician.dashboard') }}"
                       class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <span class="text-[9px] mt-0.5 font-medium">{{ __('My Jobs') }}</span>
                    </a>

                    {{-- Technician profile --}}
                    <a href="{{ route('technician.dashboard') }}"
                       class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-[9px] mt-0.5 font-medium">{{ __('Profile') }}</span>
                    </a>

                @else
                    {{-- Client: My Bookings --}}
                    <a href="{{ route('client.dashboard') }}"
                       class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-[9px] mt-0.5 font-medium">{{ __('My Bookings') }}</span>
                    </a>

                    {{-- Client profile --}}
                    <a href="{{ route('client.dashboard') }}"
                       class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-[9px] mt-0.5 font-medium">{{ __('Profile') }}</span>
                    </a>
                @endif

            @else
                {{-- Guest: Orders → login --}}
                <a href="{{ route('login') }}"
                   class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span class="text-[9px] mt-0.5 font-medium">{{ __('Orders') }}</span>
                </a>

                {{-- Guest: Profile → login --}}
                <a href="{{ route('login') }}"
                   class="flex flex-col items-center justify-center py-2.5 px-1 transition-all duration-200 text-white/40 hover:text-white/70">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-[9px] mt-0.5 font-medium">{{ __('Profile') }}</span>
                </a>
            @endauth

        </div>
    </nav>
</div>

@push('scripts')
<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                @this.set('latitude', position.coords.latitude);
                @this.set('longitude', position.coords.longitude);
                @this.set('address', 'Location detected (' + position.coords.latitude.toFixed(4) + ', ' + position.coords.longitude.toFixed(4) + ')');
            }, function(error) {
                alert('{{ app()->getLocale() === 'ar' ? 'تعذر تحديد موقعك. الرجاء إدخاله يدوياً.' : 'Unable to get location. Please enter your address manually.' }}');
            });
        }
    }

    // Launch first-visit tour after a short delay
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => window.KifahTour?.launch('home'), 800);
    });
</script>
@endpush

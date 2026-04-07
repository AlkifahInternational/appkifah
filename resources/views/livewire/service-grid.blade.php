@section('title', $metaTitle)

@section('meta')
    <meta name="description" content="{{ app()->getLocale() === 'ar' ? 'احسن موقع خدمات في السعودية: اسرع خدمات صيانة، ارخص خدمات برمجة، واسهل تعاملات مع مرونة كاملة. شركة الكفاح العالمية توفر حلولاً متكاملة لاحتياجاتك.' : $metaDescription }}">
    <meta name="keywords" content="احسن موقع خدمات, اسرع خدمات صيانة, ارخص خدمات برمجة, اسهل تعاملات, مرونة في التعامل, الكفاح العالمية">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ app()->getLocale() === 'ar' ? 'اكتشف احسن موقع خدمات في المملكة. نقوم بتوفير اسرع خدمات صيانة وارخص خدمات برمجة مع اسهل تعاملات لضمان رضاكم.' : $metaDescription }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ app()->getLocale() === 'ar' ? 'شركة الكفاح: اسرع خدمات صيانة، ارخص خدمات برمجة، واسهل تعاملات.' : $metaDescription }}">
@endsection

<div x-data="{ mobileMenuOpen: false }" class="min-h-dvh gradient-dark text-white/95">
    {{-- ── National Service Schema (SEO) ────────────────────────── --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Service",
      "name": "{{ $metaTitle }}",
      "description": "{{ $metaDescription }}",
      "url": "https://app.alkifahic.com",
      "logo": "{{ asset('images/logo.png') }}",
      "sameAs": [
        "https://www.facebook.com/alkifahinternational",
        "https://twitter.com/alkifahic",
        "https://www.linkedin.com/company/alkifah-international"
      ],
      "provider": {
        "@@type": "LocalBusiness",
        "name": "Al-Kifah Global",
        "image": "{{ asset('images/logo.png') }}",
        "address": {
          "@@type": "PostalAddress",
          "addressCountry": "SA",
          "addressRegion": "Al-Ahsa"
        }
      },
      "areaServed": "Saudi Arabia",
      "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "Professional Contracting Services",
        "itemListElement": [
          {
            "@@type": "Offer",
            "itemOffered": {
              "@@type": "Service",
              "name": "Construction"
            }
          },
          {
            "@@type": "Offer",
            "itemOffered": {
              "@@type": "Service",
              "name": "Maintenance"
            }
          }
        ]
      }
    }
    </script>

    {{-- ── Top Header Bar ────────────────────────────────────── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            {{-- Brand Section --}}
            <div class="flex items-center gap-3">
                @if($activeServiceId)
                    <button wire:click="goBack"
                        class="w-10 h-10 rounded-xl bg-white/10 border border-white/10 hover:bg-white/20 flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95">
                        <svg class="w-5 h-5 text-white/80 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}?v={{ @filemtime(public_path('images/logo.png')) ?: time() }}" alt="Al-Kifah"
                             class="w-12 h-12 rounded-xl object-cover shrink-0 shadow-lg shadow-black/40">
                    </a>
                @endif
                <div class="bg-white/5 backdrop-blur-md rounded-2xl p-3 border border-white/10 shadow-xl shadow-black/20 flex flex-col justify-center">
                    <h1 class="text-xl sm:text-2xl font-bold font-[Outfit] tracking-tighter leading-none {{ $activeServiceId ? 'text-white' : 'text-white/95' }}">
                        @if($activeSubService)
                            {{ app()->getLocale() === 'ar' ? $activeSubService->name_ar : $activeSubService->name_en }}
                        @elseif($activeService)
                            {{ app()->getLocale() === 'ar' ? $activeService->name_ar : $activeService->name_en }}
                        @else
                            @brand
                        @endif
                    </h1>
                    <p class="text-xs text-white/60 mt-1">@highlight(__('Professional Services at Your Fingertips'), 'Professional')</p>
                </div>
            </div>

            {{-- Navigation Actions --}}
            <div class="flex items-center gap-3">
                {{-- Desktop Nav --}}
                <div class="hidden sm:flex items-center gap-3">
                    <button onclick="window.KifahTour?.resetAll(); setTimeout(()=>window.KifahTour?.launch('home'),100)"
                            class="flex items-center gap-1.5 px-4 py-2 bg-white/5 rounded-xl transition-all text-sm font-semibold text-white/80 border border-white/10 hover:bg-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ app()->getLocale() === 'ar' ? 'كيف يعمل؟' : 'How it Works' }}
                    </button>

                    <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                       class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center transition-all text-sm font-bold text-white hover:bg-white/20">
                        {{ app()->getLocale() === 'ar' ? 'EN' : 'ع' }}
                    </a>

                    @if(auth()->check())
                        <div class="relative w-full sm:w-auto" x-data="{ open: false }">
                             <button @click="open = !open" class="hidden sm:flex w-10 h-10 rounded-xl bg-orange-500/20 items-center justify-center transition-all relative text-orange-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </button>
                        </div>
                        <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl bg-violet-600 text-white text-sm font-bold hover:bg-violet-700 transition-all">
                            {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-white text-sm font-bold hover:shadow-lg transition-all">
                            {{ __('Login') }}
                        </a>
                    @endif
                </div>

                {{-- Mobile Hamburger --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="sm:hidden w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" style="display:none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Full-Screen Menu --}}
        <template x-if="mobileMenuOpen">
            <div class="sm:hidden bg-[#1a0a2e] border-t border-white/10 p-5 space-y-4">
                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl bg-white/5 text-white font-medium">
                    {{ __('Home') }}
                </a>
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl bg-violet-600 text-white font-bold text-center">
                        {{ __('Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold text-center">
                        {{ __('Login') }}
                    </a>
                @endif
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="block px-4 py-3 rounded-xl bg-white/10 text-white text-center font-bold">
                    {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                </a>
            </div>
        </template>
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

            {{-- ── Smart Visual Diagnosis CTA Banner ─────────────────────── --}}
            <div class="mb-8 relative overflow-hidden rounded-[2rem] bg-linear-to-r from-[#1e0b35] to-[#120720] border border-white/10 shadow-2xl p-6 sm:p-8 flex flex-col items-center text-center group cursor-pointer" onclick="Livewire.dispatch('openAiModal')">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-orange-500/20 blur-[50px] rounded-full pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-violet-600/20 blur-[50px] rounded-full pointer-events-none"></div>

                {{-- Chatbot Avatar --}}
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-linear-to-br from-orange-500 to-violet-600 p-[2px] mb-4 shadow-lg shadow-orange-500/30 group-hover:scale-110 transition-transform duration-300">
                    <div class="w-full h-full rounded-full bg-[#120720] flex items-center justify-center">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                </div>

                <h3 class="text-xl sm:text-2xl font-bold font-[Outfit] text-white mb-2">
                    {{ app()->getLocale() === 'ar' ? 'تشخيص ذكي بالمجان' : 'Smart Free Diagnosis' }}
                </h3>
                <p class="text-sm sm:text-base text-white/70 mb-5 max-w-lg">
                    {{ app()->getLocale() === 'ar' ? 'لست متأكداً من المشكلة؟ التقط صورة أو ارفع صورتها، ودع الذكاء الاصطناعي يحللها لك ويقترح الخدمة المناسبة فوراً.' : 'Not sure what the problem is? Take a picture, and let our AI analyze it to suggest the right service instantly.' }}
                </p>

                <button @click="$dispatch('openAiModal')"
                        class="w-full max-w-xs py-4 rounded-2xl bg-linear-to-r from-orange-500 to-violet-600 text-white font-bold text-base shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all relative overflow-hidden group">
                    <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    {{-- Help Span Badge --}}
                    <span class="absolute -top-1 -right-1 bg-white text-orange-600 text-[9px] font-black px-2 py-0.5 rounded-bl-lg shadow-sm border border-orange-100 animate-pulse">
                        {{ app()->getLocale() === 'ar' ? 'تشخيص ذكي' : 'AI SMART' }}
                    </span>
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <circle cx="12" cy="13" r="3" stroke-width="2"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'صور مشكلتك الآن' : 'Picture Your Problem Now' }}
                    </span>
                </button>
            </div>

            <div id="services-grid" class="text-center mb-16 relative py-6 scroll-mt-24">
                {{-- Decorative background glow --}}
                <div class="absolute inset-0 bg-violet-600/5 blur-[120px] rounded-full -z-10"></div>
                
                <h2 class="text-4xl sm:text-6xl font-black font-[Outfit] text-white tracking-tight leading-tight mb-5 drop-shadow-2xl">
                    {{ app()->getLocale() === 'ar' ? 'خدماتنا المميزة' : __('Our Services') }}
                </h2>
                
                <div class="inline-flex items-center gap-3 px-8 py-3 rounded-full bg-orange-500 border border-orange-400 shadow-[0_10px_40px_-10px_rgba(249,115,22,0.5)] hover:scale-105 transition-all cursor-default">
                    <span class="flex h-2.5 w-2.5 rounded-full bg-white animate-pulse"></span>
                    <p class="text-base sm:text-lg font-black text-[#120720] uppercase tracking-wider leading-none">
                        {{ app()->getLocale() === 'ar' ? 'اختر فئة الخدمة للبدء' : 'Select a service category to begin' }}
                    </p>
                </div>
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

                        {{-- Journey Badge --}}
                        <div class="flex items-center gap-1 mb-2">
                            @if($service->slug === 'security-systems-cctv')
                                <span class="px-2 py-0.5 rounded-md bg-black/40 backdrop-blur-md border border-white/20 text-[9px] uppercase tracking-widest font-black text-white flex items-center gap-1 shadow-lg">
                                    <svg class="w-3 h-3 text-orange-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ app()->getLocale() === 'ar' ? 'الأكثر تقييماً' : 'Top Rated' }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-black/30 border border-white/20 text-[9px] uppercase tracking-widest font-black text-white">
                                    {{ app()->getLocale() === 'ar' ? 'الخطوة 1' : 'Step 1' }}
                                </span>
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

        {{-- ── Level 2A: Direct Product Grid (Camera, Software, etc.) ────────────── --}}
        @if($directMode && $activeServiceId && !$activeSubServiceId)
            <div x-init="$nextTick(() => { $el.scrollIntoView({ behavior: 'smooth', block: 'start' }) })" class="slide-enter scroll-mt-24" id="items-grid">

                {{-- Header --}}
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-500/20 text-xs font-bold text-orange-400 border border-orange-500/30">2</span>
                        <h2 class="text-lg font-bold text-white">{{ app()->getLocale() === 'ar' ? 'الخطوة 2: حدد خياراتك' : 'Step 2: Customize Your Selection' }}</h2>
                    </div>
                    
                    @if(count($cart) > 0)
                        <p class="text-sm font-semibold text-violet-200">
                            ✓ {{ count($cart) }} {{ app()->getLocale() === 'ar' ? 'منتج مختار' : 'selected' }}
                            · {{ number_format($calculatedPrice, 0) }} SAR
                        </p>
                    @else
                        <p class="text-black text-sm">{{ app()->getLocale() === 'ar' ? 'اختر المنتجات والخدمات — يمكنك اختيار أكثر من عنصر' : 'Select products & services — pick as many as you need' }}</p>
                    @endif
                </div>

                {{-- Product groups with Dark Violet Wrapper --}}
                <div class="bg-violet-950/60 p-4 sm:p-6 rounded-[2rem] border border-violet-500/20 shadow-inner mb-6">
                @foreach($directGroupedOptions as $group)
                    @php
                        $sub = $group['sub'];
                        $isTwoColumn = ($group['layout'] ?? 'one-column') === 'two-column';
                        $catMeta = [
                            'camera-packages'        => ['icon'=>'📦','accent'=>'from-violet-500 to-violet-700','border'=>'border-violet-500/50','bg'=>'bg-violet-500/10','text'=>'text-violet-300','badge'=>'bg-violet-500/20 text-violet-200','price'=>'text-violet-300'],
                            'store-installation'     => ['icon'=>'🔧','accent'=>'from-orange-500 to-orange-700','border'=>'border-orange-500/50','bg'=>'bg-orange-500/10','text'=>'text-orange-300','badge'=>'bg-orange-500/20 text-orange-200','price'=>'text-orange-300'],
                            'store-attendance-locks' => ['icon'=>'🔐','accent'=>'from-blue-500 to-blue-700',  'border'=>'border-blue-500/50',  'bg'=>'bg-blue-500/10',  'text'=>'text-blue-300',  'badge'=>'bg-blue-500/20 text-blue-200',  'price'=>'text-blue-300'],
                            'store-dashcams'         => ['icon'=>'🚗','accent'=>'from-teal-500 to-teal-700',  'border'=>'border-teal-500/50',  'bg'=>'bg-teal-500/10',  'text'=>'text-teal-300',  'badge'=>'bg-teal-500/20 text-teal-200',  'price'=>'text-teal-300'],
                            'cctv-cameras'           => ['icon'=>'📷','accent'=>'from-rose-500 to-rose-700',  'border'=>'border-rose-500/50',  'bg'=>'bg-rose-500/10',  'text'=>'text-rose-300',  'badge'=>'bg-rose-500/20 text-rose-200',  'price'=>'text-rose-300'],
                            'cctv-recorders'         => ['icon'=>'💾','accent'=>'from-slate-500 to-slate-600','border'=>'border-slate-500/50','bg'=>'bg-slate-500/10','text'=>'text-slate-300','badge'=>'bg-slate-500/20 text-slate-200','price'=>'text-slate-300'],
                            'cctv-accessories'       => ['icon'=>'🔌','accent'=>'from-green-500 to-green-700','border'=>'border-green-500/50','bg'=>'bg-green-500/10','text'=>'text-green-300','badge'=>'bg-green-500/20 text-green-200','price'=>'text-green-300'],
                            'alarm-systems'          => ['icon'=>'🔔','accent'=>'from-red-500 to-red-700',    'border'=>'border-red-500/50',  'bg'=>'bg-red-500/10',  'text'=>'text-red-300',  'badge'=>'bg-red-500/20 text-red-200',    'price'=>'text-red-300'],
                            'intercom-access'        => ['icon'=>'📟','accent'=>'from-indigo-500 to-indigo-700','border'=>'border-indigo-500/50','bg'=>'bg-indigo-500/10','text'=>'text-indigo-300','badge'=>'bg-indigo-500/20 text-indigo-200','price'=>'text-indigo-300'],
                            'smart-home'             => ['icon'=>'🏠','accent'=>'from-amber-500 to-amber-700','border'=>'border-amber-500/50','bg'=>'bg-amber-500/10','text'=>'text-amber-300','badge'=>'bg-amber-500/20 text-amber-200','price'=>'text-amber-300'],
                            
                            // Software & Marketing Additions
                            'web-development'        => ['icon'=>'🌐','accent'=>'from-blue-500 to-blue-700',  'border'=>'border-blue-500/50',  'bg'=>'bg-blue-500/10',  'text'=>'text-blue-300',  'badge'=>'bg-blue-500/20 text-blue-200',  'price'=>'text-blue-300'],
                            'mobile-apps'            => ['icon'=>'📱','accent'=>'from-purple-500 to-purple-700',  'border'=>'border-purple-500/50',  'bg'=>'bg-purple-500/10',  'text'=>'text-purple-300',  'badge'=>'bg-purple-500/20 text-purple-200',  'price'=>'text-purple-300'],
                            'pos-systems'            => ['icon'=>'💻','accent'=>'from-emerald-500 to-emerald-700',  'border'=>'border-emerald-500/50',  'bg'=>'bg-emerald-500/10',  'text'=>'text-emerald-300',  'badge'=>'bg-emerald-500/20 text-emerald-200',  'price'=>'text-emerald-300'],
                            'digital-marketing'      => ['icon'=>'📢','accent'=>'from-pink-500 to-pink-700',  'border'=>'border-pink-500/50',  'bg'=>'bg-pink-500/10',  'text'=>'text-pink-300',  'badge'=>'bg-pink-500/20 text-pink-200',  'price'=>'text-pink-300'],
                            'seo-analytics'          => ['icon'=>'📈','accent'=>'from-cyan-500 to-cyan-700',  'border'=>'border-cyan-500/50',  'bg'=>'bg-cyan-500/10',  'text'=>'text-cyan-300',  'badge'=>'bg-cyan-500/20 text-cyan-200',  'price'=>'text-cyan-300'],
                        ];
                        $meta    = $catMeta[$sub->slug] ?? $catMeta['cctv-cameras'];
                        $catIcon = $meta['icon'];
                    @endphp

                        <div wire:key="group-{{ $sub->id }}">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg">{{ $catIcon }}</span>
                                <h3 class="text-sm font-bold text-white">{{ app()->getLocale() === 'ar' ? $sub->name_ar : $sub->name_en }}</h3>
                                <span class="text-[10px] {{ $meta['text'] }}">{{ $group['options']->count() }}</span>
                            </div>

                            {{-- Product Cards (Two column grid for everything) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                                @foreach($group['options'] as $index => $option)
                                    @php $inCart = isset($cart[$option->id]); @endphp

                                    @if($sub->slug === 'camera-packages' && $loop->index === 4)
                                        <div class="col-span-full py-2 mb-2">
                                            <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-orange-500/10 border border-orange-500/20">
                                                <svg class="w-5 h-5 text-orange-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm font-bold text-orange-300">
                                                    {{ app()->getLocale() === 'ar' ? 'جميع الأسعار أدناه لا تشمل رسوم التركيب' : 'All the below items price are without installation fees' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <div wire:key="cam-{{ $option->id }}" class="h-full">
                                        <div wire:click="toggleOption({{ $option->id }})"
                                            class="cursor-pointer h-full w-full flex flex-col text-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} rounded-xl border-l-4 {{ $meta['border'] }} p-3 border border-white/10 transition-all
                                                {{ $inCart ? 'bg-white/10 border-white/20' : 'bg-white/5 hover:bg-white/8' }}">

                                            <div class="flex items-center justify-between gap-2">
                                                {{-- Name + desc --}}
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-sm text-white leading-tight">{{ app()->getLocale() === 'ar' ? $option->name_ar : $option->name_en }}</p>
                                                    @php $desc = app()->getLocale() === 'ar' ? $option->description_ar : $option->description_en; @endphp
                                                    @if($desc)
                                                        <p class="text-xs text-violet-100/70 mt-0.5 line-clamp-1">{{ $desc }}</p>
                                                    @endif
                                                </div>

                                                {{-- Price --}}
                                                <div class="shrink-0 text-center ml-2">
                                                    <span class="text-lg font-black font-[Outfit] {{ $inCart ? $meta['text'] : 'text-white' }}">{{ number_format($option->base_price, 0) }}</span>
                                                    <span class="text-[9px] text-white/40 block">SAR</span>
                                                </div>

                                                {{-- Check icon --}}
                                                @if($inCart)
                                                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center shrink-0">
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    </div>
                                                @endif
                                            </div>

                                            @if($inCart)
                                            <div class="mt-auto pt-2 border-t border-white/10 flex items-center justify-between w-full" wire:click.stop>
                                                <span class="text-xs {{ $meta['text'] }} font-semibold">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Qty' }}</span>
                                                <div class="flex items-center rounded-lg border border-white/20 overflow-hidden bg-black/20">
                                                    <button wire:click.stop="updateCartQuantity({{ $option->id }}, -1)" class="w-8 h-7 flex items-center justify-center text-white hover:bg-white/10 font-bold">
                                                        @if($cart[$option->id]['quantity'] <= 1)
                                                            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        @else
                                                            <span class="text-lg">−</span>
                                                        @endif
                                                    </button>
                                                    <span class="w-8 text-center text-sm font-bold text-white">{{ $cart[$option->id]['quantity'] }}</span>
                                                    <button wire:click.stop="updateCartQuantity({{ $option->id }}, 1)" class="w-8 h-7 flex items-center justify-center text-white hover:bg-white/10 font-bold">
                                                        <span class="text-lg">+</span>
                                                    </button>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                @endforeach
                </div>

                {{-- Floating Confirmation Bar --}}
                @if(count($cart) > 0)
                <div class="fixed bottom-6 inset-x-4 z-50 pointer-events-none">
                    <div class="max-w-2xl mx-auto rounded-3xl p-4 shadow-2xl border border-white/20 flex items-center justify-between gap-4 pointer-events-auto bg-violet-950/90 backdrop-blur-lg">
                        <div class="px-2">
                            <div class="text-[10px] text-white/60 uppercase tracking-widest font-bold">
                                {{ app()->getLocale() === 'ar' ? 'إجمالي القيمة التقديرية' : 'Total Estimate' }}
                            </div>
                            <div class="text-2xl font-black text-white px-1">
                                {{ number_format($calculatedPrice, 0) }}
                                <span class="text-xs font-normal">SAR</span>
                            </div>
                        </div>
                        <button wire:click="openModal" class="bg-gradient-to-r from-orange-500 to-violet-500 text-white px-8 py-4 rounded-2xl font-bold text-sm tracking-wide hover:shadow-orange-500/40 transition-all shadow-xl active:scale-95">
                            {{ __('Confirm Selections') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
        @endif



    </main>

    {{-- ── Action Modal (Level 3 / Checkout) ────────────────────────── --}}
    @if($showModal && (isset($selectedOption) || count($cart) > 0))
        <template x-teleport="body">
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center modal-overlay" wire:click.self="closeModal">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div class="relative w-full sm:max-w-lg mx-auto bg-[#120720] rounded-t-[2.5rem] sm:rounded-[2.5rem] border border-violet-500/20 modal-content max-h-[90dvh] overflow-y-auto shadow-2xl shadow-violet-900/50 overflow-x-hidden">
                <div class="absolute inset-0 bg-linear-to-b from-white/[0.02] to-transparent pointer-events-none"></div>
                
                {{-- Modal Handle --}}
                <div class="flex justify-center pt-4 sm:hidden">
                    <div class="w-12 h-1.5 bg-white/5 rounded-full"></div>
                </div>

                {{-- Modal Header --}}
                <div class="px-6 pt-6 pb-5 border-b border-white/10 relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold font-[Outfit] text-white tracking-tight">
                                @if(isset($selectedOption) && count($cart) <= 1)
                                    {{ app()->getLocale() === 'ar' ? $selectedOption->name_ar : $selectedOption->name_en }}
                                @else
                                    {{ app()->getLocale() === 'ar' ? 'ملخص الخدمات المطلوبة' : 'Booking Summary' }}
                                @endif
                            </h3>
                            <p class="text-xs text-white/50 mt-1 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span>
                                @if(isset($selectedOption) && count($cart) <= 1)
                                    {{ app()->getLocale() === 'ar' ? ($selectedOption->subService?->name_ar ?? '...') : ($selectedOption->subService?->name_en ?? '...') }}
                                @else
                                    {{ app()->getLocale() === 'ar' ? 'مراجعة الطلب قبل التأكيد' : 'Review your order before confirm' }}
                                @endif
                            </p>
                        </div>
                        <button wire:click="closeModal" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-white/70 flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Construction services: pricing explanation banner --}}
                    @if($activeService && $activeService->slug === 'construction-contracting')
                    <div class="mt-4 p-4 rounded-2xl bg-orange-500/10 border border-orange-500/20">
                        <p class="text-xs text-orange-300 font-bold mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            {{ app()->getLocale() === 'ar' ? 'ملاحظة هامة' : 'Important Note' }}
                        </p>
                        <p class="text-xs text-white/60 leading-relaxed">
                            {{ app()->getLocale() === 'ar'
                                ? 'السعر المعروض هو تقدير أولي. يُحدَّد السعر النهائي بناءً على المخطط المعماري والمساحة. سيتواصل معك أحد مهندسينا لمراجعة تفاصيل مشروعك.'
                                : 'Final cost is determined by architectural plans. One of our engineers will contact you to review your project detail.'
                            }}
                        </p>
                    </div>
                    @endif
                </div>

                <div class="relative z-10 px-5 sm:px-6 py-6 space-y-7">
                    @if($prefillFromAi && $aiProblemDescription)
                        {{-- AI Section (Collapsed for multi-item if needed) --}}
                        <div class="p-4 rounded-2xl bg-violet-500/5 border border-violet-500/10">
                            <label class="text-[10px] font-black text-violet-300 uppercase tracking-widest block mb-2">
                                {{ app()->getLocale() === 'ar' ? 'تشخيص الذكاء الاصطناعي' : 'AI Diagnosis' }}
                            </label>
                            <p class="text-xs text-white/70 leading-relaxed italic line-clamp-3">
                                "{{ $aiProblemDescription }}"
                            </p>
                        </div>
                    @endif

                    {{-- Order Summary List --}}
                    <div class="space-y-4">
                        <label class="text-sm font-bold text-white/90 block tracking-tight">
                            {{ app()->getLocale() === 'ar' ? 'مراجعة الخدمات المختارة' : 'Review Selected Services' }}
                        </label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($cart as $id => $item)
                                <div class="flex items-center justify-between p-3 rounded-2xl bg-white/5 border border-white/5">
                                    <div class="flex-1">
                                        <h4 class="text-xs font-bold text-white/90">{{ app()->getLocale() === 'ar' ? $item['name_ar'] : $item['name_en'] }}</h4>
                                        <p class="text-[10px] text-white/40 mt-0.5">{{ __('Quantity') }}:</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center rounded-lg border border-white/20 overflow-hidden bg-black/20 mr-2">
                                            <button wire:click.stop="updateCartQuantity({{ $id }}, -1)" class="w-7 h-6 flex items-center justify-center text-white hover:bg-white/10 font-bold transition-colors">
                                                @if($item['quantity'] <= 1)
                                                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                @else
                                                    <span class="text-sm">−</span>
                                                @endif
                                            </button>
                                            <span class="w-7 text-center text-xs font-bold text-white">{{ $item['quantity'] }}</span>
                                            <button wire:click.stop="updateCartQuantity({{ $id }}, 1)" class="w-7 h-6 flex items-center justify-center text-white hover:bg-white/10 font-bold transition-colors">
                                                <span class="text-sm">+</span>
                                            </button>
                                        </div>
                                        <div class="text-[10px] text-violet-400 font-bold font-[Outfit] min-w-[60px] text-end">
                                            @php 
                                               $opt = \App\Models\ServiceOption::find($id);
                                               $itemPrice = 0;
                                               if ($opt) {
                                                   $itemPrice = ($urgency === 'urgent') ? $opt->base_price * $opt->urgent_multiplier : $opt->base_price;
                                               }
                                            @endphp
                                            {{ number_format($itemPrice * $item['quantity'], 0) }} SAR
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if(count($cart) === 0)
                                <p class="text-center py-8 text-xs text-white/30 italic">
                                    {{ app()->getLocale() === 'ar' ? 'سلة الخدمات فارغة' : 'Your cart is empty' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Construction Visits (Only for Contracting) --}}
                    @if($isConstruction)
                    <div>
                        <label class="text-sm font-bold text-white/90 block mb-2">{{ app()->getLocale() === 'ar' ? 'عدد التقييمات / الزيارات المطلوبة' : 'Number of evaluation visits required' }}</label>
                        <p class="text-[10px] text-white/50 mb-3">{{ app()->getLocale() === 'ar' ? 'مشاريع المقاولات تتطلب زيارات هندسية للتقييم الدقيق.' : 'Contracting projects require engineering visits for accurate estimation.' }}</p>
                        
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-white/5 border border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-500/20 flex items-center justify-center text-orange-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-white/90">{{ app()->getLocale() === 'ar' ? 'زيارة مهندس مختص' : 'Specialized Engineer Visit' }}</h4>
                                    <p class="text-[10px] text-orange-400 font-bold">{{ number_format(\App\Livewire\ServiceGrid::CONSTRUCTION_WORKING_EXPENSE_SAR ?? 500, 0) }} SAR / {{ app()->getLocale() === 'ar' ? 'زيارة' : 'visit' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center bg-black/40 rounded-xl border border-white/10 p-1">
                                <button wire:click.stop="$set('constructionVisits', {{ max(1, $constructionVisits - 1) }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                </button>
                                <span class="w-8 text-center font-black text-white">{{ $constructionVisits }}</span>
                                <button wire:click.stop="$set('constructionVisits', {{ min(30, $constructionVisits + 1) }})" class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-white/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Urgency Toggle --}}
                    <div x-data="{ showUrgencyHelp: false }">
                        <div class="flex items-center gap-2 mb-4">
                            <label class="text-sm font-bold text-white/90 block">{{ __('When do you need it?') }}</label>
                            <button @mouseenter="showUrgencyHelp = true" @mouseleave="showUrgencyHelp = false" class="text-white/30 hover:text-orange-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                            <div x-show="showUrgencyHelp" x-transition class="absolute bottom-full left-0 mb-2 w-64 p-3 bg-violet-900 border border-white/20 rounded-xl shadow-2xl z-50 text-[10px] text-white/80 leading-relaxed backdrop-blur-md">
                                {{ app()->getLocale() === 'ar' 
                                    ? 'العاجل: يتم إرسال فني إليك فوراً (رسوم إضافية قد تطبق). المجدول: يمنحك المرونة في اختيار الموعد.' 
                                    : 'Urgent: Triggers immediate technician dispatch (extra fees may apply). Scheduled: Gives you flexibility to choose your time.' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <button wire:click="$set('urgency', 'scheduled')"
                                class="group p-4 rounded-[1.5rem] border-2 transition-all duration-300 text-center relative overflow-hidden {{ $urgency === 'scheduled' ? 'border-violet-500 bg-violet-500/20' : 'border-white/5 bg-white/5 hover:border-white/10' }}">
                                <svg class="w-6 h-6 mx-auto mb-2 transition-colors {{ $urgency === 'scheduled' ? 'text-violet-400' : 'text-white/30 group-hover:text-white/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div class="text-xs font-bold {{ $urgency === 'scheduled' ? 'text-white' : 'text-white/60 group-hover:text-white/80' }}">{{ __('Scheduled') }}</div>
                                <div class="text-[9px] uppercase tracking-wider text-white/40 mt-1 font-bold">{{ app()->getLocale() === 'ar' ? 'اختر الوقت' : 'Pick a time' }}</div>
                            </button>
                            <button wire:click="$set('urgency', 'urgent')"
                                class="group p-4 rounded-[1.5rem] border-2 transition-all duration-300 text-center relative overflow-hidden {{ $urgency === 'urgent' ? 'border-orange-500 bg-orange-500/20 urgent-flash' : 'border-white/5 bg-white/5 hover:border-white/10' }}">
                                <svg class="w-6 h-6 mx-auto mb-2 transition-colors {{ $urgency === 'urgent' ? 'text-orange-400' : 'text-white/30 group-hover:text-white/50' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <div class="text-xs font-bold {{ $urgency === 'urgent' ? 'text-white' : 'text-white/60 group-hover:text-white/80' }}">{{ __('Urgent') }}</div>
                                <div class="text-[9px] uppercase tracking-wider text-white/40 mt-1 font-bold">{{ app()->getLocale() === 'ar' ? 'الآن فوراً' : 'Right Now' }}</div>
                            </button>
                        </div>
                    </div>

                    {{-- Address / Location --}}
                    <div>
                        <label class="text-sm font-bold text-white/90 block mb-4">{{ __('Your Location') }}</label>
                        <div class="relative group">
                            <input type="text" wire:model="address" placeholder="{{ __('Enter your address or use GPS') }}"
                                class="w-full px-5 py-4 pl-12 rtl:pl-4 rtl:pr-12 rounded-2xl bg-white/5 border border-white/10 text-sm text-white placeholder-white/20 focus:border-violet-500/50 focus:bg-white/10 outline-none transition-all">
                            <svg class="w-5 h-5 text-white/30 absolute left-4 rtl:right-4 rtl:left-auto top-1/2 -translate-y-1/2 group-focus-within:text-violet-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <button type="button" onclick="getLocation()" class="mt-3 text-xs text-violet-400 hover:text-violet-300 flex items-center gap-2 transition-colors font-bold">
                            <div class="w-6 h-6 rounded-lg bg-violet-500/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            {{ __('Use my current location') }}
                        </button>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="sticky bottom-0 bg-[#120720]/95 backdrop-blur-xl pt-4 pb-6 mt-4 border-t border-violet-500/20 -mx-5 sm:-mx-6 px-5 sm:px-6 z-10">
                        <button wire:click="openCheckoutModal"
                            class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold text-base shadow-lg shadow-orange-500/20 hover:shadow-orange-500/40 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            {{ __('Confirm Selections') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </template>
        @endif

        {{-- ── 🛒 CHECKOUT MODAL (Modal 1) ───────────────────────── --}}
        @if($showCheckoutModal)
        <template x-teleport="body">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#0a0514]/80 backdrop-blur-sm" wire:click="$set('showCheckoutModal', false)"></div>
            <div class="relative w-full max-w-md bg-[#120720] border border-violet-500/30 rounded-[2rem] p-6 shadow-2xl slide-enter z-10 max-h-[90vh] overflow-y-auto">
                <button wire:click="$set('showCheckoutModal', false)" class="absolute top-4 rtl:left-4 ltr:right-4 text-white/40 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                
                <h3 class="text-xl font-bold text-white font-[Outfit] mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-violet-500/20 flex items-center justify-center text-violet-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    {{ app()->getLocale() === 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}
                </h3>

                {{-- Price Summary inside Modal --}}
                <div class="bg-white/5 rounded-[1.5rem] p-5 border border-white/5 mb-6">
                    <div class="space-y-3 font-[Outfit]">
                        @php $subTotal = 0; @endphp
                        @foreach($cart as $id => $item)
                            @php 
                                $opt = \App\Models\ServiceOption::find($id);
                                $itemPrice = ($urgency === 'urgent' && $opt) ? $opt->base_price * $opt->urgent_multiplier : ($opt->base_price ?? 0);
                                $subTotal += ($itemPrice * $item['quantity']);
                            @endphp
                            <div class="flex items-center justify-between opacity-70">
                                <span class="text-xs font-bold text-white line-clamp-1 flex-1 mr-4">
                                    {{ app()->getLocale() === 'ar' ? $item['name_ar'] : $item['name_en'] }} (x{{ $item['quantity'] }})
                                </span>
                                <span class="text-xs font-semibold text-white/80 whitespace-nowrap">
                                    {{ number_format($itemPrice * $item['quantity'], 0) }} SAR
                                </span>
                            </div>
                        @endforeach
                        
                        <div class="border-t border-white/10 pt-3 mt-1"></div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-white/70">{{ app()->getLocale() === 'ar' ? 'الإجمالي النهائي' : 'Total' }}</span>
                            <span class="text-xl font-bold font-[Outfit] text-orange-400">
                                {{ number_format($calculatedPrice, 0) }} SAR
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Auth / OTP Logic --}}
                @auth
                    <button wire:click="$set('showPaymentModal', true); $set('showCheckoutModal', false)"
                        class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold text-base shadow-lg shadow-orange-500/20 active:scale-[0.98]">
                        {{ __('Proceed to Payment') }}
                    </button>
                @else
                    @if($showOtpInput)
                        <div class="space-y-3">
                            <input type="text" wire:model="otp" placeholder="{{ __('Enter OTP (1234)') }}" class="w-full text-center tracking-[0.5em] text-xl font-bold px-4 py-3 rounded-2xl bg-white/5 border border-slate-200 focus:border-orange-500 outline-none transition-all">
                            @error('otp') <p class="text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                            <div class="grid grid-cols-2 gap-2">
                                <button wire:click="$set('showOtpInput', false)" class="py-3.5 rounded-2xl bg-white/10 text-white/80 font-bold hover:bg-white/20 transition-all">{{ __('Cancel') }}</button>
                                <button wire:click="verifyAndBook" class="py-3.5 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white font-bold shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-all">{{ __('Verify') }}</button>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3">
                            @if($needsRegistration)
                                <input type="text" wire:model="clientName" placeholder="{{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}" class="w-full px-4 py-3.5 rounded-2xl bg-white/5 border border-slate-200 text-white placeholder-white/40 focus:border-orange-500 outline-none transition-all">
                                @error('clientName') <p class="text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                            @endif
                            <input type="tel" wire:model="phone" placeholder="{{ __('Phone Number (05...)') }}" class="w-full px-4 py-3.5 rounded-2xl bg-white/5 border border-slate-200 text-white placeholder-white/40 focus:border-orange-500 outline-none transition-all" dir="ltr" {{ $needsRegistration ? 'readonly' : '' }}>
                            @error('phone') <p class="text-xs text-red-500 text-center">{{ $message }}</p> @enderror
                            <button wire:click="sendOtp" class="w-full py-3.5 rounded-2xl bg-white text-[#120720] font-bold text-base shadow-lg shadow-white/20 active:scale-[0.98] mt-2">
                                {{ __('Send OTP') }}
                            </button>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
        </template>
        @endif

        {{-- ── 💳 PAYMENT MODAL (Modal 2) ───────────────────────── --}}
        @if($showPaymentModal)
        <template x-teleport="body">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-[#0a0514]/80 backdrop-blur-sm" wire:click="$set('showPaymentModal', false)"></div>
            <div class="relative w-full max-w-md bg-[#120720] border border-violet-500/30 rounded-[2rem] p-6 shadow-2xl slide-enter z-10 max-h-[90vh] overflow-y-auto">
                {{-- Modal content remains same... --}}
                <button wire:click="$set('showPaymentModal', false); $set('showCheckoutModal', true)" class="absolute top-4 rtl:left-4 ltr:right-4 text-white/40 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                
                <h3 class="text-xl font-bold text-white font-[Outfit] mb-2 text-center">
                    {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                </h3>
                <p class="text-center text-white/50 text-sm mb-6">{{ number_format($calculatedPrice, 0) }} SAR</p>

                <div class="space-y-3 mb-6">
                    <label class="flex items-center gap-3 p-4 rounded-xl border {{ $paymentMethod === 'apple' ? 'border-violet-500 bg-violet-500/10' : 'border-white/10 bg-white/5' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="paymentMethod" value="apple" class="sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $paymentMethod === 'apple' ? 'border-violet-500' : 'border-white/20' }}">
                            @if($paymentMethod === 'apple') <div class="w-2.5 h-2.5 rounded-full bg-violet-500"></div> @endif
                        </div>
                        <span class="flex-1 font-bold text-white">Apple Pay</span>
                        <div class="h-6 w-10 bg-white/10 rounded flex items-center justify-center text-xs"></div>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-xl border {{ $paymentMethod === 'mada' ? 'border-violet-500 bg-violet-500/10' : 'border-white/10 bg-white/5' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="paymentMethod" value="mada" class="sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $paymentMethod === 'mada' ? 'border-violet-500' : 'border-white/20' }}">
                            @if($paymentMethod === 'mada') <div class="w-2.5 h-2.5 rounded-full bg-violet-500"></div> @endif
                        </div>
                        <span class="flex-1 font-bold text-white">Mada</span>
                        <div class="h-6 w-10 bg-gradient-to-r from-emerald-500 to-green-600 rounded flex items-center justify-center text-[8px] font-black italic">mada</div>
                    </label>
                    
                    <label class="flex items-center gap-3 p-4 rounded-xl border {{ $paymentMethod === 'visa' ? 'border-violet-500 bg-violet-500/10' : 'border-white/10 bg-white/5' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="paymentMethod" value="visa" class="sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $paymentMethod === 'visa' ? 'border-violet-500' : 'border-white/20' }}">
                            @if($paymentMethod === 'visa') <div class="w-2.5 h-2.5 rounded-full bg-violet-500"></div> @endif
                        </div>
                        <span class="flex-1 font-bold text-white">Credit Card (Visa/Mastercard)</span>
                        <div class="h-6 w-10 bg-blue-600 rounded flex items-center justify-center text-[10px] font-bold italic">VISA</div>
                    </label>

                    <label class="flex items-center gap-3 p-4 rounded-xl border {{ $paymentMethod === 'cash' ? 'border-orange-500 bg-orange-500/10' : 'border-white/10 bg-white/5' }} cursor-pointer transition-all">
                        <input type="radio" wire:model.live="paymentMethod" value="cash" class="sr-only">
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $paymentMethod === 'cash' ? 'border-orange-500' : 'border-white/20' }}">
                            @if($paymentMethod === 'cash') <div class="w-2.5 h-2.5 rounded-full bg-orange-500"></div> @endif
                        </div>
                        <span class="flex-1 font-bold text-white {{ $paymentMethod === 'cash' ? 'text-orange-400' : '' }}">
                            {{ app()->getLocale() === 'ar' ? 'الدفع لاحقاً (نقداً/شبكة للـفني)' : 'Pay Later (Cash/Card after service)' }}
                        </span>
                    </label>
                </div>
                
                {{-- Secure Checkout Badge --}}
                <div class="mb-6 flex items-center justify-center gap-4 py-3 bg-white/5 rounded-2xl border border-white/10">
                    <div class="flex flex-col items-center gap-1">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span class="text-[8px] font-bold text-white/50 uppercase tracking-widest">{{ app()->getLocale() === 'ar' ? 'دفع آمن' : 'SECURE' }}</span>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div class="text-[10px] text-white/60 leading-tight">
                        <p class="font-bold text-white/80 uppercase">{{ app()->getLocale() === 'ar' ? 'تشفير 256 بت' : '256-bit SSL Encryption' }}</p>
                        <p>{{ app()->getLocale() === 'ar' ? 'بياناتك محمية بالكامل' : 'All your data is fully protected' }}</p>
                    </div>
                </div>

                <div wire:loading wire:target="processPaymentAndBook" class="text-center py-4 w-full text-violet-400 animate-pulse text-sm font-bold">
                    {{ app()->getLocale() === 'ar' ? 'جاري معالجة الدفع...' : 'Processing Payment...' }}
                </div>

                <button wire:click="processPaymentAndBook" wire:loading.remove wire:target="processPaymentAndBook"
                    class="w-full py-4 rounded-2xl {{ $paymentMethod === 'cash' ? 'bg-orange-500' : 'bg-gradient-to-r from-violet-600 to-indigo-600' }} text-white font-bold shadow-lg shadow-violet-500/20 active:scale-[0.98] transition-all">
                    @if($paymentMethod === 'cash')
                        {{ app()->getLocale() === 'ar' ? 'تأكيد الحجز' : 'Confirm Booking' }}
                    @else
                        {{ app()->getLocale() === 'ar' ? 'دفع ' . number_format($calculatedPrice, 0) . ' SAR' : 'Pay ' . number_format($calculatedPrice, 0) . ' SAR' }}
                    @endif
                </button>
            </div>
        </div>
        </template>
        @endif

    {{-- ── SEO Article Section ────────────────────────────────────── --}}
    @if(!$activeServiceId)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 mt-8">
            <article class="bg-[#1a0a2e]/60 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 sm:p-12 shadow-2xl shadow-black/50 relative overflow-hidden">
                <div class="absolute inset-0 bg-linear-to-br from-violet-500/5 to-transparent pointer-events-none"></div>
                <div class="relative z-10 prose prose-invert max-w-none">
                    <h2 class="text-2xl sm:text-4xl font-black font-[Outfit] text-white tracking-tight leading-tight mb-8 drop-shadow-2xl text-center">
                        {{ app()->getLocale() === 'ar' ? 'لماذا تعتبر الكفاح العالمية احسن موقع خدمات وأنظمة مراقبة في السعودية؟' : 'Why Al-Kifah is the Top Service & Security Provider?' }}
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-white/70 leading-relaxed">
                        <div class="group">
                            <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2 group-hover:text-orange-400 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]"></span>
                                {{ app()->getLocale() === 'ar' ? 'أنظمة الكاميرات والمراقبة: الأكثر طلباً وتقييماً' : 'CCTV & Security: Our Top-Rated Service' }}
                            </h3>
                            <p class="text-sm">
                                {{ app()->getLocale() === 'ar' ? 'بناءً على التقييمات الممتازة في جوجل لمتجرنا الكتروني (alkifahstore.com)، نفخر بتقديم أجود أنواع كاميرات المراقبة والأنظمة الأمنية التي تضمن حماية متكاملة لمنزلك أو منشأتك بأسعار منافسة.' : 'Based on 5-star Google reviews from alkifahstore.com, we pride ourselves on providing the highest quality CCTV cameras and security systems ensuring complete protection.' }}
                            </p>
                        </div>

                        <div class="group">
                            <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2 group-hover:text-violet-400 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-violet-500 shadow-[0_0_8px_rgba(139,92,246,0.6)]"></span>
                                {{ app()->getLocale() === 'ar' ? 'اسرع خدمات صيانة متكاملة' : 'Fastest Integrated Maintenance' }}
                            </h3>
                            <p class="text-sm">
                                {{ app()->getLocale() === 'ar' ? 'نوفر اسرع خدمات صيانة للمنازل والشركات عبر فريق فني مختص يصلك في وقت قياسي لضمان استمرارية أعمالك وراحة منزلك، مع مرونة تامة في المواعيد.' : 'We provide the fastest maintenance services for homes and businesses via specialized teams that reach you in record time, ensuring continuity and comfort.' }}
                            </p>
                        </div>

                        <div class="group">
                            <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2 group-hover:text-green-400 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                                {{ app()->getLocale() === 'ar' ? 'ارخص خدمات برمجة وتطوير' : 'Affordable Software Development' }}
                            </h3>
                            <p class="text-sm">
                                {{ app()->getLocale() === 'ar' ? 'نقدم ارخص خدمات برمجة وتطوير تطبيقات المواقع في المملكة مع الالتزام بمعايير الجودة العالمية، مما يسهل على الشركات الناشئة الانطلاق في الفضاء الرقمي.' : 'We offer affordable programming and web development services in the Kingdom with international quality standards, facilitating digital growth for startups.' }}
                            </p>
                        </div>

                        <div class="group">
                            <h3 class="text-white font-bold text-lg mb-3 flex items-center gap-2 group-hover:text-blue-400 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                                {{ app()->getLocale() === 'ar' ? 'اسهل تعاملات ومرونة كاملة' : 'Easiest Transactions & Flexibility' }}
                            </h3>
                            <p class="text-sm">
                                {{ app()->getLocale() === 'ar' ? 'نتميز بتقديم اسهل تعاملات رقمية تبدأ من الحجز الفوري وحتى التنفيذ، مع مرونة في التعامل تتيح لك تخصيص طلبك حسب احتياجاتك الخاصة وميزانيتك.' : 'We offer the easiest digital transactions from instant booking to execution, with total flexibility to customize requests based on your needs.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    @endif
    @if(!$activeServiceId)
    <section id="about-us" class="max-w-7xl mx-auto px-4 sm:px-6 py-16 pb-8">

        {{-- Section Header --}}
        <div class="text-center mb-12 bg-[#1a0a2e]/60 backdrop-blur-xl border border-white/10 rounded-[2rem] p-8 sm:p-12 shadow-2xl max-w-4xl mx-auto ring-1 ring-white/5 relative overflow-hidden">
            <div class="absolute inset-0 bg-linear-to-br from-violet-500/10 to-transparent"></div>
            <div class="relative z-10">
                <span class="inline-block px-4 py-1.5 rounded-full bg-violet-500/20 text-violet-300 border border-violet-500/20 text-xs font-bold tracking-widest uppercase mb-6">
                    {{ app()->getLocale() === 'ar' ? 'عن الشركة' : 'About Our Company' }}
                </span>
                <h2 class="text-4xl sm:text-6xl font-black font-[Outfit] text-white tracking-tight leading-tight mb-6 drop-shadow-2xl">
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
                        <img src="{{ asset('images/logo.png') }}?v={{ @filemtime(public_path('images/logo.png')) ?: time() }}" alt="Al-Kifah Logo" class="w-12 h-12 rounded-xl object-cover">
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
                @php $role = auth()->user()?->role?->value ?? 'client'; @endphp

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


    {{-- ── Smart Visual Diagnosis Component ────────────────────────── --}}
    <livewire:visual-diagnosis />

</div>

@push('scripts')
    <script>
        async function getLocation() {
            @this.set('address', '{{ app()->getLocale() === 'ar' ? 'جاري تحديد موقعك...' : 'Locating you...' }}');
            
            // Browsers require HTTPS for navigator.geolocation (localhost is often an exception)
            const isSecure = window.isSecureContext || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

            if (isSecure && navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    @this.set('latitude', lat);
                    @this.set('longitude', lng);
                    @this.set('address', '{{ app()->getLocale() === "ar" ? "تم تحديد الموقع (GPS)" : "Location detected (GPS)" }} (' + lat.toFixed(4) + ', ' + lng.toFixed(4) + ')');
                }, function(error) {
                    console.warn('Browser GPS failed, trying IP fallback...', error);
                    fetchIpLocation();
                }, { 
                    timeout: 12000, 
                    enableHighAccuracy: true, 
                    maximumAge: 0 
                });
            } else {
                fetchIpLocation();
            }
        }

        async function fetchIpLocation() {
            // Try multiple fallback services for robustness
            const services = [
                'https://ipapi.co/json/',
                'http://ip-api.com/json'
            ];

            for (const url of services) {
                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    
                    // Normalizing response (ip-api uses lat/lon, ipapi uses latitude/longitude)
                    const lat = data.latitude || data.lat;
                    const lng = data.longitude || data.lon;
                    const city = data.city || '';

                    if (lat && lng) {
                        @this.set('latitude', lat);
                        @this.set('longitude', lng);
                        @this.set('address', city + ' ({{ app()->getLocale() === "ar" ? "تحديد تلقائي" : "Auto-detected" }})');
                        return; // Success!
                    }
                } catch (e) {
                    console.error(`Fallback service ${url} failed:`, e);
                }
            }
            
            showLocationError();
        }

        function showLocationError() {
            let msg = '{{ app()->getLocale() === 'ar' ? 'تعذر تحديد موقعك تلقائياً.' : 'Positioning unavailable.' }}';
            
            if (!window.isSecureContext) {
                msg += ' {{ app()->getLocale() === 'ar' ? '(يتطلب HTTPS للعمل التلقائي). الرجاء كتابة العنوان أدناه.' : '(HTTPS required for auto-detection). Please type your address below.' }}';
            } else {
                msg += ' {{ app()->getLocale() === 'ar' ? 'الرجاء إدخال عنوانك يدوياً.' : 'Please enter your address manually.' }}';
            }
            alert(msg);
        }

        // Scrolling & Tour logic
        document.addEventListener('livewire:initialized', () => {
            // Livewire JS Initialization Area
        });

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => window.KifahTour?.launch('home'), 800);
        });
    </script>
@endpush

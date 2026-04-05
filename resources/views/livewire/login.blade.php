<div class="min-h-dvh gradient-dark flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-white/90 shadow-2xl shadow-blue-500/20 mb-4 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}?v={{ @filemtime(public_path('images/logo.png')) ?: time() }}"
                     alt="Al-Kifah Logo"
                     class="w-full h-full object-cover">
            </div>
            <h1 class="text-2xl font-bold font-[Outfit] text-white/95">
                <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">AL-KIFAH</span>
            </h1>
            <p class="text-white/70 text-sm mt-1">{{ __('Sign in to your account') }}</p>
        </div>

        {{-- Login Form --}}
        <form wire:submit="login" class="glass rounded-3xl p-6 sm:p-8 space-y-5 fade-in" style="animation-delay: 150ms">
            {{-- Email --}}
            <div>
                <label class="text-sm font-medium text-white/80 block mb-2">{{ __('Email') }}</label>
                <div class="relative">
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-3.5 pl-12 rtl:pl-4 rtl:pr-12 rounded-2xl bg-white/95 border border-slate-200 text-sm text-slate-900 font-bold placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all shadow-sm"
                        placeholder="name@alkifah.com">
                    <svg class="w-5 h-5 text-slate-400 absolute left-4 rtl:right-4 rtl:left-auto top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                @error('email')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="text-sm font-medium text-white/80 block mb-2">{{ __('Password') }}</label>
                <div class="relative">
                    <input type="password" wire:model="password"
                        class="w-full px-4 py-3.5 pl-12 rtl:pl-4 rtl:pr-12 rounded-2xl bg-white/95 border border-slate-200 text-sm text-slate-900 font-bold placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition-all shadow-sm"
                        placeholder="••••••••">
                    <svg class="w-5 h-5 text-slate-400 absolute left-4 rtl:right-4 rtl:left-auto top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                @error('password')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="remember" class="w-4 h-4 rounded bg-white/80 border border-slate-100/50 border-slate-300 text-blue-500 focus:ring-orange-500 focus:ring-offset-0">
                    <span class="text-sm text-white/70">{{ __('Remember me') }}</span>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-violet-500 text-white/95 font-bold text-base hover:shadow-lg hover:shadow-orange-500/30 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <span wire:loading.remove>{{ __('Sign In') }}</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ __('Signing in...') }}
                </span>
            </button>
        </form>

        {{-- Demo Accounts --}}
        <div class="mt-6 glass rounded-2xl p-4 fade-in" style="animation-delay: 300ms">
            <p class="text-xs text-white/70 text-center mb-3 font-medium">{{ __('Demo Accounts') }} (password: password)</p>
            <div class="grid grid-cols-2 gap-2">
                <button wire:click="$set('email', 'admin@alkifah.com')" class="text-[10px] sm:text-xs py-2 px-3 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 border border-orange-500/20 transition-all text-center font-bold">
                    👑 Super Admin
                </button>
                <button wire:click="$set('email', 'manager@alkifah.com')" class="text-[10px] sm:text-xs py-2 px-3 rounded-xl bg-violet-500/10 text-violet-300 hover:bg-violet-500/20 border border-violet-500/20 transition-all text-center font-bold">
                    ⚙️ Manager
                </button>
                <button wire:click="$set('email', 'tech1@alkifah.com')" class="text-[10px] sm:text-xs py-2 px-3 rounded-xl bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border border-emerald-500/20 transition-all text-center font-bold">
                    🔧 Technician
                </button>
                <button wire:click="$set('email', 'client@alkifah.com')" class="text-[10px] sm:text-xs py-2 px-3 rounded-xl bg-white/5 border border-white/10 text-white/80 hover:bg-white/10 transition-all text-center font-bold">
                    👤 Client
                </button>
            </div>
        </div>

        {{-- Back to Home --}}
        <div class="text-center mt-6">
            <a href="/" class="text-sm text-white/70 hover:text-violet-600 transition-colors">
                ← {{ __('Back to Services') }}
            </a>
        </div>
    </div>
</div>

<div class="min-h-dvh gradient-dark text-white/95">
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-brand flex items-center justify-center">
                    <span class="text-sm font-bold font-[Outfit]">K</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">
                        <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">{{ __('Platform Settings & Terms') }}</span>
                    </h1>
                    <p class="text-xs text-white/70">{{ __('Control commissions, taxes, and conditions') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-sm font-medium transition-all">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 pb-24">
        @if (session()->has('message'))
            <div class="p-4 mb-6 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit="saveSettings" class="space-y-8">
            {{-- Financial Settings --}}
            <div class="glass rounded-3xl p-6 sm:p-8">
                <h3 class="text-xl font-bold font-[Outfit] text-white mb-6 border-b border-slate-700 pb-4">
                    {{ __('Financial Settings (ZATCA & Commission)') }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-white/50 mb-2">{{ __('Platform Commission (%)') }}</label>
                        <input type="number" wire:model="platform_commission" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        <span class="text-xs text-white/70">{{ __('Percentage deducted from agent earnings') }}</span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white/50 mb-2">{{ __('VAT Rate (%)') }}</label>
                        <input type="number" wire:model="vat_rate" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                        <span class="text-xs text-white/70">{{ __('Standard Value Added Tax') }}</span>
                    </div>
                </div>
            </div>

            {{-- Terms & Conditions Arabic --}}
            <div class="glass rounded-3xl p-6 sm:p-8">
                <h3 class="text-xl font-bold font-[Outfit] text-white mb-6 border-b border-slate-700 pb-4">
                    {{ __('Terms & Conditions (Arabic)') }}
                </h3>
                <div>
                    <textarea wire:model="terms_ar" rows="15" dir="rtl" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500 outline-none transition-all" placeholder="Enter Arabic terms and conditions here..."></textarea>
                </div>
            </div>

            {{-- Terms & Conditions English --}}
            <div class="glass rounded-3xl p-6 sm:p-8">
                <h3 class="text-xl font-bold font-[Outfit] text-white mb-6 border-b border-slate-700 pb-4">
                    {{ __('Terms & Conditions (English)') }}
                </h3>
                <div>
                    <textarea wire:model="terms_en" rows="15" dir="ltr" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-violet-500 focus:border-violet-500 outline-none transition-all" placeholder="Enter English terms and conditions here..."></textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-4 rounded-xl bg-gradient-to-r from-orange-500 to-violet-600 text-white font-bold shadow-lg shadow-orange-500/30 hover:scale-105 active:scale-95 transition-all">
                    {{ __('Save All Settings') }}
                </button>
            </div>
        </form>
    </main>
</div>

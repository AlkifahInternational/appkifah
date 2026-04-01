<div class="p-6 max-w-4xl mx-auto space-y-6">

    {{-- Flash --}}
    @if($savedMessage)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
         class="flex items-center gap-3 p-4 rounded-2xl bg-green-500/10 border border-green-500/20 text-green-600 text-sm font-medium">
        ✅ {{ $savedMessage }}
    </div>
    @endif

    {{-- ── 1. Payment Methods Toggle ── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold font-[Outfit] text-white/95">{{ __('Payment Methods') }}</h2>
                <p class="text-xs text-white/60 mt-0.5">{{ __('Enable or disable customer-facing payment options') }}</p>
            </div>
        </div>
        <div class="p-6 space-y-4">

            @php
            $methods = [
                ['key' => 'enable_mada',      'label' => 'Mada',          'desc' => 'Saudi local debit cards via Moyasar', 'emoji' => '💳', 'color' => 'green'],
                ['key' => 'enable_apple_pay', 'label' => 'Apple Pay',     'desc' => 'One-tap payment for Apple devices',  'emoji' => '',  'color' => 'slate'],
                ['key' => 'enable_stc_pay',   'label' => 'STC Pay',       'desc' => 'Saudi Telecom mobile wallet',       'emoji' => '📱', 'color' => 'violet'],
                ['key' => 'enable_cash',      'label' => 'Cash on Delivery','desc' => 'Technician collects on-site',     'emoji' => '💵', 'color' => 'orange'],
                ['key' => 'enable_bank_transfer','label' => 'Bank Transfer','desc' => 'Customer pays via IBAN/SWIFT',  'emoji' => '🏦', 'color' => 'blue'],
            ];
            @endphp

            @foreach($methods as $method)
            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">{{ $method['emoji'] }}</span>
                    <div>
                        <p class="text-sm font-semibold text-white/95">{{ $method['label'] }}</p>
                        <p class="text-xs text-white/60">{{ $method['desc'] }}</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="{{ $method['key'] }}" class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer
                        peer-checked:bg-violet-600 transition-all duration-200
                        after:content-[''] after:absolute after:top-[2px] after:start-[2px]
                        after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                        peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full"></div>
                </label>
            </div>
            @endforeach

            <button wire:click="savePaymentMethods"
                class="w-full py-3 rounded-xl bg-violet-600 text-white font-bold text-sm hover:bg-violet-700 transition-all shadow-lg shadow-violet-500/20">
                {{ __('Save Payment Methods') }}
            </button>
        </div>
    </div>

    {{-- ── 2. Moyasar Gateway Keys ── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-lg">🔑</div>
                <div>
                    <h2 class="text-base font-bold font-[Outfit] text-white/95">{{ __('Moyasar Gateway') }}</h2>
                    <p class="text-xs text-white/60">{{ __('Saudi-certified payment gateway supporting Mada, Visa, Apple Pay') }}</p>
                </div>
                <a href="https://moyasar.com" target="_blank" class="ml-auto text-xs text-violet-500 hover:underline">moyasar.com →</a>
            </div>
        </div>
        <div class="p-6 space-y-4">

            {{-- Mode switch --}}
            <div class="flex items-center gap-2 p-1 bg-white/10 rounded-xl w-fit">
                <button wire:click="$set('moyasar_mode', 'test')"
                    class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $moyasar_mode === 'test' ? 'bg-white shadow text-white/90' : 'text-white/60' }}">
                    🧪 {{ __('Test') }}
                </button>
                <button wire:click="$set('moyasar_mode', 'live')"
                    class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $moyasar_mode === 'live' ? 'bg-green-500 shadow text-white' : 'text-white/60' }}">
                    🚀 {{ __('Live') }}
                </button>
            </div>

            @if($moyasar_mode === 'live')
            <div class="flex items-center gap-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 text-xs">
                ⚠️ {{ __('Live mode — real transactions will be processed.') }}
            </div>
            @endif

            <div class="grid gap-4">
                <div>
                    <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Publishable Key') }}</label>
                    <input type="text" wire:model="moyasar_publishable_key"
                        placeholder="pk_test_..."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-400">
                </div>
                <div>
                    <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Secret Key') }}</label>
                    <div class="relative">
                        <input type="password" wire:model="moyasar_secret_key"
                            placeholder="sk_test_..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30 focus:border-violet-400 pr-10">
                        <span class="absolute right-3 top-3 text-white/60 text-xs">🔒</span>
                    </div>
                    <p class="text-xs text-white/60 mt-1">{{ __('Never share your secret key. Stored encrypted.') }}</p>
                </div>
            </div>

            <button wire:click="saveGatewayKeys"
                class="w-full py-3 rounded-xl bg-green-600 text-white font-bold text-sm hover:bg-green-700 transition-all shadow-lg shadow-green-500/20">
                {{ __('Save Gateway Keys') }}
            </button>
        </div>
    </div>

    {{-- ── 3. Bank Transfer Details ── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-lg">🏦</div>
                <div>
                    <h2 class="text-base font-bold font-[Outfit] text-white/95">{{ __('Bank Account Details') }}</h2>
                    <p class="text-xs text-white/60">{{ __('Shown to clients who choose bank transfer') }}</p>
                </div>
            </div>
        </div>
        <div class="p-6 grid sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Bank Name') }}</label>
                <input type="text" wire:model="bank_name" placeholder="{{ __('e.g. Al Rajhi Bank') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
            </div>
            <div>
                <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Account Holder Name') }}</label>
                <input type="text" wire:model="bank_account_name" placeholder="{{ __('Al-Kifah Global Co.') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
            </div>
            <div>
                <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Account Number') }}</label>
                <input type="text" wire:model="bank_account_number" placeholder="0123456789"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
            </div>
            <div>
                <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('IBAN') }}</label>
                <input type="text" wire:model="bank_iban" placeholder="SA00 3000 0000 0000 0000 0000"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-mono bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30">
            </div>
            <div class="sm:col-span-2">
                <button wire:click="saveBankDetails"
                    class="w-full py-3 rounded-xl bg-blue-600 text-white font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20">
                    {{ __('Save Bank Details') }}
                </button>
            </div>
        </div>
    </div>

    {{-- ── 4. Commission & VAT ── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-lg">📊</div>
                <div>
                    <h2 class="text-base font-bold font-[Outfit] text-white/95">{{ __('Commission & VAT') }}</h2>
                    <p class="text-xs text-white/60">{{ __('Platform fee deducted from technician earnings') }}</p>
                </div>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('Platform Commission (%)') }}</label>
                    <div class="relative">
                        <input type="number" wire:model="commission_rate" min="0" max="100" step="0.5"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30 pr-10">
                        <span class="absolute right-3 top-3 text-white/70 text-sm font-bold">%</span>
                    </div>
                    <p class="text-xs text-white/60 mt-1">{{ __('Deducted from technician wallet on completion') }}</p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-white/80 mb-1.5 block">{{ __('VAT Rate (%)') }}</label>
                    <div class="relative">
                        <input type="number" wire:model="vat_rate" min="0" max="30" step="0.5"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white/5 focus:outline-none focus:ring-2 focus:ring-violet-500/30 pr-10">
                        <span class="absolute right-3 top-3 text-white/70 text-sm font-bold">%</span>
                    </div>
                    <p class="text-xs text-white/60 mt-1">{{ __('Saudi VAT (15% standard)') }}</p>
                </div>
            </div>

            {{-- Live preview --}}
            <div class="p-4 rounded-2xl bg-white/5 border border-slate-100">
                <p class="text-xs font-semibold text-white/70 mb-3">{{ __('Example: 1000 SAR order') }}</p>
                <div class="space-y-1.5 text-sm">
                    <div class="flex justify-between text-white/80"><span>{{ __('Order Total') }}</span><span class="font-mono">1,000.00 SAR</span></div>
                    <div class="flex justify-between text-orange-500"><span>{{ __('Platform Commission') }} ({{ $commission_rate }}%)</span><span class="font-mono">- {{ number_format(1000 * $commission_rate / 100, 2) }} SAR</span></div>
                    <div class="flex justify-between text-white/80"><span>{{ __('VAT') }} ({{ $vat_rate }}%)</span><span class="font-mono">+ {{ number_format(1000 * $vat_rate / 100, 2) }} SAR</span></div>
                    <div class="flex justify-between font-bold text-green-600 pt-2 border-t border-slate-200">
                        <span>{{ __('Technician Receives') }}</span>
                        <span class="font-mono">{{ number_format(1000 * (1 - $commission_rate / 100), 2) }} SAR</span>
                    </div>
                </div>
            </div>

            <button wire:click="saveCommission"
                class="w-full py-3 rounded-xl bg-orange-500 text-white font-bold text-sm hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20">
                {{ __('Save Commission Rates') }}
            </button>
        </div>
    </div>

</div>

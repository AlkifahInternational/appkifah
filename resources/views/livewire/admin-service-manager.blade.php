<div class="min-h-dvh gradient-dark text-white/95">
    {{-- ── Header ── --}}
    <header class="sticky top-0 z-40 glass-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl gradient-brand flex items-center justify-center">
                    <span class="text-sm font-bold font-[Outfit]">K</span>
                </div>
                <div>
                    <h1 class="text-lg font-bold font-[Outfit]">
                        <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">{{ __('Services & Pricing') }}</span>
                    </h1>
                    <p class="text-xs text-white/70">{{ __('Control your dynamic platform tariffs') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="$set('showAddService', true)" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/20 text-green-300 hover:bg-green-500/30 text-sm font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('Add New Service') }}
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-sm font-medium transition-all">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-24 space-y-8">
        @if (session()->has('message'))
            <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                {{ session('message') }}
            </div>
        @endif

        {{-- ── Add New Service Modal ── --}}
        @if($showAddService)
        <div class="glass rounded-3xl p-6 sm:p-8 border-2 border-green-500/30">
            <h2 class="text-xl font-bold text-white mb-6 font-[Outfit]">{{ __('Add New Service') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Service Name (EN)') }}</label>
                    <input wire:model="newSvcNameEn" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Service Name (AR)') }}</label>
                    <input wire:model="newSvcNameAr" type="text" dir="rtl" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Description (EN)') }}</label>
                    <input wire:model="newSvcDescEn" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Description (AR)') }}</label>
                    <input wire:model="newSvcDescAr" type="text" dir="rtl" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Color (hex)') }}</label>
                    <input wire:model="newSvcColor" type="color" class="h-10 w-full bg-slate-900 border border-slate-600 rounded-lg cursor-pointer">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Icon (emoji)') }}</label>
                    <input wire:model="newSvcIcon" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="saveService" class="px-6 py-2.5 rounded-xl bg-green-500/20 text-green-300 font-bold hover:bg-green-500/30 transition-all">{{ __('Save') }}</button>
                <button wire:click="$set('showAddService', false)" class="px-6 py-2.5 rounded-xl bg-slate-700/50 text-white/50 hover:bg-slate-700 transition-all">{{ __('Cancel') }}</button>
            </div>
        </div>
        @endif

        {{-- ── Service Cards ── --}}
        @foreach($services as $service)
        <div class="glass rounded-3xl p-6 sm:p-8">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0" style="background-color: {{ $service->color }}20; color: {{ $service->color }}">
                        {{ $service->icon }}
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-xl font-bold font-[Outfit] text-white">
                            {{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}
                        </h2>
                        
                        {{-- Manager Assignment Display --}}
                        <div class="flex items-center gap-2 mt-1 min-w-0">
                            @if($editingServiceManagerId === $service->id)
                                <div class="flex items-center flex-wrap gap-2">
                                    <select wire:model="serviceManagerSelect" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-xs text-white outline-none focus:ring-1 focus:ring-orange-500 max-w-[150px] sm:max-w-none">
                                        <option value="">{{ __('No Manager') }}</option>
                                        @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->email }})</option>
                                        @endforeach
                                    </select>
                                    <div class="flex items-center gap-1">
                                        <button wire:click="saveServiceManager" class="p-1 rounded bg-green-500/20 text-green-400 hover:bg-green-500/30">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="$set('editingServiceManagerId', null)" class="p-1 rounded bg-slate-700/50 text-white/50 hover:bg-slate-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <span class="text-[10px] text-white/40 uppercase tracking-tighter">{{ __('Assigned Manager') }}:</span>
                                <button wire:click="editServiceManager({{ $service->id }})" class="flex items-center gap-1.5 px-2 py-0.5 rounded bg-white/5 border border-white/10 hover:bg-white/10 transition-all group">
                                    <span class="text-xs font-bold {{ $service->manager ? 'text-orange-400' : 'text-white/30' }}">
                                        {{ $service->manager ? $service->manager->name : __('Unassigned') }}
                                    </span>
                                    <svg class="w-3 h-3 text-white/20 group-hover:text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button wire:click="startAddSubService({{ $service->id }})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-500/20 text-violet-300 hover:bg-violet-500/30 text-xs font-semibold transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        {{ __('Add New Sub-Service') }}
                    </button>
                    <button wire:click="deleteService({{ $service->id }})" wire:confirm="هل أنت متأكد من حذف هذه الخدمة ومحتوياتها؟" class="p-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>

            {{-- Add Sub-Service Form --}}
            @if($addingSubServiceId === $service->id)
            <div class="mb-6 p-4 rounded-2xl bg-violet-900/20 border border-violet-500/30">
                <p class="text-sm font-semibold text-violet-300 mb-3">{{ __('Add New Sub-Service') }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input wire:model="newSubNameEn" type="text" placeholder="{{ __('Sub-Service Name (EN)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-violet-500">
                    <input wire:model="newSubNameAr" type="text" dir="rtl" placeholder="{{ __('Sub-Service Name (AR)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-3 py-2 text-white text-sm outline-none focus:ring-2 focus:ring-violet-500">
                </div>
                <div class="flex gap-3 mt-3">
                    <button wire:click="saveSubService" class="px-5 py-2 rounded-lg bg-violet-500/20 text-violet-300 font-bold hover:bg-violet-500/30 text-sm transition-all">{{ __('Save') }}</button>
                    <button wire:click="cancelAddSubService" class="px-5 py-2 rounded-lg bg-slate-700/50 text-white/50 text-sm hover:bg-slate-700 transition-all">{{ __('Cancel') }}</button>
                </div>
            </div>
            @endif

            {{-- Sub-Services --}}
            <div class="space-y-4">
                @foreach($service->subServices as $subService)
                <div class="bg-slate-800/50 rounded-2xl border border-slate-700/50 overflow-hidden">
                    <div class="px-5 py-3 bg-slate-800/80 border-b border-slate-700/50 flex items-center justify-between">
                        <h3 class="font-semibold text-white">{{ app()->getLocale() === 'ar' ? $subService->name_ar : $subService->name_en }}</h3>
                        <div class="flex items-center gap-2">
                            <button wire:click="startAddOption({{ $subService->id }})" class="flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-500/10 text-green-400 hover:bg-green-500/20 text-xs font-semibold transition-all">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                {{ __('Add New Option') }}
                            </button>
                            <button wire:click="deleteSubService({{ $subService->id }})" wire:confirm="حذف هذه الخدمة الفرعية وخياراتها؟" class="p-1 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Add Option Form --}}
                    @if($addingOptionSubId === $subService->id)
                    <div class="p-4 bg-green-900/10 border-b border-slate-700/50">
                        <p class="text-xs font-semibold text-green-400 mb-3">{{ __('Add New Option') }}</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                            <input wire:model="newOptNameEn" type="text" placeholder="{{ __('Option Name (EN)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-green-500">
                            <input wire:model="newOptNameAr" type="text" dir="rtl" placeholder="{{ __('Option Name (AR)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-green-500">
                            <input wire:model="newOptUnitEn" type="text" placeholder="{{ __('Unit Label (EN)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-slate-500">
                            <input wire:model="newOptUnitAr" type="text" dir="rtl" placeholder="{{ __('Unit Label (AR)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-slate-500">
                            <input wire:model="newOptPrice" type="number" placeholder="{{ __('Base Price (SAR)') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-orange-500">
                            <input wire:model="newOptMultiplier" type="number" step="0.1" placeholder="{{ __('Urgency Multiplier') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-violet-500">
                            <input wire:model="newOptMinQty" type="number" placeholder="{{ __('Min Qty') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-slate-500">
                            <input wire:model="newOptMaxQty" type="number" placeholder="{{ __('Max Qty') }}" class="bg-slate-900 border border-slate-600 rounded-lg px-2 py-1.5 text-white outline-none focus:ring-1 focus:ring-slate-500">
                        </div>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="saveOption2" class="px-4 py-1.5 rounded-lg bg-green-500/20 text-green-300 font-bold hover:bg-green-500/30 text-xs transition-all">{{ __('Save') }}</button>
                            <button wire:click="cancelAddOption" class="px-4 py-1.5 rounded-lg bg-slate-700/50 text-white/50 text-xs hover:bg-slate-700 transition-all">{{ __('Cancel') }}</button>
                        </div>
                    </div>
                    @endif

                    {{-- Options Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-white/50">
                            <thead class="text-xs text-white/60 uppercase bg-slate-900/50">
                                <tr>
                                    <th class="px-4 py-3">{{ __('Option') }}</th>
                                    <th class="px-4 py-3">{{ __('Base Price (SAR)') }}</th>
                                    <th class="px-4 py-3">{{ __('Urgency Multiplier') }}</th>
                                    <th class="px-4 py-3">{{ __('Min Qty') }} / {{ __('Max Qty') }}</th>
                                    <th class="px-4 py-3 text-right">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subService->serviceOptions as $option)
                                    @if($editingOptionId === $option->id)
                                    <tr class="border-b border-slate-700/50 bg-slate-900/30">
                                        <td class="px-4 py-3 text-white font-medium">{{ app()->getLocale() === 'ar' ? $option->name_ar : $option->name_en }}</td>
                                        <td class="px-4 py-3"><input type="number" wire:model="editBasePrice" class="w-24 bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm outline-none focus:ring-1 focus:ring-orange-500"></td>
                                        <td class="px-4 py-3"><input type="number" step="0.1" wire:model="editMultiplier" class="w-20 bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm outline-none focus:ring-1 focus:ring-violet-500"></td>
                                        <td class="px-4 py-3">
                                            <div class="flex gap-1">
                                                <input type="number" wire:model="editMinQuantity" class="w-14 bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm outline-none focus:ring-1 focus:ring-slate-500">
                                                <span class="text-white/70">/</span>
                                                <input type="number" wire:model="editMaxQuantity" class="w-14 bg-slate-900 border border-slate-600 rounded-lg px-2 py-1 text-white text-sm outline-none focus:ring-1 focus:ring-slate-500">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-right space-x-2 rtl:space-x-reverse whitespace-nowrap">
                                            <button wire:click="saveOption" class="px-3 py-1 rounded-lg bg-green-500/20 text-green-400 font-bold hover:bg-green-500/30 text-xs transition-all">{{ __('Save') }}</button>
                                            <button wire:click="cancelEdit" class="px-3 py-1 rounded-lg bg-slate-600/20 text-white/50 text-xs hover:bg-slate-600/40 transition-all">{{ __('Cancel') }}</button>
                                        </td>
                                    </tr>
                                    @else
                                    <tr class="border-b border-slate-700/50 hover:bg-slate-800/30 transition-colors">
                                        <td class="px-4 py-3 text-white font-medium">{{ app()->getLocale() === 'ar' ? $option->name_ar : $option->name_en }}</td>
                                        <td class="px-4 py-3 text-orange-400 font-semibold font-[Outfit]">{{ number_format($option->base_price, 2) }}</td>
                                        <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-lg bg-violet-500/10 text-violet-400 text-xs font-bold">{{ $option->urgent_multiplier }}x</span></td>
                                        <td class="px-4 py-3 text-white/60 text-xs">{{ $option->min_quantity }} – {{ $option->max_quantity }}</td>
                                        <td class="px-4 py-3 text-right space-x-1 rtl:space-x-reverse whitespace-nowrap">
                                            <button wire:click="editOption({{ $option->id }})" class="px-3 py-1 rounded-lg bg-slate-700 hover:bg-orange-500 hover:text-white text-xs font-semibold transition-all">{{ __('Edit Pricing') }}</button>
                                            <button wire:click="deleteOption({{ $option->id }})" wire:confirm="حذف هذا الخيار؟" class="px-2 py-1 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 text-xs transition-all">✕</button>
                                        </td>
                                    </tr>
                                    @endif
                                @empty
                                    <tr><td colspan="5" class="px-4 py-6 text-center text-white/70 text-sm">{{ __('No options yet. Click "Add New Option" above.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </main>
</div>

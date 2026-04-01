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
                        <span class="bg-gradient-to-r from-orange-600 to-violet-600 bg-clip-text text-transparent">{{ __('Agent Management') }}</span>
                    </h1>
                    <p class="text-xs text-white/70">{{ __('Manage technicians, verify profiles, control availability') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="$set('showAddForm', true)" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-green-500/20 text-green-300 hover:bg-green-500/30 text-sm font-semibold transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    {{ __('Add Technician') }}
                </button>
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 text-sm font-medium transition-all">
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 pb-24 space-y-6">

        @if (session()->has('message'))
            <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400">
                {{ session('message') }}
            </div>
        @endif

        {{-- ── Add Technician Form ── --}}
        @if($showAddForm)
        <div class="glass rounded-3xl p-6 sm:p-8 border-2 border-green-500/30">
            <h2 class="text-xl font-bold text-white font-[Outfit] mb-6">{{ __('Add New Technician') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Full Name') }}</label>
                    <input wire:model="newName" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-green-500 transition-all" placeholder="Mohammed Al-Khaldi">
                    @error('newName') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Email') }}</label>
                    <input wire:model="newEmail" type="email" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-green-500 transition-all" placeholder="tech@alkifah.com">
                    @error('newEmail') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Phone') }}</label>
                    <input wire:model="newPhone" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-green-500 transition-all" placeholder="+966501234567">
                    @error('newPhone') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Password') }}</label>
                    <input wire:model="newPassword" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500 transition-all" placeholder="password">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Specializations') }} <span class="text-white/80">({{ __('comma-separated') }})</span></label>
                    <input wire:model="newSpecs" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-violet-500 transition-all" placeholder="AC Repair, Plumbing, CCTV">
                </div>
                <div>
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Bio (EN)') }}</label>
                    <input wire:model="newBioEn" type="text" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500 transition-all" placeholder="Expert in...">
                </div>
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="text-xs text-white/60 mb-1 block">{{ __('Bio (AR)') }}</label>
                    <input wire:model="newBioAr" type="text" dir="rtl" class="w-full bg-slate-900 border border-slate-600 rounded-lg px-3 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-slate-500 transition-all" placeholder="متخصص في...">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button wire:click="saveAgent" class="px-6 py-2.5 rounded-xl bg-green-500/20 text-green-300 font-bold hover:bg-green-500/30 transition-all">{{ __('Save') }}</button>
                <button wire:click="$set('showAddForm', false)" class="px-6 py-2.5 rounded-xl bg-slate-700/50 text-white/50 hover:bg-slate-700 transition-all">{{ __('Cancel') }}</button>
            </div>
        </div>
        @endif

        {{-- ── Search & Tabs ── --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-1 glass rounded-xl p-1">
                <button wire:click="$set('tab', 'all')" class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $tab === 'all' ? 'bg-violet-600 text-white shadow' : 'text-white/60 hover:text-white' }}">{{ __('All Agents') }}</button>
                <button wire:click="$set('tab', 'pending')" class="px-4 py-1.5 rounded-lg text-sm font-semibold transition-all {{ $tab === 'pending' ? 'bg-orange-500 text-white shadow' : 'text-white/60 hover:text-white' }}">{{ __('Pending Approval') }}</button>
            </div>
            <div class="relative w-full sm:w-72">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Search agents...') }}" class="w-full bg-slate-900/80 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-white text-sm outline-none focus:ring-2 focus:ring-violet-500 transition-all">
            </div>
        </div>

        {{-- ── Agents Table ── --}}
        <div class="glass rounded-3xl overflow-hidden">
            @if($agents->isEmpty())
                <div class="py-20 text-center">
                    <div class="text-5xl mb-4">👷</div>
                    <p class="text-white/60">{{ $tab === 'pending' ? __('No pending agents.') : __('No technicians yet. Click "Add Technician" to get started.') }}</p>
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-white/50">
                    <thead class="text-xs text-white/60 uppercase bg-slate-900/60 border-b border-slate-700/50">
                        <tr>
                            <th class="px-5 py-4 text-left">{{ __('Technician') }}</th>
                            <th class="px-5 py-4">{{ __('Contact') }}</th>
                            <th class="px-5 py-4">{{ __('Rating') }}</th>
                            <th class="px-5 py-4">{{ __('Jobs') }}</th>
                            <th class="px-5 py-4">{{ __('Verified') }}</th>
                            <th class="px-5 py-4">{{ __('Available') }}</th>
                            <th class="px-5 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700/30">
                        @foreach($agents as $agent)
                        @php $profile = $agent->technicianProfile; @endphp
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            {{-- Name & Bio --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-orange-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($agent->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">{{ $agent->name }}</div>
                                        @if($profile)
                                            <div class="text-xs text-white/70 truncate max-w-[180px]">
                                                {{ app()->getLocale() === 'ar' ? $profile->bio_ar : $profile->bio_en }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Contact --}}
                            <td class="px-5 py-4">
                                <div class="text-xs text-white/60">{{ $agent->email }}</div>
                                <div class="text-xs text-white/70">{{ $agent->phone }}</div>
                            </td>

                            {{-- Rating --}}
                            <td class="px-5 py-4 text-center">
                                @if($profile)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-yellow-500/10 text-yellow-400 text-xs font-bold">
                                        ⭐ {{ number_format($profile->rating, 1) }}
                                    </span>
                                @else
                                    <span class="text-white/80">—</span>
                                @endif
                            </td>

                            {{-- Jobs --}}
                            <td class="px-5 py-4 text-center">
                                @if($profile)
                                    <div class="text-white font-semibold font-[Outfit]">{{ $profile->completed_jobs }}</div>
                                    <div class="text-xs text-white/70">/ {{ $profile->total_jobs }} {{ __('total') }}</div>
                                @else
                                    <span class="text-white/80">—</span>
                                @endif
                            </td>

                            {{-- Verified Toggle --}}
                            <td class="px-5 py-4 text-center">
                                @if($profile)
                                    <button wire:click="toggleVerified({{ $profile->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $profile->is_verified ? 'bg-green-500' : 'bg-slate-600' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $profile->is_verified ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="text-xs mt-1 {{ $profile->is_verified ? 'text-green-400' : 'text-white/70' }}">
                                        {{ $profile->is_verified ? __('Verified') : __('Unverified') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Available Toggle --}}
                            <td class="px-5 py-4 text-center">
                                @if($profile)
                                    <button wire:click="toggleAvailable({{ $profile->id }})" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none {{ $profile->is_available ? 'bg-violet-500' : 'bg-slate-600' }}">
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform {{ $profile->is_available ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <div class="text-xs mt-1 {{ $profile->is_available ? 'text-violet-400' : 'text-white/70' }}">
                                        {{ $profile->is_available ? __('Online') : __('Offline') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <button wire:click="deleteAgent({{ $agent->id }})" wire:confirm="هل أنت متأكد من حذف هذا الفني؟" class="px-3 py-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 text-xs font-semibold transition-all">
                                    {{ __('Remove') }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- ── Legend ── --}}
        <div class="flex flex-wrap gap-4 text-xs text-white/70">
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> {{ __('Verified = can accept jobs') }}</span>
            <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-3 rounded-full bg-violet-500"></span> {{ __('Available = currently online & dispatachable') }}</span>
        </div>

    </main>
</div>

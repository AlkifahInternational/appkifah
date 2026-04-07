<div class="min-h-screen bg-slate-950 p-4 sm:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-[Outfit]">
                    {{ __('Technical Managers') }}
                </h1>
                <p class="text-slate-400 text-sm mt-1">
                    {{ __('Manage accounts for service category supervisors') }}
                </p>
            </div>
            <button wire:click="$toggle('showAddForm')"
                    class="flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold transition-all shadow-lg shadow-violet-900/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                {{ $showAddForm ? __('Close Form') : __('Add New Manager') }}
            </button>
        </div>

        @if (session()->has('message'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Add Manager Form --}}
        @if($showAddForm)
            <div class="glass-dark border border-white/10 rounded-3xl p-6 sm:p-8 animate-in zoom-in-95 duration-200">
                <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <div class="p-2 rounded-lg bg-violet-500/20 text-violet-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    {{ __('Register New Manager') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 px-1">{{ __('Full Name') }}</label>
                        <input wire:model="newName" type="text" placeholder="e.g. John Doe" 
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white outline-none focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        @error('newName') <span class="text-red-400 text-[10px] mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 px-1">{{ __('Email Address') }}</label>
                        <input wire:model="newEmail" type="email" placeholder="manager@alkifah.com"
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white outline-none focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        @error('newEmail') <span class="text-red-400 text-[10px] mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 px-1">{{ __('Phone Number') }}</label>
                        <input wire:model="newPhone" type="text" placeholder="+9665..."
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white outline-none focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        @error('newPhone') <span class="text-red-400 text-[10px] mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 px-1">{{ __('Password') }}</label>
                        <input wire:model="newPassword" type="password" 
                               class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-white outline-none focus:ring-2 focus:ring-violet-500 transition-all text-sm">
                        @error('newPassword') <span class="text-red-400 text-[10px] mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-8">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3 px-1">{{ __('Assign Service Categories') }}</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($allServices as $service)
                            <label class="flex items-center gap-3 p-3 rounded-xl bg-slate-900/50 border border-slate-800 cursor-pointer hover:border-violet-500/50 transition-all group">
                                <input type="checkbox" wire:model="selectedServiceIds" value="{{ $service->id }}" 
                                       class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-violet-600 focus:ring-violet-500">
                                <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors">
                                    {{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button wire:click="saveManager" class="px-8 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white font-bold transition-all shadow-lg shadow-violet-900/20 active:scale-95">
                        {{ __('Create Manager Account') }}
                    </button>
                </div>
            </div>
        @endif

        {{-- Filters & Table --}}
        <div class="glass-dark border border-white/5 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-white/5 bg-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative max-w-md w-full">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input wire:model.live="search" type="text" placeholder="{{ __('Search managers by name, email or phone...') }}"
                           class="w-full bg-slate-900/50 border border-white/10 rounded-2xl pl-10 pr-4 py-2 text-white placeholder-slate-500 outline-none focus:ring-2 focus:ring-violet-500/50 transition-all text-sm">
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                        {{ count($managers) }} {{ __('Total Managers') }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs font-bold text-slate-500 uppercase tracking-widest border-b border-white/5">
                            <th class="px-6 py-4">{{ __('Manager Info') }}</th>
                            <th class="px-6 py-4">{{ __('Assigned Categories') }}</th>
                            <th class="px-6 py-4">{{ __('Account Status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($managers as $manager)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-violet-600/20 to-blue-600/20 border border-white/10 flex items-center justify-center text-violet-400 font-bold text-lg shadow-inner">
                                            {{ strtoupper(substr($manager->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-white font-bold text-base leading-none">{{ $manager->name }}</p>
                                            <p class="text-slate-500 text-xs mt-1.5 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                {{ $manager->email }}
                                            </p>
                                            <p class="text-slate-500 text-[11px] mt-1 flex items-center gap-1.5 tracking-tight group-hover:text-slate-400 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                {{ $manager->phone }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse($manager->managedServices as $service)
                                            <span class="px-2.5 py-1 rounded-lg bg-orange-500/10 border border-orange-500/20 text-orange-400 text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 shadow-[0_0_6px_rgba(249,115,22,0.6)]"></span>
                                                {{ app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en }}
                                            </span>
                                        @empty
                                            <span class="text-slate-600 text-[10px] uppercase font-bold italic">{{ __('No categories assigned') }}</span>
                                        @endforelse
                                        <a href="{{ route('admin.services') }}" class="p-1 rounded bg-white/5 text-white/20 hover:text-white/60 transition-all" title="{{ __('Manage Assignments') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        <span class="text-emerald-400 text-[11px] font-bold uppercase tracking-widest">{{ __('Active') }}</span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-tight">{{ __('Created') }}: {{ $manager->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <button x-on:click="if (confirm('{{ __('Are you sure you want to remove this manager account?') }}')) { $wire.deleteManager({{ $manager->id }}) }"
                                            class="p-2.5 rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all transform active:scale-90 group-hover:scale-105 shadow-lg shadow-red-900/5">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-900 flex items-center justify-center text-slate-700">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">{{ __('No technical managers found') }}</p>
                                        <button wire:click="$set('showAddForm', true)" class="text-violet-400 text-xs hover:text-violet-300 font-bold underline">{{ __('Create your first manager account') }}</button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

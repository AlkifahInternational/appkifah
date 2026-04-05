<div class="relative" x-data="{ open: false }" wire:poll.30s>
    <button @click="open = !open; if(open) $wire.markAllAsRead()" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-white/60 hover:text-white transition-all relative group">
        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        @if($this->unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-5 w-5 bg-orange-500 border-2 border-[#1a0a2e] text-[10px] font-black text-white items-center justify-center">
                    {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
                </span>
            </span>
        @endif
    </button>
    
    {{-- Notifications Dropdown --}}
    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="fixed inset-x-4 sm:absolute sm:inset-auto sm:right-0 mt-3 sm:w-80 bg-white rounded-2xl shadow-2xl border border-slate-200 z-50 overflow-hidden" 
         style="display: none;">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <span class="font-bold text-slate-900">{{ app()->getLocale() === 'ar' ? 'الإشعارات' : 'Notifications' }}</span>
            @if($this->unreadCount > 0)
                <span class="text-xs font-bold bg-violet-100 text-violet-600 px-2 py-0.5 rounded-full">{{ $this->unreadCount }}</span>
            @endif
        </div>
        <div class="max-h-80 overflow-y-auto p-2">
            @forelse($this->notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" class="block p-3 {{ $notification->read_at ? 'opacity-60 grayscale-[0.5]' : 'bg-orange-50/50' }} rounded-xl mb-1 flex gap-3 text-sm transition-all hover:bg-slate-50 border border-transparent hover:border-slate-100">
                    <div class="w-2 h-2 mt-2 rounded-full {{ $notification->read_at ? 'bg-slate-300' : 'bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.4)]' }} shrink-0"></div>
                    <div>
                        <p class="text-slate-900 font-bold leading-snug">{{ $notification->data['message'] ?? 'Notification' }}</p>
                        <p class="text-[10px] text-slate-500 mt-1.5 font-semibold">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="py-12 text-center text-slate-400">
                    <div class="text-4xl mb-2 opacity-30">🔔</div>
                    <p class="text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'لا توجد إشعارات جديدة' : 'No new notifications' }}</p>
                </div>
            @endforelse
        </div>
        
        @if($this->notifications->isNotEmpty())
            <div class="p-2 bg-slate-50 border-t border-slate-100">
                <button wire:click="markAllAsRead" class="w-full py-2 text-xs font-bold text-slate-500 hover:text-violet-600 transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'تم قراءة الكل' : 'Mark all as read' }}
                </button>
            </div>
        @endif
    </div>
</div>

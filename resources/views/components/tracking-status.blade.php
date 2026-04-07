@props(['order'])

@php
    $progress = $order->getJourneyProgress();
    $eta = $order->getEstimatedArrivalTime();
    $status = $order->status;
    $isEnRoute = $status->value === 'en_route';
    $isInProgress = $status->value === 'in_progress';
@endphp

<div {{ $attributes->merge(['class' => 'relative w-full py-4 px-2']) }}>
    {{-- ETA Header --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-orange-500 {{ $isEnRoute ? 'animate-pulse' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-widest text-white/40 font-bold">
                    {{ $isEnRoute ? __('Technician En Route') : ($isInProgress ? __('Job in Progress') : __('Waiting to Start')) }}
                </p>
                <p class="text-sm font-bold text-white leading-tight">
                    @if($isEnRoute && $eta !== null)
                        {{ __('Arriving in') }} <span class="text-orange-400">{{ $eta }}</span> {{ __('mins') }}
                    @elseif($isInProgress)
                        {{ __('Work is being done') }}
                    @else
                        {{ __('Technician is preparing') }}
                    @endif
                </p>
            </div>
        </div>
        @if($isEnRoute)
            <div class="px-2 py-1 rounded-full bg-orange-500/10 border border-orange-500/20">
                <span class="text-[10px] font-black text-orange-400 animate-pulse">LIVE</span>
            </div>
        @endif
    </div>

    {{-- Progress Line --}}
    <div class="relative h-1.5 w-full bg-white/5 rounded-full overflow-visible mb-2">
        {{-- Completed Line --}}
        <div class="absolute left-0 top-0 h-full bg-linear-to-r from-violet-600 to-orange-500 rounded-full transition-all duration-1000 ease-in-out" 
             style="width: {{ $progress }}%">
        </div>

        {{-- Technician Icon (Moving) --}}
        <div class="absolute top-1/2 -translate-y-1/2 transition-all duration-1000 ease-in-out flex flex-col items-center" 
             style="left: {{ $progress }}%; transform: translate(-50%, -50%);">
            <div class="w-6 h-6 rounded-lg bg-white shadow-xl shadow-orange-500/20 flex items-center justify-center border-2 border-orange-500 relative">
                <svg class="w-3.5 h-3.5 text-orange-600 {{ $isEnRoute ? 'animate-bounce' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.22.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                </svg>
                {{-- Arrow --}}
                @if($isEnRoute)
                <div class="absolute -right-1 top-1/2 -translate-y-1/2">
                    <svg class="w-2.5 h-2.5 text-orange-500 animate-pulse" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                    </svg>
                </div>
                @endif
            </div>
        </div>

        {{-- Start/End Markers --}}
        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full bg-violet-600 border-2 border-slate-900 shadow-sm"></div>
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-4 rounded-lg bg-emerald-500 border-2 border-slate-900 shadow-lg shadow-emerald-500/20 flex items-center justify-center">
            <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
        </div>
    </div>
    
    {{-- Help Text --}}
    <div class="flex justify-between mt-6">
        <span class="text-[9px] text-white/30 uppercase font-black tracking-widest">{{ __('Start') }}</span>
        <span class="text-[9px] text-white/30 uppercase font-black tracking-widest">{{ __('Destination') }}</span>
    </div>
</div>

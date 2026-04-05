<div class="px-4 sm:px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-black font-[Outfit] text-transparent bg-clip-text bg-linear-to-r from-orange-400 to-violet-400 flex items-center gap-3">
            <svg class="w-8 h-8 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            {{ app()->getLocale() === 'ar' ? 'تحليلات المنصة' : 'Platform Analytics' }}
        </h1>
        <p class="text-white/40 text-sm mt-1 uppercase tracking-widest font-black">{{ __('Deep Data Insights') }}</p>
    </div>

    {{-- ── Analytics Section ─────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 fade-in">
        {{-- Revenue by Category Chart --}}
        <div class="glass rounded-3xl p-6 border border-white/5 relative overflow-hidden bg-slate-900/40">
            <div class="absolute inset-x-0 top-0 h-32 bg-linear-to-b from-green-500/5 to-transparent pointer-events-none"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div>
                    <h3 class="text-lg font-bold text-white/90">{{ __('Revenue by Category') }}</h3>
                    <p class="text-[10px] text-white/40 mt-1 uppercase tracking-wider font-bold">{{ __('Completed Orders Only') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-400 border border-green-500/10 shadow-lg shadow-green-500/5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
            </div>
            
            <div id="revenueChart" class="min-h-[350px]"></div>
            
            @if($revenueByCategory->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[4px] rounded-3xl z-20">
                    <div class="text-center">
                        <div class="text-3xl mb-3">💰</div>
                        <p class="text-xs font-black text-white/60 uppercase tracking-widest">{{ __('No revenue data recorded') }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Orders by Category Chart --}}
        <div class="glass rounded-3xl p-6 border border-white/5 relative overflow-hidden bg-slate-900/40">
            <div class="absolute inset-x-0 top-0 h-32 bg-linear-to-b from-violet-500/5 to-transparent pointer-events-none"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <div>
                    <h3 class="text-lg font-bold text-white/90">{{ __('Orders by Category') }}</h3>
                    <p class="text-[10px] text-white/40 mt-1 uppercase tracking-wider font-bold">{{ __('All Recorded Orders') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-400 border border-violet-500/10 shadow-lg shadow-violet-500/5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>

            <div id="ordersChart" class="min-h-[350px]"></div>

            @if($ordersByCategory->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center bg-black/40 backdrop-blur-[4px] rounded-3xl z-20">
                    <div class="text-center">
                        <div class="text-3xl mb-3">📦</div>
                        <p class="text-xs font-black text-white/60 uppercase tracking-widest">{{ __('No orders data recorded') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:navigated', function () {
            // Theme Colors
            const colors = ['#f97316', '#8b5cf6', '#10b981', '#ef4444', '#06b6d4', '#f59e0b'];
            const locale = '{{ app()->getLocale() }}';

            // 1. Revenue Chart
            const revenueData = @json($revenueByCategory);
            if (revenueData.length > 0) {
                new ApexCharts(document.querySelector("#revenueChart"), {
                    series: revenueData.map(item => parseInt(item.revenue)),
                    labels: revenueData.map(item => locale === 'ar' ? item.name_ar : item.name_en),
                    chart: { type: 'donut', height: 350 },
                    colors: colors,
                    stroke: { show: false },
                    legend: { position: 'bottom', labels: { colors: '#fff' } },
                    dataLabels: { enabled: true, dropShadow: { enabled: false } },
                    plotOptions: { pie: { donut: { size: '70%', background: 'transparent' } } }
                }).render();
            }

            // 2. Orders Chart
            const ordersData = @json($ordersByCategory);
            if (ordersData.length > 0) {
                new ApexCharts(document.querySelector("#ordersChart"), {
                    series: [{
                        name: locale === 'ar' ? 'الطلبات' : 'Orders',
                        data: ordersData.map(item => parseInt(item.count))
                    }],
                    chart: { type: 'bar', height: 350, toolbar: { show: false } },
                    colors: ['#8b5cf6'],
                    plotOptions: { bar: { borderRadius: 8, columnWidth: '45%' } },
                    xaxis: { 
                        categories: ordersData.map(item => locale === 'ar' ? item.name_ar : item.name_en),
                        labels: { style: { colors: '#fff', fontWeight: 600 } }
                    },
                    yaxis: { labels: { style: { colors: '#fff' } } },
                    grid: { borderColor: 'rgba(255,255,255,0.05)' },
                    dataLabels: { enabled: false }
                }).render();
            }
        });
    </script>
    @endpush
</div>

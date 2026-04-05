<?php

namespace App\Livewire;

use App\Models\Order;
use App\Enums\OrderStatus;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['title' => 'Analytics'])]
class AdminAnalytics extends Component
{
    public function render()
    {
        // ── Analytics Data ─────────────────────────────────
        // Revenue by Service Category
        $revenueByCategory = DB::table('services')
            ->join('sub_services', 'services.id', '=', 'sub_services.service_id')
            ->join('service_options', 'sub_services.id', '=', 'service_options.sub_service_id')
            ->join('order_items', 'service_options.id', '=', 'order_items.service_option_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatus::COMPLETED->value)
            ->select('services.name_en', 'services.name_ar', DB::raw('SUM(order_items.total_price) as revenue'))
            ->groupBy('services.id', 'services.name_en', 'services.name_ar')
            ->get();

        // Orders by Service Category
        $ordersByCategory = DB::table('services')
            ->join('sub_services', 'services.id', '=', 'sub_services.service_id')
            ->join('service_options', 'sub_services.id', '=', 'service_options.sub_service_id')
            ->join('order_items', 'service_options.id', '=', 'order_items.service_option_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('services.name_en', 'services.name_ar', DB::raw('COUNT(DISTINCT orders.id) as count'))
            ->groupBy('services.id', 'services.name_en', 'services.name_ar')
            ->get();

        return view('livewire.admin-analytics', [
            'revenueByCategory' => $revenueByCategory,
            'ordersByCategory'  => $ordersByCategory,
        ]);
    }
}

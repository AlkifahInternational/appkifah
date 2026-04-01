<?php

namespace App\Livewire;

use App\Models\Order;
use App\Enums\OrderStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class AdminLiveOrders extends Component
{
    public string $filter = 'active'; // active | pending | all | completed

    #[On('order-placed')]
    #[On('order-status-changed')]
    public function refresh(): void
    {
        // Livewire re-renders on these events from JS Echo
    }

    public function forceDispatch(int $orderId): void
    {
        $order = Order::with(['client'])->findOrFail($orderId);
        \App\Services\DispatchService::dispatch($order);
        $order->refresh()->load(['client', 'technician']);
        \App\Events\OrderStatusChanged::dispatch($order);
    }

    public function cancelOrder(int $orderId): void
    {
        Order::findOrFail($orderId)->update([
            'status'       => OrderStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);
    }

    public function deleteOrder(int $orderId): void
    {
        Order::findOrFail($orderId)->delete();
        session()->flash('success', __('Order deleted permanently.'));
    }

    public function render()
    {
        $query = Order::with(['client', 'technician', 'items.serviceOption'])
            ->latest();

        $query = match($this->filter) {
            'pending'   => $query->where('status', OrderStatus::PENDING),
            'active'    => $query->whereIn('status', [
                                OrderStatus::CONFIRMED,
                                OrderStatus::ASSIGNED,
                                OrderStatus::EN_ROUTE,
                                OrderStatus::IN_PROGRESS,
                            ]),
            'completed' => $query->where('status', OrderStatus::COMPLETED),
            default     => $query,
        };

        $orders = $query->take(50)->get();

        $counts = [
            'pending'   => Order::pending()->whereNull('technician_id')->count(),
            'active'    => Order::active()->count(),
            'completed' => Order::where('status', OrderStatus::COMPLETED)->count(),
            'total'     => Order::count(),
        ];

        return view('livewire.admin-live-orders', compact('orders', 'counts'));
    }
}

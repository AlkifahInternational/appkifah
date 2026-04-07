<?php

namespace App\Livewire;

use App\Models\Order;
use App\Enums\OrderStatus;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class AdminLiveOrders extends Component
{
    use WithPagination;

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
        $user = auth()->user();
        $isManager = $user->role === \App\Enums\UserRole::TECHNICAL_MANAGER;
        $managedServiceIds = $isManager ? $user->managedServices->pluck('id')->toArray() : [];

        $query = Order::with(['client', 'technician', 'items.serviceOption'])
            ->latest();

        // Scope by manager's services if not super admin
        if ($isManager) {
            $query->whereHas('items.serviceOption.subService', function($q) use ($managedServiceIds) {
                $q->whereIn('service_id', $managedServiceIds);
            });
        }

        $filteredQuery = match($this->filter) {
            'pending'   => (clone $query)->where('status', OrderStatus::PENDING),
            'active'    => (clone $query)->whereIn('status', [
                                OrderStatus::CONFIRMED,
                                OrderStatus::ASSIGNED,
                                OrderStatus::EN_ROUTE,
                                OrderStatus::IN_PROGRESS,
                            ]),
            'completed' => (clone $query)->where('status', OrderStatus::COMPLETED),
            default     => clone $query,
        };

        $orders = $filteredQuery->paginate(15);

        // Scope counts
        $countQuery = Order::query();
        if ($isManager) {
            $countQuery->whereHas('items.serviceOption.subService', function($q) use ($managedServiceIds) {
                $q->whereIn('service_id', $managedServiceIds);
            });
        }

        $counts = [
            'pending'   => (clone $countQuery)->where('status', OrderStatus::PENDING)->whereNull('technician_id')->count(),
            'active'    => (clone $countQuery)->whereIn('status', [
                                OrderStatus::CONFIRMED,
                                OrderStatus::ASSIGNED,
                                OrderStatus::EN_ROUTE,
                                OrderStatus::IN_PROGRESS,
                            ])->count(),
            'completed' => (clone $countQuery)->where('status', OrderStatus::COMPLETED)->count(),
            'total'     => (clone $countQuery)->count(),
        ];

        return view('livewire.admin-live-orders', compact('orders', 'counts'));
    }
}

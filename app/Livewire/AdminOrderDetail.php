<?php

namespace App\Livewire;

use App\Models\Order;
use App\Enums\OrderStatus;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

#[Layout('layouts.admin')]
class AdminOrderDetail extends Component
{
    public Order $order;
    public bool $editMode = false;
    public string $editAddress = '';
    public string $editNotes = '';

    public function mount(int $id): void
    {
        $this->order = Order::with([
            'client',
            'technician.technicianProfile',
            'items.serviceOption.subService.service',
        ])->findOrFail($id);
        
        $this->editAddress = $this->order->address ?? '';
        $this->editNotes = $this->order->notes ?? '';
    }

    #[On('order-status-changed')]
    public function refresh(): void
    {
        $this->order = $this->order->fresh([
            'client',
            'technician.technicianProfile',
            'items.serviceOption.subService.service',
        ]);
    }

    public function forceDispatch(): void
    {
        \App\Services\DispatchService::dispatch($this->order);
        $this->order->refresh()->load(['client', 'technician']);
        \App\Events\OrderStatusChanged::dispatch($this->order);
    }

    public function cancelOrder(): void
    {
        $this->order->update([
            'status'       => OrderStatus::CANCELLED,
            'cancelled_at' => now(),
        ]);
        $this->refresh();
    }

    public function toggleEditMode(): void
    {
        $this->editMode = !$this->editMode;
        if ($this->editMode) {
            $this->editAddress = $this->order->address ?? '';
            $this->editNotes = $this->order->notes ?? '';
        }
    }

    public function saveOrder(): void
    {
        $this->order->update([
            'address' => $this->editAddress,
            'notes' => $this->editNotes,
        ]);
        $this->editMode = false;
        session()->flash('success', __('Order updated successfully.'));
        $this->refresh();
    }

    public function deleteOrder(): void
    {
        $this->order->delete();
        $this->redirectRoute('admin.orders.live', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin-order-detail', ['order' => $this->order]);
    }
}

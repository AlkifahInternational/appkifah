<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\ServiceOption;
use App\Enums\UserRole;
use App\Enums\OrderStatus;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ManagerDashboard extends Component
{
    private function canManageDispatch(): bool
    {
        $role = Auth::user()?->role;
        return in_array($role, [UserRole::TECHNICAL_MANAGER, UserRole::SUPER_ADMIN], true);
    }

    public function logout()
    {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function manualDispatch($orderId, $userId)
    {
        if (!$this->canManageDispatch()) {
            abort(403);
        }

        $order = Order::findOrFail($orderId);
        $user = User::findOrFail($userId);

        if ($user->role !== UserRole::TECHNICIAN) {
            session()->flash('error', __('Selected user is not a technician.'));
            return;
        }

        if (!$user->technicianProfile?->is_verified) {
            session()->flash('error', __('Technician is not verified yet.'));
            return;
        }

        if ($order->status !== OrderStatus::PENDING) {
            session()->flash('error', 'Order is already assigned or completed.');
            return;
        }

        $order->update([
            'technician_id' => $user->id,
            'status' => OrderStatus::ASSIGNED,
        ]);

        // Increment total_jobs counter
        if ($user->technicianProfile) {
            $user->technicianProfile->increment('total_jobs');
        }

        // Broadcast update via Reverb for real-time technician notification
        try {
            \App\Events\OrderPlaced::dispatch($order->load(['client', 'technician']));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[Manager] Broadcast failed during manual dispatch: ' . $e->getMessage());
        }

        session()->flash('message', "Order #{$order->order_number} manually assigned to {$user->name}.");
    }

    public function cancelOrder($orderId)
    {
        if (!$this->canManageDispatch()) {
            abort(403);
        }

        $order = Order::findOrFail($orderId);

        if (in_array($order->status, [OrderStatus::COMPLETED, OrderStatus::CANCELLED], true)) {
            session()->flash('error', __('This order cannot be cancelled.'));
            return;
        }

        $order->update(['status' => OrderStatus::CANCELLED]);

        session()->flash('message', "Order #{$order->order_number} has been cancelled.");
    }

    public function render()
    {
        $pendingOrders = Order::pending()->with(['client', 'items.serviceOption'])->latest()->get();
        $activeOrders = Order::active()->with(['client', 'technician'])->latest()->get();
        $availableTechnicians = User::where('role', UserRole::TECHNICIAN)
            ->whereHas('technicianProfile', fn($q) => $q->where('is_available', true)->where('is_verified', true))
            ->with(['technicianProfile', 'wallet'])
            ->get();

        return view('livewire.manager-dashboard', [
            'pendingOrders' => $pendingOrders,
            'activeOrders' => $activeOrders,
            'availableTechnicians' => $availableTechnicians,
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\ServiceOption;
use App\Enums\UserRole;
use App\Enums\OrderStatus;
use Livewire\Component;

class ManagerDashboard extends Component
{
    public function logout()
    {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        $pendingOrders = Order::pending()->with(['client', 'items.serviceOption'])->latest()->get();
        $activeOrders = Order::active()->with(['client', 'technician'])->latest()->get();
        $availableTechnicians = User::where('role', UserRole::TECHNICIAN)
            ->whereHas('technicianProfile', fn($q) => $q->where('is_available', true)->where('is_verified', true))
            ->with('technicianProfile')
            ->get();

        return view('livewire.manager-dashboard', [
            'pendingOrders' => $pendingOrders,
            'activeOrders' => $activeOrders,
            'availableTechnicians' => $availableTechnicians,
        ]);
    }
}

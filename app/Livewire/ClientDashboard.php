<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class ClientDashboard extends Component
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
        $user = auth()->user();
        $activeOrders = Order::where('client_id', $user->id)
            ->whereNotIn('status', [\App\Enums\OrderStatus::COMPLETED, \App\Enums\OrderStatus::CANCELLED])
            ->with('technician')->latest()->get();
        $pastOrders = Order::where('client_id', $user->id)->whereIn('status', ['completed', 'cancelled'])->latest()->take(10)->get();

        return view('livewire.client-dashboard', [
            'activeOrders' => $activeOrders,
            'pastOrders' => $pastOrders,
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\User;
use App\Models\AuditLog;
use App\Enums\UserRole;
use App\Enums\OrderStatus;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin', ['title' => 'Dashboard'])]
class AdminDashboard extends Component
{
    public function logout()
    {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function assignTechnician(int $orderId, int $technicianId)
    {
        $order = Order::findOrFail($orderId);
        $order->update([
            'technician_id' => $technicianId,
            'status'        => OrderStatus::ASSIGNED,
            'assigned_by'   => auth()->id(),
        ]);
        session()->flash('admin_message', 'Order assigned successfully.');
    }

    public function render()
    {
        $totalRevenue     = Order::where('status', OrderStatus::COMPLETED)->sum('total');
        $totalOrders      = Order::count();
        $activeOrders     = Order::active()->count();
        $pendingOrders    = Order::pending()->whereNull('technician_id')->count();
        $totalTechnicians = User::where('role', UserRole::TECHNICIAN)->count();
        $totalClients     = User::where('role', UserRole::CLIENT)->count();
        $recentOrders     = Order::with(['client', 'technician'])->latest()->take(10)->get();
        $recentLogs       = AuditLog::with('user')->latest()->take(10)->get();

        return view('livewire.admin-dashboard', [
            'totalRevenue'      => $totalRevenue,
            'totalOrders'       => $totalOrders,
            'activeOrders'      => $activeOrders,
            'pendingOrders'     => $pendingOrders,
            'totalTechnicians'  => $totalTechnicians,
            'totalClients'      => $totalClients,
            'recentOrders'      => $recentOrders,
            'recentLogs'        => $recentLogs,
        ]);
    }
}

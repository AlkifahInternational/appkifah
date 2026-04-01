<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TechnicianProfile;
use App\Enums\OrderStatus;
use App\Services\DispatchService;
use Livewire\Component;
use Livewire\Attributes\On;

class TechnicianDashboard extends Component
{
    public $tab = 'jobs';
    public bool $isAvailable = false;

    public function mount()
    {
        $profile = auth()->user()->technicianProfile;
        $this->isAvailable = $profile?->is_available ?? false;
    }

    public function logout()
    {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    // ── Availability Toggle ─────────────────────────────
    public function toggleAvailability()
    {
        $profile = auth()->user()->technicianProfile;
        if ($profile) {
            $this->isAvailable = !$this->isAvailable;
            $profile->update(['is_available' => $this->isAvailable]);
        }
    }

    // ── GPS Ping (called from JS every 30s) ─────────────
    public function updateLocation(float $lat, float $lng)
    {
        $profile = auth()->user()->technicianProfile;
        if ($profile) {
            $profile->update(['latitude' => $lat, 'longitude' => $lng]);
        }
    }

    // ── Manually Accept a Pending Order ────────────────
    public function acceptOrder(int $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('status', OrderStatus::PENDING)
            ->whereNull('technician_id')
            ->firstOrFail();

        $order->update([
            'technician_id' => auth()->id(),
            'status'        => OrderStatus::ASSIGNED,
        ]);

        // Update profile job counter
        auth()->user()->technicianProfile?->increment('total_jobs');

        // Broadcast change to admin & client
        $order->load(['client', 'technician']);
        \App\Events\OrderStatusChanged::dispatch($order);

        session()->flash('job_message', __('Order accepted! Check your active jobs.'));
    }

    // ── Job Lifecycle ───────────────────────────────────

    public function startJob(int $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        $order->update([
            'status'     => OrderStatus::IN_PROGRESS,
            'started_at' => now(),
        ]);

        $order->load(['client', 'technician']);
        \App\Events\OrderStatusChanged::dispatch($order);

        session()->flash('job_message', __('Job started!'));
    }

    public function completeJob(int $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('technician_id', auth()->id())
            ->firstOrFail();

        $order->update([
            'status'       => OrderStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        // Update profile counters
        $profile = auth()->user()->technicianProfile;
        if ($profile) {
            $profile->increment('completed_jobs');
        }

        // Credit wallet with commission deducted
        $commissionRate = (float) (\DB::table('settings')->where('key', 'commission_rate')->value('value') ?? 15) / 100;
        DispatchService::creditWallet($order, $commissionRate);

        // Broadcast completion to client + admin
        $order->load(['client', 'technician']);
        \App\Events\OrderStatusChanged::dispatch($order);

        session()->flash('job_message', __('Job completed! Wallet credited.'));
    }

    public function render()
    {
        $user    = auth()->user();
        $profile = $user->technicianProfile;
        $wallet  = $user->wallet;

        // Assigned to me, not yet completed
        $activeJobs = Order::where('technician_id', $user->id)
            ->whereIn('status', [OrderStatus::ASSIGNED, OrderStatus::EN_ROUTE, OrderStatus::IN_PROGRESS])
            ->with(['client', 'items.serviceOption'])
            ->latest()
            ->get();

        $completedJobs = Order::where('technician_id', $user->id)
            ->where('status', OrderStatus::COMPLETED)
            ->latest()
            ->take(10)
            ->get();

        // Admin-level pending orders (unassigned) that this technician could see
        $pendingOrders = Order::where('status', OrderStatus::PENDING)
            ->whereNull('technician_id')
            ->with(['client', 'items.serviceOption'])
            ->latest()
            ->take(10)
            ->get();

        $recentTransactions = $wallet?->transactions()->latest()->take(10)->get() ?? collect();

        return view('livewire.technician-dashboard', [
            'profile'            => $profile,
            'wallet'             => $wallet,
            'activeJobs'         => $activeJobs,
            'completedJobs'      => $completedJobs,
            'pendingOrders'      => $pendingOrders,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}

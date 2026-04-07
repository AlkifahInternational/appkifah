<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\TechnicianProfile;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Services\DispatchService;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TechnicianDashboard extends Component
{
    public $tab = 'jobs';
    public bool $isAvailable = false;

    private function ensureTechnicianRole(): void
    {
        if (Auth::user()?->role !== UserRole::TECHNICIAN) {
            abort(403);
        }
    }

    public function mount()
    {
        $this->ensureTechnicianRole();
        $profile = Auth::user()->technicianProfile;
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
        $this->ensureTechnicianRole();
        $profile = Auth::user()->technicianProfile;
        if ($profile) {
            $this->isAvailable = !$this->isAvailable;
            $profile->update(['is_available' => $this->isAvailable]);
        }
    }

    // ── GPS Ping (called from JS every 30s) ─────────────
    public function updateLocation(float $lat, float $lng)
    {
        $this->ensureTechnicianRole();
        $profile = Auth::user()->technicianProfile;
        if ($profile) {
            $profile->update(['latitude' => $lat, 'longitude' => $lng]);
        }
    }

    // ── Manually Accept a Pending Order ────────────────
    public function acceptOrder(int $orderId)
    {
        $this->ensureTechnicianRole();

        if (!$this->isAvailable) {
            session()->flash('job_message', __('Set your status to online before accepting orders.'));
            return;
        }

        $order = Order::where('id', $orderId)
            ->where('status', OrderStatus::PENDING)
            ->whereNull('technician_id')
            ->firstOrFail();

        $order->update([
            'technician_id' => Auth::id(),
            'status'        => OrderStatus::ASSIGNED,
        ]);

        // Update profile job counter
        Auth::user()->technicianProfile?->increment('total_jobs');

        // ── Notify Managers ──
        try {
            $managedServiceIds = $order->items->map(fn($i) => $i->serviceOption?->subService?->service_id)->unique()->filter();
            $managers = \App\Models\User::whereIn('role', [\App\Enums\UserRole::SUPER_ADMIN, \App\Enums\UserRole::TECHNICAL_MANAGER])
                ->where(function($q) use ($managedServiceIds) {
                    $q->whereHas('managedServices', fn($sub) => $sub->whereIn('id', $managedServiceIds))
                      ->orWhere('role', \App\Enums\UserRole::SUPER_ADMIN);
                })->get();
            
            \Illuminate\Support\Facades\Notification::send($managers, new \App\Notifications\NewOrderNotification($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[Claim] Failed to notify managers: ' . $e->getMessage());
        }

        session()->flash('job_message', __('Order accepted! Check your active jobs.'));
    }

    // ── Job Lifecycle ───────────────────────────────────

    public function setEnRoute(int $orderId)
    {
        $this->ensureTechnicianRole();

        $order = Order::where('id', $orderId)
            ->where('technician_id', Auth::id())
            ->where('status', OrderStatus::ASSIGNED)
            ->firstOrFail();

        // Calculate initial distance for progress tracking
        $dist = $order->getDistanceToClient();

        $order->update([
            'status'              => OrderStatus::EN_ROUTE,
            'en_route_at'         => now(),
            'initial_distance_km' => $dist,
        ]);

        try {
            $order->load(['client', 'technician']);
            \App\Events\OrderStatusChanged::dispatch($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast failed on setEnRoute: ' . $e->getMessage());
        }

        session()->flash('job_message', __('You are now en route to the client!'));
    }

    public function startJob(int $orderId)
    {
        $this->ensureTechnicianRole();

        $order = Order::where('id', $orderId)
            ->where('technician_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== OrderStatus::ASSIGNED && $order->status !== OrderStatus::EN_ROUTE) {
            session()->flash('job_message', __('This job cannot be started in its current status.'));
            return;
        }

        $order->update([
            'status'     => OrderStatus::IN_PROGRESS,
            'started_at' => now(),
        ]);

        try {
            $order->load(['client', 'technician']);
            \App\Events\OrderStatusChanged::dispatch($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast failed on startJob: ' . $e->getMessage());
        }

        session()->flash('job_message', __('Job started!'));
    }

    public function completeJob(int $orderId)
    {
        $this->ensureTechnicianRole();

        $order = Order::where('id', $orderId)
            ->where('technician_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== OrderStatus::IN_PROGRESS) {
            session()->flash('job_message', __('Only in-progress jobs can be completed.'));
            return;
        }

        $order->update([
            'status'       => OrderStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        // Update profile counters
        $profile = Auth::user()->technicianProfile;
        if ($profile) {
            $profile->increment('completed_jobs');
        }

        // Credit wallet with commission deducted
        $commissionRate = (float) (DB::table('settings')->where('key', 'commission_rate')->value('value') ?? 15) / 100;
        DispatchService::creditWallet($order, $commissionRate);

        // Broadcast completion to client + admin
        try {
            $order->load(['client', 'technician']);
            \App\Events\OrderStatusChanged::dispatch($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast failed on completeJob: ' . $e->getMessage());
        }

        session()->flash('job_message', __('Job completed! Wallet credited.'));
    }

    public function render()
    {
        $user    = Auth::user();
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

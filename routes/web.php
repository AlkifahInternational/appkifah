<?php

use App\Livewire\ServiceGrid;
use App\Livewire\Login;
use App\Livewire\AdminDashboard;
use App\Livewire\ManagerDashboard;
use App\Livewire\TechnicianDashboard;
use App\Livewire\ClientDashboard;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────

Route::get('/', ServiceGrid::class)->name('home');
Route::get('/login', Login::class)->name('login');

// ── Locale Switch ──────────────────────────────────────

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
    }
    return redirect()->back();
})->name('locale.switch');

// ── Authenticated Routes ───────────────────────────────

Route::middleware('auth')->group(function () {
    // Generic dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role->value) {
            'super_admin' => redirect()->route('admin.dashboard'),
            'technical_manager' => redirect()->route('manager.dashboard'),
            'technician' => redirect()->route('technician.dashboard'),
            default => redirect()->route('client.dashboard'),
        };
    })->name('dashboard');

    // Super Admin
    Route::middleware('super_admin')->group(function () {
        Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/admin/analytics', \App\Livewire\AdminAnalytics::class)->name('admin.analytics');
        Route::get('/admin/services', \App\Livewire\AdminServiceManager::class)->name('admin.services');
        Route::get('/admin/managers', \App\Livewire\AdminUserManager::class)->name('admin.managers');
        Route::get('/admin/settings', \App\Livewire\AdminSettingsManager::class)->name('admin.settings');
        Route::get('/admin/agents', \App\Livewire\AdminAgentManager::class)->name('admin.agents');
        Route::get('/admin/orders', \App\Livewire\AdminLiveOrders::class)->name('admin.orders.live');
        Route::get('/admin/orders/{id}', \App\Livewire\AdminOrderDetail::class)->name('admin.orders.detail');
        Route::get('/admin/payments', \App\Livewire\AdminPaymentSettings::class)->name('admin.payments');
    });

    // Technical Manager (also accessible by Super Admin)
    Route::middleware('technical_manager')->group(function () {
        Route::get('/manager', ManagerDashboard::class)->name('manager.dashboard');
    });

    // Technician
    Route::middleware('technician')->group(function () {
        Route::get('/technician', TechnicianDashboard::class)->name('technician.dashboard');
    });

    // Client
    Route::get('/client', ClientDashboard::class)->name('client.dashboard');

    // Logout
    Route::post('/logout', function () {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Service;
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.admin', ['title' => 'Managers'])]
class AdminUserManager extends Component
{
    // ── Add Manager Form ──────────────────────────────────
    public $showAddForm = false;
    public $newName     = '';
    public $newEmail    = '';
    public $newPhone    = '';
    public $newPassword = 'password';
    public $selectedServiceIds = [];

    // ── Search ────────────────────────────────────────────
    public $search = '';

    public function managers()
    {
        return User::with('managedServices')
            ->where('role', UserRole::TECHNICAL_MANAGER)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->latest()
            ->get();
    }

    public function saveManager()
    {
        $this->validate([
            'newName'     => 'required|string|max:120',
            'newEmail'    => ['required', 'email', Rule::unique('users', 'email')],
            'newPhone'    => ['required', 'string', Rule::unique('users', 'phone')],
            'newPassword' => 'required|string|min:6',
        ]);

        $manager = User::create([
            'name'              => $this->newName,
            'email'             => $this->newEmail,
            'phone'             => $this->newPhone,
            'password'          => Hash::make($this->newPassword),
            'role'              => UserRole::TECHNICAL_MANAGER,
            'phone_verified'    => true,
            'email_verified_at' => now(),
        ]);

        if (!empty($this->selectedServiceIds)) {
            Service::whereIn('id', $this->selectedServiceIds)->update(['manager_id' => $manager->id]);
        }

        $this->reset(['newName', 'newEmail', 'newPhone', 'newPassword', 'selectedServiceIds']);
        $this->showAddForm = false;
        session()->flash('message', __('Manager added successfully.'));
    }

    public function deleteManager($userId)
    {
        $user = User::findOrFail($userId);
        
        // Dissociate from services first
        \App\Models\Service::where('manager_id', $userId)->update(['manager_id' => null]);
        
        $user->delete();
        session()->flash('message', __('Manager removed successfully.'));
    }

    public function render()
    {
        return view('livewire.admin-user-manager', [
            'managers' => $this->managers(),
            'allServices' => Service::all(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\TechnicianProfile;
use App\Models\Wallet;
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.admin')]
class AdminAgentManager extends Component
{
    // ── Tabs ─────────────────────────────────────────────
    public $tab = 'all'; // all | pending

    // ── Add Agent Form ────────────────────────────────────
    public $showAddForm  = false;
    public $newName      = '';
    public $newEmail     = '';
    public $newPhone     = '';
    public $newPassword  = 'password';
    public $newBioEn     = '';
    public $newBioAr     = '';
    public $newSpecs     = '';   // comma-separated specializations

    // ── Search ────────────────────────────────────────────
    public $search = '';

    public function agents()
    {
        return User::with('technicianProfile')
            ->where('role', UserRole::TECHNICIAN)
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%"))
            ->when($this->tab === 'pending', fn($q) =>
                $q->whereHas('technicianProfile', fn($p) => $p->where('is_verified', false))
            )
            ->latest()
            ->get();
    }

    public function saveAgent()
    {
        $this->validate([
            'newName'     => 'required|string|max:120',
            'newEmail'    => ['required', 'email', Rule::unique('users', 'email')],
            'newPhone'    => ['required', 'string', Rule::unique('users', 'phone')],
            'newPassword' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'              => $this->newName,
            'email'             => $this->newEmail,
            'phone'             => $this->newPhone,
            'password'          => Hash::make($this->newPassword),
            'role'              => UserRole::TECHNICIAN,
            'phone_verified'    => true,
            'email_verified_at' => now(),
        ]);

        TechnicianProfile::create([
            'user_id'        => $user->id,
            'bio_en'         => $this->newBioEn,
            'bio_ar'         => $this->newBioAr,
            'specializations' => $this->newSpecs ? array_map('trim', explode(',', $this->newSpecs)) : [],
            'is_available'   => false,
            'is_verified'    => true,
            'rating'         => 0,
            'total_jobs'     => 0,
            'completed_jobs' => 0,
        ]);

        Wallet::create(['user_id' => $user->id, 'balance' => 0]);

        $this->reset(['newName', 'newEmail', 'newPhone', 'newPassword', 'newBioEn', 'newBioAr', 'newSpecs']);
        $this->showAddForm = false;
        session()->flash('message', __('Technician added successfully.'));
    }

    public function toggleVerified($profileId)
    {
        $profile = TechnicianProfile::findOrFail($profileId);
        $profile->update(['is_verified' => !$profile->is_verified]);
        session()->flash('message', __('Verification status updated.'));
    }

    public function toggleAvailable($profileId)
    {
        $profile = TechnicianProfile::findOrFail($profileId);
        $profile->update(['is_available' => !$profile->is_available]);
    }

    public function deleteAgent($userId)
    {
        User::findOrFail($userId)->delete();
        session()->flash('message', __('Technician removed.'));
    }

    public function render()
    {
        return view('livewire.admin-agent-manager', [
            'agents' => $this->agents(),
        ]);
    }
}

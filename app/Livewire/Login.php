<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            // Redirect based on role
            return match ($user->role->value) {
                'super_admin' => redirect()->route('admin.dashboard'),
                'technical_manager' => redirect()->route('manager.dashboard'),
                'technician' => redirect()->route('technician.dashboard'),
                default => redirect()->route('client.dashboard'),
            };
        }

        $this->addError('email', __('Invalid credentials. Please try again.'));
    }

    public function render()
    {
        return view('livewire.login');
    }
}

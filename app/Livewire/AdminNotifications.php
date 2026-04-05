<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminNotifications extends Component
{
    public function getUnreadCountProperty()
    {
        return Auth::user()->unreadNotifications->count();
    }

    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()->take(10)->get();
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        // $this->dispatch('notifications-updated');
    }

    public function render()
    {
        return view('livewire.admin-notifications');
    }
}

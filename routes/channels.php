<?php

use Illuminate\Support\Facades\Broadcast;
use App\Enums\UserRole;

// Public channel — admin & manager can listen (order placed, status changed)
Broadcast::channel('orders', function ($user) {
    return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::TECHNICAL_MANAGER]);
});

// Private technician channel — only the matching technician
Broadcast::channel('technician.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === UserRole::TECHNICIAN;
});

// Private client channel — only the matching client
Broadcast::channel('client.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->role === UserRole::CLIENT;
});

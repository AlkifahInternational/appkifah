<?php

namespace App\Http\Middleware;

use App\Models\Blacklist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklist
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check IP blacklist
        if (Blacklist::isBlocked('ip', $request->ip())) {
            abort(403, 'Your IP has been blocked.');
        }

        // Check phone blacklist for authenticated users
        if ($request->user() && $request->user()->phone) {
            if (Blacklist::isBlocked('phone', $request->user()->phone)) {
                auth()->logout();
                abort(403, 'Your account has been suspended.');
            }
        }

        // Check user blacklist flag
        if ($request->user() && $request->user()->is_blacklisted) {
            auth()->logout();
            abort(403, 'Your account has been suspended.');
        }

        return $next($request);
    }
}

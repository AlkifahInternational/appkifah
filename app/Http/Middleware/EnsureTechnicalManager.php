<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTechnicalManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, [
            UserRole::SUPER_ADMIN,
            UserRole::TECHNICAL_MANAGER,
        ])) {
            abort(403, 'Access denied. Technical Manager privileges required.');
        }

        return $next($request);
    }
}

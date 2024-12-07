<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || $request->user()->userable instanceOf $role) {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}

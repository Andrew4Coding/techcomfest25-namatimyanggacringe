<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is a teacher
        if (!request()->user()->userable instanceof \App\Models\Teacher) {
            // Return redirect with error
            return redirect()->route('courses')->withErrors('You are not authorized to access this page');
        }

        return $next($request);
    }
}

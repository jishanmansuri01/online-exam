<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is logged in AND has the correct role
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // If they don't have the role, kick them back to login or home
        return redirect('/login')->with('error', 'You do not have access to this section.');
    }
}
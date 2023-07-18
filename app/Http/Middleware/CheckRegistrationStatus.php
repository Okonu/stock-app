<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRegistrationStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->level === 'admin' || $user->registration_status) {
            return $next($request);
        }

        return redirect()->route('auth.pending')->with('message', 'Your registration is pending approval or has been rejected.');
    }
}


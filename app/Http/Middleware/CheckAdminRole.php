<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->level === 'admin') {
            return $next($request);
        }

        // Redirect or return response for non-admin users
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
    }
}

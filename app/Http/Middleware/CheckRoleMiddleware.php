<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

class CheckRoleMiddleware
{
    public function handle(Request $request, \Closure $next, ...$roles)
    {
        // Get the authenticated user
        $user = $request->user();

        // Check if the user has any of the specified roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Redirect or abort the request if the user does not have any of the specified roles
        abort(403, 'Unauthorized');
    }
}

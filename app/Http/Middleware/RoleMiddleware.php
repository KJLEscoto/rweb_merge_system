<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }
        // Check if user has the correct role

        if (Auth::user()->role->position != $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

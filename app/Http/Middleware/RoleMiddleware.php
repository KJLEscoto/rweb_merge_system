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
    public function handle(Request $request, Closure $next, $role, $type = null)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect('/login');
        }

        if ($type != null) {
            $admin_roles = ['admin', 'operations_supervisor', 'assistant_supervisor', 'top_management'];
            if (!in_array(Auth::user()->roles->position, $admin_roles)) {
                abort(403, 'Unauthorized action.');
            }
        }
        // Check if user has the correct role

        if (Auth::user()->roles->position != $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}

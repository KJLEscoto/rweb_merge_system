<?php

namespace App\Http\Middleware;

use App\Models\Privilege;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Exists;

class RolesChannel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $page, $privilege)
    {
        $privilege = intval(trim($privilege));
        $page = intval(trim($page));

        //get the current user role channnel
        if (Auth::user()->role_channels->where('privilege_id', $privilege)->where('page_id', $page)->first()) {
            return $next($request);
        } else {
            @dd('UnAuthorized');
        }
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Controllers\DashboardController;
use App\Models\Signature;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectOnMissingSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,)
    {

        if (Auth::check()) {

            $dashboard = Route::is('admin.smm.dashboard');

            if (!$dashboard) {

                $user = User::find(Auth::user()->id);
                $route = redirect()->route('admin.smm.dashboard');

                $NormalizeSignature = Signature::mySignature(Auth::user()->id);

                if ($user && !$NormalizeSignature) {
                    $user->update(['signature' => '']);
                    $user->save(); // While saving might not be necessary, it's included for consistency
                    return $route;
                }

                $staticSignature = Auth::user()->signature;
                if (!$staticSignature) {
                    return $route;
                }
            }
        }
        return $next($request);
    }
}

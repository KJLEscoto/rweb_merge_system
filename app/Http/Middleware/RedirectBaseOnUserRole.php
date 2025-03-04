<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBaseOnUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {

        
        if(Auth::check()){
            $user = Auth::user();
            if(Auth::user()->role === $role){
                if(Auth::user()->role==='user'){
                    $timezone = 'Asia/Manila';

                    if (!is_null($user->starting_date) 
                        && !(Carbon::parse($user->starting_date)->timezone($timezone)->startOfDay()->lessThanOrEqualTo(Carbon::now($timezone)->startOfDay()))) {
                        
                        Auth::logout();
                        return redirect()->route('show.login')->with('invalid', "You are forced to log out. This account will be open at " . 
                            Carbon::parse($user->starting_date)->timezone($timezone)->format('M j ,Y ') . 
                            ". Please contact admin for more information.");
                    }
                }
                return $next($request);
            }
            return redirect()->route('forbidden');
        }
        abort(401);
    }
}
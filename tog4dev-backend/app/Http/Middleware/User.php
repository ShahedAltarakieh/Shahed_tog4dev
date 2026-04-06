<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        if(Auth::user()->role == 1){
            abort(403, "You're not have permission to open this page");
        }
        if(Auth::user()->role == 2 || Auth::user()->role == 3){
            return $next($request);
        }
    }
}
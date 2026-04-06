<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the 'type' parameter from the route
        $type = $request->route('type');

        // Share it globally with all views
        View::share('type', $type);

        // Optionally, bind it to the request instance for global access
        $request->merge(['type' => $type]);

        return $next($request);
    }
}

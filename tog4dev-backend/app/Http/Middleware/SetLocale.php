<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
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
        // Get the preferred locale from the Accept-Language header
        $locale = $request->header('Accept-Language', config('app.locale'));

        // Validate the locale (ensure it's one of the supported languages)
        if (in_array($locale, config('app.supported_locales', ['en', 'ar']))) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}

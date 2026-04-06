<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AllowEfawateercomIp
{
    /**
     * Allow the request only when the client IP is listed in config('efawateercom.allow_ips').
     * If the list is empty, all IPs are allowed (configure EFAWATEERCOM_ALLOW_IPS in production).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowed = config('efawateercom.allow_ips', []);

        if ($allowed === [] || $allowed === null) {
            return $next($request);
        }

        $ip = $request->ip();

        if (! in_array($ip, $allowed, true)) {
            Log::warning('Efawateercom middleware rejected IP', ['ip' => $ip]);

            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

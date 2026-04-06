<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the 'api-key' header is present
        $apiKey = $request->header('api-key');

        // You can either hardcode a key, use environment variables, or check a database
        $validApiKey = env('API_KEY'); // Assuming you store your API key in .env file

        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Invalid API Key'
            ], Response::HTTP_UNAUTHORIZED); // Return 401 Unauthorized
        }

        // If the API key is valid, allow the request to proceed
        return $next($request);
    }
}

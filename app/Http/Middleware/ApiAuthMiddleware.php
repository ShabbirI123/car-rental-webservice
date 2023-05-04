<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();

        if ($clientIp !== '127.0.0.1' || $clientIp !== '::1') {
            $apiKey = Config::get('API_KEY');

            $requestApiKey = $request->header('API-Key');

            if ($requestApiKey && $requestApiKey === $apiKey) {
                return $next($request);
            }

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

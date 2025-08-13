<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = session('jwt_token');
        if (!$token) {
            return redirect()->route('login');
        }

        try {
            JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['message' => 'Session expired, please log in again.']);
        }

        return $next($request);
    }
}

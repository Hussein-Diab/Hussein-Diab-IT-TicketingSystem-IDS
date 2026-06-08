<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtCookieAuth
{
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->cookie('jwt_token');

        if ($token) {
            try {
                JWTAuth::setToken($token);
                if ($user = JWTAuth::authenticate()) {
                    Auth::setUser($user);
                    return $next($request);
                }
            } catch (\Exception $e) {
            }
        }
        return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
    }
}

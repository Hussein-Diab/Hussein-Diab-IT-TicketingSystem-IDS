<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class JwtCookieAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('jwt_token');

        if ($token) {
            try {
                JWTAuth::setToken($token);
                $payload = JWTAuth::getPayload();
                $userId  = $payload->get('sub');

                // Fetch full User model so our custom methods work
                $user = User::find($userId);

                if ($user) {
                    Auth::setUser($user);
                    return $next($request);
                }
            } catch (\Exception $e) {
                //
            }
        }

        return redirect()->route('login')
            ->withErrors(['email' => 'Please login first.']);
    }
}
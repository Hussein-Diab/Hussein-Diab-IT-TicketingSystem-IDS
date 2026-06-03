<?php 

namespace App\Http\Controllers; 

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller 
{ 
    public function showLogin() 
    { 
        return view('auth.login'); 
    } 

    public function login(Request $request) 
    { 
        $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required|min:6', 
        ]); 

        // 1. Manual query matching your custom casing fields ('Email')
        $user = User::where('Email', $request->email)->first(); 

        if (!$user || !Hash::check($request->password, $user->Password)) { 
            return back()->withErrors([ 
                'email' => 'Invalid email or password.' 
            ]); 
        } 

        // 2. Generate the JWT string explicitly from the user object
        $token = JWTAuth::fromUser($user);

        // 3. Attach token to a secure HTTP-Only cookie and redirect to dashboard
        return redirect('/dashboard')
            ->cookie('jwt_token', $token, 60, null, null, false, true); 
            // Params: name, value, minutes, path, domain, secure, httpOnly
    } 

    public function logout() 
    { 
        // 4. Invalidate the JWT token string itself
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
            // Token already missing or expired, skip
        }

        // 5. Delete the browser cookie during redirect
        return redirect('/login')->withoutCookie('jwt_token'); 
    } 
}

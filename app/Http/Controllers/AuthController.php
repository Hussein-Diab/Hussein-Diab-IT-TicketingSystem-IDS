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

        $user = User::where('Email', $request->email)->first(); 

        if (!$user || !Hash::check($request->password, $user->Password)) { 
            return back()->withErrors([ 
                'email' => 'Invalid email or password.' 
            ]); 
        } 

        $token = JWTAuth::fromUser($user);


        return redirect('/dashboard')
            ->cookie('jwt_token', $token, 60, null, null, false, true); 

    } 

    public function logout() 
    { 
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {

        }
        return redirect('/login')->withoutCookie('jwt_token'); 
    } 
}

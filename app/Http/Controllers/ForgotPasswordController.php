<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.reset'); 
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        $user = User::where('Email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email Not Found.'
            ]);
        }
        $token = Str::random(64);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email], 
            [
                'token' => $token, 
                'created_at' => now() 
            ]
        );

        Mail::raw("Click the link to reset your password: " . url('/reset-password/' . $token), function($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset Request');
        });

        return back()->with('status', 'We have emailed you the password reset link! Please check your inbox');
    }

    public function showResetForm($token){
        //
    }
    public function resetPassword(Request $request){
        
    }
}


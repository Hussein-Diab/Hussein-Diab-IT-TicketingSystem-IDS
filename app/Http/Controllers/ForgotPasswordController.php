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
        return view('auth.forgot-password');
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

        Mail::raw("Click the link to reset your password: " . url('/reset-password/' . $token), function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset Request');
        });

        return back()->with('status', 'We have emailed you the password reset link! Please check your inbox');
    }

    public function showResetForm($token)
    {
        $tokenData = DB::table('password_resets')
            ->where('Token', $token)
            ->first();
        if (!$tokenData) {
            return redirect('/forgot-password')
                ->withErrors(['email' => 'Invalid reset link.']);
        }
        return view('auth.reset-password', compact('token'));
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|min:8|confirmed',
        ]);
        $tokenData = DB::table('password_resets')
            ->where('Token', $request->token)
            ->first();
        if (!$tokenData) {
            return back()->withErrors([
                'email' => 'Invalid or expired reset link.'
            ]);
        }
        if (now()->diffInMinutes($tokenData->created_at) > 60) {
            DB::table('password_resets')
                ->where('Token', $request->token)
                ->delete();
            return back()->withErrors([
                'email' => 'Reset link has expired. Please request a new one.'
            ]);
        }
        $user = User::where('Email', $tokenData->Email)->first();
        $user->update([
            'Password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);
        DB::table('password_resets')
            ->where('Token', $request->token)
            ->delete();

        return redirect('/login')
            ->with('status', 'Password reset successfully! Please login.');
    }
}

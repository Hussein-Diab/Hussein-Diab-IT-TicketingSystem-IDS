@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-card">

        <div class="logo-circle">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>

        <h2 class="login-title">RESET PASSWORD</h2>

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/reset-password">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <input type="password"
                       name="password"
                       placeholder="New password"
                       required>
            </div>
            <div class="input-group">
                <input type="password"
                       name="password_confirmation"
                       placeholder="Confirm new password"
                       required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn-login">
                    RESET PASSWORD
                </button>
            </div>
            <div class="btn-group">
                <a href="/login" class="btn-forgot">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
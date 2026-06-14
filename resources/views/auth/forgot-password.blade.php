@extends('layouts.app')

@section('content')
<div class="login-wrapper">
    <div class="login-card">

        <div class="logo-circle">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>

        <h2 class="login-title">FORGOT PASSWORD</h2>

        @if(session('status'))
        <div class="alert-success">
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
        <div class="error-msg">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="/forgot-password">
            @csrf
            <div class="input-group">
                <input type="email"
                       name="email"
                       placeholder="Enter your email"
                       value="{{ old('email') }}"
                       required>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn-login">
                    SEND RESET LINK
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
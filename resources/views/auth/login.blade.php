@extends('layouts.app')

@section('content')

<div class="login-wrapper">
    <div class="login-card">
        <div class="logo-circle">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <h3  class="login-title">LOG IN INTO THE SYSTEM</h3>
        @if($errors->any())
            <div class="error-msg">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="/login">
            @csrf

            <div class="input-group">
                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                    required>
            </div>

            <div class="input-group">
                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-login">
                    LOG IN
                </button>
            </div>

            <div class="btn-group">
                <a href="/forgot-password" class="btn-forgot">
                    Forgot Password
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
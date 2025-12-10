@extends('layouts.app')

@section('content')
    <section class="auth-section">
        <h1 class="page-title">Enter your One-Time Password (OTP)</h1>
        <p class="page-subtitle">
            We have sent a 6-digit code to your email. Enter it below to access your dashboard.
        </p>

        <form class="card auth-card" method="POST" action="{{ route('auth.otp.verify') }}" aria-describedby="otp-help">
            @csrf

            <p id="otp-help" class="sr-only">
                Enter the 6-digit code from your email. If you did not receive it, go back and request a new code.
            </p>

            <div class="form-group">
                <label for="email">Email address *</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email', $email) }}"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="otp_code">6-digit OTP code *</label>
                <input
                    id="otp_code"
                    name="otp_code"
                    type="text"
                    pattern="[0-9]*"
                    inputmode="numeric"
                    maxlength="6"
                    value="{{ old('otp_code') }}"
                    required
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Verify and continue
                </button>

                <p class="form-footer-text">
                    Didn’t receive an OTP?
                    <a href="{{ route('login') }}">Request a new code</a>.
                </p>
            </div>
        </form>
    </section>
@endsection



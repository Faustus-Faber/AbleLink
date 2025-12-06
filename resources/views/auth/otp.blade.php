@extends('layouts.auth')

@section('title', 'Verify code · Ablelink')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">Verify your code</h1>
        <p class="text-slate-500 text-lg leading-relaxed">Enter the six-digit OTP we sent to <strong>{{ $email ?: 'your email' }}</strong>. Codes expire after ten minutes.</p>
    </div>

    <form method="POST" action="{{ route('otp.verify') }}" aria-label="OTP verification form" class="space-y-6">
        @csrf
        <input type="hidden" name="context" value="{{ $context }}">

        @if(session('joke_otp'))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded shadow-sm" role="alert">
                <p class="font-bold text-lg">🤫 Psst! Don't tell anyone...</p>
                <p>The code is: <span class="font-mono bg-white px-2 py-1 rounded border border-yellow-200 text-black text-xl tracking-widest select-all cursor-pointer">{{ session('joke_otp') }}</span></p>
            </div>
        @endif

        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email</label>
            <input id="email" name="email" type="email" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg"
                   value="{{ old('email', $email) }}" required readonly>
        </div>

        <div>
            <label for="code" class="block text-sm font-bold text-slate-700 mb-2 ml-1">One-Time Password</label>
            <input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" minlength="6" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg tracking-widest text-center"
                   placeholder="000000"
                   value="{{ old('code') }}" required autofocus>
        </div>

        <button type="submit" 
                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:brightness-110 transition-all duration-300 transform">
            Verify and Continue
        </button>
    </form>

    <div class="mt-8 pt-6 border-t border-slate-100 text-center">
        <p class="text-slate-500 text-sm mb-4">Didn't receive the code?</p>
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="context" value="{{ $context }}">
            <button type="submit" class="text-blue-600 font-bold hover:text-blue-700 hover:underline transition-all">
                Resend Code
            </button>
        </form>
    </div>
@endsection

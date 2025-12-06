@extends('layouts.auth')

@section('title', 'Community login · Ablelink')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">Community login</h1>
        <p class="text-slate-500 text-lg leading-relaxed">Enter the email you registered with. We will send a six-digit OTP code for passwordless access.</p>
    </div>

    <form method="POST" action="{{ route('login.send-otp') }}" aria-label="Send OTP form" class="space-y-6">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
            <input id="email" name="email" type="email" inputmode="email" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="you@example.com"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" 
                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:brightness-110 transition-all duration-300 transform">
            Send Code
        </button>
    </form>
@endsection

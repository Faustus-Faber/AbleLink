@extends('layouts.auth')

@section('title', 'Admin login · Ablelink')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">Admin Portal</h1>
        <p class="text-slate-500 text-lg leading-relaxed">Only AbleLink administrators can access this area.</p>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}" aria-label="Admin login form" class="space-y-6">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
            <input id="email" name="email" type="email" inputmode="email" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="admin@ablelink.com"
                   value="{{ old('email') }}" required autofocus>
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Password</label>
            <input id="password" name="password" type="password" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="••••••••"
                   required>
        </div>

        <div class="flex items-center ml-1">
            <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))
                   class="w-5 h-5 rounded border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all cursor-pointer">
            <label for="remember" class="ml-3 block text-base font-medium text-slate-600 cursor-pointer select-none">
                Keep me signed in
            </label>
        </div>

        <button type="submit" 
                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-slate-700 to-slate-900 text-white font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:brightness-110 transition-all duration-300 transform">
            Enter Admin Dashboard
        </button>
    </form>
@endsection

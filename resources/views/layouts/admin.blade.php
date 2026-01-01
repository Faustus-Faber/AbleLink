@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- SIDEBAR -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sticky top-24">
                <div class="text-center mb-6">
                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                        <div class="w-24 h-24 mx-auto rounded-full mb-4 p-1 bg-gradient-to-br from-slate-700 to-slate-900 shadow-inner">
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover border-2 border-white">
                        </div>
                    @else
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-slate-700 to-slate-900 shadow-inner mb-4 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-slate-800">{{ Auth::user()->name ?? 'Admin' }}</h2>
                    <p class="text-slate-500 text-sm">{{ Auth::user()->email }}</p>
                    <div class="mt-2 inline-block px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wide">
                        Administrator
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center w-full px-4 py-3 rounded-xl font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50 font-medium' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.list') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.users*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Users
                    </a>
                    <a href="{{ route('admin.volunteers.list') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.volunteers*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Volunteers
                    </a>
                     <a href="{{ route('admin.employers.list') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.employers*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Employers
                    </a>
                    <a href="{{ route('admin.caregivers.list') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.caregivers*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Caregivers
                    </a>
                    <a href="{{ route('admin.jobs.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.jobs*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Jobs
                    </a>
                    <a href="{{ route('admin.courses.index') }}" 
                       class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.courses*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Learning Hub
                    </a>
                    <a href="{{ route('admin.community.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.community*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Community
                    </a>
                    <a href="{{ route('admin.moderation.index') }}" 
                       class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.moderation*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Moderation
                    </a>

                    <!-- F20 - Akida Lisi -->
                    <a href="{{ route('admin.aid.index') }}" 
                       class="flex items-center w-full px-4 py-3 rounded-xl font-medium transition-all {{ request()->routeIs('admin.aid*') ? 'bg-slate-50 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Aid Programs
                    </a>
                   
                </nav>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-bold hover:from-red-700 hover:to-red-800 transition-all flex justify-center items-center shadow-md">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="lg:col-span-3">
             @yield('admin-content')
        </div>
    </div>
</div>
@endsection

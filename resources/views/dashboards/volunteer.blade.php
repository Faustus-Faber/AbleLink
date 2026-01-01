@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sticky top-24">
                <div class="text-center mb-6">
                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                        <div class="w-24 h-24 mx-auto rounded-full mb-4 p-1 bg-gradient-to-br from-cyan-400 to-blue-500 shadow-inner">
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover border-2 border-white">
                        </div>
                    @else
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 shadow-inner mb-4 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-slate-800">{{ Auth::user()->name ?? 'Volunteer' }}</h2>
                    <p class="text-slate-500 text-sm">{{ Auth::user()->email }}</p>
                    <div class="mt-2 inline-block px-3 py-1 rounded-full bg-cyan-50 text-cyan-600 text-xs font-bold uppercase tracking-wide">
                        Volunteer
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-xl bg-cyan-50 text-cyan-700 font-bold transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('volunteer.requests.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Help Requests
                    </a>
                    <a href="{{ route('volunteer.assistance.active') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Active Assistance
                    </a>
                    <a href="{{ route('volunteer.assistance.history') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Task History
                    </a>
                    <a href="{{ route('volunteer.profile.show') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Skill Profile
                    </a>
                </nav>

                <div class="mt-8 pt-6 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-red-50 to-red-100 text-red-600 font-bold hover:from-red-100 hover:to-red-200 transition-all flex justify-center items-center">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="lg:col-span-3">
             <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900">Personal Progress</h3>
                    <p class="text-slate-500 font-medium uppercase tracking-wide text-xs">VOLUNTEER DASHBOARD OVERVIEW</p>
                </div>
                <a href="mailto:support@ablelink.com" class="px-6 py-3 rounded-full bg-purple-600 hover:bg-purple-700 text-white font-bold shadow-lg hover:shadow-xl transition-all inline-block">
                    Contact Support
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-cyan-50 border-4 border-cyan-100 flex items-center justify-center text-cyan-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Available Requests</h3>
                    <p class="text-3xl font-extrabold text-cyan-600">{{ $pendingRequests ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-green-50 border-4 border-green-100 flex items-center justify-center text-green-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Active Tasks</h3>
                    <p class="text-3xl font-extrabold text-green-600">{{ $activeMatches ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-blue-50 border-4 border-blue-100 flex items-center justify-center text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Completed Tasks</h3>
                    <p class="text-3xl font-extrabold text-blue-600">{{ $completedMatches ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Level Progress</h5>
                        <div class="w-full bg-slate-100 rounded-full h-8 overflow-hidden relative group">
                             <div class="bg-gradient-to-r from-green-400 to-cyan-400 h-8 rounded-full transition-all duration-1000" style="width: {{ $levelProgress }}%"></div>
                             <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-slate-700">{{ $levelProgress }}% to next Level</span>
                        </div>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Complete 10 tasks to level up!</p>
                    </div>
                </div>
                <div class="lg:col-span-1">
                     <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Weekly Goal</h5>
                        <div class="w-24 h-24 mx-auto rounded-full bg-white border-8 border-cyan-100 flex items-center justify-center relative">
                             <span class="text-2xl font-bold text-cyan-600">{{ $weeklyGoalPercent }}%</span>
                        </div>
                     </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

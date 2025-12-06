@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- SIDEBAR -->
        <div class="lg:col-span-1">
            @if(session('success') && !session('sos_success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-green-800 font-bold">
                    {{ session('success') }}
                </div>
            @endif
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
                    <h2 class="text-xl font-bold text-slate-800">{{ Auth::user()->name ?? 'User' }}</h2>
                    <p class="text-slate-500 text-sm">{{ Auth::user()->email }}</p>
                    <div class="mt-2 inline-block px-3 py-1 rounded-full bg-cyan-50 text-cyan-600 text-xs font-bold uppercase tracking-wide">
                        User
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-xl bg-cyan-50 text-cyan-700 font-bold transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('profile.show') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile
                    </a>
                    
                    {{-- F17 - Rifat Jahan Roza --}}
                    <a href="{{ route('user.appointments.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Appointments
                    </a>
                    
                    {{-- F9 - Evan Yuvraj Munshi --}}
                    <a href="{{ route('jobs.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Jobs
                    </a>
                    
                    {{-- F11 - Akida Lisi --}}
                    <a href="{{ route('courses.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Learning Hub
                    </a>

                    <a href="{{ route('community.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Community
                    </a>
                    <a href="#sos-section" onclick="document.getElementById('sos-dashboard-button').scrollIntoView({behavior: 'smooth'})" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Safety
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

        <!-- MAIN CONTENT -->
        <div class="lg:col-span-3">
             <!-- HEADER -->
             <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900">User Progress</h3>
                    <p class="text-slate-500 font-medium uppercase tracking-wide text-xs">USER DASHBOARD OVERVIEW</p>
                </div>
                <!-- Updated Contact Support Button -->
                <a href="mailto:support@ablelink.com" class="px-6 py-3 rounded-full bg-purple-600 hover:bg-purple-700 text-white font-bold shadow-lg hover:shadow-xl transition-all inline-block !border-b-0">
                    Contact Support
                </a>
            </div>
            
            <!-- F17 - My Doctor Appointments Widget - Rifat Jahan Roza -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">My Doctor Appointments</h4>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wide">
                                {{ isset($upcomingAppointments) ? $upcomingAppointments->count() : 0 }} Upcoming
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('user.appointments.index') }}" class="text-sm font-bold text-cyan-600 hover:text-cyan-800 hover:underline">
                        View All
                    </a>
                </div>

                @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                    <div class="space-y-3 relative z-10">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex items-start sm:items-center justify-between gap-4 group hover:bg-white hover:shadow-sm hover:border-slate-200 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col items-center bg-white border border-slate-200 rounded-lg p-2 min-w-[60px]">
                                        <span class="text-xs font-bold text-slate-400 uppercase">{{ $appointment->appointment_date->format('M') }}</span>
                                        <span class="text-xl font-extrabold text-slate-800">{{ $appointment->appointment_date->format('d') }}</span>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-slate-800">{{ $appointment->doctor_name }}</h5>
                                        <p class="text-sm text-slate-500">{{ $appointment->clinic_name }}</p>
                                        <p class="text-xs text-slate-400 mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $appointment->appointment_date->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right hidden sm:block">
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-green-50 text-green-700 border border-green-100">
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <p class="text-slate-500 text-sm">No upcoming appointments scheduled.</p>
                        @if(isset($appointments) && $appointments->where('status', 'scheduled')->count() > 0)
                             <a href="{{ route('user.appointments.index') }}" class="text-xs font-bold text-cyan-600 mt-2 inline-block">View Scheduled</a>
                        @endif
                    </div>
                @endif
                
                <!-- Decor -->
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-cyan-50 rounded-full opacity-50 blur-2xl z-0"></div>
            </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                 <!-- Profile Completion -->
                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                     <div class="w-16 h-16 mx-auto rounded-full bg-cyan-50 border-4 border-cyan-100 flex items-center justify-center text-cyan-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                     </div>
                     <h3 class="font-bold text-slate-800 text-lg mb-1">Profile Completion</h3>
                     <p class="text-3xl font-extrabold text-cyan-600">{{ $completion }}%</p>
                 </div>

                 <!-- Job Applications -->
                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                     <div class="w-16 h-16 mx-auto rounded-full bg-orange-50 border-4 border-orange-100 flex items-center justify-center text-orange-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                     </div>
                     <h3 class="font-bold text-slate-800 text-lg mb-1">Job Applications</h3>
                     <p class="text-3xl font-extrabold text-orange-600">{{ $jobApplicationsCount }}</p>
                 </div>

                 <!-- Learning Progress -->
                 <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                     <div class="w-16 h-16 mx-auto rounded-full bg-indigo-50 border-4 border-indigo-100 flex items-center justify-center text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                     </div>
                     <h3 class="font-bold text-slate-800 text-lg mb-1">Learning Progress</h3>
                     <p class="text-3xl font-extrabold text-indigo-600">{{ $learningProgress }}%</p>
                 </div>
            </div>

            <!-- PROGRESS & SAFETY -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Overall Completion</h5>
                        <div class="w-full bg-slate-100 rounded-full h-8 overflow-hidden">
                             <div class="bg-gradient-to-r from-green-400 to-cyan-400 h-8 rounded-full" style="width: {{ $completion }}%"></div>
                        </div>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Keep updating your profile to access more features!</p>
                    </div>
                </div>
                <div class="lg:col-span-1">
                     <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Safety Status</h5>
                        <div class="w-16 h-16 mx-auto rounded-full bg-green-50 border-4 border-green-100 flex items-center justify-center text-green-600">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-green-600 font-bold mt-2">Safe & Secure</p>
                     </div>
                </div>
            </div>

            <!-- FEATURE CARDS GRID -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- F3 - Evan Yuvraj Munshi --}}
                <a href="{{ route('profile.edit') }}" class="block">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer h-full">
                        <div class="flex items-center mb-3">
                             <div class="p-3 rounded-lg bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             </div>
                        </div>
                        <h6 class="font-bold text-slate-800 text-lg">Profile Settings</h6>
                        <p class="text-sm text-slate-500 mt-1">Edit personal & accessibility settings</p>
                    </div>
                </a>

                {{-- F9 - Evan Yuvraj Munshi --}}
                <a href="{{ route('candidate.applications') }}" class="block">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer h-full">
                        <div class="flex items-center mb-3">
                             <div class="p-3 rounded-lg bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                             </div>
                        </div>
                        <h6 class="font-bold text-slate-800 text-lg">Job Applications</h6>
                        <p class="text-sm text-slate-500 mt-1">Search, save & track jobs</p>
                    </div>
                </a>

                {{-- F11 - Akida Lisi --}}
                <a href="{{ route('courses.index') }}" class="block">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer h-full">
                        <div class="flex items-center mb-3">
                             <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                             </div>
                        </div>
                        <h6 class="font-bold text-slate-800 text-lg">Learning Hub</h6>
                        <p class="text-sm text-slate-500 mt-1">Courses, certificates & skill tests</p>
                    </div>
                </a>

                {{-- F19 - Evan Munshi --}}
                <a href="{{ route('health.dashboard') }}" class="block">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer h-full">
                        <div class="flex items-center mb-3">
                             <div class="p-3 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                             </div>
                        </div>
                        <h6 class="font-bold text-slate-800 text-lg">Health & Meds</h6>
                        <p class="text-sm text-slate-500 mt-1">Track medications & goals</p>
                    </div>
                </a>

                {{-- Community (Was Bottom) --}}
                <a href="{{ route('community.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer block h-full">
                    <div class="flex items-center mb-3">
                         <div class="p-3 rounded-lg bg-pink-50 text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                         </div>
                    </div>
                    <h6 class="font-bold text-slate-800 text-lg">Community & Support</h6>
                    <p class="text-sm text-slate-500 mt-1">Groups, discussions & messages</p>
                </a>

                <!-- Emergency SOS (Was Bottom) -->
                <form id="sos-dashboard-form" action="{{ route('sos.store') }}" method="POST" class="h-full">
                    @csrf
                    <input type="hidden" name="latitude" id="sos-dashboard-latitude" value="">
                    <input type="hidden" name="longitude" id="sos-dashboard-longitude" value="">
                    <input type="hidden" name="accuracy_m" id="sos-dashboard-accuracy" value="">
                    <input type="hidden" name="address" id="sos-dashboard-address" value="">
                    <input type="hidden" name="notes" id="sos-dashboard-notes" value="SOS triggered from dashboard.">

                    <button type="submit" id="sos-dashboard-button"
                            class="w-full text-left bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group h-full">
                        <div class="flex items-center mb-3">
                            <div class="p-3 rounded-lg bg-red-50 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </div>
                        <h6 class="font-bold text-slate-800 text-lg">Emergency</h6>
                        <p class="text-sm text-slate-500 mt-1">Tap to send SOS with your location</p>
                        <p id="sos-dashboard-status" class="text-xs text-slate-400 mt-2"></p>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- SOS Success Modal -->
@if(session('sos_success'))
<div id="sos-success-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 text-center transform transition-all scale-100">
        <div class="w-20 h-20 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-900 mb-2">SOS Alert Sent!</h3>
        <p class="text-slate-600 mb-8 max-w-sm mx-auto">Admins and your active caregivers (if any) have been notified with your current location.</p>
        <button onclick="document.getElementById('sos-success-modal').remove()" class="w-full py-3.5 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl">
            Close
        </button>
    </div>
</div>
@endif

<script>
(() => {
  const form = document.getElementById('sos-dashboard-form');
  const btn = document.getElementById('sos-dashboard-button');
  const status = document.getElementById('sos-dashboard-status');
  if (!form || !btn) return;

  let hasSubmitted = false;
  const setStatus = (text) => { if (status) status.textContent = text || ''; };

  const submitWith = (coords) => {
    const lat = document.getElementById('sos-dashboard-latitude');
    const lng = document.getElementById('sos-dashboard-longitude');
    const acc = document.getElementById('sos-dashboard-accuracy');
    const address = document.getElementById('sos-dashboard-address');

    if (coords && typeof coords.latitude === 'number' && typeof coords.longitude === 'number') {
      lat.value = coords.latitude;
      lng.value = coords.longitude;
      acc.value = coords.accuracy ? Math.round(coords.accuracy) : '';
      address.value = '';
    }

    hasSubmitted = true;
    form.submit();
  };

  form.addEventListener('submit', (e) => {
    if (hasSubmitted) return;
    e.preventDefault();

    btn.disabled = true;
    btn.classList.add('opacity-70', 'cursor-not-allowed');
    setStatus('Getting your location…');

    if (!navigator.geolocation) {
      setStatus('Location not supported. Sending SOS without location…');
      submitWith(null);
      return;
    }

    navigator.geolocation.getCurrentPosition(
      (pos) => {
        setStatus('Location captured. Sending SOS…');
        submitWith(pos.coords);
      },
      (err) => {
        let msg = 'Location error.';
         switch(err.code) {
            case err.PERMISSION_DENIED: msg = 'User denied location permission.'; break;
            case err.POSITION_UNAVAILABLE: msg = 'Location information unavailable.'; break;
            case err.TIMEOUT: msg = 'Location request timed out.'; break;
            default: msg = 'Unknown location error.'; break;
        }
        setStatus(msg + ' Sending SOS without location…');
        submitWith(null);
      },
      { enableHighAccuracy: false, timeout: 15000, maximumAge: 0 }
    );
  });
})();
</script>
@endsection

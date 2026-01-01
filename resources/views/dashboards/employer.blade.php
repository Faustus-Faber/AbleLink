
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sticky top-24">
                <div class="text-center mb-6">
                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                        <div class="w-24 h-24 mx-auto rounded-full mb-4 p-1 bg-gradient-to-br from-blue-500 to-indigo-600 shadow-inner">
                            <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover border-2 border-white">
                        </div>
                    @else
                        <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 shadow-inner mb-4 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-slate-800">{{ Auth::user()->name ?? 'Employer' }}</h2>
                    <p class="text-slate-500 text-sm">{{ Auth::user()->email }}</p>
                    <div class="mt-2 inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wide">
                        Employer
                    </div>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center w-full px-4 py-3 rounded-xl bg-indigo-50 text-indigo-700 font-bold transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('employer.jobs.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Job Management
                    </a>
                    <a href="{{ route('employer.applications') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Applications
                    </a>
                    <a href="{{ route('employer.interviews.index') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                         <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                        Interviews
                    </a>
                    <a href="{{ route('employer.profile.show') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Company Profile
                    </a>
                    <a href="{{ route('employer.reports') }}" class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Reports
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
                    <h3 class="text-2xl font-extrabold text-slate-900">Employer Progress</h3>
                    <p class="text-slate-500 font-medium uppercase tracking-wide text-xs">EMPLOYER DASHBOARD OVERVIEW</p>
                </div>
                <a href="mailto:support@ablelink.com" class="px-6 py-3 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg hover:shadow-xl transition-all inline-block">
                    Contact Support
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-indigo-50 border-4 border-indigo-100 flex items-center justify-center text-indigo-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Total Jobs Posted</h3>
                    <p class="text-3xl font-extrabold text-indigo-600">{{ $totalJobs ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-green-50 border-4 border-green-100 flex items-center justify-center text-green-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Active Jobs</h3>
                    <p class="text-3xl font-extrabold text-green-600">{{ $activeJobs ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center hover:shadow-md transition-all">
                    <div class="w-16 h-16 mx-auto rounded-full bg-purple-50 border-4 border-purple-100 flex items-center justify-center text-purple-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-2">Total Applications</h3>
                    <p class="text-3xl font-extrabold text-purple-600">{{ $totalApplications ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Hiring Progress</h5>
                        <div class="w-full bg-slate-100 rounded-full h-8 overflow-hidden relative group">
                             <div class="bg-gradient-to-r from-blue-400 to-indigo-500 h-8 rounded-full transition-all duration-1000" style="width: {{ $hiringProgress }}%"></div>
                             <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-slate-700">{{ $hiringProgress }}% Processed</span>
                        </div>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Keep moving candidates forward!</p>
                    </div>
                </div>
                <div class="lg:col-span-1">
                     <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 text-center h-full">
                        <h5 class="text-lg font-bold text-slate-800 mb-4">Shortlisted Candidates</h5>
                        <div class="w-24 h-24 mx-auto rounded-full bg-white border-8 border-indigo-100 flex items-center justify-center relative">
                             <span class="text-4xl font-bold text-indigo-600">{{ $shortlistedCount ?? 0 }}</span>
                        </div>
                     </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                 <a href="{{ route('employer.jobs.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer">
                    <h6 class="font-bold text-slate-800 text-lg mb-1">Job Management</h6>
                    <p class="text-sm text-slate-500">Create, edit & close job posts</p>
                </a>
                <a href="{{ route('employer.applications') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer">
                    <h6 class="font-bold text-slate-800 text-lg mb-1">Application Handling</h6>
                    <p class="text-sm text-slate-500">View, shortlist & reject candidates</p>
                </a>
                <a href="{{ route('employer.interviews.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer">
                    <h6 class="font-bold text-slate-800 text-lg mb-1">Interview Panel</h6>
                    <p class="text-sm text-slate-500">Schedule & conduct interviews</p>
                </a>
            </div>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('employer.profile.show') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer">
                   <h6 class="font-bold text-slate-800 text-lg mb-1">Company Accessibility Profile</h6>
                   <p class="text-sm text-slate-500">Update accessibility & accommodation details</p>
               </a>
               <a href="{{ route('employer.reports') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer">
                   <h6 class="font-bold text-slate-800 text-lg mb-1">Reports & Communication</h6>
                   <p class="text-sm text-slate-500">View hiring statistics and insights</p>
               </a>
           </div>

        </div>
    </div>
</div>
@endsection

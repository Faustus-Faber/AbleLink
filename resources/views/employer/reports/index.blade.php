@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-12 pb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">
            Reports & Analytics
        </h1>
        <p class="text-slate-500 mt-2 font-medium">
             View hiring statistics and insights
        </p>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                <h3 class="font-bold text-slate-500 text-sm uppercase tracking-widest mb-4">Total Jobs</h3>
                <p class="text-6xl font-black text-indigo-600 group-hover:scale-110 transition-transform duration-300">{{ $totalJobs }}</p>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                <h3 class="font-bold text-slate-500 text-sm uppercase tracking-widest mb-4">Active Jobs</h3>
                <p class="text-6xl font-black text-emerald-500 group-hover:scale-110 transition-transform duration-300">{{ $activeJobs }}</p>
            </div>
            <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center justify-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                <h3 class="font-bold text-slate-500 text-sm uppercase tracking-widest mb-4">Total Applications</h3>
                <p class="text-6xl font-black text-purple-600 group-hover:scale-110 transition-transform duration-300">{{ $totalApplications }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10">
                <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    Applications by Status
                </h2>
                <div class="space-y-6">
                    @foreach(['pending', 'reviewing', 'shortlisted', 'interviewed', 'accepted', 'rejected'] as $status)
                        <div class="flex justify-between items-center group">
                            <span class="text-slate-600 font-bold capitalize group-hover:text-indigo-600 transition-colors">{{ $status }}</span>
                             <div class="flex-1 mx-4 border-b-2 border-dotted border-slate-100 relative top-1"></div>
                            <span class="font-black text-slate-900 text-lg bg-slate-50 px-3 py-1 rounded-lg min-w-[2rem] text-center group-hover:bg-indigo-50 group-hover:text-indigo-700 transition-colors">{{ $applicationsByStatus[$status] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10">
                <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    Upcoming Interviews
                </h2>
                @if ($upcomingInterviews->count() > 0)
                    <div class="space-y-4">
                        @foreach ($upcomingInterviews as $interview)
                            <div class="p-6 bg-slate-50 rounded-2xl hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100">
                                <div class="flex justify-between items-start mb-2">
                                     <h4 class="font-bold text-slate-900 text-lg">{{ $interview->title }}</h4>
                                     <span class="text-xs font-bold text-slate-400 bg-white px-2 py-1 rounded-md shadow-sm border border-slate-100">{{ $interview->scheduled_at->format('M d') }}</span>
                                </div>
                                <p class="text-sm text-slate-600 font-medium mb-1">
                                    <span class="text-slate-400">Candidate:</span> {{ $interview->applicant->name }}
                                </p>
                                <p class="text-xs text-indigo-600 font-bold uppercase tracking-wide">
                                    {{ $interview->scheduled_at->format('h:i A') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-64 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-slate-400 font-medium">No upcoming interviews</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10">
            <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                <span class="w-2 h-8 bg-purple-500 rounded-full"></span>
                Recent Applications
            </h2>
            @if ($recentApplications->count() > 0)
                <div class="space-y-4">
                    @foreach ($recentApplications as $application)
                        <div class="flex flex-col md:flex-row justify-between items-center p-6 bg-slate-50 rounded-2xl hover:bg-white hover:shadow-md transition-all border border-transparent hover:border-slate-100 gap-4">
                            <div class="flex-1 w-full md:w-auto">
                                <h4 class="font-bold text-slate-900 text-lg mb-1">{{ $application->applicant->name }}</h4>
                                <p class="text-sm text-slate-600 font-medium mb-2">{{ $application->job->title }}</p>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Applied {{ $application->applied_at->diffForHumans() }}</p>
                            </div>
                             @php
                                $statusClasses = match($application->status) {
                                    'accepted' => 'bg-emerald-100 text-emerald-700',
                                    'shortlisted' => 'bg-indigo-100 text-indigo-700',
                                    'reviewing' => 'bg-blue-100 text-blue-700',
                                    'rejected' => 'bg-rose-100 text-rose-700',
                                    'interviewed' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-amber-100 text-amber-700'
                                };
                            @endphp
                            <span class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wide {{ $statusClasses }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                 <div class="flex flex-col items-center justify-center h-32 text-center">
                    <p class="text-slate-400 font-medium">No recent applications</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

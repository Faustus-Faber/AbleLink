@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-12 pb-8">
        <a href="{{ route('employer.jobs.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-8">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Jobs
        </a>

        <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight">
                        {{ $job->title }}
                    </h1>
                     @php
                        $statusClasses = match($job->status) {
                            'active' => 'bg-emerald-100 text-emerald-700',
                            'draft' => 'bg-amber-100 text-amber-700',
                            'closed' => 'bg-slate-100 text-slate-600',
                            'filled' => 'bg-blue-100 text-blue-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusClasses }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
                <p class="text-slate-500 font-medium text-lg">
                    Posted {{ $job->created_at->diffForHumans() }}
                </p>
            </div>
            
            <a href="{{ route('employer.jobs.edit', $job) }}" 
               class="inline-flex items-center px-8 py-3 rounded-full bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 hover:shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                Edit Job
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Job Details (Left Column) --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-slate-100">
                    <h2 class="text-2xl font-black text-slate-900 mb-8 border-b border-slate-100 pb-4">Job Details</h2>
                    
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Description</h3>
                            <p class="text-slate-600 leading-relaxed text-lg font-medium">
                                {{ $job->description }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Location</h3>
                                <p class="text-lg font-bold text-slate-900">{{ $job->location ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Job Type</h3>
                                <p class="text-lg font-bold text-slate-900">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</p>
                            </div>
                            @if ($job->salary_min && $job->salary_max)
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Salary Range</h3>
                                <p class="text-lg font-bold text-slate-900">{{ $job->salary_currency }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</p>
                            </div>
                            @endif
                            @if ($job->application_deadline)
                            <div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Application Deadline</h3>
                                <p class="text-lg font-bold text-slate-900">{{ $job->application_deadline->format('M d, Y') }}</p>
                            </div>
                            @endif
                        </div>

                        @if ($job->accessibility_features)
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Accessibility Features</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($job->accessibility_features as $feature)
                                    <span class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl text-sm font-bold border border-indigo-100">
                                        {{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if ($job->accessibility_accommodations)
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Accessibility Accommodations</h3>
                            <p class="text-slate-600 leading-relaxed font-medium bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                {{ $job->accessibility_accommodations }}
                            </p>
                        </div>
                        @endif
                         @if ($job->additional_requirements)
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Additional Requirements</h3>
                            <p class="text-slate-600 leading-relaxed font-medium">
                                {{ $job->additional_requirements }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                 <!-- Applications List -->
                <div class="mt-8">
                    <h2 class="text-2xl font-black text-slate-900 mb-6">Applications ({{ $applications->total() }})</h2>

                    @if ($applications->count() > 0)
                        <div class="space-y-4">
                            @foreach ($applications as $application)
                                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all">
                                    <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-4 mb-2">
                                                <h3 class="text-xl font-black text-slate-900">{{ $application->applicant->name }}</h3>
                                                
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
                                                 <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusClasses }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </div>
                                            <p class="text-slate-500 text-sm mb-4 font-bold">{{ $application->applicant->email }}</p>

                                            @if ($application->applicant->profile && $application->applicant->profile->disability_type)
                                                <p class="text-slate-600 text-sm mb-3">
                                                    <span class="font-bold text-slate-400 uppercase text-xs tracking-wider">Disability Type:</span> {{ $application->applicant->profile->disability_type }}
                                                </p>
                                            @endif

                                            @if ($application->cover_letter)
                                                 <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-6">
                                                    <p class="text-slate-600 leading-relaxed text-sm font-medium line-clamp-3">
                                                        {{ Str::limit($application->cover_letter, 150) }}
                                                    </p>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center gap-4">
                                                @if ($application->resume_path)
                                                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" 
                                                    class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 hover:border-indigo-200 hover:text-indigo-600 transition-all text-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        View CV / Application
                                                    </a>
                                                @endif
                                                <p class="text-slate-400 text-xs font-bold ml-auto uppercase tracking-wide">Applied {{ $application->applied_at->diffForHumans() }}</p>
                                            </div>
                                        </div>

                                        <div class="ml-4 flex flex-col gap-4 w-full md:w-64">
                                            <a href="{{ route('employer.interviews.create', $application) }}" 
                                                class="w-full px-5 py-3 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition-all text-center">
                                                Schedule Interview
                                            </a>
                                            <form action="{{ route('employer.jobs.update-application-status', $application) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="relative">
                                                    <select name="status" onchange="this.form.submit()" 
                                                        class="w-full px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white cursor-pointer hover:border-indigo-300 transition-all">
                                                        <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                                        <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                        <option value="interviewed" {{ $application->status === 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                                        <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                     <svg class="w-4 h-4 absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            </form>
                                            <button onclick="document.getElementById('notes-{{ $application->id }}').classList.toggle('hidden')" 
                                                class="w-full px-4 py-3 rounded-xl bg-slate-50 text-slate-700 font-bold hover:bg-slate-100 transition-all text-center">
                                                Add Notes
                                            </button>
                                        </div>
                                    </div>

                                    <div id="notes-{{ $application->id }}" class="hidden mt-6 pt-6 border-t border-slate-100">
                                        <form action="{{ route('employer.jobs.update-application-status', $application) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="employer_notes" rows="3" placeholder="Add notes about this candidate..."
                                                class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 mb-4">{{ $application->employer_notes }}</textarea>
                                            <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all text-sm">
                                                Save Notes
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-[2.5rem] p-12 text-center shadow-sm border border-slate-100">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                 <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-2">No applications yet</h3>
                            <p class="text-slate-500">Applications will appear here when candidates apply</p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Sidebar (Right Column) --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Applications Stats --}}
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 sticky top-8">
                    <h3 class="text-xl font-black text-slate-900 mb-6">Applications</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-center p-3 rounded-xl bg-slate-50">
                            <span class="font-bold text-slate-600 text-sm">Total</span>
                            <span class="font-black text-slate-900">{{ $job->applications->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-xl bg-amber-50">
                            <span class="font-bold text-amber-700 text-sm">Pending</span>
                            <span class="font-black text-amber-700">{{ $job->applications->where('status', 'pending')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-xl bg-emerald-50">
                            <span class="font-bold text-emerald-700 text-sm">Shortlisted</span>
                            <span class="font-black text-emerald-700">{{ $job->applications->where('status', 'shortlisted')->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 rounded-xl bg-indigo-50">
                            <span class="font-bold text-indigo-700 text-sm">Accepted</span>
                            <span class="font-black text-indigo-700">{{ $job->applications->where('status', 'accepted')->count() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('employer.applications') }}" class="block w-full py-4 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition-all text-center border-2 border-indigo-50 hover:border-indigo-200">
                        View All Applications
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div x-data="{ showApplyModal: false }">
    <div class="min-h-screen bg-zinc-50 py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
            {{-- Back Navigation --}}
            <div class="mb-8">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-slate-500 hover:text-blue-700 font-bold mb-6 transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Jobs
                </a>
                
                <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                    <div>
                        <div class="flex items-center gap-4 mb-3">
                            <h1 class="text-4xl font-extrabold text-blue-900 tracking-tight">{{ $job->title }}</h1>
                            @php
                                $statusStyles = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ];
                                $style = $statusStyles[$job->status] ?? 'bg-blue-50 text-blue-700 border-blue-100';
                            @endphp
                            <span class="px-3 py-1 rounded-lg border text-xs font-bold uppercase tracking-wide {{ $style }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                        <p class="text-slate-500 text-lg font-medium flex items-center gap-2">
                            <span>Posted by <span class="text-slate-700 font-bold">{{ $job->employer->name ?? 'Confidential' }}</span></span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>{{ $job->created_at->diffForHumans() }}</span>
                        </p>
                    </div>

                    @auth
                        @if(Auth::user()->hasRole('employer') && Auth::id() === $job->employer_id)
                            <a href="{{ route('employer.jobs.show', $job) }}" class="px-8 py-3 rounded-xl bg-blue-800 hover:bg-blue-900 text-white font-bold shadow-lg shadow-blue-900/10 transition-all">
                                Manage Job
                            </a>
                        @elseif($hasApplied)
                            {{-- REMOVED: Applied Button (Header) --}}
                            <a href="{{ route('candidate.applications') }}" class="px-8 py-3 rounded-xl bg-white border-2 border-blue-100 text-blue-700 font-bold hover:border-blue-300 hover:bg-blue-50 transition-all shadow-sm">
                                View Status
                            </a>
                        @else
                            <button @click="showApplyModal = true" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 transition-all transform hover:-translate-y-0.5">
                                Apply Now
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 transition-all">
                            Login to Apply
                        </a>
                    @endauth
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8 lg:p-10 mb-6">
                        <h2 class="text-2xl font-bold text-blue-900 mb-8 pb-4 border-b border-zinc-100">Job Details</h2>
                        
                        <div class="mb-10">
                            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Description</h3>
                            <div class="text-slate-700 leading-relaxed whitespace-pre-line text-lg prose prose-slate max-w-none">{!! Str::markdown($job->description) !!}</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 mb-10 bg-slate-50/50 p-8 rounded-xl border border-zinc-100">
                            @if ($job->location)
                                <div>
                                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Location</h3>
                                    <p class="text-slate-800 font-bold text-lg flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $job->location }}
                                    </p>
                                </div>
                            @endif
                            <div>
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Job Type</h3>
                                <p class="text-slate-800 font-bold text-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                </p>
                            </div>
                            @if ($job->salary_min && $job->salary_max)
                                <div>
                                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Salary Range</h3>
                                    <p class="text-slate-800 font-bold text-lg flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $job->salary_currency }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                    </p>
                                </div>
                            @endif
                            @if ($job->application_deadline)
                                <div>
                                    <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Application Deadline</h3>
                                    <p class="text-slate-800 font-bold text-lg flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $job->application_deadline->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        @if ($job->accessibility_features)
                            <div class="mb-10">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-4">Accessibility Features</h3>
                                <div class="flex flex-wrap gap-3">
                                    @foreach ($job->accessibility_features as $feature)
                                        <span class="px-4 py-2 bg-blue-50 text-blue-700 border border-blue-100 rounded-xl text-sm font-bold shadow-sm">{{ $feature }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($job->accessibility_accommodations)
                            <div class="mb-8">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Accessibility Accommodations</h3>
                                <p class="text-slate-700 whitespace-pre-line bg-blue-50/30 p-6 rounded-xl border border-blue-50/50">{{ $job->accessibility_accommodations }}</p>
                            </div>
                        @endif

                        @if ($job->additional_requirements)
                            <div>
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Additional Requirements</h3>
                                <p class="text-slate-700 whitespace-pre-line bg-slate-50 p-6 rounded-xl border border-zinc-100">{{ $job->additional_requirements }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8 sticky top-24">
                        <h2 class="text-lg font-bold text-blue-900 mb-6 pb-4 border-b border-zinc-100">About the Company</h2>
                        
                        <div class="flex items-center gap-4 mb-6">
                             <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold shadow-sm">
                                {{ substr($job->employer->name ?? 'C', 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg">{{ $job->employer->name ?? 'Confidential' }}</h3>
                                <p class="text-slate-500 text-sm font-medium">Verified Employer</p>
                            </div>
                        </div>
                        
                        <p class="text-slate-600 text-sm mb-8 leading-relaxed">
                            This employer is committed to providing an inclusive and accessible workplace, ensuring equal opportunities for all candidates.
                        </p>

                        <div class="space-y-4">
                            @auth
                                @if(Auth::user()->hasRole('employer') && Auth::id() === $job->employer_id)
                                    <a href="{{ route('employer.jobs.show', $job) }}" class="block w-full text-center px-6 py-3.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold transition-all shadow-md">
                                        Manage Job
                                    </a>
                                @elseif($hasApplied)
                                    <button disabled class="block w-full text-center px-6 py-3.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 font-bold cursor-not-allowed">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Applied
                                        </div>
                                    </button>
                                @else
                                    <button @click="showApplyModal = true" class="block w-full text-center px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all shadow-lg shadow-blue-600/20">
                                        Apply Now
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="block w-full text-center px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold transition-all shadow-lg shadow-blue-600/20">
                                    Login to Apply
                                </a>
                            @endauth
                            
                            {{-- REMOVED: Save Job Button (Sidebar) --}}
                        </div>
                    </div>
                </div>
            </div>

            @auth
                @if(!$hasApplied && !Auth::user()->hasRole('employer'))
                <div x-show="showApplyModal" 
                     style="display: none;"
                     class="fixed inset-0 z-50 overflow-y-auto" 
                     aria-labelledby="modal-title" 
                     role="dialog" 
                     aria-modal="true">
                    
                    <div x-show="showApplyModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                         @click="showApplyModal = false"></div>

                    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                        <div x-show="showApplyModal"
                             x-transition:enter="ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-zinc-200">
                            
                            <div class="bg-white px-8 pb-8 pt-8">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-2xl font-extrabold text-blue-900" id="modal-title">Apply for Job</h3>
                                    <button @click="showApplyModal = false" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 p-2 rounded-full transition-colors">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                <form action="{{ route('jobs.apply', $job) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="mb-6">
                                        <label for="cv" class="block text-sm font-bold text-slate-700 mb-2">Upload CV / Resume <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <input type="file" name="cv" id="cv" required accept=".pdf,.doc,.docx"
                                                class="block w-full text-sm text-slate-500
                                                file:mr-4 file:py-2.5 file:px-4
                                                file:rounded-xl file:border-0
                                                file:text-sm file:font-bold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100
                                                cursor-pointer border border-slate-200 rounded-xl p-2 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                        </div>
                                        <p class="mt-2 text-xs text-slate-500 font-medium ml-1">Accepted formats: PDF, DOC, DOCX (Max 2MB)</p>
                                    </div>

                                    <div class="mb-8">
                                        <label for="cover_letter" class="block text-sm font-bold text-slate-700 mb-2">Cover Letter (Optional)</label>
                                        <textarea name="cover_letter" id="cover_letter" rows="4" 
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-colors p-4 font-medium text-slate-700"
                                            placeholder="Explain why you're a good fit for this role..."></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                        <button type="button" @click="showApplyModal = false" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 hover:text-slate-800 transition-all">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 transition-all">
                                            Submit Application
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection

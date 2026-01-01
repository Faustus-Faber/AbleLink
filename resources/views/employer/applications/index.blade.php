@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-12 pb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    All Applications
                </h1>
                <p class="text-slate-500 mt-2 font-medium">
                    Manage applications across all your job postings
                </p>
            </div>
            
            <a href="{{ route('employer.jobs.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-full bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 hover:shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                View Jobs
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">

        <div class="mb-8 bg-white rounded-3xl shadow-sm border border-slate-100 p-2 pl-6 pr-2">
            <form action="{{ route('employer.applications') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                
                <div class="flex-grow w-full md:w-auto py-2">
                    <label for="search" class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Search Applications</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Search by applicant name, email..." 
                        class="w-full border-none p-0 text-slate-900 font-bold placeholder-slate-300 focus:ring-0 bg-transparent text-lg">
                </div>

                <div class="h-10 w-px bg-slate-100 hidden md:block"></div>

                <div class="w-full md:w-48 py-2">
                    <label for="filter_status" class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Status</label>
                     <div class="relative" x-data="{
                        status: '{{ request('status') }}',
                        open: false,
                        options: {
                            '': 'All Status',
                            'pending': 'Pending',
                            'reviewing': 'Reviewing',
                            'shortlisted': 'Shortlisted',
                            'interviewed': 'Interviewed',
                            'accepted': 'Accepted',
                            'rejected': 'Rejected'
                        },
                        select(key) {
                            this.status = key;
                            this.open = false;
                            document.getElementById('filter_status').value = key;
                            document.getElementById('filter_status').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('filter_status').addEventListener('change', (e) => {
                                this.status = e.target.value;
                            });
                        }
                     }">
                        <select name="status" id="filter_status" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                            <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="interviewed" {{ request('status') == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                            <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full border-none p-0 text-slate-900 font-bold focus:ring-0 bg-transparent cursor-pointer flex items-center justify-between text-lg">
                            <span x-text="options[status] || 'All Status'"></span>
                            <svg class="w-5 h-5 ml-2 text-slate-400 transition-transform duration-200" 
                                 :class="{'rotate-180': open}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-0 top-full mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5 z-50"
                             style="display: none;">
                            <div class="py-1">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="select(key)"
                                            class="w-full text-left px-4 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                        <span x-text="label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-shrink-0">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-indigo-600/20 transition-all">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('employer.applications') }}" class="block text-center mt-2 text-xs font-bold text-slate-400 hover:text-indigo-600">Clear Filters</a>
                    @endif
                </div>
            </form>
        </div>

        @if ($applications->count() > 0)
            <div class="space-y-4">
                @foreach ($applications as $application)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-8">
                            
                            {{-- Applicant Details --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-4 mb-2">
                                    <h3 class="text-2xl font-black text-slate-900 truncate">{{ $application->applicant->name }}</h3>
                                    
                                    {{-- Status Badge --}}
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

                                <p class="text-indigo-600 font-bold text-sm mb-4">
                                    Applying for: {{ $application->job->title }}
                                </p>
                                
                                <div class="flex flex-col gap-1 text-sm text-slate-500 mb-6 font-medium">
                                    <span class="flex items-center">
                                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                         {{ $application->applicant->email }}
                                    </span>
                                    @if ($application->applicant->profile && $application->applicant->profile->disability_type)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            Disability Type: {{ $application->applicant->profile->disability_type }}
                                        </span>
                                    @endif
                                </div>

                                @if ($application->cover_letter)
                                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 mb-6">
                                        <p class="text-slate-600 leading-relaxed text-sm font-medium line-clamp-3">
                                            {{ Str::limit($application->cover_letter, 200) }}
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
                                    
                                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider ml-auto">
                                        Applied {{ $application->applied_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-4 w-full md:w-64">
                                <a href="{{ route('employer.jobs.show', $application->job) }}" 
                                    class="w-full px-5 py-3 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition-all text-center">
                                    View Job
                                </a>

                                <a href="{{ route('employer.interviews.create', $application) }}"
                                    class="w-full px-5 py-3 rounded-xl bg-purple-50 text-purple-700 font-bold hover:bg-purple-100 transition-all text-center">
                                    Schedule Interview
                                </a>

                                <form action="{{ route('employer.jobs.update-application-status', $application) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                        {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                                        @php $selectId = 'application_status_' . $application->id; @endphp
                                        <div class="relative" x-data="{
                                            status: '{{ $application->status }}',
                                            open: false,
                                            selectId: '{{ $selectId }}',
                                            options: {
                                                'pending': 'Pending',
                                                'reviewing': 'Reviewing',
                                                'shortlisted': 'Shortlisted',
                                                'interviewed': 'Interviewed',
                                                'accepted': 'Accepted',
                                                'rejected': 'Rejected'
                                            },
                                            updateStatus(key) {
                                                this.status = key;
                                                this.open = false;
                                                document.getElementById(this.selectId).value = key;
                                                document.getElementById(this.selectId).dispatchEvent(new Event('change', { bubbles: true }));
                                                this.$nextTick(() => {
                                                    this.$refs.nativeSelect.form.submit();
                                                });
                                            },
                                            init() {
                                                document.getElementById(this.selectId).addEventListener('change', (e) => {
                                                    this.status = e.target.value;
                                                });
                                            }
                                        }">
                                            {{-- Hidden Native Select (AI-Compatible) --}}
                                            <select name="status" id="{{ $selectId }}" x-ref="nativeSelect" class="sr-only" tabindex="-1" aria-hidden="true">
                                                <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="reviewing" {{ $application->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                                <option value="shortlisted" {{ $application->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                                <option value="interviewed" {{ $application->status == 'interviewed' ? 'selected' : '' }}>Interviewed</option>
                                                <option value="accepted" {{ $application->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            
                                            {{-- Premium Visual Dropdown --}}
                                            <button type="button" 
                                                    @click="open = !open"
                                                    @click.away="open = false"
                                                    class="w-full px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:border-indigo-300 transition-all bg-white flex items-center justify-between">
                                                <span x-text="options[status]"></span>
                                                <svg class="w-4 h-4 ml-2 text-slate-400 transition-transform duration-200" 
                                                     :class="{'rotate-180': open}"
                                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5 z-50 py-1"
                                                 style="display: none;">
                                                <template x-for="(label, key) in options" :key="key">
                                                    <button type="button"
                                                            @click="updateStatus(key)"
                                                            class="w-full text-left px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors"
                                                            :class="{'bg-slate-50 text-indigo-600': status === key}">
                                                        <span x-text="label"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        @else
            <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-2">No applications yet</h3>
                <p class="text-slate-500 text-lg mb-8 max-w-md mx-auto">
                    Applications will appear here when candidates apply to your jobs.
                </p>
                <a href="{{ route('employer.jobs.create') }}" class="px-8 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-all">
                    Post a Job
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

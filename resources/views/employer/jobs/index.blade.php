@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-12 pb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">
                    Job Management
                </h1>
                <p class="text-slate-500 mt-2 font-medium">
                    Manage your job postings and applications
                </p>
            </div>
            
            <a href="{{ route('employer.jobs.create') }}" 
               class="inline-flex items-center px-6 py-3 rounded-full bg-indigo-600 text-white font-bold shadow-lg hover:bg-indigo-700 hover:shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Post New Job
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">

        @if (session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <p class="text-emerald-900 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Filter Bar --}}
        <div class="mb-8 bg-white rounded-3xl shadow-sm border border-slate-100 p-2 pl-6 pr-2">
            <form action="{{ route('employer.jobs.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                
                {{-- Search --}}
                <div class="flex-grow w-full md:w-auto py-2">
                    <label for="search" class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Search Jobs</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Search by title, description, location..." 
                        class="w-full border-none p-0 text-slate-900 font-bold placeholder-slate-300 focus:ring-0 bg-transparent text-lg">
                </div>

                <div class="h-10 w-px bg-slate-100 hidden md:block"></div>

                {{-- Status --}}
                <div class="w-full md:w-40 py-2">
                     <label for="status" class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Status</label>
                     {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                     <div class="relative" x-data="{
                        status: '{{ request('status') }}',
                        open: false,
                        options: {
                            '': 'All Status',
                            'active': 'Active',
                            'draft': 'Draft',
                            'closed': 'Closed',
                            'filled': 'Filled'
                        },
                        select(key) {
                            this.status = key;
                            this.open = false;
                            document.getElementById('status').value = key;
                            document.getElementById('status').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('status').addEventListener('change', (e) => {
                                this.status = e.target.value;
                            });
                        }
                     }">
                        {{-- Hidden Native Select (AI-Compatible) --}}
                        <select name="status" id="status" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                        </select>
                        
                        {{-- Premium Visual Dropdown --}}
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

                <div class="h-10 w-px bg-slate-100 hidden md:block"></div>

                {{-- Job Type --}}
                <div class="w-full md:w-40 py-2">
                    <label for="job_type_filter" class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Job Type</label>
                    {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                    <div class="relative" x-data="{
                        jobType: '{{ request('job_type') }}',
                        open: false,
                        options: {
                            '': 'All Types',
                            'full-time': 'Full Time',
                            'part-time': 'Part Time',
                            'contract': 'Contract',
                            'remote': 'Remote'
                        },
                        select(key) {
                            this.jobType = key;
                            this.open = false;
                            document.getElementById('job_type_filter').value = key;
                            document.getElementById('job_type_filter').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('job_type_filter').addEventListener('change', (e) => {
                                this.jobType = e.target.value;
                            });
                        }
                    }">
                        {{-- Hidden Native Select (AI-Compatible) --}}
                        <select name="job_type" id="job_type_filter" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="" {{ !request('job_type') ? 'selected' : '' }}>All Types</option>
                            <option value="full-time" {{ request('job_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ request('job_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ request('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="remote" {{ request('job_type') == 'remote' ? 'selected' : '' }}>Remote</option>
                        </select>
                        
                        {{-- Premium Visual Dropdown --}}
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full border-none p-0 text-slate-900 font-bold focus:ring-0 bg-transparent cursor-pointer flex items-center justify-between text-lg">
                            <span x-text="options[jobType] || 'All Types'"></span>
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

                {{-- Filter Button --}}
                <div class="flex-shrink-0">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-indigo-600/20 transition-all">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'status', 'job_type']))
                        <a href="{{ route('employer.jobs.index') }}" class="block text-center mt-2 text-xs font-bold text-slate-400 hover:text-indigo-600">Clear Filters</a>
                    @endif
                </div>

            </form>
        </div>

        @if ($jobs->count() > 0)
            <div class="space-y-4">
                @foreach ($jobs as $job)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 group">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                            
                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-4 mb-3">
                                    <h3 class="text-2xl font-black text-slate-900 truncate">{{ $job->title }}</h3>
                                    
                                    {{-- Status Badge --}}
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
                                
                                <p class="text-slate-500 font-medium mb-6 line-clamp-2 max-w-3xl leading-relaxed">
                                    {{ Str::limit($job->description, 200) }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-6 text-sm font-bold text-slate-600 mb-6">
                                    @if ($job->location)
                                        <span class="flex items-center text-slate-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                    
                                    <span class="flex items-center text-slate-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                    </span>

                                    @if ($job->salary_min && $job->salary_max)
                                        <span class="flex items-center text-slate-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $job->salary_currency }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                        </span>
                                    @endif
                                </div>

                                @if ($job->accessibility_features)
                                    <div class="flex flex-wrap gap-2 mb-6">
                                        @foreach ($job->accessibility_features as $feature)
                                            <span class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold border border-indigo-100">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="flex items-center gap-6 text-sm font-bold pt-4 border-t border-slate-50">
                                    <span class="text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $job->applications_count }} Applications</span>
                                    <span class="text-emerald-600">{{ $job->shortlisted_applications_count }} Shortlisted</span>
                                    <span class="text-amber-600">{{ $job->pending_applications_count }} Pending</span>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-row lg:flex-col gap-3 min-w-[140px]">
                                <a href="{{ route('employer.jobs.show', $job) }}" class="flex-1 px-5 py-2.5 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition-all text-center">
                                    View
                                </a>
                                <a href="{{ route('employer.jobs.edit', $job) }}" class="flex-1 px-5 py-2.5 rounded-xl bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 transition-all text-center border border-slate-100">
                                    Edit
                                </a>
                                <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" onsubmit="return confirm('Delete this job?');" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-5 py-2.5 rounded-xl bg-rose-50 text-rose-600 font-bold hover:bg-rose-100 transition-all text-center">
                                        Delete
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-2">No jobs found</h3>
                <p class="text-slate-500 text-lg mb-8 max-w-md mx-auto">
                    Try adjusting your search filters or post a new job opportunity.
                </p>
                @if(request()->hasAny(['search', 'status', 'job_type']))
                    <a href="{{ route('employer.jobs.index') }}" class="px-8 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-all">
                        Clear Filters
                    </a>
                @else
                    <a href="{{ route('employer.jobs.create') }}" class="px-8 py-3 rounded-full bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all">
                        Post New Job
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

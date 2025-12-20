@extends('layouts.app')

{{-- F9 - Evan Yuvraj Munshi --}}
@section('content')
<div class="min-h-screen bg-zinc-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-extrabold text-blue-900 tracking-tight">Find Your Dream Job</h1>
                <p class="text-slate-500 mt-2 text-lg">Discover opportunities with inclusive employers.</p>
            </div>
            <div class="flex items-center gap-3" x-data>
                @auth
                    <a href="{{ route('candidate.applications') }}" 
                        class="flex items-center gap-2 px-5 py-2.5 bg-white border border-blue-200 text-blue-700 font-bold rounded-xl shadow-sm hover:bg-blue-50 hover:border-blue-300 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        My Applications
                    </a>
                @endauth

                {{-- F12 - Modal Trigger --}}
                <button @click="$dispatch('open-recommendations', { type: 'job' })" 
                    class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-sm hover:bg-blue-700 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Smart Recommendations
                </button>
            </div>
        </div>
        
        {{-- F12 - Modal Component --}}
        <x-recommendation-modal type="job" />

        {{-- Search & Filters --}}
        <div class="mb-10 bg-white rounded-2xl shadow-sm border border-zinc-200 overflow-hidden">
            <form action="{{ route('jobs.index') }}" method="GET" class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
                    <div class="md:col-span-5">
                        <label for="search" class="block text-sm font-bold text-slate-700 mb-2">Search Keywords</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                placeholder="Job title, skills, or company..." 
                                class="w-full pl-11 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-3 transition-colors text-slate-900 font-medium">
                        </div>
                    </div>
                    
                    <div class="md:col-span-4">
                        <label for="location" class="block text-sm font-bold text-slate-700 mb-2">Location</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="text" name="location" id="location" value="{{ request('location') }}" 
                                placeholder="City or Remote" 
                                class="w-full pl-11 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 py-3 transition-colors text-slate-900 font-medium">
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label for="job_type" class="block text-sm font-bold text-slate-700 mb-2">Job Type</label>
                        @php
                            $jobTypes = [
                                '' => 'All Types',
                                'full-time' => 'Full Time',
                                'part-time' => 'Part Time',
                                'contract' => 'Contract',
                                'remote' => 'Remote',
                            ];
                            $currentType = request('job_type', '');
                            $currentLabel = $jobTypes[$currentType] ?? 'All Types';
                        @endphp
                        
                        {{-- Hybrid Dropdown: Hidden native select (for AI) + Premium visual dropdown --}}
                        <div x-data="{ 
                            open: false, 
                            selected: '{{ $currentType }}',
                            label: '{{ $currentLabel }}',
                            options: {
                                '': 'All Types',
                                'full-time': 'Full Time',
                                'part-time': 'Part Time',
                                'contract': 'Contract',
                                'remote': 'Remote'
                            },
                            select(value, label) {
                                this.selected = value;
                                this.label = label;
                                this.open = false;
                                // Sync with native select for AI compatibility
                                document.getElementById('job_type').value = value;
                                document.getElementById('job_type').dispatchEvent(new Event('change', { bubbles: true }));
                            },
                            init() {
                                // Sync visual dropdown when AI changes the native select
                                document.getElementById('job_type').addEventListener('change', (e) => {
                                    this.selected = e.target.value;
                                    this.label = this.options[e.target.value] || 'All Types';
                                });
                            }
                        }" @click.away="open = false" class="relative">
                            
                            {{-- Hidden Native Select (AI-Compatible) --}}
                            <select name="job_type" id="job_type" class="sr-only" tabindex="-1" aria-hidden="true">
                                @foreach($jobTypes as $value => $lbl)
                                    <option value="{{ $value }}" {{ $currentType == $value ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>

                            {{-- Premium Visual Dropdown Trigger --}}
                            <button type="button" @click="open = !open" 
                                class="relative w-full rounded-xl border border-slate-200 bg-white py-3 pl-4 pr-10 text-left text-slate-900 font-medium shadow-sm transition-all duration-200
                                       hover:border-blue-300 hover:shadow-md
                                       focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                                <span class="block truncate" x-text="label"></span>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <svg class="h-5 w-5 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                            </button>

                            {{-- Premium Dropdown Menu --}}
                            <div x-show="open" 
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-50 mt-2 w-full rounded-2xl bg-white shadow-xl ring-1 ring-black/5 overflow-hidden"
                                style="display: none;">
                                <div class="py-1">
                                    @foreach($jobTypes as $value => $lbl)
                                        <div @click="select('{{ $value }}', '{{ $lbl }}')" 
                                            class="cursor-pointer select-none relative py-3 pl-4 pr-9 transition-colors duration-150"
                                            :class="{ 'bg-blue-50 text-blue-900 font-semibold': selected === '{{ $value }}', 'text-slate-700 hover:bg-slate-50': selected !== '{{ $value }}' }">
                                            <span class="block truncate">{{ $lbl }}</span>
                                            
                                            {{-- Checkmark --}}
                                            <span x-show="selected === '{{ $value }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-blue-600">
                                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50/50 rounded-xl p-6 border border-blue-100/50">
                    <h3 class="text-sm font-bold text-blue-900 mb-4 uppercase tracking-wider">Accessibility Filters</h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach([
                            'wheelchair_accessible' => 'Wheelchair Accessible',
                            'sign_language_support' => 'Sign Language Support',
                            'screen_reader_compatible' => 'Screen Reader Compatible',
                            'flexible_hours' => 'Flexible Hours',
                            'remote_work_available' => 'Remote Work'
                        ] as $name => $label)
                            <label for="{{ $name }}" class="inline-flex items-center bg-white px-4 py-2 rounded-lg border border-blue-100 shadow-sm cursor-pointer hover:border-blue-300 transition-colors select-none">
                                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" {{ request($name) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5">
                                <span class="ml-3 text-sm font-medium text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
                    @if(request()->hasAny(['search', 'location', 'job_type', 'wheelchair_accessible', 'sign_language_support', 'screen_reader_compatible', 'flexible_hours', 'remote_work_available']))
                        <a href="{{ route('jobs.index') }}" class="px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold rounded-xl transition-colors">
                            Clear Filters
                        </a>
                    @endif
                    <button type="submit" class="px-8 py-3 bg-blue-800 hover:bg-blue-900 text-white font-bold rounded-xl transition-colors shadow-lg shadow-blue-900/10">
                        Search Jobs
                    </button>
                </div>
            </form>
            
            <!-- F12 - Smart Recommendations Trigger -->
            <div class="mt-4 flex justify-end">
                <button @click="$dispatch('open-recommendation-modal', { type: 'jobs' })" 
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm transition-all duration-200">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Smart Recommendations</span>
                </button>
            </div>
        </div>

        {{-- Job Listings --}}
        @if ($jobs->count() > 0)
            <div class="grid grid-cols-1 gap-6">
                @foreach ($jobs as $job)
                    <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-8 hover:shadow-md hover:border-blue-300 transition-all group">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-6">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-2xl font-bold text-blue-900 group-hover:text-blue-700 transition-colors">{{ $job->title }}</h3>
                                    @if($job->is_featured)
                                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-700 uppercase tracking-wide">Featured</span>
                                    @endif
                                </div>
                                <div class="flex items-center text-slate-500 font-medium mb-4">
                                     <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $job->employer->name ?? 'Confidential' }}
                                </div>

                                <p class="text-slate-600 mb-6 line-clamp-2 leading-relaxed max-w-3xl">{{ Str::limit($job->description, 180) }}</p>
                                
                                <div class="flex flex-wrap gap-y-3 gap-x-6 text-sm text-slate-600 font-medium mb-6">
                                    @if ($job->location)
                                        <span class="flex items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                    <span class="flex items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                    </span>
                                    @if ($job->salary_min && $job->salary_max)
                                        <span class="flex items-center bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $job->salary_currency }} {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                        </span>
                                    @endif
                                </div>

                                @if ($job->accessibility_features)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($job->accessibility_features as $feature)
                                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-md text-xs font-bold uppercase tracking-wide">{{ $feature }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="flex-shrink-0 pt-2">
                                <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center px-6 py-3 rounded-xl border-2 border-blue-600 text-blue-700 font-bold hover:bg-blue-600 hover:text-white transition-all duration-200 text-center whitespace-nowrap shadow-sm">
                                    View Details
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-zinc-200 p-16 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">No jobs found</h3>
                <p class="text-slate-500 max-w-md mx-auto">We couldn't find any opportunities matching your criteria. Try adjusting your search keywords or removing some filters.</p>
                <div class="mt-8">
                     <a href="{{ route('jobs.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                        Clear All Filters
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

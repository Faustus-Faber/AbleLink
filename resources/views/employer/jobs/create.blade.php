@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-12 pb-8">
        <a href="{{ route('employer.jobs.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Jobs
        </a>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">
            Post New Job
        </h1>
        <p class="text-slate-500 mt-3 text-lg font-medium">
            Create an accessible job posting for candidates.
        </p>
    </div>

    {{-- Form Section --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
        <form action="{{ route('employer.jobs.store') }}" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-12">
            @csrf

            {{-- Basic Info --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 border-b border-slate-100 pb-4">Basic Information</h2>
                
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Job Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    @error('title') <p class="text-red-500 text-sm font-bold pl-2">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Description *</label>
                    <textarea name="description" rows="6" required placeholder="Markdown supported..."
                         class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-sm font-bold pl-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                    
                    <div class="space-y-3">
                         <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Job Type *</label>
                         {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                         <div class="relative" x-data="{
                            jobType: '{{ old('job_type') }}',
                            open: false,
                            options: {
                                'full-time': 'Full-time',
                                'part-time': 'Part-time',
                                'contract': 'Contract',
                                'remote': 'Remote'
                            },
                            select(key) {
                                this.jobType = key;
                                this.open = false;
                                document.getElementById('job_type').value = key;
                                document.getElementById('job_type').dispatchEvent(new Event('change', { bubbles: true }));
                            },
                            init() {
                                document.getElementById('job_type').addEventListener('change', (e) => {
                                    this.jobType = e.target.value;
                                });
                            }
                        }">
                            {{-- Hidden Native Select (AI-Compatible) --}}
                            <select name="job_type" id="job_type" class="sr-only" tabindex="-1" aria-hidden="true" required>
                                <option value="">Select Job Type</option>
                                <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="remote" {{ old('job_type') == 'remote' ? 'selected' : '' }}>Remote</option>
                            </select>

                            {{-- Premium Visual Dropdown --}}
                            <button type="button" 
                                    @click="open = !open"
                                    @click.away="open = false"
                                    class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 flex items-center justify-between">
                                <span x-text="options[jobType] || 'Select Job Type'"></span>
                                <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" 
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
                                 class="absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5 z-50 py-2"
                                 style="display: none;">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="select(key)"
                                            class="w-full text-left px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors"
                                            :class="{'bg-slate-50 text-indigo-600': jobType === key}">
                                        <span x-text="label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-3">
                         <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Min Salary</label>
                        <input type="number" name="salary_min" value="{{ old('salary_min') }}" step="0.01" min="0" placeholder="0.00"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                     <div class="space-y-3">
                         <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Max Salary</label>
                        <input type="number" name="salary_max" value="{{ old('salary_max') }}" step="0.01" min="0" placeholder="0.00"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                    <div class="space-y-3">
                         <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Currency</label>
                        <input type="text" name="salary_currency" value="{{ old('salary_currency', 'USD') }}" maxlength="3" placeholder="USD"
                            class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    </div>
                </div>

                 <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Application Deadline</label>
                    <input type="date" name="application_deadline" value="{{ old('application_deadline') }}"
                        class="w-full md:w-1/3 px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900">
                </div>
            </div>

            {{-- Accessibility --}}
            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 border-b border-slate-100 pb-4">Accessibility</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                     @foreach(['wheelchair_accessible' => 'Wheelchair Accessible', 'sign_language_support' => 'Sign Language Support', 'screen_reader_compatible' => 'Screen Reader Compatible', 'flexible_hours' => 'Flexible Hours', 'remote_work_available' => 'Remote Work Available'] as $field => $label)
                        <label class="flex items-center p-4 bg-slate-50 rounded-xl hover:bg-slate-100 cursor-pointer transition-all border border-transparent hover:border-slate-200">
                            <input type="checkbox" name="{{ $field }}" value="1" {{ old($field) ? 'checked' : '' }}
                                class="w-5 h-5 text-indigo-600 rounded-md border-slate-300 focus:ring-indigo-500 bg-white">
                            <span class="ml-3 font-bold text-slate-700">{{ $label }}</span>
                        </label>
                     @endforeach
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Accommodations</label>
                    <textarea name="accessibility_accommodations" rows="3"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400"
                         placeholder="Describe additional accommodations...">{{ old('accessibility_accommodations') }}</textarea>
                </div>
                 
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Additional Requirements</label>
                    <textarea name="additional_requirements" rows="3"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400"
                        placeholder="Any additional requirements...">{{ old('additional_requirements') }}</textarea>
                </div>
            </div>

            {{-- Status --}}
            <div class="bg-indigo-50/50 p-8 rounded-[2rem] border border-indigo-50">
                 <label class="block text-xs font-bold text-indigo-900 uppercase tracking-widest mb-4">Post Status</label>
                 <div class="relative max-w-sm" x-data="{
                    status: '{{ old('status', 'active') }}',
                    open: false,
                    options: {
                        'active': 'Publish Now',
                        'draft': 'Save as Draft'
                    }
                 }">
                    <input type="hidden" name="status" :value="status">
                    <button type="button" 
                            @click="open = !open"
                            @click.away="open = false"
                            class="w-full px-6 py-4 rounded-2xl bg-white border-2 border-indigo-200 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-indigo-900 flex items-center justify-between">
                        <span x-text="options[status]" class="uppercase tracking-wide"></span>
                        <svg class="w-5 h-5 text-indigo-400 transition-transform duration-200" 
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
                         class="absolute left-0 top-full mt-2 w-full bg-white rounded-2xl shadow-xl border border-indigo-100 overflow-hidden ring-1 ring-black ring-opacity-5 z-50 py-2"
                         style="display: none;">
                        <template x-for="(label, key) in options" :key="key">
                            <button type="button"
                                    @click="status = key; open = false"
                                    class="w-full text-left px-6 py-3 text-sm font-bold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors uppercase tracking-wide"
                                    :class="{'bg-indigo-50 text-indigo-900': status === key}">
                                <span x-text="label"></span>
                            </button>
                        </template>
                    </div>
                 </div>
            </div>

            <div class="flex justify-end items-center gap-6 pt-4">
                <a href="{{ route('employer.jobs.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" 
                        class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold shadow-xl shadow-indigo-600/20 hover:shadow-2xl hover:bg-indigo-700 hover:scale-[1.02] transition-all duration-300">
                    Post Job
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

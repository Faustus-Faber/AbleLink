@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4" x-data="{ showMedModal: false, showGoalModal: false }">
    <!-- Breadcrumb / Back -->
    <a href="{{ route('caregiver.dashboard') }}" class="group inline-flex items-center text-slate-500 hover:text-teal-600 mb-8 font-medium transition-colors">
        <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-teal-200 group-hover:bg-teal-50 shadow-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </div>
        Back to Dashboard
    </a>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden mb-8">
        
        <!-- Header (Solid Color, No Gradient) -->
        <div class="bg-teal-900 px-8 py-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
            </div>
            <div class="relative z-10">
                <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">Manage Health</h1>
                <div class="flex items-center text-teal-200 font-medium">
                    <span class="bg-teal-800/50 px-3 py-1 rounded-lg border border-teal-700/50 mr-3">{{ $user->name }}</span>
                    <span>Update diagnosis, medications, and health goals</span>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10">
            <!-- Diagnosis Section -->
            <section class="mb-12">
                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Diagnosis & Medical History</h2>
                        <p class="text-sm text-slate-500">Core medical information.</p>
                    </div>
                </div>

                <form action="{{ route('caregiver.patient.diagnosis.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Current Diagnosis</label>
                            <textarea name="diagnosis" rows="5" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all shadow-sm resize-none" placeholder="Enter current diagnosis details...">{{ old('diagnosis', $user->profile->diagnosis) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Medical History</label>
                            <textarea name="medical_history" rows="5" class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-100 focus:border-teal-500 outline-none transition-all shadow-sm resize-none" placeholder="Enter relevant medical history...">{{ old('medical_history', $user->profile->medical_history) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            Save Medical Profile
                        </button>
                    </div>
                </form>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Medications Section -->
                <section>
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Medications</h2>
                                <p class="text-sm text-slate-500">Active prescriptions</p>
                            </div>
                        </div>
                        <button @click="showMedModal = true" class="inline-flex items-center text-sm bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 px-4 py-2 rounded-lg font-bold transition-colors border border-slate-200 hover:border-indigo-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Schedule
                        </button>
                    </div>
                    
                    @if($medications->count() > 0)
                        <div class="space-y-4">
                            @foreach($medications as $med)
                                <div class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-indigo-200 hover:shadow-md transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-lg group-hover:text-indigo-600 transition-colors">{{ $med->medication_name }}</h4>
                                            <p class="text-slate-500 font-medium">{{ $med->dosage }} • {{ $med->frequency }}</p>
                                            @if($med->scheduled_time)
                                                <div class="flex items-center mt-2 text-indigo-600 font-bold text-xs uppercase tracking-wide">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ \Carbon\Carbon::parse($med->scheduled_time)->format('h:i A') }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs rounded-md font-bold uppercase tracking-wider border border-emerald-100">Active</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-slate-500 font-medium">No medication schedules set.</p>
                        </div>
                    @endif
                </section>

                <!-- Health Goals Section -->
                <section>
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Health Goals</h2>
                                <p class="text-sm text-slate-500">Targets & Objectives</p>
                            </div>
                        </div>
                        <button @click="showGoalModal = true" class="inline-flex items-center text-sm bg-slate-100 text-slate-700 hover:bg-orange-50 hover:text-orange-700 px-4 py-2 rounded-lg font-bold transition-colors border border-slate-200 hover:border-orange-100">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Goal
                        </button>
                    </div>

                    @if($goals->count() > 0)
                        <div class="space-y-4">
                            @foreach($goals as $goal)
                                <div class="bg-white p-5 rounded-2xl border border-slate-200 hover:border-orange-200 hover:shadow-md transition-all group">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-bold text-slate-900 text-lg group-hover:text-orange-600 transition-colors">{{ $goal->title }}</h4>
                                            <p class="text-slate-500 font-medium">{{ $goal->description }}</p>
                                            @if($goal->target_metric)
                                                <div class="flex items-center mt-2 text-orange-600 font-bold text-xs uppercase tracking-wide">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                                    Target: {{ $goal->target_value }} {{ $goal->target_metric }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs rounded-md font-bold uppercase tracking-wider border border-amber-100">{{ ucfirst($goal->status) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                            <p class="text-slate-500 font-medium">No health goals set.</p>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>

    <!-- Add Medication Modal (Premium) -->
    <div x-show="showMedModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showMedModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="showMedModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showMedModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-indigo-900 px-6 py-4">
                    <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">Add Medication Schedule</h3>
                </div>
                
                <form action="{{ route('health.medications.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Medication Name</label>
                            <input type="text" name="medication_name" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all shadow-sm" required placeholder="e.g. Lisinopril">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Dosage</label>
                                <input type="text" name="dosage" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all shadow-sm" required placeholder="e.g. 10mg">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Frequency</label>
                                {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                                <div class="relative" x-data="{
                                    frequency: 'daily',
                                    open: false,
                                    options: {
                                        'daily': 'Daily',
                                        'twice_daily': 'Twice Daily',
                                        'weekly': 'Weekly',
                                        'as_needed': 'As Needed'
                                    },
                                    select(key) {
                                        this.frequency = key;
                                        this.open = false;
                                        document.getElementById('frequency').value = key;
                                        document.getElementById('frequency').dispatchEvent(new Event('change', { bubbles: true }));
                                    },
                                    init() {
                                        document.getElementById('frequency').addEventListener('change', (e) => {
                                            this.frequency = e.target.value;
                                        });
                                    }
                                }">
                                    {{-- Hidden Native Select (AI-Compatible) --}}
                                    <select name="frequency" id="frequency" class="sr-only" tabindex="-1" aria-hidden="true">
                                        <option value="daily" selected>Daily</option>
                                        <option value="twice_daily">Twice Daily</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="as_needed">As Needed</option>
                                    </select>
                                    
                                    {{-- Premium Visual Dropdown --}}
                                    <button type="button" 
                                            @click="open = !open"
                                            @click.away="open = false"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                                        <span x-text="options[frequency]"></span>
                                        <div class="text-slate-500 group-hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5 transition-transform duration-200" 
                                                 :class="{'rotate-180': open}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                         style="display: none;">
                                        <div class="py-1">
                                            <template x-for="(label, key) in options" :key="key">
                                                <button type="button"
                                                        @click="select(key)"
                                                        class="w-full text-left px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors flex items-center justify-between">
                                                    <span x-text="label"></span>
                                                    <svg x-show="frequency == key" class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Scheduled Time</label>
                            <input type="time" name="scheduled_time" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 flex flex-col-reverse md:flex-row justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="showMedModal = false" class="w-full md:w-auto px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 hover:text-slate-900 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                            Save Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Goal Modal (Premium) -->
    <div x-show="showGoalModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showGoalModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="showGoalModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showGoalModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-teal-900 px-6 py-4">
                    <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">Add Health Goal</h3>
                </div>
                
                <form action="{{ route('health.goals.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Goal Title</label>
                            <input type="text" name="title" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all shadow-sm" required placeholder="e.g. Morning Walk">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                            <textarea name="description" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all shadow-sm" rows="3"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Target Metric</label>
                                {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                                <div class="relative" x-data="{
                                    metric: '',
                                    open: false,
                                    options: {
                                        '': 'None',
                                        'weight': 'Weight (kg)',
                                        'steps': 'Steps (count)',
                                        'blood_pressure': 'Blood Pressure'
                                    },
                                    select(key) {
                                        this.metric = key;
                                        this.open = false;
                                        document.getElementById('target_metric').value = key;
                                        document.getElementById('target_metric').dispatchEvent(new Event('change', { bubbles: true }));
                                    },
                                    init() {
                                        document.getElementById('target_metric').addEventListener('change', (e) => {
                                            this.metric = e.target.value;
                                        });
                                    }
                                }">
                                    {{-- Hidden Native Select (AI-Compatible) --}}
                                    <select name="target_metric" id="target_metric" class="sr-only" tabindex="-1" aria-hidden="true">
                                        <option value="" selected>None</option>
                                        <option value="weight">Weight (kg)</option>
                                        <option value="steps">Steps (count)</option>
                                        <option value="blood_pressure">Blood Pressure</option>
                                    </select>
                                    
                                    {{-- Premium Visual Dropdown --}}
                                    <button type="button" 
                                            @click="open = !open"
                                            @click.away="open = false"
                                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 flex items-center justify-between hover:bg-slate-50 transition-colors group text-left">
                                        <span x-text="options[metric]"></span>
                                        <div class="text-slate-500 group-hover:text-teal-600 transition-colors">
                                            <svg class="w-5 h-5 transition-transform duration-200" 
                                                 :class="{'rotate-180': open}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </button>
                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute z-50 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                         style="display: none;">
                                        <div class="py-1">
                                            <template x-for="(label, key) in options" :key="key">
                                                <button type="button"
                                                        @click="select(key)"
                                                        class="w-full text-left px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-teal-600 transition-colors flex items-center justify-between">
                                                    <span x-text="label"></span>
                                                    <svg x-show="metric == key" class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Target Value</label>
                                <input type="text" name="target_value" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deadline</label>
                            <input type="date" name="deadline" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 flex flex-col-reverse md:flex-row justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="showGoalModal = false" class="w-full md:w-auto px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 hover:text-slate-900 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 text-sm">
                            Cancel
                        </button>
                        <button type="submit" class="w-full md:w-auto px-6 py-3 rounded-xl bg-teal-600 text-white font-bold hover:bg-teal-700 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 text-sm">
                            Save Goal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

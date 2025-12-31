{{-- F19 - Evan Munshi --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-10" x-data="{ activeTab: 'healthcare' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Health Dashboard</h1>
                <p class="text-slate-500 mt-2 text-md">Monitor your vitals, medications, and wellness goals.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center space-x-3 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
                    <button @click="activeTab = 'healthcare'" 
                            :class="{ 'bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-black/5': activeTab === 'healthcare', 'text-slate-600 hover:text-slate-900 hover:bg-slate-50': activeTab !== 'healthcare' }"
                            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200">
                        Healthcare
                    </button>
                    <button @click="activeTab = 'medication'" 
                            :class="{ 'bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-black/5': activeTab === 'medication', 'text-slate-600 hover:text-slate-900 hover:bg-slate-50': activeTab !== 'medication' }"
                            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200">
                        Medication
                    </button>
                    <button @click="activeTab = 'metrics'" 
                            :class="{ 'bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-black/5': activeTab === 'metrics', 'text-slate-600 hover:text-slate-900 hover:bg-slate-50': activeTab !== 'metrics' }"
                            class="px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200">
                        Metrics
                    </button>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-xl border border-emerald-100 mb-8 flex items-center font-medium shadow-sm">
                <svg class="w-5 h-5 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Healthcare Tab -->
        <div x-show="activeTab === 'healthcare'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
            
            @if($missedMedications->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden">
                    <div class="bg-rose-50 px-6 py-4 border-b border-rose-100 flex items-center">
                         <svg class="h-5 w-5 text-rose-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <h3 class="text-rose-800 font-bold">Missed Medications Alert</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-700 mb-4 text-sm">You have missed the following scheduled doses. Please take action immediately.</p>
                        <ul class="space-y-3">
                            @foreach($missedMedications as $med)
                                <li class="flex items-center justify-between bg-rose-50/50 p-4 rounded-xl border border-rose-100">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ $med->medication_name }}</p>
                                        <p class="text-xs text-rose-600 font-semibold mt-1">Scheduled: {{ \Carbon\Carbon::parse($med->scheduled_time)->format('h:i A') }}</p>
                                    </div>
                                    <form action="{{ route('health.medications.log', $med) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="taken">
                                        <button type="submit" class="text-xs font-bold bg-white text-rose-600 border border-rose-200 shadow-sm px-4 py-2 rounded-lg hover:bg-rose-50 transition-colors">Mark Taken</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Diagnosis Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 h-full flex flex-col">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center space-x-3">
                             <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                             </div>
                             <h3 class="text-lg font-bold text-slate-800">Current Diagnosis</h3>
                        </div>
                    </div>
                    <div class="p-8 flex-1">
                        @if($user->profile->diagnosis)
                            <h4 class="text-2xl font-bold text-slate-900 mb-2 leading-tight">{{ $user->profile->diagnosis }}</h4>
                            <p class="text-slate-500 text-sm font-medium">Recorded Status</p>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-center py-6">
                                <p class="text-slate-400 font-medium">No active diagnosis recorded.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Medical History Card -->
                 <div class="bg-white rounded-2xl shadow-sm border border-slate-200 h-full flex flex-col">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl">
                        <div class="flex items-center space-x-3">
                             <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             </div>
                             <h3 class="text-lg font-bold text-slate-800">Medical History</h3>
                        </div>
                    </div>
                    <div class="p-8 flex-1">
                         @if($user->profile->medical_history)
                            <div class="prose prose-slate prose-sm max-w-none text-slate-600">
                                {{ $user->profile->medical_history }}
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-full text-center py-6">
                                <p class="text-slate-400 font-medium">No medical history available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Medication Tab -->
        <div x-show="activeTab === 'medication'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                         <h3 class="text-lg font-bold text-slate-800">Medication Schedule</h3>
                         <p class="text-slate-500 text-sm mt-1">Daily dosage and tracking</p>
                    </div>
                    @if(auth()->user()->isCaregiver())
                        <a href="{{ route('health.medications.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Add Medication
                        </a>
                    @endif
                </div>
                
                 <div class="divide-y divide-slate-100">
                    @forelse($medications as $med)
                        <div class="p-6 transition-colors hover:bg-slate-50/50 group">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors">{{ $med->medication_name }}</h4>
                                        <div class="flex flex-wrap items-center gap-3 mt-2 text-sm">
                                            <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-600 font-semibold border border-slate-200">{{ $med->dosage }}</span>
                                            <span class="text-slate-500 font-medium flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ ucfirst(str_replace('_', ' ', $med->frequency)) }}
                                                @if($med->scheduled_time)
                                                    at {{ \Carbon\Carbon::parse($med->scheduled_time)->format('h:i A') }}
                                                @endif
                                            </span>
                                        </div>
                                         @if($med->notes)
                                            <p class="text-slate-400 text-xs mt-2">{{ $med->notes }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex-shrink-0">
                                    @php
                                        $loggedToday = $med->logs->where('taken_at', '>=', today())->first();
                                    @endphp
                                    @if($loggedToday)
                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Taken Today
                                        </span>
                                    @else
                                        <form action="{{ route('health.medications.log', $med) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="taken">
                                            <button type="submit" class="w-full md:w-auto px-5 py-2.5 bg-white border border-emerald-200 text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition-all shadow-sm">
                                                Mark as Taken
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                             <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                             </div>
                            <h3 class="text-slate-900 font-bold">No Medications</h3>
                            <p class="text-slate-500 mt-1">No medications are currently scheduled.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Metrics Tab -->
        <div x-show="activeTab === 'metrics'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" style="display: none;">
            
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Add Goal Card -->
                @if(auth()->user()->isCaregiver())
                     <a href="{{ route('health.goals.create') }}" class="block h-full group">
                        <div class="h-full bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl flex flex-col items-center justify-center p-8 hover:border-emerald-500 hover:bg-emerald-50/30 transition-all cursor-pointer">
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <h3 class="font-bold text-slate-800 text-center">Set New Goal</h3>
                        </div>
                    </a>
                @endif
                
                @foreach($goals as $goal)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div>
                             <div class="flex justify-between items-start mb-4">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                </div>
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg uppercase tracking-wider">{{ $goal->status }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-slate-900 mb-2">{{ $goal->title }}</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">{{ $goal->description }}</p>
                        </div>
                         @if($goal->target_metric)
                            <div class="mt-auto pt-4 border-t border-slate-100">
                                 <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-500 font-medium">Target</span>
                                    <span class="text-slate-900 font-bold bg-slate-50 px-2 py-1 rounded-md">{{ $goal->target_value }} {{ $goal->target_metric }}</span>
                                 </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Metrics Log -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                         <h3 class="text-lg font-bold text-slate-800">Health Metrics History</h3>
                         <p class="text-slate-500 text-sm mt-1">Tracked vitals over time</p>
                    </div>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200" onclick="document.getElementById('addMetricModal').classList.remove('hidden')">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Log Metric
                    </button>
                </div>
                
                 <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Value</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Date Recorded</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentMetrics as $metric)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-4 font-bold text-slate-800 capitalize flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                                        {{ str_replace('_', ' ', $metric->metric_type) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-slate-100 text-slate-700 font-bold px-2 py-1 rounded-md text-sm">{{ $metric->value }} <span class="text-xs text-slate-500 font-normal ml-0.5">{{ $metric->unit }}</span></span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-sm font-medium">{{ $metric->measured_at->format('M d, h:i A') }}</td>
                                    <td class="px-6 py-4 text-slate-400 text-sm italic">{{ $metric->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center text-slate-500 font-medium">No metrics logged recently. Start tracking to see data here.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Metric Modal -->
    <div id="addMetricModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('addMetricModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                <form action="{{ route('health.metrics.store') }}" method="POST" class="p-8">
                    @csrf
                    <h3 class="text-xl font-extrabold text-slate-900 mb-6" id="modal-title">Log New Metric</h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Metric Type</label>
                            <select name="metric_type" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4 font-medium" required>
                                <option value="weight">Weight</option>
                                <option value="blood_pressure">Blood Pressure</option>
                                <option value="blood_sugar">Blood Sugar</option>
                                <option value="heart_rate">Heart Rate</option>
                                <option value="steps">Steps</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Value</label>
                                <input type="text" name="value" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4 font-medium" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Unit</label>
                                <input type="text" name="unit" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4 font-medium" placeholder="e.g. kg">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Notes</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-700 focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm py-3 px-4 font-medium resize-none"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3 justify-end">
                        <button type="button" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors" onclick="document.getElementById('addMetricModal').classList.add('hidden')">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-700 transition-colors shadow-lg hover:shadow-emerald-200">
                            Save Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Doctor Appointments</h1>
                <p class="mt-3 text-lg text-slate-500 max-w-2xl">Manage your patients' medical schedules with ease and precision.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('caregiver.dashboard') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-slate-300 shadow-sm text-base font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-all duration-200">
                    Back to Dashboard
                </a>
                @if($patients->count() > 0)
                    <button onclick="document.getElementById('create-appointment-modal').classList.remove('hidden')" 
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-xl text-white bg-slate-900 hover:bg-slate-800 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Appointment
                    </button>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 rounded-2xl bg-emerald-50 border border-emerald-100 p-4 flex items-center shadow-sm">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($patients->isEmpty())
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-16 text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3">No Patients Linked</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto text-lg">Connect with patients to start managing their appointments and healthcare journey.</p>
                <a href="{{ route('caregiver.dashboard') }}" class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-2xl text-white bg-slate-900 hover:bg-slate-800 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Go to Dashboard
                </a>
            </div>
        @else
            <!-- Calendar Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 mb-10">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-slate-900">Calendar View</h2>
                    <div class="flex space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Scheduled
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Completed
                        </span>
                    </div>
                </div>
                <div id="calendar" class="fc-premium"></div>
            </div>

            <!-- Upcoming Appointments List -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-white flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-slate-900">Upcoming Appointments</h2>
                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-600 text-sm font-bold">
                        {{ $appointments->where('status', 'scheduled')->count() }} Scheduled
                    </span>
                </div>
                
                <div class="divide-y divide-slate-100">
                    @forelse($appointments->where('status', 'scheduled')->sortBy('appointment_date') as $appointment)
                        <div class="p-8 hover:bg-slate-50 transition-colors duration-150 group">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                <!-- Date Badge -->
                                <div class="flex-shrink-0">
                                    <div class="w-20 h-20 rounded-2xl bg-indigo-50 border border-indigo-100 flex flex-col items-center justify-center text-indigo-900">
                                        <span class="text-sm font-bold uppercase tracking-wider">{{ $appointment->appointment_date->format('M') }}</span>
                                        <span class="text-3xl font-extrabold">{{ $appointment->appointment_date->format('j') }}</span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="flex-grow min-w-0">
                                    <div class="flex flex-wrap items-center gap-3 mb-2">
                                        <h3 class="text-xl font-bold text-slate-900 truncate">{{ $appointment->doctor_name }}</h3>
                                        @if($appointment->specialization)
                                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wide">
                                                {{ $appointment->specialization }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 mt-2">
                                        <div class="flex items-center text-slate-600">
                                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="font-medium text-slate-900 mr-2">Patient:</span>
                                            {{ $appointment->user->name }}
                                        </div>
                                        <div class="flex items-center text-slate-600">
                                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $appointment->appointment_date->format('g:i A') }}
                                        </div>
                                        @if($appointment->clinic_name)
                                            <div class="flex items-center text-slate-600 md:col-span-2">
                                                <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $appointment->clinic_name }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3 mt-4 lg:mt-0 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('caregiver.appointments.edit', $appointment) }}" 
                                       class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-100 transition-all shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('caregiver.appointments.destroy', $appointment) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this appointment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold hover:bg-red-50 hover:text-red-700 hover:border-red-100 transition-all shadow-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-medium text-slate-900">No upcoming appointments</p>
                            <p class="text-slate-500 mt-1">Schedule a new appointment to see it here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Create Appointment Modal -->
@if($patients->count() > 0)
<div id="create-appointment-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('create-appointment-modal').classList.add('hidden')"></div>

    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl sm:w-full border border-slate-100">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h3 class="text-2xl font-bold text-slate-900" id="modal-title">New Appointment</h3>
                <button onclick="document.getElementById('create-appointment-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('caregiver.appointments.create', ['user' => 'placeholder']) }}" method="GET" id="create-appointment-form" class="p-8">
                <div class="space-y-8">
                    <div>
                        <label for="patient-search" class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Select Patient</label>
                        
                        <!-- Search Input -->
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="patient-search" autocomplete="off" placeholder="Search by name or email..." 
                                   class="block w-full pl-12 pr-4 py-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 focus:bg-white transition-all font-medium text-lg">
                        </div>

                        <input type="hidden" name="patient_id" id="patient_id" required>

                        <!-- Patient List -->
                        <div class="border border-slate-100 rounded-2xl bg-white max-h-72 overflow-y-auto mt-4 shadow-lg custom-scrollbar hidden" id="patient-list">
                            @foreach($patients as $patient)
                                <button type="button" onclick="selectPatient({{ $patient->id }}, '{{ addslashes($patient->name) }}', '{{ addslashes($patient->email) }}')" 
                                        class="patient-option w-full text-left px-5 py-4 hover:bg-slate-50 transition-all border-b border-slate-50 last:border-0 group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-lg group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            {{ substr($patient->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="patient-name font-bold text-slate-900 text-lg group-hover:text-indigo-900 transition-colors">{{ $patient->name }}</p>
                                            <p class="patient-email text-sm text-slate-500">{{ $patient->email }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-300 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </button>
                            @endforeach
                            <div id="no-patients-found" class="hidden px-6 py-10 text-center text-slate-500">
                                <p class="text-base">No patients found matching your search.</p>
                            </div>
                        </div>

                        <!-- Selected Patient Display -->
                        <div id="selected-patient" class="hidden mt-4 p-4 bg-indigo-50 border border-indigo-100 rounded-2xl">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                        <span id="selected-initial"></span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 text-lg" id="selected-name"></p>
                                        <p class="text-sm text-indigo-700 font-medium" id="selected-email"></p>
                                    </div>
                                </div>
                                <button type="button" onclick="clearSelection()" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm px-4 py-2 hover:bg-indigo-100 rounded-lg transition-colors">
                                    Change
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    Next, you'll enter the appointment details including doctor info, clinic location, and time.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex gap-4">
                    <button type="button" onclick="document.getElementById('create-appointment-modal').classList.add('hidden')"
                            class="flex-1 px-6 py-4 border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 hover:text-slate-800 transition-all text-base">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-6 py-4 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 shadow-lg hover:shadow-xl transition-all text-base">
                        Continue to Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<style>
    /* Custom Scrollbar for patient list */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Premium Calendar Overrides */
    .fc-premium {
        font-family: inherit;
         --fc-border-color: #e2e8f0;
         --fc-button-text-color: #475569;
         --fc-button-bg-color: #fff;
         --fc-button-border-color: #e2e8f0;
         --fc-button-hover-bg-color: #f8fafc;
         --fc-button-hover-border-color: #cbd5e1;
         --fc-button-active-bg-color: #0f172a;
         --fc-button-active-border-color: #0f172a;
         --fc-button-active-text-color: #fff;
         --fc-event-bg-color: #3b82f6;
         --fc-event-border-color: #3b82f6;
         --fc-event-text-color: #fff;
         --fc-today-bg-color: #f8fafc;
    }

    /* Toolbar & Buttons */
    .fc-premium .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
    }
    .fc-premium .fc-button-primary {
        background-color: var(--fc-button-bg-color);
        border-color: var(--fc-button-border-color);
        color: var(--fc-button-text-color);
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        border-radius: 0.75rem;
        text-transform: capitalize;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s;
        outline: none !important;
    }
    .fc-premium .fc-button-primary:hover {
        background-color: var(--fc-button-hover-bg-color);
        border-color: var(--fc-button-hover-border-color);
        color: #1e293b;
    }
    .fc-premium .fc-button-primary:not(:disabled).fc-button-active, 
    .fc-premium .fc-button-primary:not(:disabled):active {
        background-color: var(--fc-button-active-bg-color);
        border-color: var(--fc-button-active-border-color);
        color: var(--fc-button-active-text-color);
    }
    
    /* Headers (Month, Week, Day) */
    .fc-premium .fc-col-header-cell-cushion {
        font-weight: 700;
        color: #334155;
        padding: 12px 0;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        text-decoration: none;
    }
    .fc-premium .fc-daygrid-day-number {
        font-weight: 600;
        color: #64748b;
        padding: 8px;
        text-decoration: none;
    }
    
    /* Events */
    .fc-premium .fc-event {
        border: none;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 2px;
    }

    /* TimeGrid (Week/Day) Specifics */
    .fc-premium .fc-timegrid-slot-label-cushion {
        font-weight: 600;
        color: #94a3b8;
        font-size: 0.85rem;
        text-transform: lowercase;
    }
    .fc-premium .fc-timegrid-axis-cushion {
        color: #94a3b8;
        font-size: 0.75rem;
    }
    .fc-premium .fc-timegrid-event {
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* General Border Softening */
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc-theme-standard .fc-scrollgrid {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        overflow: hidden;
    }
    
    /* View Specific overrides to ensure consistency */
    .fc-view-harness {
        background-color: #fff;
    }
</style>

<!-- Appointment Details Modal -->
<div id="appointment-details-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="details-modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeDetailsModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-slate-100">
            <div class="absolute top-0 right-0 pt-4 pr-4">
                <button type="button" onclick="closeDetailsModal()" class="bg-white rounded-full p-2 text-slate-400 hover:text-slate-500 hover:bg-slate-100 transition-all focus:outline-none">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900" id="modal-doctor-name"></h3>
                        <p class="text-sm font-medium text-slate-500" id="modal-specialization"></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 mt-0.5">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Patient</p>
                            <p class="text-sm text-slate-600" id="modal-patient-name"></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 mt-0.5">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Date & Time</p>
                            <p class="text-sm text-slate-600" id="modal-datetime"></p>
                        </div>
                    </div>

                    <div class="flex items-start" id="modal-clinic-container">
                        <div class="flex-shrink-0 w-6 mt-0.5">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-slate-900">Clinic</p>
                            <p class="text-sm text-slate-600" id="modal-clinic-name"></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-2 pt-4 border-t border-slate-100">
                        <span id="modal-status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a id="modal-edit-link" href="#" class="flex-1 px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-100 transition-all text-center">
                        Edit
                    </a>
                    <!-- Since delete requires a form, we might just link to edit, or keep it simple. Let's just have Edit for now to keep modal clean, or perform delete via edit page. -->
                    <!-- Alternatively we can add a close button -->
                    <button onclick="closeDetailsModal()" class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard', // Use standard theme which we override
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                day: 'Day'
            },
            navLinks: true, // can click day/week names to navigate views
            nowIndicator: false, // Removed per user request (red line)
            dayMaxEvents: true, // allow "more" link when too many events
            events: '{{ route("caregiver.appointments.calendar") }}',
            eventDidMount: function(info) {
                // Tooltip or styling enhancements could go here
                info.el.title = info.event.title + ' (' + info.event.extendedProps.status + ')';
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
                
                // Populate Modal Data
                const props = info.event.extendedProps;
                document.getElementById('modal-doctor-name').textContent = info.event.title.split(' - ')[0]; // Assuming title is "Doctor - Patient"
                document.getElementById('modal-patient-name').textContent = props.patient;
                document.getElementById('modal-specialization').textContent = props.specialization || 'General Practice';
                
                // Format Date
                const date = info.event.start;
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('modal-datetime').textContent = date.toLocaleDateString('en-US', dateOptions);

                // Clinic
                const clinicContainer = document.getElementById('modal-clinic-container');
                if (props.clinic) {
                    document.getElementById('modal-clinic-name').textContent = props.clinic;
                    clinicContainer.classList.remove('hidden');
                } else {
                    clinicContainer.classList.add('hidden');
                }

                // Status Badge
                const badge = document.getElementById('modal-status-badge');
                badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide';
                if (props.status === 'completed') {
                    badge.classList.add('bg-emerald-50', 'text-emerald-700');
                    badge.textContent = 'Completed';
                } else if (props.status === 'cancelled') {
                    badge.classList.add('bg-red-50', 'text-red-700');
                    badge.textContent = 'Cancelled';
                } else {
                    badge.classList.add('bg-blue-50', 'text-blue-700');
                    badge.textContent = 'Scheduled';
                }

                // Edit Link
                const editLink = document.getElementById('modal-edit-link');
                editLink.href = `/caregiver/appointments/${info.event.id}/edit`;

                // Show Modal
                document.getElementById('appointment-details-modal').classList.remove('hidden');
            },
            // Enhance time grid styling dynamically if needed, mostly handled by CSS
        });
        calendar.render();
    }

    // Modal Close Function
    window.closeDetailsModal = function() {
        document.getElementById('appointment-details-modal').classList.add('hidden');
    };

    // Patient Search Logic

    const patientSearch = document.getElementById('patient-search');
    const patientList = document.getElementById('patient-list');
    
    if (patientSearch && patientList) {
        // Always show the list initially if there are patients
        patientList.classList.remove('hidden');

        patientSearch.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const patients = document.querySelectorAll('.patient-option');
            let matchCount = 0;
            
            // If we hid it manually, show it again on input
            patientList.classList.remove('hidden');

            patients.forEach(patient => {
                const name = patient.querySelector('.patient-name').innerText.toLowerCase();
                const email = patient.querySelector('.patient-email').innerText.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    patient.classList.remove('hidden');
                    matchCount++;
                } else {
                    patient.classList.add('hidden');
                }
            });

            const noPatientsMsg = document.getElementById('no-patients-found');
            if (matchCount === 0 && patients.length > 0) {
                noPatientsMsg.classList.remove('hidden');
            } else {
                noPatientsMsg.classList.add('hidden');
            }
        });
    }

    // Select Patient Function
    window.selectPatient = function(id, name, email) {
        document.getElementById('patient_id').value = id;
        document.getElementById('selected-name').textContent = name;
        document.getElementById('selected-email').textContent = email;
        document.getElementById('selected-initial').textContent = name ? name.charAt(0).toUpperCase() : '';
        
        // Hide list after selection
        patientList.classList.add('hidden');
        document.getElementById('selected-patient').classList.remove('hidden');
        
        // Clear search
        patientSearch.value = '';
        
        // Update UI state
        document.querySelector('label[for="patient-search"]').innerText = 'Selected Patient';
        // Hide the search input container
        patientSearch.closest('.relative').style.display = 'none';
        
        // Show the "Next" button section prominently? 
        // It is already visible.
    };

    // Clear Selection Function
    window.clearSelection = function() {
        document.getElementById('patient_id').value = '';
        
        // Reset UI
        document.getElementById('selected-patient').classList.add('hidden');
        const searchContainer = patientSearch.closest('.relative');
        searchContainer.style.display = 'block';
        document.querySelector('label[for="patient-search"]').innerText = 'Select Patient';
        
        // Show list again
        patientList.classList.remove('hidden');
        
        // Reset search filter
        patientSearch.value = '';
        document.querySelectorAll('.patient-option').forEach(patient => {
            patient.classList.remove('hidden');
        });
        document.getElementById('no-patients-found').classList.add('hidden');
        
        // Focus search for better UX
        patientSearch.focus();
    };

    // Handle create appointment form
    const createForm = document.getElementById('create-appointment-form');
    if (createForm) {
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(createForm);
            const patientId = formData.get('patient_id');
            
            if (!patientId) {
                alert('Please select a patient to continue.');
                return;
            }

            // Redirect to the create page with the selected patient
            window.location.href = `/caregiver/appointments/${patientId}/create`;
        });
    }
});
</script>
@endsection

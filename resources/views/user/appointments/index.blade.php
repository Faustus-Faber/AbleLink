@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">My Doctor Appointments</h1>
            <p class="text-slate-600">View your scheduled and past medical appointments</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dashboard') }}" 
               class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-all">
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div id="calendar" class="p-6 fc-premium"></div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="text-xl font-bold text-slate-900">Appointment History</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($appointments->sortByDesc('appointment_date') as $appointment)
                <div class="p-6 hover:bg-slate-50 transition-colors">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-bold text-slate-900">{{ $appointment->doctor_name }}</h3>
                                @if($appointment->specialization)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded">{{ $appointment->specialization }}</span>
                                @endif
                                <span class="px-2 py-1 
                                    @if($appointment->status === 'scheduled') bg-green-50 text-green-700
                                    @elseif($appointment->status === 'completed') bg-gray-50 text-gray-700
                                    @elseif($appointment->status === 'cancelled') bg-red-50 text-red-700
                                    @endif
                                    text-xs font-bold rounded capitalize">
                                    {{ $appointment->status }}
                                </span>
                            </div>
                            <p class="text-slate-600 mb-1">
                                <span class="font-semibold">Date & Time:</span> 
                                {{ $appointment->appointment_date->format('F j, Y \a\t g:i A') }}
                            </p>
                            @if($appointment->clinic_name)
                                <p class="text-slate-600">
                                    <span class="font-semibold">Clinic:</span> {{ $appointment->clinic_name }}
                                </p>
                            @endif
                            @if($appointment->caregiver)
                                <p class="text-slate-500 text-sm mt-1">
                                    Scheduled by: {{ $appointment->caregiver->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-500">
                    <p>No appointments found.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
<style>
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
    
    .fc-premium .fc-event {
        border: none;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        margin-bottom: 2px;
    }

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

    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc-theme-standard .fc-scrollgrid {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        overflow: hidden;
    }
    
    .fc-view-harness {
        background-color: #fff;
    }
</style>

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
                            <p class="text-sm font-bold text-slate-900">Caregiver</p>
                            <p class="text-sm text-slate-600" id="modal-caregiver-name"></p>
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
                    <button onclick="closeDetailsModal()" class="flex-1 px-4 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition-all">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'standard',
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
            navLinks: true, 
            nowIndicator: false, 
            dayMaxEvents: true,
            events: '{{ route("user.appointments.calendar") }}',
            eventDidMount: function(info) {
                info.el.title = info.event.title + ' (' + info.event.extendedProps.status + ')';
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                
                const props = info.event.extendedProps;
                document.getElementById('modal-doctor-name').textContent = info.event.title; 
                document.getElementById('modal-caregiver-name').textContent = props.caregiver || 'Self/None';
                document.getElementById('modal-specialization').textContent = props.specialization || 'General Practice';
                
                const date = info.event.start;
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                document.getElementById('modal-datetime').textContent = date.toLocaleDateString('en-US', dateOptions);

                const clinicContainer = document.getElementById('modal-clinic-container');
                if (props.clinic) {
                    document.getElementById('modal-clinic-name').textContent = props.clinic;
                    clinicContainer.classList.remove('hidden');
                } else {
                    clinicContainer.classList.add('hidden');
                }

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

                document.getElementById('appointment-details-modal').classList.remove('hidden');
            }
        });
        calendar.render();
    }

    window.closeDetailsModal = function() {
        document.getElementById('appointment-details-modal').classList.add('hidden');
    };
});
</script>
@endsection

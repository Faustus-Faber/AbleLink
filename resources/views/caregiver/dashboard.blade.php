@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<section 
    id="caregiver-dashboard-container"
    class="container mx-auto px-6 py-8"
    x-data="caregiverDashboardHandler()"
>
    <section 
        id="caregiver-dashboard-grid"
        class="grid grid-cols-1 lg:grid-cols-4 gap-8"
    >
        
        <aside 
            id="caregiver-sidebar" 
            class="lg:col-span-1"
        >
            <article class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sticky top-24">
                <header 
                    id="sidebar-user-info-header" 
                    class="text-center mb-6"
                >
                    @if(Auth::user()->profile && Auth::user()->profile->avatar)
                        <figure class="w-24 h-24 mx-auto rounded-full mb-4 p-1 bg-gradient-to-br from-blue-400 to-purple-500">
                             <img 
                                id="sidebar-user-avatar-image"
                                src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" 
                                alt="Profile" 
                                class="w-full h-full rounded-full object-cover border-2 border-white"
                            >
                        </figure>
                    @else
                        <figure class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-blue-400 to-purple-500 shadow-inner mb-4 flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </figure>
                    @endif
                    <h2 
                        id="sidebar-user-name-heading" 
                        class="text-xl font-bold text-slate-800"
                    >
                        {{ Auth::user()->name ?? 'Caregiver' }}
                    </h2>
                    <p 
                        id="sidebar-user-email-text" 
                        class="text-slate-500 text-sm"
                    >
                        {{ Auth::user()->email }}
                    </p>
                    <span class="mt-2 inline-block px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wide">
                        Caregiver
                    </span>
                </header>

                <nav 
                    id="caregiver-dashboard-navigation-menu" 
                    class="space-y-2"
                >
                    <a 
                        id="nav-link-my-patients-btn"
                        href="{{ route('caregiver.dashboard') }}" 
                        class="flex items-center w-full px-4 py-3 rounded-xl bg-blue-50 text-blue-700 font-bold transition-all"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        My Patients
                    </a>
                    <a 
                        id="nav-link-appointments-btn"
                        href="{{ route('caregiver.appointments.index') }}" 
                        class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Appointments
                    </a>
                    <a 
                        id="nav-link-profile-btn"
                        href="{{ route('profile.show') }}" 
                        class="flex items-center w-full px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 font-medium transition-all"
                    >
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        My Profile
                    </a>
                </nav>

                <footer class="mt-8 pt-6 border-t border-slate-100">
                    <form 
                        id="caregiver-logout-form-element" 
                        action="{{ route('logout') }}" 
                        method="POST"
                    >
                        @csrf
                        <button 
                            id="caregiver-logout-submit-btn"
                            type="submit" 
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-red-50 to-red-100 text-red-600 font-bold hover:from-red-100 hover:to-red-200 transition-all flex justify-center items-center"
                        >
                            Logout
                        </button>
                    </form>
                </footer>
            </article>
        </aside>

        <section 
            id="caregiver-main-dashboard-content" 
            class="lg:col-span-3"
        >
            @if(isset($sosAlerts))
                @if($sosAlerts->isNotEmpty())
                    <section 
                        id="caregiver-sos-alerts-overview-section" 
                        class="bg-red-50 border border-red-100 rounded-3xl p-8 mb-8 shadow-sm"
                    >
                        <header class="flex items-start justify-between gap-4 mb-5">
                            <article>
                                <h3 class="text-2xl font-extrabold text-red-800">Emergency SOS Alerts</h3>
                                <p class="text-red-700/80 font-medium">One or more of your linked patients triggered an SOS.</p>
                            </article>
                            <span class="px-4 py-2 rounded-full bg-white/70 text-red-700 text-sm font-bold border border-red-100">
                                {{ $sosAlerts->count() }} Active
                            </span>
                        </header>

                        <ul 
                            id="caregiver-sos-alerts-list-container" 
                            class="space-y-4"
                        >
                            @foreach($sosAlerts as $sosEvent)
                                <li id="caregiver-sos-alert-item-{{ $sosEvent->id }}">
                                    <article class="bg-white rounded-2xl border border-red-100 p-6">
                                        <section class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                            <section class="min-w-0">
                                                <p class="text-xs font-bold text-red-500 uppercase tracking-wider">SOS</p>
                                                <p class="text-lg font-extrabold text-slate-900 truncate">
                                                    {{ $sosEvent->user?->name ?? 'Unknown patient' }}
                                                </p>
                                                <p class="text-sm text-slate-600">
                                                    <span class="font-bold">Time:</span> {{ $sosEvent->created_at?->format('M j, Y g:i A') }}
                                                </p>
                                                @if($sosEvent->user)
                                                    @if($sosEvent->user->profile)
                                                        @if($sosEvent->user->profile->emergency_contact_phone)
                                                            <p class="text-sm text-slate-600 mt-1">
                                                                <span class="font-bold">Emergency contact:</span>
                                                                {{ $sosEvent->user->profile->emergency_contact_name ?? 'Contact' }}
                                                                ({{ $sosEvent->user->profile->emergency_contact_phone }})
                                                            </p>
                                                        @endif
                                                    @endif
                                                @endif

                                                <section class="mt-3 text-sm text-slate-700">
                                                    @if($sosEvent->latitude !== null)
                                                        @if($sosEvent->longitude !== null)
                                                            <p>
                                                                <span class="font-bold">Location:</span>
                                                                {{ $sosEvent->latitude }}, {{ $sosEvent->longitude }}
                                                                @if($sosEvent->accuracy_m)
                                                                    <span class="text-slate-500">(±{{ $sosEvent->accuracy_m }}m)</span>
                                                                @endif
                                                            </p>
                                                            <a 
                                                                id="caregiver-view-sos-map-link-{{ $sosEvent->id }}"
                                                                class="inline-flex items-center mt-2 text-blue-700 font-bold hover:underline"
                                                                target="_blank"
                                                                href="https://www.google.com/maps?q={{ $sosEvent->latitude }},{{ $sosEvent->longitude }}"
                                                            >
                                                                View on Map
                                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7v7m0-7L10 14m-4 0h4v4H6v-4z"></path></svg>
                                                            </a>
                                                        @endif
                                                    @elseif($sosEvent->address)
                                                        <p><span class="font-bold">Address:</span> {{ $sosEvent->address }}</p>
                                                    @else
                                                        <p class="text-slate-500 italic">No location provided.</p>
                                                    @endif

                                                    @if($sosEvent->notes)
                                                        <p class="mt-2"><span class="font-bold">Notes:</span> {{ $sosEvent->notes }}</p>
                                                    @endif
                                                </section>
                                            </section>

                                            <aside class="flex-shrink-0">
                                                @if($sosEvent->user)
                                                    <form 
                                                        id="caregiver-resolve-sos-form-{{ $sosEvent->id }}"
                                                        action="{{ route('caregiver.sos.resolve', $sosEvent->id) }}" 
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        <button 
                                                            id="caregiver-resolve-sos-submit-btn-{{ $sosEvent->id }}"
                                                            type="submit" 
                                                            class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 transition-all shadow-md hover:shadow-lg"
                                                        >
                                                            Marked Safe
                                                        </button>
                                                    </form>
                                                @endif
                                            </aside>
                                        </section>
                                    </article>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            @endif

            <section 
                id="caregiver-dashboard-statistics-grid" 
                class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8"
            >
                 <article 
                    id="caregiver-stat-card-active-patients-container" 
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between"
                 >
                      <section>
                          <p class="text-slate-500 font-bold text-sm uppercase tracking-wide">Active Patients</p>
                          <h3 class="text-3xl font-extrabold text-slate-800 mt-1">
                              @if(isset($patients))
                                {{ count($patients) }}
                              @else
                                0
                              @endif
                          </h3>
                      </section>
                      <figure class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                      </figure>
                 </article>
                 <article 
                    id="caregiver-stat-card-pending-requests-container" 
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between"
                 >
                      <section>
                          <p class="text-slate-500 font-bold text-sm uppercase tracking-wide">Pending Requests</p>
                          <h3 class="text-3xl font-extrabold text-slate-800 mt-1">
                              @if(isset($pendingRequestsCount))
                                {{ $pendingRequestsCount }}
                              @else
                                0
                              @endif
                          </h3>
                      </section>
                      <figure class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      </figure>
                 </article>
                 <article 
                    id="caregiver-stat-card-appointments-summary-container" 
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between"
                 >
                      <section>
                          <p class="text-slate-500 font-bold text-sm uppercase tracking-wide">Appointments</p>
                          <h3 class="text-3xl font-extrabold text-slate-800 mt-1">
                              @if(isset($appointmentsCount))
                                {{ $appointmentsCount }}
                              @else
                                0
                              @endif
                          </h3>
                      </section>
                      <figure class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                      </figure>
                 </article>
            </section>

            <section 
                id="caregiver-connect-patient-form-section" 
                class="bg-indigo-900 rounded-2xl p-8 mb-8 text-white shadow-xl relative overflow-hidden border border-indigo-800"
            >
                <figure class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-64 h-64 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                </figure>
                
                <article class="relative z-10">
                    <h3 class="text-2xl font-bold mb-2 text-white tracking-tight">Connect with a New Patient</h3>
                    <p class="text-indigo-200 mb-6 max-w-xl">Enter the email address of the patient you wish to assist. They will receive a notification to approve your request.</p>

                    <form 
                        id="caregiver-connect-patient-form-element" 
                        action="{{ route('caregiver.request') }}" 
                        method="POST"
                    >
                        <fieldset class="flex flex-col md:flex-row gap-3">
                            @csrf
                            <input 
                                id="caregiver-patient-email-input-field"
                                type="email" 
                                name="email" 
                                required 
                                class="flex-grow px-5 py-3.5 rounded-lg bg-indigo-800/50 border border-indigo-700 text-white placeholder-indigo-300 focus:ring-2 focus:ring-white/20 focus:border-white/50 outline-none transition-all"
                                placeholder="Enter patient email address"
                            >
                            <button 
                                id="caregiver-submit-patient-request-submit-btn"
                                type="submit" 
                                class="px-6 py-3.5 rounded-lg bg-white text-indigo-900 font-bold hover:bg-indigo-50 transition-colors shadow-sm"
                            >
                                Send Request
                            </button>
                        </fieldset>
                    </form>
                    @if($errors->has('email'))
                        <p 
                            id="caregiver-connect-email-error-message" 
                            class="text-red-300 mt-3 text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                    @if(session('success'))
                        <p 
                            id="caregiver-connect-success-message" 
                            class="text-emerald-300 mt-3 text-sm font-medium flex items-center"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </p>
                    @endif
                </article>
            </section>

            @if(isset($patients))
                @if($patients->isEmpty())
                    <section 
                        id="caregiver-empty-patients-illustration-section" 
                        class="text-center py-16 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50/50"
                    >
                        <figure class="text-slate-300 mb-4">
                            <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </figure>
                        <h3 class="text-xl font-bold text-slate-700">No patients linked yet</h3>
                        <p class="text-slate-500">Send a connection request to get started.</p>
                    </section>
                @else
                    <section 
                        id="caregiver-patient-list-overview-section" 
                        class="space-y-6"
                    >
                        <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="text-xl font-bold text-slate-800">Linked Patients</h3>
                            <section class="relative w-full sm:w-72">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </span>
                                <input 
                                    id="caregiver-patient-search-input-field"
                                    type="text" 
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-lg text-sm bg-white placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium shadow-sm" 
                                    placeholder="Search patients..."
                                >
                            </section>
                        </header>

                        <article 
                            id="caregiver-patient-list-scrollable-container"
                            class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col max-h-[600px]"
                        >
                            <ul 
                                id="caregiver-patient-items-list" 
                                class="overflow-y-auto custom-scrollbar p-1"
                            >
                                @foreach($patients as $currentPatient)
                                    <li 
                                        id="caregiver-patient-list-item-{{ $currentPatient->id }}" 
                                        class="patient-item p-4 border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition-all flex flex-col md:flex-row md:items-center justify-between gap-4 group"
                                        data-name="{{ strtolower($currentPatient->name) }}"
                                        data-email="{{ strtolower($currentPatient->email) }}"
                                    >
                                        <section class="flex items-center gap-4 min-w-0 flex-1">
                                            @if($currentPatient->profile && $currentPatient->profile->avatar)
                                                <figure class="h-12 w-12 rounded-xl border border-slate-200 overflow-hidden flex-shrink-0">
                                                    <img 
                                                        src="{{ asset('storage/' . $currentPatient->profile->avatar) }}" 
                                                        alt="{{ $currentPatient->name }}" 
                                                        class="w-full h-full object-cover"
                                                    >
                                                </figure>
                                            @else
                                                <figure class="h-12 w-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0">
                                                    {{ substr($currentPatient->name, 0, 1) }}
                                                </figure>
                                            @endif
                                            <article class="min-w-0">
                                                <header class="flex items-center gap-2">
                                                    <h3 class="font-bold text-slate-900 text-base truncate patient-name">
                                                        {{ $currentPatient->name }}
                                                    </h3>
                                                    @if($currentPatient->pivot->status === 'active')
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-100">
                                                            Pending
                                                        </span>
                                                    @endif
                                                </header>
                                                <p class="text-sm text-slate-500 truncate patient-email">
                                                    {{ $currentPatient->email }}
                                                </p>
                                            </article>
                                        </section>

                                        <nav class="flex items-center gap-3 flex-wrap md:flex-nowrap justify-end">
                                            @if($currentPatient->pivot->status === 'active')
                                                <a 
                                                    id="caregiver-manage-patient-profile-link-{{ $currentPatient->id }}"
                                                    href="{{ route('caregiver.patient.edit', $currentPatient) }}" 
                                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition-all shadow-sm whitespace-nowrap"
                                                >
                                                    Manage Profile
                                                </a>
                                                <a 
                                                    id="caregiver-view-patient-health-link-{{ $currentPatient->id }}"
                                                    href="{{ route('caregiver.patient.health', $currentPatient) }}" 
                                                    class="inline-flex items-center px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 text-sm font-bold hover:bg-slate-50 hover:text-slate-900 transition-all whitespace-nowrap"
                                                >
                                                    Health
                                                </a>
                                            @else
                                                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-2 rounded-lg border border-slate-200">
                                                    Waiting Approval
                                                </span>
                                            @endif
                                            
                                            <button 
                                                id="caregiver-unlink-patient-trigger-btn-{{ $currentPatient->id }}"
                                                type="button"
                                                @click="openUnlinkModal({{ $currentPatient->id }}, '{{ addslashes($currentPatient->name) }}')"
                                                class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" 
                                                title="Remove Connection"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                            
                                            <form 
                                                id="caregiver-unlink-patient-request-form-{{ $currentPatient->id }}"
                                                action="{{ route('caregiver.patient.unlink', $currentPatient) }}" 
                                                method="POST"
                                                class="hidden"
                                            >
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </nav>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <aside 
                                id="caregiver-no-patients-search-results-feedback" 
                                class="hidden p-8 text-center text-slate-500"
                            >
                                <p>No patients found matching your search.</p>
                            </aside>
                        </article>
                    </section>
                @endif
            @endif

        </section>
    </section>

    <aside 
        id="caregiver-unlink-confirmation-modal-container"
        x-show="isModalOpen"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeUnlinkModal()"
    >
        <section 
            id="caregiver-unlink-modal-overlay-backdrop"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
            @click="closeUnlinkModal()"
            x-show="isModalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        ></section>

        <section 
            id="caregiver-unlink-modal-content-positioner"
            class="flex min-h-full items-center justify-center p-4"
            x-show="isModalOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <article 
                id="caregiver-unlink-modal-dialog-box"
                class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 border border-slate-100"
                @click.stop
            >
                <figure class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 mb-4">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </figure>

                <header class="text-center mb-4">
                    <h3 class="text-xl font-bold text-slate-900">Remove Connection</h3>
                    <p class="text-slate-500 mt-2">
                        Are you sure you want to remove 
                        <span 
                            class="font-semibold text-slate-700" 
                            x-text="patientName"
                        ></span>?
                    </p>
                    <p class="text-sm text-slate-400 mt-1">This action cannot be undone.</p>
                </header>

                <footer class="flex gap-3 mt-6">
                    <button 
                        id="caregiver-unlink-modal-cancel-action-btn"
                        type="button"
                        @click="closeUnlinkModal()"
                        class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        id="caregiver-unlink-modal-confirm-action-btn"
                        type="button"
                        @click="confirmUnlink()"
                        class="flex-1 px-4 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors shadow-md"
                    >
                        Remove
                    </button>
                </footer>
            </article>
        </section>
    </aside>
</section>

<script>
    function caregiverDashboardHandler() {
        return {
            isModalOpen: false,
            patientId: null,
            patientName: '',

            openUnlinkModal(id, name) {
                this.patientId = id;
                this.patientName = name;
                this.isModalOpen = true;
            },

            closeUnlinkModal() {
                this.isModalOpen = false;
                this.patientId = null;
                this.patientName = '';
            },

            confirmUnlink() {
                if (this.patientId) {
                    var formElement = document.getElementById('caregiver-unlink-patient-request-form-' + this.patientId);
                    if (formElement) {
                        formElement.submit();
                    }
                }
                this.closeUnlinkModal();
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var searchInputElement = document.getElementById('caregiver-patient-search-input-field');
        if (searchInputElement) {
            searchInputElement.addEventListener('input', function(inputEvent) {
                var searchTerm = inputEvent.target.value.toLowerCase();
                var patientItems = document.querySelectorAll('.patient-item');
                var visibleCount = 0;

                patientItems.forEach(function(patientItem) {
                    var patientName = patientItem.dataset.name;
                    var patientEmail = patientItem.dataset.email;
                    
                    if (patientName.includes(searchTerm) || patientEmail.includes(searchTerm)) {
                        patientItem.style.display = '';
                        visibleCount++;
                    } else {
                        patientItem.style.display = 'none';
                    }
                });

                var emptyStateElement = document.getElementById('caregiver-no-patients-search-results-feedback');
                if (emptyStateElement) {
                    if (visibleCount === 0 && patientItems.length > 0) {
                        emptyStateElement.classList.remove('hidden');
                    } else {
                        emptyStateElement.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>

@endsection

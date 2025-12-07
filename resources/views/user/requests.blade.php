@extends('layouts.app')

@section('content')
<section 
    id="user-connection-requests-container" 
    class="min-h-screen bg-slate-50/50 font-sans selection:bg-indigo-100 selection:text-indigo-700"
>
    <section 
        id="user-connection-requests-inner-wrapper"
        class="max-w-2xl mx-auto px-6 py-16"
    >
        <header 
            id="user-connection-requests-page-header"
            class="text-center mb-16"
        >
            <h1 
                id="user-connection-requests-main-title"
                class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3"
            >
                Connection Requests
            </h1>
            <p 
                id="user-connection-requests-page-description"
                class="text-base text-slate-500 font-medium max-w-sm mx-auto leading-relaxed"
            >
                Review and manage incoming requests from caregivers.
            </p>
        </header>

        @if(session('success'))
            <article 
                id="user-connection-requests-success-alert"
                class="mb-8 px-6 py-4 rounded-2xl bg-emerald-50/50 border border-emerald-100/50 flex items-center gap-4 text-emerald-800 text-sm font-bold shadow-sm backdrop-blur-sm"
            >
                <figure 
                    id="user-success-alert-icon-wrapper"
                    class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </figure>
                <p id="user-success-alert-message-text">
                    {{ session('success') }}
                </p>
            </article>
        @endif

        @if(session('error'))
            <article 
                id="user-connection-requests-error-alert"
                class="mb-8 px-6 py-4 rounded-2xl bg-red-50/50 border border-red-100/50 flex items-center gap-4 text-red-800 text-sm font-bold shadow-sm backdrop-blur-sm"
            >
                <figure 
                    id="user-error-alert-icon-wrapper"
                    class="flex-shrink-0 w-6 h-6 rounded-full bg-red-100 flex items-center justify-center"
                >
                    <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </figure>
                <p id="user-error-alert-message-text">
                    {{ session('error') }}
                </p>
            </article>
        @endif

        @if($requests->isEmpty())
            <section 
                id="user-connection-no-requests-empty-state"
                class="text-center py-24"
            >
                <figure 
                    id="user-no-requests-illustration-box"
                    class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-6 transform rotate-3"
                >
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </figure>
                <h3 
                    id="user-no-requests-heading"
                    class="text-lg font-bold text-slate-900 mb-2"
                >
                    No Pending Requests
                </h3>
                <p 
                    id="user-no-requests-subtext"
                    class="text-slate-500 text-sm font-medium"
                >
                    You're all caught up!
                </p>
            </section>
        @else
            <section 
                id="user-connection-requests-list-wrapper"
                class="space-y-4"
            >
                @foreach($requests as $caregiver)
                    <article 
                        id="user-caregiver-request-card-{{ $caregiver->id }}"
                        class="group bg-white rounded-[1.5rem] p-5 shadow-[0_2px_12px_-4px_rgba(6,81,237,0.06)] border border-slate-100 hover:border-indigo-100 hover:shadow-[0_8px_30px_-6px_rgba(6,81,237,0.1)] transition-all duration-300"
                    >
                        <section 
                            id="user-caregiver-request-card-content-{{ $caregiver->id }}"
                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-6"
                        >
                            <section 
                                id="user-caregiver-info-section-{{ $caregiver->id }}"
                                class="flex items-center gap-5"
                            >
                                <figure 
                                    id="user-caregiver-avatar-initials-{{ $caregiver->id }}"
                                    class="h-14 w-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-700 font-bold text-xl shadow-sm border border-slate-100"
                                >
                                    {{ substr($caregiver->name, 0, 1) }}
                                </figure>
                                <section id="user-caregiver-text-details-{{ $caregiver->id }}">
                                    <header 
                                        id="user-caregiver-name-badge-header-{{ $caregiver->id }}"
                                        class="flex items-center gap-3"
                                    >
                                        <h3 
                                            id="user-caregiver-name-heading-{{ $caregiver->id }}"
                                            class="text-lg font-bold text-slate-900"
                                        >
                                            {{ $caregiver->name }}
                                        </h3>
                                        <span 
                                            id="user-caregiver-role-badge-{{ $caregiver->id }}"
                                            class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider"
                                        >
                                            Caregiver
                                        </span>
                                    </header>
                                    <nav 
                                        id="user-caregiver-action-indicators-{{ $caregiver->id }}"
                                        class="mt-2 flex flex-wrap gap-2"
                                    >
                                        <span class="inline-flex items-center text-xs font-medium text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            View Profile
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-slate-300 self-center"></span>
                                        <span class="inline-flex items-center text-xs font-medium text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Manage Prefs
                                        </span>
                                    </nav>
                                </section>
                            </section>
                            
                            <nav 
                                id="user-caregiver-request-actions-nav-{{ $caregiver->id }}"
                                class="flex items-center gap-3 w-full sm:w-auto pl-19 sm:pl-0"
                            >
                                <form 
                                    id="user-approve-request-form-{{ $caregiver->id }}"
                                    action="{{ route('user.requests.approve', $caregiver) }}" 
                                    method="POST" 
                                    class="flex-1 sm:flex-initial"
                                >
                                    @csrf
                                    <button 
                                        id="user-approve-request-submit-btn-{{ $caregiver->id }}"
                                        type="submit" 
                                        class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-900/10 hover:shadow-xl hover:bg-slate-800 hover:-translate-y-0.5 transition-all duration-200"
                                    >
                                        Accept
                                    </button>
                                </form>
                                
                                <form 
                                    id="user-deny-request-form-{{ $caregiver->id }}"
                                    action="{{ route('user.requests.deny', $caregiver) }}" 
                                    method="POST" 
                                    class="flex-1 sm:flex-initial"
                                >
                                    @csrf
                                    <button 
                                        id="user-deny-request-submit-btn-{{ $caregiver->id }}"
                                        type="submit" 
                                        class="w-full sm:w-auto px-6 py-2.5 bg-white text-slate-600 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all duration-200"
                                    >
                                        Decline
                                    </button>
                                </form>
                            </nav>
                        </section>
                    </article>
                @endforeach
            </section>
        @endif
    </section>
</section>
@endsection

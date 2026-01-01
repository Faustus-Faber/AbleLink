@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-red-500 via-orange-500 to-red-500"></div>
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 rounded-full bg-red-50 blur-2xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 rounded-full bg-orange-50 blur-2xl opacity-50"></div>

        <div class="px-8 py-12 relative z-10 text-center">
            <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner ring-8 ring-red-50/50">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Account Suspended</h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-8 max-w-sm mx-auto">
                Your access to community features has been temporarily restricted due to a guideline violation.
            </p>

            <div class="bg-red-50/50 border border-red-100 rounded-xl p-4 mb-8 text-left flex items-start">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Restriction Active</h3>
                    <p class="text-sm text-red-600 mt-1">You cannot post, comment, or interact with other members at this time.</p>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('messages.index') }}" class="group w-full flex items-center justify-center px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/10 hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        Contact Admin for Appeal
                    </span>
                    <svg class="w-4 h-4 ml-2 text-slate-500 group-hover:text-white transition-colors group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-8 py-3 bg-white text-slate-500 font-bold rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-colors border border-transparent hover:border-slate-200">
                        Log Out
                    </button>
                </form>
            </div>
            
             <p class="mt-8 text-xs text-slate-400">
                Case Review ID: #{{ Auth::id() }}-{{ now()->format('dmY') }}
            </p>
        </div>
    </div>
</div>
@endsection

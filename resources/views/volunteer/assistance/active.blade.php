@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50/50 py-12" x-data="{ showConfirmation: false, targetId: null }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        
        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                    Active Missions
                </h1>
                <p class="text-slate-500 mt-2 text-lg font-medium">
                    Your ongoing contributions to the community.
                </p>
            </div>
            <a href="{{ route('volunteer.requests.index') }}" 
               class="inline-flex items-center px-6 py-3 rounded-xl bg-white text-slate-700 font-bold shadow-sm border border-slate-200 hover:bg-slate-50 hover:text-slate-900 transition-all group">
                <svg class="w-5 h-5 mr-2 text-slate-400 group-hover:text-slate-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Find More Requests
            </a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-4 shadow-sm animate-fade-in-up">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-emerald-900">Task Completed!</h3>
                    <p class="text-emerald-700 text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Content Grid --}}
        @if ($matches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($matches as $match)
                    <div class="cursor-default group relative bg-white rounded-[2rem] p-6 shadow-[0_2px_20px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-100 transition-all duration-300 flex flex-col h-full">
                        
                        {{-- Status Badge --}}
                        <div class="flex justify-between items-start mb-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wider border border-emerald-100">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                Active
                            </span>
                            <span class="text-xs font-bold text-slate-400">
                                {{ $match->matched_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Request Title --}}
                        <h3 class="text-xl font-bold text-slate-900 mb-3 leading-tight group-hover:text-indigo-600 transition-colors">
                            {{ $match->assistanceRequest->title }}
                        </h3>
                        
                        {{-- User Info --}}
                        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-50">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm ring-2 ring-white shadow-sm">
                                {{ substr($match->assistanceRequest->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $match->assistanceRequest->user->name }}</p>
                                <p class="text-xs text-slate-500 font-medium">Requester</p>
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-3 mb-8 flex-grow">
                            @if ($match->assistanceRequest->location)
                                <div class="flex items-start gap-3 text-slate-600">
                                    <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-sm font-medium leading-relaxed">{{ $match->assistanceRequest->location }}</span>
                                </div>
                            @endif
                            
                            @if ($match->assistanceRequest->preferred_date_time)
                                <div class="flex items-start gap-3 text-slate-600">
                                    <svg class="w-5 h-5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-sm font-medium leading-relaxed">
                                        {{ $match->assistanceRequest->preferred_date_time instanceof \Carbon\Carbon ? $match->assistanceRequest->preferred_date_time->format('M d, h:i A') : $match->assistanceRequest->preferred_date_time }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Actions --}}
                        <div class="mt-auto pt-6 border-t border-slate-50">
                            {{-- Hidden Form --}}
                            <form id="complete-task-form-{{ $match->id }}" action="{{ route('volunteer.assistance.complete', $match->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('PUT')
                            </form>
                            
                            {{-- Trigger Button --}}
                            <button type="button" 
                                    @click="targetId = '{{ $match->id }}'; showConfirmation = true"
                                    class="w-full relative overflow-hidden rounded-xl bg-slate-900 text-white font-bold py-3.5 shadow-lg shadow-slate-900/20 hover:shadow-slate-900/30 hover:scale-[1.02] transition-all duration-300 z-10 cursor-pointer">
                                <span class="relative flex items-center justify-center gap-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mark as Complete
                                </span>
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg shadow-slate-200/50 mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">
                    All caught up!
                </h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8 text-lg">
                    You have no active assistance tasks. Check the requests board to find someone to help.
                </p>
                <a href="{{ route('volunteer.requests.index') }}" 
                   class="inline-flex items-center px-8 py-4 rounded-xl bg-slate-900 text-white font-bold shadow-xl shadow-slate-900/20 hover:bg-slate-800 hover:scale-[1.02] transition-all">
                    Browse Requests
                </a>
            </div>
        @endif
    </div>

    {{-- Confirmation Modal --}}
    <div x-show="showConfirmation" 
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         style="display: none;">
        
        {{-- Backdrop --}}
        <div x-show="showConfirmation"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="showConfirmation = false"></div>

        {{-- Modal Content --}}
        <div x-show="showConfirmation"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-3xl p-8 max-w-sm w-full shadow-2xl overflow-hidden">
            
            <div class="text-center">
                <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 mb-2">Complete Mission?</h3>
                <p class="text-slate-500 mb-8">
                    Are you sure you have successfully assisted this user? This will move the task to your history.
                </p>

                <div class="flex flex-col gap-3">
                    <button type="button" 
                            @click="document.getElementById('complete-task-form-' + targetId).submit()"
                            class="w-full py-3.5 rounded-xl bg-emerald-500 text-white font-bold shadow-lg shadow-emerald-500/20 hover:shadow-emerald-500/30 hover:bg-emerald-600 hover:scale-[1.02] transition-all">
                        Yes, Mark Complete
                    </button>
                    <button type="button" 
                            @click="showConfirmation = false"
                            class="w-full py-3.5 rounded-xl bg-slate-50 text-slate-600 font-bold hover:bg-slate-100 hover:text-slate-800 transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes shimmer {
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 1.5s infinite;
    }
    @keyframes fade-in-up {
        0% { opacity: 0; transform: translateY(10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
    }
</style>
@endsection

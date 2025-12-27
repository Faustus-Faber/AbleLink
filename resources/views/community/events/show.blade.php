@extends('layouts.app')



@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Back Button -->
    <a href="{{ route('community.events.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 mb-8 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Events
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Event Header -->
        <div class="p-8 md:p-10 border-b border-slate-100">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide">
                    {{ $event->type === 'online' ? 'Online Event' : 'In-Person' }}
                </span>
                @if($event->event_date->isPast())
                    <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide">Ended</span>
                @endif
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">{{ $event->title }}</h1>
            
            <div class="flex flex-col md:flex-row md:items-center gap-6 text-sm font-medium text-slate-600">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span>{{ $event->event_date->format('F d, Y • h:i A') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <span>{{ $event->type === 'online' ? 'Online' : $event->location }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span>Organized by <span class="text-slate-900 font-bold">{{ $event->organizer->name }}</span></span>
                </div>
            </div>
        </div>

        <!-- Event Body -->
        <div class="p-8 md:p-10">
            <h3 class="text-lg font-bold text-slate-900 mb-4">About this event</h3>
            <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed mb-10">
                {!! Str::markdown($event->description) !!}
            </div>

            @if($event->type === 'online' && $event->participants->contains(auth()->user()))
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-10">
                    <h4 class="text-blue-900 font-bold mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Join Online Meeting
                    </h4>
                    <p class="text-blue-700 text-sm mb-3">You are registered! Here is your access link:</p>
                    <a href="{{ $event->meeting_link }}" target="_blank" class="inline-flex items-center text-blue-600 font-bold hover:underline break-all">
                        {{ $event->meeting_link }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            @endif

            <!-- Participants & Action -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-8 border-t border-slate-100">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 mb-3">Participants ({{ $event->participants->count() }})</h3>
                    <div class="flex -space-x-3">
                        @foreach($event->participants->take(5) as $participant)
                            <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600 shadow-sm" title="{{ $participant->name }}">
                                {{ substr($participant->name, 0, 1) }}
                            </div>
                        @endforeach
                        @if($event->participants->count() > 5)
                            <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-500 shadow-sm">
                                +{{ $event->participants->count() - 5 }}
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    @if($event->participants->contains(auth()->user()))
                        <form action="{{ route('community.events.leave', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-all">
                                Leave Event
                            </button>
                        </form>
                    @else
                        <form action="{{ route('community.events.join', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg hover:shadow-blue-200 transition-all">
                                Join Event
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

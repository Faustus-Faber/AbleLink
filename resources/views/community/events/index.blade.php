@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Community Events</h1>
            <p class="text-slate-500 font-medium mt-1">Connect, learn, and grow with the community.</p>
        </div>
        <a href="{{ route('community.events.create') }}" 
           class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Event
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-10 max-w-2xl mx-auto">
        <form action="{{ route('community.events.index') }}" method="GET" class="relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                class="w-full pl-12 pr-4 py-4 rounded-2xl bg-white border border-slate-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700 font-medium placeholder-slate-400"
                placeholder="Search events by title, location, or keywords...">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('community.events.index') }}" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-2xl border border-green-100 mb-8 flex items-center animate-fade-in-up">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($events as $event)
            <div class="group bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <!-- Date Badge -->
                <div class="relative h-2 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-slate-50 text-slate-600 font-bold text-xs uppercase px-3 py-1 rounded-full border border-slate-100">
                            {{ $event->type === 'online' ? 'Online Event' : 'In-Person' }}
                        </div>
                        @if($event->event_date->isPast())
                            <span class="text-xs font-bold text-slate-400 uppercase">Ended</span>
                        @endif
                    </div>

                    <h2 class="text-xl font-extrabold text-slate-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                        {{ $event->title }}
                    </h2>
                    
                    <p class="text-slate-500 text-sm mb-6 line-clamp-2 leading-relaxed">
                        {{ Str::limit($event->description, 100) }}
                    </p>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm text-slate-600 font-medium">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span>{{ $event->event_date->format('M d, Y • h:i A') }}</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-slate-600 font-medium">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="truncate">{{ $event->type === 'online' ? 'Online Meeting Details' : Str::limit($event->location, 30) }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                             <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600 border border-white shadow-sm">
                                {{ substr($event->organizer->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-xs font-bold text-slate-500">{{ $event->organizer->name ?? 'Admin' }}</span>
                        </div>
                        <a href="{{ route('community.events.show', $event) }}" 
                           class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline">
                            View Details →
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No upcoming events</h3>
                <p class="text-slate-500 max-w-sm mx-auto mb-8">There are currently no scheduled community events. Why not start one yourself?</p>
                <a href="{{ route('community.events.create') }}" class="px-6 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-bold hover:border-blue-600 hover:text-blue-600 transition-all">
                    Host an Event
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $events->links() }}
    </div>
</div>
@endsection

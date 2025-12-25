@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-16 pb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h5 class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase mb-3">Volunteer Dashboard</h5>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Help Requests
                </h1>
                <p class="text-slate-500 mt-4 text-lg">
                    Browse available assistance requests and make a difference today.
                </p>
            </div>
            
            <a href="{{ route('volunteer.profile.show') }}" 
               class="inline-flex items-center px-6 py-3 rounded-full bg-white text-slate-900 text-sm font-bold tracking-wide border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                My Profile
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">

        {{-- Search Bar --}}
        <div class="mb-12">
            <form action="{{ route('volunteer.requests.index') }}" method="GET" class="relative max-w-2xl">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       class="w-full pl-12 pr-6 py-4 rounded-2xl bg-white border border-slate-200 text-slate-900 font-bold placeholder-slate-400 shadow-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" 
                       placeholder="Search requests by title, description, or location...">
            </form>
        </div>

        @if (session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-rose-800 font-bold text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if ($requests->count() > 0)
            <div class="grid grid-cols-1 gap-8">
                @foreach ($requests as $request)
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            
                            {{-- Content --}}
                            <div class="flex-grow space-y-6">
                                {{-- Meta Badges --}}
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold uppercase tracking-widest">
                                        {{ str_replace('_', ' ', $request->type) }}
                                    </span>
                                    
                                    @php
                                        $urgencyStyles = [
                                            'low' => 'text-slate-500 bg-slate-50',
                                            'medium' => 'text-amber-700 bg-amber-50',
                                            'high' => 'text-orange-700 bg-orange-50',
                                            'emergency' => 'text-rose-700 bg-rose-50 animate-pulse',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest {{ $urgencyStyles[$request->urgency] }}">
                                        {{ $request->urgency }} Priority
                                    </span>
                                </div>

                                <div>
                                    <h3 class="text-2xl font-black text-slate-900 mb-2">
                                        {{ $request->title }}
                                    </h3>
                                    <p class="text-slate-500 leading-relaxed text-lg">
                                        {{ $request->description }}
                                    </p>
                                </div>

                                {{-- Details Grid --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Requested By</p>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                                {{ substr($request->user->name, 0, 1) }}
                                            </div>
                                            <p class="font-bold text-slate-900">{{ $request->user->name }}</p>
                                        </div>
                                    </div>
                                    
                                    @if ($request->location)
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Location</p>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <p class="font-bold text-slate-900">{{ $request->location }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if ($request->preferred_date_time)
                                    <div class="space-y-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">When</p>
                                        <p class="font-bold text-slate-900">{{ $request->preferred_date_time instanceof \Carbon\Carbon ? $request->preferred_date_time->format('M d, h:i A') : $request->preferred_date_time }}</p>
                                    </div>
                                    @endif

                                    <div class="space-y-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Posted</p>
                                        <p class="font-bold text-slate-500">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                @if ($request->special_requirements)
                                    <div class="bg-amber-50/50 rounded-xl p-4 border border-amber-100/50">
                                        <p class="text-amber-900 text-sm font-medium">
                                            <span class="font-bold text-amber-600 uppercase text-[10px] tracking-widest mr-2">Requirement</span>
                                            {{ $request->special_requirements }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-row md:flex-col gap-3 min-w-[180px] shrink-0">
                                <form action="{{ route('volunteer.requests.accept', $request->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-4 rounded-xl bg-slate-900 text-white font-bold shadow-lg shadow-slate-900/10 hover:shadow-xl hover:scale-[1.02] hover:bg-slate-800 transition-all duration-300 flex justify-center items-center gap-2">
                                        Accept Request
                                    </button>
                                </form>
                                <form action="{{ route('volunteer.requests.decline', $request->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full py-4 rounded-xl bg-white text-slate-400 font-bold border border-slate-100 hover:border-slate-200 hover:text-slate-600 hover:bg-slate-50 transition-all duration-300">
                                        Decline
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                {{ $requests->links() }}
            </div>
        @else
            <div class="bg-white rounded-[2.5rem] p-20 text-center border border-slate-100 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">
                    @if(request('search'))
                        No matches found
                    @else
                        All Caught Up!
                    @endif
                </h3>
                <p class="text-slate-500 text-lg max-w-md mx-auto leading-relaxed">
                    @if(request('search'))
                        We couldn't find any requests searching for "{{ request('search') }}". Try different keywords or clear the search.
                    @else
                        There are no pending requests at the moment. Thank you for your willingness to help!
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('volunteer.requests.index') }}" class="inline-block mt-8 text-indigo-600 font-bold hover:underline">Clear Search</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-16 pb-20">
        
        {{-- Back Link --}}
        <div class="mb-8">
            <a href="{{ route('user.assistance.index') }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Requests
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-white rounded-[2.5rem] p-10 md:p-14 shadow-sm border border-slate-100">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-10 pb-10 border-b border-slate-50">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ $request->type }}
                        </span>
                        <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            #{{ $request->id }}
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-2">
                        {{ $request->title }}
                    </h1>
                    <p class="text-slate-400 font-medium">
                        Posted {{ $request->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                </div>

                {{-- Status Badge --}}
                <div class="flex flex-col items-end gap-2">
                    @if($request->status === 'completed')
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 text-xs font-bold tracking-wide border border-emerald-100">
                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Completed
                        </span>
                    @elseif($request->status === 'matched')
                         <span class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold tracking-wide border border-indigo-100">
                            <span class="relative flex h-2 w-2 mr-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                            Matched
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-slate-100 text-slate-500 text-xs font-bold tracking-wide border border-slate-200">
                            Pending Match
                        </span>
                    @endif
                </div>
            </div>

            {{-- Detail Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="col-span-2 space-y-8">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-4">Description</h3>
                        <p class="text-slate-600 leading-loose text-lg prose prose-slate max-w-none">
                            {!! Str::markdown($request->description) !!}
                        </p>
                    </div>

                    @if($request->special_requirements)
                    <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Special Requirements
                        </h3>
                        <p class="text-slate-600">
                            {{ $request->special_requirements }}
                        </p>
                    </div>
                    @endif
                </div>

                <div class="space-y-8">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Location</h3>
                        <div class="flex items-start">
                             <svg class="w-5 h-5 mr-2 text-slate-900 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             <p class="text-slate-900 font-bold leading-tight">{{ $request->location }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Time Requested</h3>
                         <div class="flex items-start">
                             <svg class="w-5 h-5 mr-2 text-slate-900 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             <p class="text-slate-900 font-bold leading-tight">{{ $request->preferred_date_time instanceof \Carbon\Carbon ? $request->preferred_date_time->format('M d, Y @ h:i A') : $request->preferred_date_time }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Urgency</h3>
                         @php
                            $urgencyStyles = [
                                'low' => 'text-slate-600 bg-slate-100',
                                'medium' => 'text-amber-700 bg-amber-50',
                                'high' => 'text-orange-700 bg-orange-50',
                                'emergency' => 'text-rose-700 bg-rose-50',
                            ];
                        @endphp
                        <span class="inline-block px-3 py-1.5 rounded-lg text-xs font-extrabold uppercase tracking-wide {{ $urgencyStyles[$request->urgency] ?? 'bg-slate-100' }}">
                            {{ $request->urgency }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

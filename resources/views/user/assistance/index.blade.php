@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Minimal Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-16 pb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h5 class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase mb-3">Community Support</h5>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Assistance Requests
                </h1>
            </div>
            
            <a href="{{ route('user.assistance.create') }}" 
               class="inline-flex items-center px-6 py-3 rounded-full bg-slate-900 text-white text-sm font-bold tracking-wide hover:bg-slate-800 transition-all hover:scale-105 shadow-lg group">
                <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Request
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20" x-data="{ filter: 'all' }">
        @if($requests->isEmpty())
            <div class="bg-white rounded-[2rem] p-20 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No Active Requests</h3>
                <p class="text-slate-500 mb-8 max-w-md mx-auto leading-relaxed">
                    You haven't submitted any requests yet. Start by creating a request to connect with our network.
                </p>
                <a href="{{ route('user.assistance.create') }}" class="text-slate-900 font-bold border-b-2 border-slate-900 pb-0.5 hover:text-indigo-600 hover:border-indigo-600 transition-colors">
                    Create first request
                </a>
            </div>
        @else
            {{-- Filter/Utility Bar --}}
            <div class="flex items-center gap-2 mb-8 overflow-x-auto pb-2">
                <button @click="filter = 'all'" 
                        :class="filter === 'all' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-200">
                    All Requests
                </button>
                <button @click="filter = 'active'" 
                        :class="filter === 'active' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-200">
                    Active
                </button>
                <button @click="filter = 'completed'" 
                        :class="filter === 'completed' ? 'bg-slate-900 text-white shadow-md' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'"
                        class="px-5 py-2.5 rounded-full text-xs font-bold transition-all duration-200">
                    Completed
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($requests as $request)
                    <div x-show="filter === 'all' || (filter === 'active' && '{{ $request->status }}' !== 'completed') || (filter === 'completed' && '{{ $request->status }}' === 'completed')"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="group bg-white rounded-[2rem] p-8 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 hover:border-slate-200 flex flex-col h-full">
                        {{-- Header --}}
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    {{ $request->type }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                                    {{ $request->title }}
                                </h3>
                            </div>
                            
                            {{-- Status Indicator (Minimal Dot) --}}
                            @if($request->status === 'completed')
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 flex-shrink-0" title="Completed"></div>
                            @elseif($request->status === 'matched')
                                <div class="w-2.5 h-2.5 rounded-full bg-indigo-500 flex-shrink-0 animate-pulse" title="Matched"></div>
                            @else
                                <div class="w-2.5 h-2.5 rounded-full bg-slate-300 flex-shrink-0" title="Pending"></div>
                            @endif
                        </div>

                        {{-- Body --}}
                        <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3">
                            {{ $request->description }}
                        </p>

                        {{-- Footer --}}
                        <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-50">
                            {{-- Location --}}
                            <div class="flex items-center text-xs font-bold text-slate-400">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($request->location, 20) }}
                            </div>
                            
                            {{-- Urgency Badge (Subtle) --}}
                            @php
                                $urgencyStyles = [
                                    'low' => 'text-slate-400 bg-slate-50',
                                    'medium' => 'text-amber-600 bg-amber-50',
                                    'high' => 'text-orange-600 bg-orange-50',
                                    'emergency' => 'text-rose-600 bg-rose-50',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wide {{ $urgencyStyles[$request->urgency] ?? 'text-slate-400 bg-slate-50' }}">
                                {{ $request->urgency }}
                            </span>
                        </div>
                        
                        {{-- Hover Action --}}
                        <div class="mt-6 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0">
                            <a href="{{ route('user.assistance.show', $request) }}" class="text-xs font-bold text-indigo-600 flex items-center hover:underline">
                                View Details 
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Ensure Alpine.js is loaded -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection

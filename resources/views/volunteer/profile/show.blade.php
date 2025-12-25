@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl pt-16 pb-12">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h5 class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase mb-3">Volunteer Dashboard</h5>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
                    Volunteer Profile
                </h1>
                <p class="text-slate-500 mt-4 text-lg">
                    Your skills, availability, and volunteer information.
                </p>
            </div>
            
            <a href="{{ route('volunteer.profile.edit') }}" 
               class="inline-flex items-center px-6 py-3 rounded-full bg-slate-900 text-white text-sm font-bold tracking-wide hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:scale-105">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-5xl pb-20">

        @if (session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-emerald-800 font-bold text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-sm border border-slate-100">
            
            {{-- Bio Section --}}
            <div class="mb-12">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">About You</h3>
                @if ($profile->bio)
                    <p class="text-xl text-slate-900 leading-relaxed font-medium">
                        {{ $profile->bio }}
                    </p>
                @else
                    <p class="text-slate-400 italic">No bio provided yet.</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12 border-t border-slate-50 pt-10">
                
                {{-- Location & Logistics --}}
                <div class="space-y-8">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Location</h3>
                        <p class="text-lg font-bold text-slate-900">{{ $profile->location ?? 'Not set' }}</p>
                    </div>
                    
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Maximum Distance</h3>
                        <div class="flex items-center gap-2">
                             <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                             <p class="text-lg font-bold text-slate-900">{{ $profile->max_distance_km }} km</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Emergency Availability</h3>
                        @if($profile->available_for_emergency)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold uppercase tracking-wide">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wide">
                                Not Available
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Specializations --}}
                <div>
                     <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Specializations</h3>
                     @if ($profile->specializations)
                        <div class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-50">
                            <p class="text-indigo-900 leading-relaxed font-medium">
                                {{ $profile->specializations }}
                            </p>
                        </div>
                     @else
                        <p class="text-slate-400 italic">No specializations listed.</p>
                     @endif
                </div>
            </div>

            {{-- Skills & Availability Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-slate-50 pt-10">
                
                {{-- Skills --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Skills</h3>
                    @if ($profile->skills && count($profile->skills) > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach ($profile->skills as $skill)
                                <span class="px-4 py-2 bg-slate-50 border border-slate-100 text-slate-700 rounded-xl text-sm font-bold shadow-sm">
                                    {{ ucfirst(str_replace('_', ' ', $skill)) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400 italic">No skills selected.</p>
                    @endif
                </div>

                {{-- Availability --}}
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Weekly Availability</h3>
                    @if ($profile->availability && count($profile->availability) > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach ($profile->availability as $day)
                                <span class="px-4 py-2 bg-emerald-50/50 border border-emerald-100 text-emerald-800 rounded-xl text-sm font-bold">
                                    {{ ucfirst($day) }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-slate-400 italic">No availability selected.</p>
                    @endif
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

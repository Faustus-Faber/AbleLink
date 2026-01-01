@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    {{-- Hero Section --}}
    <div class="bg-violet-900 pt-20 pb-24 relative overflow-hidden rounded-b-[2.5rem] shadow-xl shadow-violet-900/10 mb-8">
        {{-- Abstract background pattern --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>
        
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <div class="max-w-3xl text-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-800/50 border border-violet-700 text-violet-200 text-xs font-bold uppercase tracking-widest mb-6">
                        <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                        Accessible Learning
                    </div>
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6 text-white leading-tight">
                        Expand Your Knowledge
                    </h1>
                    <p class="text-violet-100 text-lg md:text-xl leading-relaxed opacity-90 max-w-2xl">
                        Explore our accessible course library designed with inclusivity in mind. Features subtitles, transcripts, and audio descriptions for every learner.
                    </p>
                </div>
                
                @auth
                <div class="flex flex-col sm:flex-row gap-4" x-data>
                     {{-- F12 - Modal Trigger --}}
                    <button @click="$dispatch('open-recommendations', { type: 'course' })" 
                        class="px-8 py-4 rounded-2xl bg-teal-500 hover:bg-teal-400 text-teal-950 font-bold shadow-lg shadow-teal-900/20 transition-all flex items-center justify-center gap-3 group">
                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Get Personal Suggestions
                    </button>

                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.courses.index') }}"
                        class="px-6 py-4 rounded-2xl bg-violet-800 hover:bg-violet-700 text-white font-bold shadow-lg transition-all flex items-center justify-center border border-violet-700">
                            Manage Library
                        </a>
                    @endif
                </div>
                @endauth
            </div>
        </div>
    </div>
    
    {{-- Search Section (Floating) --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-20 -mt-20 mb-2">
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 p-2 flex flex-col md:flex-row items-center gap-2 max-w-4xl mx-auto border border-slate-100">
            <div class="flex-1 w-full relative">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <form action="{{ route('courses.index') }}" method="GET" class="w-full" id="course-search-form">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-14 pr-4 py-4 rounded-xl border-none text-slate-700 placeholder-slate-400 focus:ring-0 focus:outline-none text-lg font-medium bg-transparent" 
                        placeholder="Search for courses, topics, or accessibility features...">
                </form>
            </div>
            <button type="submit" form="course-search-form" class="w-full md:w-auto px-8 py-4 rounded-xl bg-violet-600 hover:bg-violet-700 text-white font-bold shadow-lg shadow-violet-600/20 transition-all flex items-center justify-center gap-2">
                Search
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">
        {{-- F12 - Modal Component --}}
        <x-recommendation-modal type="course" />

        @if($courses->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900">No courses found</h3>
                <p class="text-slate-500 mt-2">Try adjusting your search or filters.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($courses as $course)
                    @php
                        $media = $course->media ?? collect();
                        $hasCaptions = $media->contains(fn($m) => !empty($m->captions_path));
                        $hasTranscript = $media->contains(fn($m) => !empty($m->transcript));
                        $hasAudioDesc = $media->contains(fn($m) => !empty($m->audio_description_path));
                    @endphp

                    <a href="{{ route('courses.show', ['course' => $course->slug]) }}"
                       class="group bg-white rounded-3xl p-1 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full ring-1 ring-slate-200 hover:ring-violet-500">
                        <div class="bg-slate-50 rounded-[1.25rem] p-8 h-full flex flex-col relative overflow-hidden">
                            {{-- Decorative Top Bar --}}
                            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="flex items-center justify-between mb-6">
                                @if($course->level)
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-extrabold uppercase tracking-widest bg-white border border-slate-200 text-slate-600 group-hover:border-violet-200 group-hover:text-violet-700 transition-colors shadow-sm">
                                        {{ $course->level }}
                                    </span>
                                @endif
                                <div class="flex items-center text-slate-400 text-xs font-bold bg-white px-2 py-1 rounded-lg shadow-sm border border-slate-100">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @if($course->estimated_minutes)
                                        {{ $course->estimated_minutes }} MIN
                                    @endif
                                </div>
                            </div>

                            <h2 class="text-xl font-bold text-slate-900 group-hover:text-violet-900 transition-colors mb-4 line-clamp-2 leading-tight">
                                {{ $course->title }}
                            </h2>
                            
                            @if($course->summary)
                                <p class="text-slate-500 text-sm leading-relaxed line-clamp-3 mb-8 flex-grow">
                                    {{ $course->summary }}
                                </p>
                            @endif

                            <div class="mt-auto pt-6 border-t border-slate-200 group-hover:border-violet-100 transition-colors">
                                <div class="flex flex-wrap gap-2 text-xs font-bold">
                                    @if($hasCaptions)
                                        <div class="flex items-center text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1.5 rounded-lg" title="Captions Available">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                            CC
                                        </div>
                                    @endif
                                    @if($hasTranscript)
                                        <div class="flex items-center text-emerald-700 bg-emerald-50 border border-emerald-100 px-2.5 py-1.5 rounded-lg" title="Transcript Available">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            TXT
                                        </div>
                                    @endif
                                    @if($hasAudioDesc)
                                        <div class="flex items-center text-fuchsia-700 bg-fuchsia-50 border border-fuchsia-100 px-2.5 py-1.5 rounded-lg" title="Audio Description Available">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                            AD
                                        </div>
                                    @endif
                                    @if(!$hasCaptions && !$hasTranscript && !$hasAudioDesc && $media->count() > 0)
                                        <span class="text-slate-500 bg-slate-100 border border-slate-200 px-2.5 py-1.5 rounded-lg">Media Available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50">
    {{-- Hero Section --}}
    <div class="bg-violet-900 pt-24 pb-32 relative overflow-hidden rounded-b-[3rem] shadow-xl shadow-violet-900/10">
        {{-- Abstract background pattern --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
            </svg>
        </div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl relative z-10">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center text-violet-200 hover:text-white font-bold mb-8 transition-colors group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Library
            </a>

            <div class="max-w-4xl">
                <div class="flex flex-wrap gap-3 mb-6">
                    @if($course->level)
                        <span class="px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-widest bg-violet-800/50 border border-violet-700 text-violet-200">
                            {{ $course->level }}
                        </span>
                    @endif
                    @if($course->estimated_minutes)
                        <span class="px-3 py-1 rounded-lg text-xs font-extrabold uppercase tracking-widest bg-violet-800/50 border border-violet-700 text-violet-200 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $course->estimated_minutes }} MIN
                        </span>
                    @endif
                </div>

                <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight mb-6">
                    {{ $course->title }}
                </h1>
                
                @if($course->published_at)
                    <p class="text-violet-200 font-medium">
                        Published on {{ $course->published_at->toFormattedDateString() }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl -mt-20 relative z-20 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Description & Media --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- About Course Card --}}
                @if($course->description)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">About this course</h2>
                        <div class="prose prose-slate prose-lg max-w-none text-slate-600">
                            {!! Str::markdown($course->description) !!}
                        </div>
                    </div>
                @endif

                {{-- Media Section --}}
                <div class="space-y-6">
                    @forelse($course->media as $media)
                        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 transition-shadow hover:shadow-md">
                            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-lg font-bold text-slate-900">
                                            {{ $media->title ?: ucfirst($media->kind) }}
                                        </h3>
                                        @if($media->is_primary)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-violet-100 text-violet-700">
                                                Primary
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-wider text-[10px]">
                                        {{ $media->kind }} Content
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    @if($media->captions_url)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600" title="Captions Available">
                                            <span class="text-[10px] font-bold">CC</span>
                                        </span>
                                    @endif
                                    @if($media->audio_description_url)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-fuchsia-50 text-fuchsia-600" title="Audio Description Available">
                                            <span class="text-[10px] font-bold">AD</span>
                                        </span>
                                    @endif
                                    @if(!empty($media->transcript))
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-600" title="Transcript Available">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                @if($media->kind === \App\Models\Education\CourseMedia::KIND_VIDEO)
                                    @if($media->embed_url)
                                        <div class="w-full aspect-video rounded-2xl overflow-hidden bg-slate-900 shadow-inner">
                                            <iframe class="w-full h-full" src="{{ $media->embed_url }}" 
                                                    title="{{ $media->title }}" 
                                                    frameborder="0" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                                    referrerpolicy="strict-origin-when-cross-origin" 
                                                    allowfullscreen></iframe>
                                        </div>
                                    @elseif($media->media_url)
                                        <video class="w-full rounded-2xl bg-black shadow-inner" controls>
                                            <source src="{{ $media->media_url }}" type="{{ $media->mime_type ?: 'video/mp4' }}">
                                            @if($media->captions_url)
                                                <track kind="subtitles" src="{{ $media->captions_url }}" srclang="{{ $media->captions_language ?: 'en' }}" label="English" default>
                                            @endif
                                        </video>
                                    @endif
                                @elseif($media->kind === \App\Models\Education\CourseMedia::KIND_AUDIO && $media->media_url)
                                    <div class="bg-slate-50 rounded-2xl p-6">
                                        <audio class="w-full" controls src="{{ $media->media_url }}"></audio>
                                    </div>
                                @elseif($media->media_url)
                                    <a href="{{ $media->media_url }}" target="_blank" class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 transition-colors group">
                                        <span class="font-bold text-slate-700 group-hover:text-violet-700">Open Resource</span>
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif

                                {{-- Accessibility Tracks --}}
                                @if($media->audio_description_url || $media->captions_url || !empty($media->transcript))
                                    <div class="mt-8 space-y-4">
                                        @if($media->audio_description_url)
                                            <div class="p-4 rounded-xl bg-fuchsia-50 border border-fuchsia-100">
                                                <h4 class="text-sm font-extrabold text-fuchsia-900 mb-2 flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                                    Audio Description
                                                </h4>
                                                <audio class="w-full" controls src="{{ $media->audio_description_url }}"></audio>
                                            </div>
                                        @endif

                                        @if(!empty($media->transcript))
                                            <div x-data="{ open: false }" class="border border-slate-200 rounded-xl overflow-hidden">
                                                <button @click="open = !open" class="w-full px-4 py-3 bg-slate-50 flex items-center justify-between font-bold text-slate-700 hover:bg-slate-100 transition-colors">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        View Transcript
                                                    </span>
                                                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>
                                                <div x-show="open" class="p-6 bg-white border-t border-slate-200">
                                                    <div class="prose prose-slate max-w-none">
                                                        {!! nl2br(e($media->transcript)) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-12 text-center border dashed border-2 border-slate-200">
                            <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <h3 class="text-xl font-bold text-slate-900">No Content Yet</h3>
                            <p class="text-slate-500 mt-2">Media will be uploaded soon.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Right Column: Sidebar --}}
            <div class="space-y-6">
                @if($course->summary)
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                        <h3 class="text-sm font-extrabold text-slate-400 uppercase tracking-widest mb-4">Summary</h3>
                        <p class="text-slate-700 font-medium leading-relaxed">
                            {{ $course->summary }}
                        </p>
                    </div>
                @endif
                
                {{-- F21 - AI Certificate Generation --}}
                @auth
                    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden group">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:bg-white/20 transition-all"></div>
                        
                        <h3 class="text-lg font-bold mb-2 flex items-center relative z-10">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Course Completed?
                        </h3>
                        <p class="text-indigo-100 text-sm mb-4 relative z-10">
                            Claim your personalized AI-enhanced certificate now.
                        </p>
                        
                        <form action="{{ route('courses.certificate', $course) }}" method="POST" class="relative z-10">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 rounded-xl bg-white text-indigo-700 font-bold hover:bg-indigo-50 transition-colors shadow-sm flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Get Smart Certificate
                            </button>
                        </form>
                    </div>
                @endauth
                
                {{-- Admin Actions --}}
                @auth
                    @if (Auth::user()->isAdmin())
                        <div class="bg-slate-900 rounded-3xl p-6 text-white shadow-lg">
                            <h3 class="text-lg font-bold mb-4">Admin Controls</h3>
                            <a href="{{ route('admin.courses.edit', $course) }}" class="block w-full py-3 px-4 rounded-xl bg-violet-600 hover:bg-violet-500 text-center font-bold transition-colors">
                                Edit Course
                            </a>
                        </div>
                    @endif
                @endauth

                {{-- Accessibility Features Summary --}}
                <div class="bg-violet-50 rounded-3xl p-6 border border-violet-100">
                    <h3 class="flex items-center text-sm font-extrabold text-violet-900 uppercase tracking-widest mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Accessibility
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-center text-violet-800 text-sm font-medium">
                            <svg class="w-5 h-5 mr-3 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Screen Reader Optimized
                        </li>
                        <li class="flex items-center text-violet-800 text-sm font-medium">
                            <svg class="w-5 h-5 mr-3 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Keyboard Navigation
                        </li>
                        <li class="flex items-center text-violet-800 text-sm font-medium">
                            <svg class="w-5 h-5 mr-3 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            High Contrast Support
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


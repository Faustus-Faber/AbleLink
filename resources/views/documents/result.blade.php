@extends('layouts.app')

@section('title', 'Result - AbleLink')

@section('content')
<div class="min-h-screen bg-white font-sans selection:bg-black selection:text-white">
    <div class="container mx-auto px-6 py-24 max-w-7xl">
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
            <div>
                <h1 class="text-5xl md:text-6xl font-black text-zinc-900 tracking-tighter mb-4">
                    Result.
                </h1>
                @if(!empty($source_filename))
                    <p class="text-xl text-zinc-400 font-medium">
                        Source: <span class="text-zinc-900 font-bold decoration-zinc-900/30 underline underline-offset-4">{{ $source_filename }}</span>
                    </p>
                @endif
            </div>
            <a href="{{ url('/upload') }}" class="group flex items-center gap-3 px-8 py-4 rounded-full bg-zinc-100 text-zinc-900 font-bold hover:bg-zinc-900 hover:text-white transition-all duration-300">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Process Another File
            </a>
        </div>

        @if(!empty($warnings))
            <div class="mb-12 bg-amber-50 border-l-4 border-amber-500 p-8 rounded-r-3xl">
                <div class="flex items-center gap-4 mb-3">
                     <div class="p-2 bg-amber-100 rounded-full text-amber-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                     </div>
                    <p class="font-black text-amber-900 text-lg">System Notes</p>
                </div>
                <ul class="list-disc list-inside space-y-2 text-amber-800 font-medium ml-2">
                    @foreach($warnings as $w)
                        <li>{{ $w }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
            
            {{-- Extracted Text Column --}}
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between mb-8">
                     <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-zinc-100 text-zinc-900 rounded-xl flex items-center justify-center font-black shadow-sm">1</div>
                        <h2 class="text-2xl font-black text-zinc-900 tracking-tight">Extracted Text</h2>
                    </div>
                    
                    <form action="{{ url('/simplify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="text" value="{{ $original_text }}">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-zinc-900 text-white font-bold hover:bg-black hover:scale-105 transition-all shadow-lg shadow-zinc-900/10 flex items-center gap-2">
                             <span>Simplify Again</span>
                             <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </button>
                    </form>
                </div>

                <div class="flex-grow bg-zinc-50 rounded-[2.5rem] border border-zinc-100 p-10 relative group">
                    <div class="absolute top-0 right-0 p-6 opacity-0 group-hover:opacity-100 transition-opacity">
                         <span class="text-xs font-black text-zinc-300 uppercase tracking-widest">Original</span>
                    </div>
                    <textarea readonly class="w-full h-full bg-transparent border-none p-0 text-zinc-600 font-mono text-sm leading-relaxed focus:ring-0 resize-none" style="min-height: 600px;">{{ $original_text }}</textarea>
                </div>
            </div>

            {{-- Simplified Text Column --}}
            <div class="flex flex-col h-full">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-zinc-900 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-zinc-900/20">2</div>
                    <h2 class="text-2xl font-black text-zinc-900 tracking-tight">Simplified Result</h2>
                </div>

                <div class="flex-grow bg-white rounded-[2.5rem] border-2 border-zinc-100 p-10 shadow-xl shadow-zinc-200/50 flex flex-col gap-8">
                    
                    @if(!empty($simplified_bullets))
                        <div class="bg-zinc-50 rounded-2xl p-8 border-l-4 border-zinc-900">
                            <p class="font-black text-zinc-900 text-lg uppercase tracking-tight mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Key Points
                            </p>
                            <ul class="space-y-3">
                                @foreach($simplified_bullets as $b)
                                    <li class="flex items-start gap-3 text-zinc-700 font-medium">
                                        <span class="mt-2 w-1.5 h-1.5 bg-zinc-400 rounded-full flex-shrink-0"></span>
                                        <span class="leading-relaxed">{{ $b }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(isset($simplified_text) && trim($simplified_text) !== '')
                        <div>
                             <p class="font-black text-zinc-900 text-lg uppercase tracking-tight mb-4">Full Simplified Text</p>
                             <textarea readonly class="w-full bg-transparent border-none p-0 text-zinc-800 font-medium text-lg leading-loose focus:ring-0 resize-none h-[400px]">{{ $simplified_text }}</textarea>
                        </div>
                    @else
                        <div class="flex-grow flex flex-col items-center justify-center text-center p-12 border-2 border-dashed border-zinc-200 rounded-3xl bg-zinc-50/50">
                            <div class="w-16 h-16 bg-zinc-100 rounded-full flex items-center justify-center text-zinc-400 mb-4">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 mb-2">No simplified text yet</h3>
                            <p class="text-zinc-500 max-w-xs mx-auto">Click the "Simplify" button above to generate a clearer version using our AI engine.</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

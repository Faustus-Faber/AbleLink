@extends('layouts.app')

@section('title', 'Content Simplification & OCR - AbleLink')

@section('content')
<div class="min-h-screen bg-white font-sans selection:bg-black selection:text-white">
    <div class="container mx-auto px-6 py-24 max-w-6xl">

        {{-- Header --}}
        <div class="text-center mb-24">
            <h1 class="text-5xl md:text-7xl font-black text-zinc-900 tracking-tighter mb-8">
                Simplify.
            </h1>
            <p class="text-xl md:text-2xl text-zinc-500 font-medium max-w-3xl mx-auto leading-relaxed">
                Transform complex documents into clear, accessible language using our advanced <span class="text-zinc-900 font-bold decoration-zinc-900/30 underline decoration-2 underline-offset-4">OCR & Simplification</span> engine.
            </p>
        </div>

        @if ($errors->any())
            <div class="max-w-4xl mx-auto mb-16 bg-red-50 border-l-4 border-red-500 p-8 rounded-r-3xl">
                <div class="flex items-center gap-4 mb-3">
                    <div class="p-2 bg-red-100 rounded-full text-red-600">
                         <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-red-900">Unable to process request</h3>
                </div>
                <ul class="list-disc list-inside text-red-800 text-lg font-medium ml-2 space-y-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-32 items-stretch max-w-6xl mx-auto relative">

            {{-- Divider (Desktop) --}}
            <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-zinc-200 to-transparent -translate-x-1/2">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white py-6 px-3 text-zinc-300 font-black text-xs tracking-[0.2em] uppercase">OR</div>
            </div>

            {{-- Left Column: File Upload --}}
            <div class="group flex flex-col h-full">
                <div class="mb-10 flex items-center gap-5">
                    <div class="w-14 h-14 bg-zinc-900 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl shadow-zinc-900/10 group-hover:scale-110 transition-transform duration-500">1</div>
                    <div>
                        <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Upload File</h2>
                        <p class="text-zinc-400 font-medium mt-1">PDF, Images, or Text files</p>
                    </div>
                </div>

                <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data" class="flex-grow flex flex-col">
                    @csrf
                    
                    <div class="relative flex-grow min-h-[400px] rounded-[3rem] border-2 border-dashed border-zinc-200 bg-zinc-50 hover:bg-white hover:border-zinc-900 transition-all duration-500 cursor-pointer flex flex-col items-center justify-center p-12 text-center group-hover:shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] overflow-hidden">
                        <input type="file" name="document" id="fileInput" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" accept=".pdf,.png,.jpg,.jpeg,.txt">
                        
                        {{-- Decorative Background Circle --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none">
                            <div class="w-[500px] h-[500px] bg-gradient-to-tr from-zinc-100 to-transparent rounded-full blur-3xl"></div>
                        </div>

                        <div id="iconContainer" class="relative z-10 w-24 h-24 bg-white rounded-3xl flex items-center justify-center shadow-sm mb-8 border border-zinc-100 group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500">
                            <svg id="uploadIcon" class="w-10 h-10 text-zinc-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                            <svg id="successIcon" class="w-10 h-10 text-emerald-500 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 id="uploadTitle" class="relative z-10 text-2xl font-bold text-zinc-900 mb-3 group-hover:text-black transition-colors px-4 truncate w-full">Drop document here</h3>
                        <p id="uploadDesc" class="relative z-10 text-zinc-400 text-base font-medium mb-8">or click to browse your files</p>
                        
                        <div class="relative z-10 flex flex-wrap justify-center gap-3 transition-opacity duration-300" id="fileTypes">
                            <span class="px-4 py-2 bg-white border border-zinc-100 rounded-full text-xs font-black text-zinc-400 tracking-wider">PDF</span>
                            <span class="px-4 py-2 bg-white border border-zinc-100 rounded-full text-xs font-black text-zinc-400 tracking-wider">PNG</span>
                            <span class="px-4 py-2 bg-white border border-zinc-100 rounded-full text-xs font-black text-zinc-400 tracking-wider">JPG</span>
                        </div>
                    </div>

                    <div class="mt-10 space-y-6">
                         <label class="flex items-center gap-4 cursor-pointer group/check bg-zinc-50 p-4 rounded-2xl hover:bg-zinc-100 transition-colors">
                            <div class="relative">
                                <input type="checkbox" name="auto_simplify" value="1" checked class="peer sr-only">
                                <div class="w-7 h-7 bg-white border-2 border-zinc-300 rounded-lg peer-checked:bg-zinc-900 peer-checked:border-zinc-900 transition-all"></div>
                                <svg class="w-5 h-5 text-white absolute top-1 left-1 opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="font-bold text-zinc-600 group-hover/check:text-zinc-900 transition-colors text-lg">Auto-simplify output</span>
                        </label>

                        <button type="submit" class="w-full py-5 bg-zinc-900 text-white rounded-2xl font-black text-lg hover:bg-black hover:scale-[1.02] transition-all shadow-xl shadow-zinc-900/10 flex items-center justify-center group/btn">
                            <span>Start Processing</span>
                            <svg class="w-5 h-5 ml-3 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Right Column: Paste Text --}}
            <div class="group flex flex-col h-full mt-16 lg:mt-0">
                <div class="mb-10 flex items-center gap-5">
                     <div class="w-14 h-14 bg-white border-2 border-zinc-100 text-zinc-900 rounded-2xl flex items-center justify-center font-black text-2xl group-hover:border-zinc-900 transition-colors duration-500">2</div>
                    <div>
                        <h2 class="text-3xl font-black text-zinc-900 tracking-tight">Paste Text</h2>
                        <p class="text-zinc-400 font-medium mt-1">Direct text input</p>
                    </div>
                </div>

                <form action="{{ url('/simplify') }}" method="POST" class="flex-grow flex flex-col h-full">
                    @csrf
                    <div class="relative flex-grow group/textarea">
                        <div class="absolute -inset-1 bg-gradient-to-r from-zinc-200 to-zinc-100 rounded-[3.2rem] opacity-0 group-hover/textarea:opacity-100 transition-opacity duration-500 blur"></div>
                        <textarea name="text" 
                            class="relative w-full h-[500px] lg:h-full rounded-[3rem] border-2 border-zinc-100 bg-white p-10 text-zinc-800 font-medium text-lg leading-relaxed focus:border-zinc-900 focus:ring-0 transition-all resize-none placeholder:text-zinc-300 focus:shadow-2xl focus:shadow-zinc-200/50" 
                            placeholder="Type or paste your text here to simplify it directly without file upload..."
                        >{{ old('text') }}</textarea>
                        
                        <div class="absolute bottom-8 right-8 z-10">
                             <button type="submit" class="w-20 h-20 bg-zinc-900 text-white rounded-[2rem] flex items-center justify-center hover:bg-black hover:scale-110 hover:rotate-3 transition-all shadow-xl shadow-zinc-900/20 group/fab">
                                <svg class="w-8 h-8 group-hover/fab:animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </button>
                        </div>
                    </div>
                     <p class="mt-6 text-center text-zinc-400 text-sm font-bold tracking-wide uppercase">
                        Instant simplification • No OCR required
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            document.getElementById('uploadTitle').textContent = fileName;
            document.getElementById('uploadDesc').textContent = 'File selected - Ready to process';
            document.getElementById('uploadDesc').classList.add('text-emerald-600', 'font-bold');
            document.getElementById('uploadDesc').classList.remove('text-zinc-400');
            
            // Swap Icons
            document.getElementById('uploadIcon').classList.add('hidden');
            document.getElementById('successIcon').classList.remove('hidden');
            
            // Hide file types line to reduce clutter
            document.getElementById('fileTypes').style.opacity = '0';
        }
    });
</script>
@endsection

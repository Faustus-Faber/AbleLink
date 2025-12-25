@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-16 pb-12">
        <h5 class="text-xs font-bold text-slate-500 tracking-[0.2em] uppercase mb-3">Volunteer Dashboard</h5>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight leading-tight">
            Task History
        </h1>
        <p class="text-slate-500 mt-4 text-lg">
            A record of all the lives you've impacted.
        </p>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">

        @if ($matches->count() > 0)
            <div class="space-y-6">
                @foreach ($matches as $match)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            
                            {{-- Checkmark Icon --}}
                            <div class="flex-shrink-0 pt-1">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>

                            <div class="flex-grow space-y-4">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-900">{{ $match->assistanceRequest->title }}</h3>
                                        <p class="text-slate-400 text-sm font-bold mt-1">Completed on {{ $match->completed_at ? $match->completed_at->format('M d, Y') : 'Unknown Date' }}</p>
                                    </div>
                                    
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-widest">
                                        {{ ucfirst($match->status) }}
                                    </span>
                                </div>

                                <p class="text-slate-500 leading-relaxed">
                                    {{ Str::limit($match->assistanceRequest->description, 180) }}
                                </p>

                                <div class="flex items-center gap-6 pt-4 border-t border-slate-50">
                                    <div class="flex items-center gap-2 text-sm text-slate-500 font-medium">
                                        <span class="text-slate-300 uppercase tracking-widest text-[10px] font-bold">Assisted</span>
                                        <span class="text-slate-700 font-bold">{{ $match->assistanceRequest->user->name }}</span>
                                    </div>

                                    @if ($match->rating)
                                        <div class="flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $match->rating ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    @endif
                                </div>

                                @if ($match->user_feedback)
                                    <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-100 italic text-slate-600 text-sm">
                                        "{{ $match->user_feedback }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 text-center">
                {{ $matches->links() }}
            </div>
        @else
            <div class="bg-white rounded-[2.5rem] p-20 text-center border border-slate-100 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">History empty</h3>
                <p class="text-slate-500 text-lg max-w-md mx-auto leading-relaxed">
                    You haven't completed any assistance tasks yet.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection

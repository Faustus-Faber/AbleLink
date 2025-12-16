@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">

    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pt-12 pb-8">
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">
            Interview Management
        </h1>
        <p class="text-slate-500 mt-2 font-medium">
            Schedule and manage interviews with candidates
        </p>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl pb-20">
        @if (session('success'))
            <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-2xl text-sm font-bold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($interviews->count() > 0)
            <div class="space-y-6">
                @foreach ($interviews as $interview)
                    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300">
                        <div class="flex flex-col lg:flex-row justify-between items-start gap-8">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-4 mb-2">
                                    <h3 class="text-2xl font-black text-slate-900 truncate">{{ $interview->title }}</h3>
                                    
                                     @php
                                        $statusClasses = match($interview->status) {
                                            'completed' => 'bg-emerald-100 text-emerald-700',
                                            'scheduled' => 'bg-indigo-100 text-indigo-700',
                                            'cancelled' => 'bg-rose-100 text-rose-700',
                                            'rescheduled' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $statusClasses }}">
                                        {{ ucfirst($interview->status) }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2 text-sm text-slate-600 font-medium mb-6">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400 font-bold uppercase text-xs tracking-wider w-20">Candidate</span>
                                        <span class="text-slate-900 font-bold">{{ $interview->applicant->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                         <span class="text-slate-400 font-bold uppercase text-xs tracking-wider w-20">Job</span>
                                         <span class="text-slate-900 font-bold">{{ $interview->jobApplication->job->title }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                         <span class="text-slate-400 font-bold uppercase text-xs tracking-wider w-20">Time</span>
                                         <span class="text-slate-900 font-bold">{{ $interview->scheduled_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                         <span class="text-slate-400 font-bold uppercase text-xs tracking-wider w-20">Type</span>
                                         <span class="text-slate-900 font-bold">{{ ucfirst($interview->type) }}</span>
                                    </div>
                                    @if ($interview->location)
                                    <div class="flex items-center gap-2">
                                         <span class="text-slate-400 font-bold uppercase text-xs tracking-wider w-20">Location</span>
                                         <span class="text-slate-900 font-bold">{{ $interview->location }}</span>
                                    </div>
                                    @endif
                                </div>

                                @if ($interview->meeting_link)
                                    <div class="flex items-center gap-3 mb-6 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                        <div class="p-2 bg-white rounded-full text-indigo-600 shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Meeting Link</p>
                                            <a href="{{ $interview->meeting_link }}" target="_blank" class="text-indigo-700 font-bold hover:underline truncate block">
                                                {{ $interview->meeting_link }}
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if ($interview->feedback)
                                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                                         <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Feedback</p>
                                        <p class="text-slate-600 font-medium">{{ $interview->feedback }}</p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex flex-col gap-4 w-full lg:w-72">
                                <form action="{{ route('employer.interviews.update-status', $interview) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1 pl-1">Update Status</label>
                                    <div class="relative">
                                        <select name="status" onchange="this.form.submit()" 
                                            class="w-full px-5 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white cursor-pointer hover:border-indigo-300 transition-all">
                                            <option value="scheduled" {{ $interview->status === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            <option value="completed" {{ $interview->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $interview->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            <option value="rescheduled" {{ $interview->status === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                        </select>
                                        <svg class="w-4 h-4 absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </form>

                                @if ($interview->status === 'completed')
                                    <button onclick="document.getElementById('feedback-{{ $interview->id }}').classList.toggle('hidden')"
                                            class="w-full px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-all text-center">
                                        {{ $interview->feedback ? 'Edit Feedback' : 'Add Feedback' }}
                                    </button>
                                    
                                    <div id="feedback-{{ $interview->id }}" class="hidden">
                                        <form action="{{ route('employer.interviews.update-status', $interview) }}" method="POST" class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <textarea name="feedback" rows="3" placeholder="Enter interview feedback..."
                                                class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 mb-3 bg-white font-medium text-slate-600"></textarea>
                                            <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all text-sm">
                                                Save
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $interviews->links() }}
            </div>
        @else
             <div class="bg-white rounded-[2.5rem] p-24 text-center border border-slate-100 shadow-sm flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-900 mb-2">No interviews scheduled</h3>
                <p class="text-slate-500 text-lg mb-4 max-w-md mx-auto">
                    Schedule interviews from the applications page.
                </p>
                <a href="{{ route('employer.applications') }}" class="text-indigo-600 font-bold hover:text-indigo-700 hover:underline">
                    Go to Applications &rarr;
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

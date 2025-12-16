@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 font-sans">
    
    {{-- Header --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pt-12 pb-8">
        <a href="{{ route('employer.jobs.show', $application->job) }}" class="inline-flex items-center text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Job
        </a>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-tight">
            Schedule Interview
        </h1>
        <p class="text-slate-500 mt-3 text-lg font-medium">
            Schedule an interview with <span class="text-indigo-600 font-bold">{{ $application->applicant->name }}</span>
        </p>
    </div>

    {{-- Form Section --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl pb-20">
        <form action="{{ route('employer.interviews.store', $application) }}" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-12 space-y-12">
            @csrf

            <div class="space-y-8">
                <h2 class="text-xl font-black text-slate-900 border-b border-slate-100 pb-4">Interview Details</h2>

                 <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Interview Title *</label>
                    <input type="text" name="title" value="{{ old('title', 'Interview with ' . $application->applicant->name) }}" required
                         class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                    @error('title') <p class="text-red-500 text-sm font-bold pl-2">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-slate-600 placeholder-slate-400 leading-relaxed"
                        placeholder="Interview details, agenda, or special instructions...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                     <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Scheduled Date & Time *</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                             class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400">
                        @error('scheduled_at') <p class="text-red-500 text-sm font-bold pl-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Interview Type *</label>
                         <div class="relative">
                            <select name="type" required
                                class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 appearance-none cursor-pointer">
                                <option value="phone" {{ old('type') === 'phone' ? 'selected' : '' }}>Phone</option>
                                <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video Call</option>
                                <option value="in-person" {{ old('type') === 'in-person' ? 'selected' : '' }}>In-Person</option>
                                <option value="online" {{ old('type') === 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                             <svg class="w-5 h-5 absolute right-6 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                 <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                        class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-900 placeholder-slate-400"
                        placeholder="Office address or meeting location">
                </div>

                 <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-900 uppercase tracking-widest">Meeting Link</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}"
                         class="w-full px-6 py-4 rounded-2xl bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-bold text-slate-600 placeholder-slate-400"
                        placeholder="Zoom, Teams, or other video call link">
                </div>
            </div>

            <div class="flex justify-end items-center gap-6 pt-4">
                <a href="{{ route('employer.jobs.show', $application->job) }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" 
                        class="px-8 py-4 rounded-xl bg-indigo-600 text-white font-bold shadow-xl shadow-indigo-600/20 hover:shadow-2xl hover:bg-indigo-700 hover:scale-[1.02] transition-all duration-300">
                    Schedule Interview
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

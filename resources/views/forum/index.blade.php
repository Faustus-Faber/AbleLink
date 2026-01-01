
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
        <div class="max-w-xl">
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Community Forum</h1>
            <p class="text-lg text-slate-500 font-medium">Join the conversation, ask questions, and share your journey with the community.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <form action="{{ route('forum.index') }}" method="GET" class="relative group w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl leading-5 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all font-medium shadow-sm" 
                       placeholder="Search topics...">
            </form>

            <a href="{{ route('forum.create') }}" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center whitespace-nowrap !border-b-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Start Discussion
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-8 p-4 rounded-xl bg-amber-50 border border-amber-100 text-amber-800 flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span class="font-medium">{{ session('warning') }}</span>
        </div>
    @endif

    <div class="space-y-4">
        @forelse($threads as $thread)
            <div class="group bg-white p-6 sm:p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6">
                    <div class="flex-grow space-y-3">
                        <div class="flex items-center space-x-3 text-xs font-bold tracking-wider uppercase">
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                {{ $thread->category }}
                            </span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-slate-500">{{ $thread->created_at->format('M d, Y') }}</span>
                        </div>
                        
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight">
                            <a href="{{ route('forum.show', $thread->id) }}" class="hover:text-indigo-600 transition-colors focus:outline-none">
                                <span class="absolute inset-0" aria-hidden="true"></span>
                                {{ $thread->title }}
                            </a>
                        </h2>
                        
                        <p class="text-slate-600 line-clamp-2 text-base leading-relaxed">{{ Str::limit($thread->body, 180) }}</p>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end gap-4 sm:gap-1 flex-shrink-0">
                        <div class="flex items-center space-x-2 z-10">
                            <span class="text-sm font-semibold text-slate-700">{{ $thread->user->name }}</span>
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 border border-slate-200">
                                {{ substr($thread->user->name, 0, 1) }}
                            </div>
                        </div>

                        <div class="flex items-center space-x-1.5 text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100 mt-2">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                             <span class="text-xs font-bold">{{ $thread->replies_count }}</span>
                             <span class="text-xs">Replies</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">
                    @if(request('search'))
                        No matches found
                    @else
                        No discussions yet
                    @endif
                </h3>
                <p class="text-slate-500 max-w-md mx-auto mb-8">
                    @if(request('search'))
                        We couldn't find any threads matching "{{ request('search') }}". Try a different keyword or start a new topic.
                    @else
                        The community is quiet. Be the voice that starts the conversation!
                    @endif
                </p>
                <a href="{{ route('forum.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg hover:shadow-indigo-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Start a New Discussion
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $threads->links() }}
    </div>
</div>
@endsection

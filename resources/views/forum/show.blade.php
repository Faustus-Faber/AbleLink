<!-- //F13 - Farhan Zarif -->
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb / Back Link -->
    <nav class="mb-8">
        <a href="{{ route('forum.index') }}" class="inline-flex items-center px-5 py-2.5 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 hover:shadow-md transition-all">
            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Forum
        </a>
    </nav>

    <!-- Main Thread Card -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-10">
        <div class="p-8 sm:p-10">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200">
                        {{ $thread->category }}
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-sm font-medium text-slate-500">{{ $thread->created_at->format('F j, Y') }}</span>
                </div>
                
                <!-- ID/Actions could go here -->
                @auth
                    @if(Auth::id() === $thread->user_id)
                        <form action="{{ route('forum.destroy', $thread->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this thread?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition-colors border border-red-100">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Delete Thread
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
            
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-6 leading-tight">{{ $thread->title }}</h1>
            
            <!-- Author Info (Top) -->
            <div class="flex items-center mb-8 pb-8 border-b border-slate-100">
                <div class="w-12 h-12 rounded-full bg-slate-900 text-white flex items-center justify-center font-bold text-lg shadow-md ring-4 ring-slate-50">
                    {{ substr($thread->user->name, 0, 1) }}
                </div>
                <div class="ml-4">
                    <p class="text-base font-bold text-slate-900">{{ $thread->user->name }}</p>
                    <p class="text-xs text-slate-500 font-medium">Original Poster</p>
                </div>
                
                @auth
                    @if(Auth::id() !== $thread->user_id)
                        <div class="ml-auto">
                            <form action="{{ route('messages.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="recipient_id" value="{{ $thread->user_id }}">
                                <input type="hidden" name="body" value="Hi, I saw your post '{{ Str::limit($thread->title, 20) }}' on the forum...">
                                <button type="submit" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors bg-indigo-50 px-4 py-2 rounded-xl border border-indigo-100 hover:border-indigo-200">
                                    Message User
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <div class="prose prose-slate prose-lg max-w-none text-slate-700 leading-relaxed">
                {!! Str::markdown($thread->body) !!}
            </div>
        </div>
    </div>

    <!-- Replies Section -->
    <div class="mb-10">
        <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
            Replies 
            <span class="ml-3 px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-xs font-bold">{{ $thread->replies->count() }}</span>
        </h3>

        <div class="space-y-6">
            @forelse($thread->replies as $reply)
                <div class="flex items-start {{ $reply->user_id === $thread->user_id ? 'pl-6 border-l-4 border-indigo-500' : '' }}">
                    <div class="flex-shrink-0 mr-4 hidden sm:block">
                         <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-base border border-slate-200">
                            {{ substr($reply->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-grow bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm relative group">
                        <!-- Reply Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs border border-slate-200 sm:hidden mr-3">
                                    {{ substr($reply->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 text-sm sm:text-base">{{ $reply->user->name }}</span>
                                    @if($reply->user_id === $thread->user_id)
                                        <span class="ml-2 px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase border border-indigo-100">Author</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="text-slate-700 leading-relaxed prose prose-slate prose-sm max-w-none">
                            {!! Str::markdown($reply->body) !!}
                        </div>

                        @if(Auth::id() === $reply->user_id)
                            <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('forum.reply.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('Delete this reply?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors flex items-center px-3 py-1.5 rounded-lg hover:bg-red-50">
                                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Delete Reply
                                    </button>
                                </form>
                            </div>
                        @else
                             <!-- Placeholder for spacing if needed -->
                             <div class="h-2"></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-slate-300">
                    <p class="text-slate-500 font-medium">No replies yet. Be the first to join the conversation!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Reply Form -->
    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Leave a Reply</h3>
        <form action="{{ route('forum.reply', $thread->id) }}" method="POST">
            @csrf
            <div class="mb-6">
                <textarea name="body" rows="4" class="block w-full rounded-2xl border-slate-300 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 sm:text-base p-5 shadow-sm transition-all" placeholder="Share your thoughts... Markdown is supported." required></textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 !border-b-0">
                    Post Reply
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

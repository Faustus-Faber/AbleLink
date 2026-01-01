@extends('layouts.admin')

@section('admin-content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h3 class="text-2xl font-extrabold text-slate-900">Community Management</h3>
            <p class="text-slate-500 font-medium text-xs uppercase tracking-wide">Manage Forum Threads</p>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('admin.community.index') }}" method="GET" class="w-full md:w-96">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-white border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all shadow-sm"
                    placeholder="Search threads by title, content or author...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-100 mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($threads->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wide text-slate-500">
                            <th class="p-4 font-bold">Thread</th>
                            <th class="p-4 font-bold">Author</th>
                            <th class="p-4 font-bold text-center">Replies</th>
                            <th class="p-4 font-bold">Posted</th>
                            <th class="p-4 font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($threads as $thread)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $thread->title }}</div>
                                    <div class="text-xs text-slate-500 line-clamp-1 mt-1">{{ Str::limit($thread->content, 60) }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ substr($thread->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-700">{{ $thread->user->name ?? 'Unknown' }}</div>
                                            <div class="text-xs text-slate-400">{{ $thread->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        {{ $thread->replies_count ?? $thread->replies->count() }}
                                    </span>
                                </td>
                                <td class="p-4 text-sm text-slate-500">
                                    {{ $thread->created_at->format('M j, Y') }}
                                    <div class="text-xs text-slate-400">{{ $thread->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Read Button (Link to Public) -->
                                        <a href="{{ route('forum.show', $thread) }}" target="_blank"
                                           class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all font-bold text-xs" title="View Thread">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.community.threads.destroy', $thread) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this thread? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Delete Thread">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-50">
                {{ $threads->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No threads found</h3>
                <p class="text-slate-500">Try adjusting your search criteria.</p>
                @if(request('search'))
                    <a href="{{ route('admin.community.index') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Clear Search</a>
                @endif
            </div>
        @endif
    </div>
@endsection

@extends('layouts.admin')

@section('admin-content')
<div class="w-full px-6 py-8">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Moderation Dashboard</h1>
            <p class="text-slate-500 font-medium mt-2 text-lg">Review and manage flagged content to ensure community safety.</p>
        </div>
        <div class="flex items-center gap-2">
             <span class="px-4 py-2 rounded-full bg-orange-50 text-orange-700 text-xs font-bold uppercase tracking-wider border border-orange-100">
                {{ $flaggedThreads->count() + $flaggedReplies->count() }} Pending
            </span>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-8 flex items-center bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl p-4 shadow-sm">
             <svg class="w-5 h-5 mr-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="space-y-12">
        <!-- Flagged Threads Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center mr-3 text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
                    </div>
                    Flagged Threads
                </h2>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden shadow-xl shadow-slate-200/50">
                @if($flaggedThreads->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">All clean! No flagged threads found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 border-b border-slate-100 backdrop-blur-sm">
                                <tr>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Title</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Author</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Content Snippet</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Reason</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($flaggedThreads as $thread)
                                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <p class="font-bold text-slate-900 text-sm">{{ $thread->title }}</p>
                                            <p class="text-xs text-slate-400 mt-1">{{ $thread->created_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-3">
                                                    {{ substr($thread->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-700">{{ $thread->user->name ?? 'Unknown' }}</p>
                                                    @if($thread->user && $thread->user->isBanned())
                                                        <span class="text-[10px] font-bold text-red-600 uppercase tracking-wide bg-red-50 px-2 py-0.5 rounded border border-red-100">Banned</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 max-w-xs">
                                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">
                                                {{ Str::limit($thread->body, 80) }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                                {{ $thread->flag_reason ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end items-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                                <form action="{{ route('admin.moderation.thread.approve', $thread) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold hover:bg-emerald-100 hover:text-emerald-700 transition border border-emerald-100 shadow-sm">
                                                        Allow
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                    x-data
                                                    @click="$dispatch('open-confirmation-modal', { 
                                                        action: '{{ route('admin.moderation.thread.delete', $thread) }}', 
                                                        method: 'DELETE',
                                                        title: 'Delete Thread', 
                                                        message: 'Are you sure you want to delete this thread? This action cannot be undone.',
                                                        confirmText: 'Delete'
                                                    })"
                                                    class="px-4 py-2 rounded-xl bg-white text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-slate-900 transition border border-slate-200 shadow-sm">
                                                    Remove
                                                </button>
                                                 @if($thread->user && !$thread->user->isBanned())
                                                    <button type="button" 
                                                        x-data
                                                        @click="$dispatch('open-confirmation-modal', { 
                                                            action: '{{ route('admin.moderation.user.ban', $thread->user) }}', 
                                                            method: 'POST',
                                                            title: 'Ban User', 
                                                            message: 'Are you sure you want to ban {{ $thread->user->name }}? They will lose access to community features.',
                                                            confirmText: 'Ban User'
                                                        })"
                                                        class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition shadow-md shadow-slate-900/10">
                                                        Ban
                                                    </button>
                                                @elseif($thread->user && $thread->user->isBanned())

                                                     <button type="button" 
                                                        x-data 
                                                        @click="$dispatch('open-unban-modal', { action: '{{ route('admin.moderation.user.unban', $thread->user) }}', userName: '{{ $thread->user->name }}' })"
                                                        class="px-4 py-2 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold hover:bg-slate-200 transition">
                                                        Unban
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <!-- Flagged Replies Section -->
        <section>
            <div class="flex items-center justify-between mb-6">
                 <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center mr-3 text-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    Flagged Replies
                </h2>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden shadow-xl shadow-slate-200/50">
                @if($flaggedReplies->isEmpty())
                     <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">All caught up! No flagged replies.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 border-b border-slate-100 backdrop-blur-sm">
                                <tr>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Context</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Author</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Reply Content</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Reason</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($flaggedReplies as $reply)
                                    <tr class="hover:bg-orange-50/30 transition-colors group">
                                         <td class="px-8 py-6 max-w-xs">
                                            <p class="font-bold text-slate-900 text-sm truncate">{{ $reply->thread->title ?? 'Deleted Thread' }}</p>
                                            <p class="text-xs text-slate-400 mt-1">In response to thread</p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-3">
                                                    {{ substr($reply->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                 <div>
                                                    <p class="text-sm font-bold text-slate-700">{{ $reply->user->name ?? 'Unknown' }}</p>
                                                     @if($reply->user && $reply->user->isBanned())
                                                        <span class="text-[10px] font-bold text-red-600 uppercase tracking-wide bg-red-50 px-2 py-0.5 rounded border border-red-100">Banned</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6 max-w-xs">
                                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">
                                                {{ Str::limit($reply->body, 80) }}
                                            </p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                                {{ $reply->flag_reason ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            <div class="flex justify-end items-center gap-2 opacity-80 group-hover:opacity-100 transition-opacity">
                                                <form action="{{ route('admin.moderation.reply.approve', $reply) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold hover:bg-emerald-100 hover:text-emerald-700 transition border border-emerald-100 shadow-sm">
                                                        Allow
                                                    </button>
                                                </form>
                                                <button type="button" 
                                                    x-data
                                                    @click="$dispatch('open-confirmation-modal', { 
                                                        action: '{{ route('admin.moderation.reply.delete', $reply) }}', 
                                                        method: 'DELETE',
                                                        title: 'Delete Reply', 
                                                        message: 'Are you sure you want to delete this reply? This action cannot be undone.',
                                                        confirmText: 'Delete'
                                                    })"
                                                    class="px-4 py-2 rounded-xl bg-white text-slate-600 text-xs font-bold hover:bg-slate-50 hover:text-slate-900 transition border border-slate-200 shadow-sm">
                                                    Remove
                                                </button>
                                                @if($reply->user && !$reply->user->isBanned())
                                                    <button type="button" 
                                                        x-data
                                                        @click="$dispatch('open-confirmation-modal', { 
                                                            action: '{{ route('admin.moderation.user.ban', $reply->user) }}', 
                                                            method: 'POST',
                                                            title: 'Ban User', 
                                                            message: 'Are you sure you want to ban {{ $reply->user->name }}? They will lose access to community features.',
                                                            confirmText: 'Ban User'
                                                        })"
                                                        class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition shadow-md shadow-slate-900/10">
                                                        Ban
                                                    </button>
                                                @elseif($reply->user && $reply->user->isBanned())

                                                     <button type="button" 
                                                        x-data 
                                                        @click="$dispatch('open-unban-modal', { action: '{{ route('admin.moderation.user.unban', $reply->user) }}', userName: '{{ $reply->user->name }}' })"
                                                        class="px-4 py-2 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold hover:bg-slate-200 transition">
                                                        Unban
                                                    </button>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>

        <!-- Banned Users Management Section (F23 Fix) -->
        <section>
            <div class="flex items-center justify-between mb-6">
                 <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center mr-3 text-red-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                    </div>
                    Banned Users
                </h2>
            </div>
            
            <div class="bg-white border border-slate-200 rounded-[1.5rem] overflow-hidden shadow-xl shadow-slate-200/50">
                @if($bannedUsers->isEmpty())
                     <div class="p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-slate-500 font-bold text-lg">No active bans.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/80 border-b border-slate-100 backdrop-blur-sm">
                                <tr>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">User</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Email</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400">Banned At</th>
                                    <th class="px-8 py-5 text-[11px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($bannedUsers as $user)
                                    <tr class="hover:bg-red-50/30 transition-colors group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600 mr-3">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <p class="text-sm font-bold text-slate-900">{{ $user->name }}</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                             <p class="text-sm text-slate-600">{{ $user->email }}</p>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-xs text-red-600 font-medium">
                                                {{ $user->banned_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                             <button type="button" 
                                                x-data 
                                                @click="$dispatch('open-unban-modal', { action: '{{ route('admin.moderation.user.unban', $user) }}', userName: '{{ $user->name }}' })"
                                                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-500 text-xs font-bold hover:bg-slate-200 transition">
                                                Unban
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Unban Confirmation Modal -->
    <div x-data="{ 
        open: false, 
        action: '', 
        userName: '' 
    }" 
    @open-unban-modal.window="open = true; action = $event.detail.action; userName = $event.detail.userName"
    @keydown.escape.window="open = false"
    class="relative z-50">
        
        <!-- Backdrop -->
        <div x-show="open" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
            @click="open = false"
            style="display: none;"></div>

        <!-- Panel -->
        <div x-show="open" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="fixed inset-0 z-10 overflow-y-auto"
            style="display: none;">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-slate-900">Unban User</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500">
                                        Are you sure you want to lift the ban for <span class="font-bold text-slate-800" x-text="userName"></span>? They will immediately regain access to all community features.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                        <form :action="action" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-all hover:-translate-y-0.5">
                                Lift Ban
                            </button>
                        </form>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all" @click="open = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Generic Confirmation Modal (Red Theme) -->
    <div x-data="{ 
        open: false, 
        action: '', 
        method: 'POST',
        title: '',
        message: '',
        confirmText: 'Confirm'
    }" 
    @open-confirmation-modal.window="
        open = true; 
        action = $event.detail.action; 
        method = $event.detail.method || 'POST'; 
        title = $event.detail.title; 
        message = $event.detail.message; 
        confirmText = $event.detail.confirmText || 'Confirm';
    "
    @keydown.escape.window="open = false"
    class="relative z-50">
        
        <!-- Backdrop -->
        <div x-show="open" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
            @click="open = false"
            style="display: none;"></div>

        <!-- Panel -->
        <div x-show="open" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="fixed inset-0 z-10 overflow-y-auto"
            style="display: none;">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-slate-900" x-text="title"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500" x-text="message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100">
                        <form :action="action" method="POST">
                            @csrf
                            <input type="hidden" name="_method" :value="method">
                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-600/20 hover:bg-red-500 sm:ml-3 sm:w-auto transition-all hover:-translate-y-0.5" x-text="confirmText">
                                Confirm
                            </button>
                        </form>
                        <button type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-all" @click="open = false">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

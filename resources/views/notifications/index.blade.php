@extends('layouts.app')
{{-- F9 - Evan Yuvraj Munshi --}}
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900">Notifications</h1>
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @forelse($notifications as $notification)
            <div class="p-6 border-b border-slate-100 hover:bg-slate-50 transition-colors {{ $notification->read_at ? 'opacity-75' : '' }}">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-lg font-bold text-slate-900 mb-1">
                            {{ $notification->data['message'] ?? 'New Notification' }}
                        </p>
                        <p class="text-sm text-slate-500">
                            {{ $notification->created_at->diffForHumans() }} • 
                            {{ ucfirst(str_replace('_', ' ', Str::snake(class_basename($notification->type)))) }}
                        </p>
                    </div>
                    
                    @if(is_null($notification->read_at))
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
                                Mark as Read
                            </button>
                        </form>
                    @else
                        <span class="text-sm font-medium text-slate-400">Read</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">No notifications yet</h3>
                <p class="text-slate-500">We'll let you know when something important happens.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
@endsection

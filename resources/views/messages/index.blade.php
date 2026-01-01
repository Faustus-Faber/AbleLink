@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-900 mb-8">Messages</h1>

    <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="md:flex h-[600px]">
            <div class="md:w-1/3 border-r border-slate-100 bg-slate-50 overflow-y-auto">
                <div class="p-4 border-b border-slate-100 bg-white sticky top-0 z-10 flex justify-between items-center">
                    <h2 class="font-bold text-slate-700">Conversations</h2>
                    <button onclick="document.getElementById('new-chat-modal').classList.remove('hidden')" class="text-indigo-600 hover:text-indigo-800 p-1" title="New Message">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($conversations as $convo)
                        @php
                            $otherUser = $convo->user_one_id == Auth::id() ? $convo->userTwo : $convo->userOne;
                            $isActive = isset($activeConversation) && $activeConversation->id === $convo->id;
                        @endphp
                        @if($otherUser && $otherUser->name)
                            <a href="{{ route('messages.show', $convo->id) }}" class="block p-4 hover:bg-indigo-50 transition-colors {{ $isActive ? 'bg-indigo-50 border-r-4 border-indigo-600' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center font-bold text-slate-500">
                                        {{ substr($otherUser->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $otherUser->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">
                                            @if($convo->messages->last())
                                                {{ Str::limit($convo->messages->last()->body, 30) }} · {{ $convo->messages->last()->created_at->diffForHumans(null, true, true) }}
                                            @else
                                                Start a conversation
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-slate-500 italic">No conversations yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="hidden md:flex md:w-2/3 flex-col items-center justify-center text-center p-8 bg-white">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-400 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Select a Conversation</h3>
                <p class="text-slate-500 max-w-sm">Choose a person from the left to view your secure, encrypted messages.</p>
            </div>
        </div>
    </div>

    <div id="new-chat-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('new-chat-modal').classList.add('hidden')"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-md w-full overflow-hidden relative border border-slate-100">
                
                <button onclick="document.getElementById('new-chat-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-full hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-6">
                    <div class="text-center mb-5">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-indigo-100 rotate-3 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-slate-900" id="modal-title">New Conversation</h3>
                    </div>

                    <form action="{{ route('messages.store') }}" method="POST" class="space-y-4" id="new-message-form">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5 uppercase tracking-wide">Recipient</label>
                            
                            <div class="relative mb-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="user-search" placeholder="Search people..." class="block w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-sm">
                            </div>

                            <input type="hidden" name="recipient_id" id="recipient_id" required>

                            <div class="border border-slate-200 rounded-lg max-h-32 overflow-y-auto custom-scrollbar bg-white" id="user-list">
                                @forelse($users as $user)
                                    <div class="user-option p-2.5 hover:bg-slate-50 cursor-pointer flex items-center transition-all border-b border-slate-50 last:border-0" onclick="selectUser('{{ $user->id }}', this)">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex-shrink-0 flex items-center justify-center font-bold text-white text-xs shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-bold text-slate-900 user-name">{{ $user->name }}</p>
                                            <p class="text-[10px] text-slate-500 user-email">{{ $user->email }}</p>
                                        </div>
                                        <div class="w-5 h-5 rounded-full bg-indigo-500 text-white items-center justify-center hidden check-icon shadow-sm transform scale-0 transition-transform duration-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center">
                                        <p class="text-slate-500 text-xs">No other users found.</p>
                                    </div>
                                @endforelse
                                <p id="no-users-found" class="hidden p-3 text-center text-xs text-slate-500">No users found.</p>
                            </div>
                        </div>

                        <div>
                            <label for="body" class="block text-xs font-bold text-slate-900 mb-1.5 uppercase tracking-wide">Message</label>
                            <textarea name="body" id="body" rows="3" class="block w-full rounded-lg border-slate-200 bg-slate-50 p-3 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-sm resize-none" placeholder="Write your message here..." required></textarea>
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="w-full flex items-center justify-center py-3 px-6 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-all hover:-translate-y-0.5">
                                Send Message
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
                
                <script>
                    document.getElementById('user-search').addEventListener('input', function(e) {
                        const searchTerm = e.target.value.toLowerCase();
                        const users = document.querySelectorAll('.user-option');
                        let matchCount = 0;

                        users.forEach(user => {
                            const name = user.querySelector('.user-name').innerText.toLowerCase();
                            const email = user.querySelector('.user-email').innerText.toLowerCase();
                            
                            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                                user.classList.remove('hidden');
                                matchCount++;
                            } else {
                                user.classList.add('hidden');
                            }
                        });

                        const noUsersMsg = document.getElementById('no-users-found');
                        if (matchCount === 0 && users.length > 0) {
                            noUsersMsg.classList.remove('hidden');
                        } else {
                            noUsersMsg.classList.add('hidden');
                        }
                    });

                    function selectUser(id, element) {
                        document.getElementById('recipient_id').value = id;

                        document.querySelectorAll('.user-option').forEach(el => {
                            el.classList.remove('bg-indigo-50', 'ring-1', 'ring-indigo-500', 'border-indigo-200');
                            const icon = el.querySelector('.check-icon');
                            icon.classList.add('hidden');
                            icon.classList.remove('flex', 'scale-100');
                        });
                        
                        element.classList.add('bg-indigo-50', 'ring-1', 'ring-indigo-500', 'border-indigo-200');
                        const activeIcon = element.querySelector('.check-icon');
                        activeIcon.classList.remove('hidden');
                        activeIcon.classList.add('flex');
                        
                        setTimeout(() => {
                            activeIcon.classList.add('scale-100');
                        }, 10);
                    }

                    const newMessageForm = document.getElementById('new-message-form');
                    if (newMessageForm) {
                        newMessageForm.addEventListener('submit', function(e) {
                            if (!document.getElementById('recipient_id').value) {
                                e.preventDefault();
                                alert('Please select a recipient to message.');
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
</div>
@endsection

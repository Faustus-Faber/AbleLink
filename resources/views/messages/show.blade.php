<!-- //F13 - Farhan Zarif -->
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-slate-900 mb-8">Messages</h1>

    <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="md:flex h-[600px]">
            <!-- Sidebar / Conversation List -->
            <div class="hidden md:block md:w-1/3 border-r border-slate-100 bg-slate-50 overflow-y-auto">
                <div class="p-4 border-b border-slate-100 bg-white sticky top-0 z-10 flex justify-between items-center">
                    <h2 class="font-bold text-slate-700">Conversations</h2>
                    <button onclick="document.getElementById('new-chat-modal').classList.remove('hidden')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-full hover:bg-slate-50 transition-colors" title="New Message">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($conversations as $convo)
                        @php
                            $otherUser = $convo->user_one_id == Auth::id() ? $convo->userTwo : $convo->userOne;
                            $isActive = $conversation->id === $convo->id;
                        @endphp
                        @if($otherUser && $otherUser->name)
                             <a href="{{ route('messages.show', $convo->id) }}" class="block p-4 hover:bg-indigo-50 transition-colors {{ $isActive ? 'bg-indigo-50 border-r-4 border-indigo-600' : 'border-r-4 border-transparent' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 flex-shrink-0 flex items-center justify-center font-bold text-slate-500 overflow-hidden">
                                            {{ substr($otherUser->name, 0, 1) }}
                                        </div>
                                        @if($isActive)
                                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline">
                                            <p class="text-sm font-bold text-slate-900 truncate">{{ $otherUser->name }}</p>
                                            @if($convo->messages->last())
                                                <span class="text-[10px] text-slate-400">{{ $convo->messages->last()->created_at->format('H:i') }}</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 truncate mt-0.5">
                                            @if($convo->messages->last())
                                               <span class="{{ $isActive ? 'text-indigo-600 font-medium' : 'text-slate-500' }}">
                                                    {{ $convo->messages->last()->sender_id === Auth::id() ? 'You: ' : '' }}
                                                    {{ Str::limit($convo->messages->last()->body, 30) }}
                                               </span>
                                            @else
                                                <span class="italic text-slate-400">No messages yet</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-slate-500 italic">No conversations found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-full md:w-2/3 flex flex-col h-full bg-white relative">
                @php
                    $otherUser = $conversation->user_one_id === Auth::id() ? $conversation->userTwo : $conversation->userOne;
                @endphp
                
                <!-- Chat Header -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-white z-10">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('messages.index') }}" class="md:hidden text-slate-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                            {{ substr($otherUser->name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-900">{{ $otherUser->name }}</h2>
                            <p class="text-xs text-green-600 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span> Encrypted
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('messages.destroy', $conversation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this conversation? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors p-2 rounded-full hover:bg-red-50" title="Delete Conversation">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50" id="chat-messages">
                    @forelse($conversation->messages as $msg)
                        <div class="flex {{ $msg->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[75%] {{ $msg->sender_id === Auth::id() ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none' }} rounded-2xl px-5 py-3 shadow-sm">
                                @if($msg->attachment_path)
                                    @if(str_starts_with($msg->attachment_type, 'image/'))
                                        <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $msg->attachment_path) }}" alt="Attachment" class="rounded-lg max-w-full h-auto mb-2 border border-white/20 hover:opacity-90 transition-opacity cursor-pointer object-cover max-h-64">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="flex items-center p-3 {{ $msg->sender_id === Auth::id() ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} rounded-xl mb-2 transition-colors border border-transparent">
                                            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <div class="overflow-hidden text-left">
                                                <p class="text-xs font-bold truncate">{{ $msg->attachment_original_name ?? 'File Attachment' }}</p>
                                                <p class="text-[10px] opacity-70">Download</p>
                                            </div>
                                        </a>
                                    @endif
                                @endif
                                <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-slate-400 text-sm">This is the start of your encrypted conversation with {{ $otherUser->name }}.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="p-4 border-t border-slate-100 bg-white">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex items-end space-x-3" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="recipient_id" value="{{ $otherUser->id }}">
                        
                        <!-- File Input -->
                        <div class="relative pb-1">
                            <input type="file" name="attachment" id="file-input" class="hidden" onchange="previewFile(this)">
                            <button type="button" onclick="document.getElementById('file-input').click()" class="p-2.5 text-slate-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500/20" title="Attach file">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            </button>
                            
                            <!-- Preview Popover -->
                            <div id="file-preview-container" class="hidden absolute bottom-full left-0 mb-3 w-48 bg-white rounded-xl shadow-xl border border-indigo-100 p-2 z-20 transform transition-all">
                                <div class="relative group">
                                    <button type="button" onclick="clearFile()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md transform scale-90 opacity-0 group-hover:opacity-100 transition-all">
                                         <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <div id="preview-content" class="text-xs text-slate-600 truncate p-2 bg-indigo-50/50 rounded-lg text-center font-medium border border-indigo-100/50"></div>
                                </div>
                                <div class="absolute -bottom-1 left-4 w-2 h-2 bg-white border-b border-r border-indigo-100 transform rotate-45"></div>
                            </div>
                        </div>

                        <!-- Text Input -->
                        <div class="flex-1">
                            <textarea name="body" id="chat-input" rows="1" placeholder="Type a message..." class="block w-full rounded-2xl border-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder-slate-400 bg-slate-50 py-3 px-4 resize-none leading-normal" style="min-height: 48px; max-height: 120px;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        </div>

                        <!-- Send Button -->
                        <div class="pb-1">
                            <button type="submit" id="chat-send-btn" class="p-3 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-all shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </div>
                    </form>
                    @if(session('error'))
                        <p class="text-red-500 text-xs mt-2 text-center">{{ session('error') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // Scroll to bottom of chat
        const chatContainer = document.getElementById('chat-messages');
        if(chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // File Preview Logic
        function previewFile(input) {
            const previewContainer = document.getElementById('file-preview-container');
            const previewContent = document.getElementById('preview-content');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                let content = '';

                // Check size (25MB limit handled in backend, but good to warn frontend)
                if (file.size > 25 * 1024 * 1024) {
                    alert('File is too large. Max 25MB.');
                    clearFile();
                    return;
                }

                if (file.type.startsWith('image/')) {
                    content = `<div class="flex items-center justify-center"><svg class="w-4 h-4 mr-1 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Image selected</div>`;
                } else {
                     content = `<div class="flex items-center justify-center"><svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> ${file.name}</div>`;
                }
                
                previewContent.innerHTML = content;
                previewContainer.classList.remove('hidden');
            }
        }

        function clearFile() {
            const input = document.getElementById('file-input');
            input.value = ''; // Reset file input
            document.getElementById('file-preview-container').classList.add('hidden');
        }
    </script>
    
    <!-- New Chat Modal -->
    <div id="new-chat-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('new-chat-modal').classList.add('hidden')"></div>

        <!-- Modal Panel -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-md w-full overflow-hidden relative border border-slate-100">
                
                <!-- Close Button -->
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

                    <form action="{{ route('messages.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- User Selection -->
                        <div>
                            <label class="block text-xs font-bold text-slate-900 mb-1.5 uppercase tracking-wide">Recipient</label>
                            
                            <!-- Search Input -->
                            <div class="relative mb-2">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" id="user-search" placeholder="Search people..." class="block w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 py-2.5 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-sm">
                            </div>

                            <input type="hidden" name="recipient_id" id="recipient_id" required>

                            <!-- User List -->
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
                                        <!-- Checkmark -->
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

                        <!-- Message Body -->
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
                    // Search Logic
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

                    // Selection Logic
                    function selectUser(id, element) {
                        document.getElementById('recipient_id').value = id;

                        // Visual Reset
                        document.querySelectorAll('.user-option').forEach(el => {
                            el.classList.remove('bg-indigo-50', 'ring-1', 'ring-indigo-500', 'border-indigo-500');
                            const icon = el.querySelector('.check-icon');
                            icon.classList.add('hidden');
                            icon.classList.remove('flex', 'scale-100');
                        });
                        
                        // Active State
                        element.classList.add('bg-indigo-50', 'ring-1', 'ring-indigo-500', 'border-indigo-500');
                        const activeIcon = element.querySelector('.check-icon');
                        activeIcon.classList.remove('hidden');
                        activeIcon.classList.add('flex');
                        
                        // Small timeout to allow display:flex to apply before scaling
                        setTimeout(() => {
                            activeIcon.classList.add('scale-100');
                        }, 10);
                    }

                    // Form Validation logic
                    const newMessageForm = document.querySelector('.space-y-4'); // Using class since form ID is missing in show view but present in index
                    // Actually, let's look at line 235: <form action="{{ route('messages.store') }}" method="POST" class="space-y-4">
                    // It doesn't have an ID in this view. I should add one or target it more specifically.
                    
                    // Better approach: Add ID to the form first, then target it. But to minimize touch points with just replace_file_content on this block:
                    const messageForms = document.querySelectorAll('form[action*="messages"]');
                    messageForms.forEach(form => {
                        form.addEventListener('submit', function(e) {
                             // Only validate if it's the new message form (which has recipient_id input)
                             const recipientInput = form.querySelector('input[name="recipient_id"]');
                             // And ensuring it's not the chat input form (which sends to same route but has hidden recipient input populated)
                             // wait, chat input form has recipient_id populated.
                             // The modal form has id="recipient_id". The chat input form has name="recipient_id" but value is set.
                             // The modal form's recipient_id is empty initially.
                             
                             if (form.classList.contains('space-y-4')) { // Unique class for the modal form
                                const recipientId = document.getElementById('recipient_id').value;
                                if(!recipientId) {
                                    e.preventDefault();
                                    alert('Please select a recipient to message.');
                                }
                             }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection

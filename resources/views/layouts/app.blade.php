<!DOCTYPE html>
@php
    $bodyClassString = '';
    
    // F5 - Adaptive UI Class Generation
    if (isset($accessibilityPreferences) && is_array($accessibilityPreferences)) {
        if (isset($accessibilityPreferences['font_size']) && $accessibilityPreferences['font_size'] !== 'normal') {
            $bodyClassString .= ' access-font-' . $accessibilityPreferences['font_size'];
        }
        
        if (isset($accessibilityPreferences['spacing']) && $accessibilityPreferences['spacing'] !== 'normal') {
            $bodyClassString .= ' access-spacing-' . $accessibilityPreferences['spacing'];
        }
        
        if (isset($accessibilityPreferences['contrast_mode']) && $accessibilityPreferences['contrast_mode'] !== 'normal') {
            $bodyClassString .= ' access-contrast-' . $accessibilityPreferences['contrast_mode'];
        }
        
        if (!empty($accessibilityPreferences['color_blind_mode']) && $accessibilityPreferences['color_blind_mode'] !== 'none') {
            $bodyClassString .= ' access-cb-' . $accessibilityPreferences['color_blind_mode'];
        }
        
        if (!empty($accessibilityPreferences['reduce_motion'])) {
            $bodyClassString .= ' access-reduce-motion';
        }
        
        if (!empty($accessibilityPreferences['screen_reader_enabled'])) {
            $bodyClassString .= ' access-screen-reader';
        }
    }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $bodyClassString }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AbleLink') }}</title>

    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

    <script>
        window.ableLinkUserRole = @json(auth()->check() ? auth()->user()->role : 'guest');
        
        // F5 - Shared Preferences for JS
        window.ableLinkPrefs = @json($accessibilityPreferences ?? []);
        
        // F6 - Context for Voice Interaction
        window.ableLinkIsDisabled = @json(auth()->check() && auth()->user()->hasRole('disabled'));

        // F5 - Text-to-Speech Helper
        function speak(text) {
            if (window.ableLinkPrefs && window.ableLinkPrefs.text_to_speech_enabled) {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(text);
                    window.speechSynthesis.speak(utterance);
                }
            }
        }
    </script>
    @vite(['resources/js/app.js'])


    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<a href="#main-content" class="skip-to-content">Skip to main content</a>
<body class="antialiased text-slate-900 bg-slate-50 {{ $bodyClassString }}">
    
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm/50 backdrop-blur-md bg-white/90">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            <a href="{{ url('/') }}" class="group flex items-center space-x-3">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <svg class="w-10 h-10 transform group-hover:scale-110 transition-transform duration-300 drop-shadow-md" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M25 45C36.0457 45 45 36.0457 45 25C45 13.9543 36.0457 5 25 5C13.9543 5 5 13.9543 5 25C5 36.0457 13.9543 45 25 45Z" fill="url(#paint0_linear)" class="opacity-10"/>
                        <path d="M16 34L22 18L28 34" stroke="url(#paint1_linear)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18.5 28H25.5" stroke="url(#paint2_linear)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="34" cy="18" r="4" fill="url(#paint3_linear)"/>
                        <path d="M34 22V28" stroke="url(#paint4_linear)" stroke-width="3" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="paint0_linear" x1="5" y1="5" x2="45" y2="45" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                            <linearGradient id="paint1_linear" x1="16" y1="34" x2="28" y2="18" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                            <linearGradient id="paint2_linear" x1="18.5" y1="28" x2="25.5" y2="28" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"/>
                                <stop offset="1" stop-color="#7C3AED"/>
                            </linearGradient>
                            <linearGradient id="paint3_linear" x1="30" y1="18" x2="38" y2="18" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F472B6"/>
                                <stop offset="1" stop-color="#9333EA"/>
                            </linearGradient>
                            <linearGradient id="paint4_linear" x1="34" y1="22" x2="34" y2="28" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F472B6"/>
                                <stop offset="1" stop-color="#9333EA"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <span class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 tracking-tight">AbleLink</span>
            </a>
            
            <nav class="hidden md:flex items-center space-x-2">

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-all">
                           Dashboard
                        </a>
                        @if(Auth::user()->hasRole('caregiver'))
                            <a href="{{ route('caregiver.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Caregiver Dashboard
                            </a>
                        @endif
                        <a href="{{ route('requests.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            Requests
                        </a>

                        <!-- Profile Link -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                             <button @click="open = !open" class="group relative flex items-center gap-2 focus:outline-none">
                                <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 font-bold border border-zinc-200 group-hover:bg-zinc-200 transition-colors">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 py-2 z-50 origin-top-right border border-zinc-100"
                                 style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-zinc-100 bg-zinc-50 rounded-t-xl mb-1">
                                    <p class="text-xs text-zinc-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900">Your Profile</a>
                                <a href="{{ route('documents.upload') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900">OCR & Simplifier</a>
                                <a href="{{ route('accessibility.edit') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 hover:text-zinc-900 border-b border-zinc-100 mb-1">Accessibility</a>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-all">
                           Log in
                        </a>

                        <a href="{{ route('admin.login') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition-all">
                           Admin
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="ml-2 px-7 py-3 rounded-full bg-slate-900 text-white font-bold shadow-lg hover:shadow-xl hover:bg-slate-800 hover:-translate-y-0.5 transition-all">
                               Get Started
                            </a>
                        @endif
                    @endauth
                @endif
            </nav>
        </div>
    </header>

    <div class="relative min-h-screen flex flex-col">
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
             <div class="absolute -top-[20%] -right-[10%] w-[800px] h-[800px] bg-blue-100/50 rounded-full blur-3xl opacity-60"></div>
             <div class="absolute top-[20%] -left-[10%] w-[600px] h-[600px] bg-purple-100/50 rounded-full blur-3xl opacity-60"></div>
        </div>

        <main class="relative z-10 flex-grow container mx-auto px-6 py-12">
            @yield('content')
        </main>

    @include('partials.footer')

    @include('partials.footer')
    
    <!-- F12 - Recommendation Modal -->
    <x-recommendation-modal />

    <!-- F15 - Emergency SOS Trigger -->
    @auth
        <div class="fixed bottom-6 left-6 z-50">
            <form action="{{ route('sos.store') }}" method="POST" onsubmit="return confirm('Are you sure you want to trigger an SOS alert? This will start a live audio recording and notify your emergency contacts.');">
                @csrf
                <input type="hidden" name="latitude" id="sos_latitude">
                <input type="hidden" name="longitude" id="sos_longitude">
                <button type="submit" class="w-16 h-16 bg-red-600 rounded-full shadow-2xl flex items-center justify-center text-white hover:bg-red-700 hover:scale-110 transition-all duration-300 animate-pulse border-4 border-white/30">
                    <span class="sr-only">Emergency SOS</span>
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </button>
            </form>
            <script>
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('sos_latitude').value = position.coords.latitude;
                        document.getElementById('sos_longitude').value = position.coords.longitude;
                    });
                }
            </script>
        </div>
    @endauth

    <!-- F7 - AI Chat Widget -->
    @auth
    <div x-data="aiChatWidget()" x-init="init()" class="fixed bottom-6 right-6 z-50 font-sans">
        <!-- Chat Toggle Button -->
        <button 
            @click="toggleChat"
            class="w-14 h-14 bg-slate-900 rounded-full shadow-2xl flex items-center justify-center text-white hover:bg-slate-800 hover:scale-105 transition-all duration-300 ease-out focus:outline-none focus:ring-4 focus:ring-slate-200"
            aria-label="Open AI Assistant"
        >
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
        </button>

        <!-- Chat Window -->
        <div 
            x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="absolute bottom-20 right-0 w-[400px] h-[600px] bg-white rounded-2xl shadow-2xl border border-slate-100 flex flex-col overflow-hidden"
            style="display: none;"
        >
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-100 bg-white flex justify-between items-center bg-gray-50/50 backdrop-blur-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">AbleBot AI</h3>
                        <p class="text-xs text-slate-500 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Online
                        </p>
                    </div>
                </div>
                <button @click="toggleChat" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50/50 scroll-smooth" id="chat-messages">
                <!-- Welcome Message -->
                <div class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center border border-slate-200">
                        <span class="text-xs font-bold text-slate-600">AI</span>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-sm text-slate-600 leading-relaxed max-w-[85%]">
                        Hello! I'm AbleBot. I can help you navigate, fill forms, or answer questions about AbleLink. How can I assist you today?
                    </div>
                </div>

                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.isUser ? 'flex-row-reverse' : 'flex-row'" class="flex gap-3 animate-fade-in-up">
                        <div 
                            :class="msg.isUser ? 'bg-slate-900 border-slate-900 text-white' : 'bg-slate-100 border-slate-200 text-slate-600'"
                            class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center border text-xs font-bold">
                            <span x-text="msg.isUser ? 'ME' : 'AI'"></span>
                        </div>
                        <div 
                            :class="msg.isUser ? 'bg-slate-800 text-white rounded-tr-none' : 'bg-white text-slate-600 rounded-tl-none border border-slate-100 shadow-sm'"
                            class="p-3.5 rounded-2xl text-sm leading-relaxed max-w-[85%] break-words">
                            <p x-text="msg.text"></p>
                        </div>
                    </div>
                </template>

                <div x-show="isLoading" class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex-shrink-0 flex items-center justify-center border border-slate-200">
                        <span class="text-xs font-bold text-slate-600">AI</span>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 max-w-[85%] flex gap-1.5 items-center">
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100">
                <form @submit.prevent="sendMessage" class="relative">
                    <input 
                        type="text" 
                        x-model="userInput" 
                        id="chat-input"
                        placeholder="Type a message..." 
                        class="w-full pl-5 pr-12 py-3.5 bg-slate-50 border-slate-200 focus:border-slate-300 focus:ring-0 rounded-xl text-sm transition-all shadow-inner placeholder:text-slate-400"
                    >
                    <button 
                        type="submit"
                        id="chat-send-btn"
                        :disabled="isLoading || !userInput.trim()"
                        class="absolute right-2 top-2 p-1.5 bg-slate-900 text-white rounded-lg hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function aiChatWidget() {
            return {
                isOpen: false,
                userInput: '',
                messages: [],
                isLoading: false,

                init() {
                    // Auto-scroll logic
                    this.$watch('messages', () => {
                        this.$nextTick(() => {
                            const container = document.getElementById('chat-messages');
                            container.scrollTop = container.scrollHeight;
                        });
                    });
                },

                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen) {
                        this.$nextTick(() => document.getElementById('chat-input').focus());
                    }
                },

                async sendMessage() {
                    if (!this.userInput.trim() || this.isLoading) return;

                    const text = this.userInput;
                    this.messages.push({ text: text, isUser: true });
                    this.userInput = '';
                    this.isLoading = true;

                    try {
                        // Gather context
                        const pageStructure = this.getPageStructure();

                        const response = await fetch('{{ route("ai.chat") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                message: text,
                                current_url: window.location.href,
                                page_structure: pageStructure
                            })
                        });

                        const data = await response.json();

                        if (data.reply) {
                            this.messages.push({ text: data.reply, isUser: false });
                        }

                        // Voice Feedback (F6 Integration if available)
                        if (data.voice_summary && typeof speak === 'function') {
                            speak(data.voice_summary);
                        }

                        // Execute Actions
                        if (data.actions && Array.isArray(data.actions)) {
                             await this.executeActions(data.actions);
                        } else if (data.action) {
                             // Fallback for single action
                             if (data.action === 'navigate' && data.url) {
                                  window.location.href = data.url;
                             }
                        }

                    } catch (error) {
                        console.error('AI Error:', error);
                        this.messages.push({ text: "Sorry, something went wrong. Please try again.", isUser: false });
                    } finally {
                        this.isLoading = false;
                    }
                },

                getPageStructure() {
                     // Basic DOM scraper for context
                     // In a real app, this would be more robust
                     const elements = [];
                     
                     // Helper to push input
                     document.querySelectorAll('input, select, textarea').forEach(el => {
                         if(el.type === 'hidden') return;
                         let label = null;
                         if(el.id) {
                             const setLabel = document.querySelector(`label[for="${el.id}"]`);
                             if(setLabel) label = setLabel.innerText;
                         }
                         elements.push({
                             tag: el.tagName.toLowerCase(),
                             id: el.id,
                             type: el.type || null,
                             name: el.name,
                             placeholder: el.placeholder,
                             text: label,
                             value: el.value,
                             checked: el.checked
                         });
                     });

                     return { elements: elements };
                },

                async executeActions(actions) {
                    for (const action of actions) {
                        const name = action.name;
                        const args = action.args || {};

                        if (name === 'navigate' && args.url) {
                            window.location.href = args.url;
                            break; // Navigation stops other actions on this page
                        }

                        if (name === 'fill_input' && args.selector) {
                             const el = document.querySelector(args.selector);
                             if (el) {
                                 el.value = args.value;
                                 el.dispatchEvent(new Event('input', { bubbles: true }));
                                 el.dispatchEvent(new Event('change', { bubbles: true }));
                             }
                        }

                        if (name === 'click_element' && args.selector) {
                            const el = document.querySelector(args.selector);
                            if (el) el.click();
                        }
                    }
                }
            }
        }
    </script>
    @endauth

    </div>
</body>
</html>

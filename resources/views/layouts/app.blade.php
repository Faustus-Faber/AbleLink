<!DOCTYPE html>
@php
    // F5 - Rifat Jahan Roza
    $prefs = $accessibilityPreferences ?? session('accessibility_preferences', []);

    $fontSize = $prefs['font_size'] ?? 'normal';
    $contrast = $prefs['contrast_mode'] ?? 'normal';
    $spacing = $prefs['spacing'] ?? 'normal';
    $cbMode  = $prefs['color_blind_mode'] ?? 'none';

    $bodyClasses = [
        'access-font-small' => $fontSize === 'small',
        'access-font-normal' => $fontSize === 'normal',
        'access-font-large' => $fontSize === 'large',
        'access-font-xlarge' => $fontSize === 'extra_large',
        'access-spacing-compact' => $spacing === 'compact',
        'access-spacing-relaxed' => $spacing === 'relaxed',
        'access-contrast-high' => $contrast === 'high',
        'access-contrast-inverted' => $contrast === 'inverted',
        'access-reduce-motion' => !empty($prefs['animation_reduced']),
        'access-screen-reader' => !empty($prefs['screen_reader_enabled']),
        'access-cb-protanopia' => $cbMode === 'protanopia',
        'access-cb-deuteranopia' => $cbMode === 'deuteranopia',
        'access-cb-tritanopia' => $cbMode === 'tritanopia',
    ];

    $bodyClassString = collect($bodyClasses)
        ->filter()
        ->keys()
        ->implode(' ');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $bodyClassString }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AbleLink') }}</title>

    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

    <!-- //F6 - Evan Yuvraj Munshi// -->
    <script>
        window.ableLinkPrefs = @json($prefs);
        window.ableLinkUserRole = @json(auth()->check() ? auth()->user()->role : 'guest');
        window.ableLinkIsDisabled = @json(auth()->check() && auth()->user()->hasRole('disabled'));
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

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center">
                                Services
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-2 z-50">
                                <a href="{{ Auth::user()->hasRole('employer') ? route('employer.jobs.index') : route('jobs.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">
                                   Find Jobs
                                </a>
                                <a href="{{ route('courses.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 font-medium">
                                   Learning Hub
                                </a>
                                <a href="{{ route('community.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-700 font-medium">
                                   Community
                                </a>
                                @if(Auth::user()->hasRole('disabled'))
                                    <a href="{{ route('aid.index') }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-green-50 hover:text-green-700 font-medium">
                                       Aid Directory
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center">
                                Tools
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-2 z-50">
                                <a href="{{ route('documents.upload') }}"
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                   OCR & Simplify
                                </a>

                                @if(Auth::user()->hasRole('disabled'))
                                    <a href="{{ route('health.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Health Tracker
                                    </a>
                                    
                                    <a href="{{ route('user.appointments.index') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Doctor Appointments
                                    </a>
                                @endif

                                @if(Auth::user()->hasRole('caregiver'))
                                    <a href="{{ route('caregiver.appointments.index') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Manage Appointments
                                    </a>
                                @endif

                                @if(Auth::user()->hasRole('volunteer'))
                                    <a href="{{ route('volunteer.requests.index') }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-cyan-50 hover:text-cyan-700 font-medium">
                                       Assist Requests
                                    </a>
                                @elseif(!Auth::user()->hasRole('employer') && !Auth::user()->hasRole('admin'))
                                    <a href="{{ route('user.assistance.index') }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-cyan-50 hover:text-cyan-700 font-medium">
                                       Get Help
                                    </a>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('messages.index') }}" class="relative p-2 rounded-full text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all ml-2" title="Messages">
                           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </a>

                        <!-- Notifications - F9 - Evan Yuvraj Munshi -->
                        <div class="relative items-center mr-4" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 rounded-full text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-all focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-red-100 transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">
                                        {{ Auth::user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50 origin-top-right"
                                 style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50 rounded-t-xl">
                                    <span class="text-sm font-bold text-slate-700">Notifications</span>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-semibold hover:underline">Mark all read</button>
                                        </form>
                                    @endif
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(Auth::user()->notifications->take(5) as $notification)
                                        <div class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors {{ $notification->read_at ? 'opacity-70' : '' }}">
                                            <p class="text-sm text-slate-800 font-semibold">{{ $notification->data['message'] ?? 'New Notification' }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-slate-500 text-sm">
                                            No notifications yet.
                                        </div>
                                    @endforelse
                                </div>
                                
                                <a href="{{ route('notifications.index') }}" class="block px-4 py-3 text-center text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-slate-50 hover:bg-slate-100 transition-colors rounded-b-xl">
                                    View All Notifications
                                </a>
                            </div>
                        </div>

                        <!-- Profile Link -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                             <button @click="open = !open" class="group relative flex items-center gap-2 focus:outline-none">
                                @if(Auth::user()->profile && Auth::user()->profile->avatar)
                                    <div class="w-10 h-10 rounded-full p-0.5 bg-gradient-to-tr from-zinc-500 to-zinc-900 group-hover:from-black group-hover:to-zinc-800 transition-all">
                                        <img src="{{ asset('storage/' . Auth::user()->profile->avatar) }}" alt="Profile" class="w-full h-full rounded-full object-cover border-2 border-white">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 font-bold border border-zinc-200 group-hover:bg-zinc-200 transition-colors">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <svg class="w-4 h-4 text-zinc-400 group-hover:text-zinc-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 py-2 z-50 origin-top-right border border-zinc-100"
                                 style="display: none;">
                                
                                <div class="px-4 py-3 border-b border-zinc-100 bg-zinc-50 rounded-t-xl mb-1">
                                    <p class="text-sm font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-zinc-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                                    <svg class="w-5 h-5 mr-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    My Profile
                                </a>

                                @if(Auth::user()->hasRole('disabled'))
                                    <a href="{{ route('accessibility.edit') }}" class="flex items-center px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 hover:text-black transition-colors">
                                        <svg class="w-5 h-5 mr-3 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Accessibility
                                    </a>
                                @endif

                                <div class="border-t border-zinc-100 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST" onsubmit="clearChatHistory()">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors text-left">
                                        <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('jobs.index') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition-all">
                           Jobs
                        </a>
                        <a href="{{ route('documents.upload') }}"
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                           OCR & Simplify
                        </a>

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
    </div>
    <!-- F7 - Farhan Zarif -->
    <div id="ai-widget-container" class="fixed bottom-6 right-6 z-50 font-sans">
        <button id="ai-toggle-btn" class="w-14 h-14 bg-slate-900 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-105 transition-transform group relative hover:shadow-slate-500/20 border border-slate-700">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-full transition-opacity"></div>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </button>

        <div id="ai-chat-window" class="hidden absolute bottom-20 right-0 w-[400px] h-[500px] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden transition-all origin-bottom-right scale-95 opacity-0 ring-1 ring-black/5">
            <div class="bg-white/90 backdrop-blur-md p-4 flex items-center justify-between shrink-0 border-b border-slate-100 sticky top-0 z-10">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-slate-900 to-slate-700 flex items-center justify-center text-white shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base leading-tight">AbleBot</h3>
                        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-wider">AI Assistant</p>
                    </div>
                </div>
                <button id="ai-close-btn" class="text-slate-400 hover:text-slate-800 transition-colors p-1 rounded-md hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div id="ai-messages" class="flex-grow p-5 overflow-y-auto space-y-6 bg-slate-50/50 scroll-smooth">
                <div class="flex items-start">
                    <div class="w-6 h-6 rounded-md bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mr-3 text-[10px] font-bold uppercase tracking-wider mt-1">AI</div>
                    <div class="bg-white text-slate-700 p-4 rounded-xl rounded-tl-sm shadow-sm border border-slate-100 text-sm leading-relaxed max-w-[85%] break-words">
                        👋 Hi! I can help you navigate AbleLink. Try asking "Go to my profile" or "Find a caregiver".
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-slate-100 shrink-0">
                <div id="ai-file-preview" class="hidden px-3 py-2 mb-3 text-xs bg-slate-50 border border-slate-200 rounded-lg flex items-center justify-between text-slate-600">
                     <div class="flex items-center gap-2 overflow-hidden">
                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span id="ai-filename" class="truncate font-medium"></span>
                     </div>
                     <button type="button" id="ai-remove-file" class="ml-2 text-slate-400 hover:text-red-500 transition-colors">&times;</button>
                </div>

                <form id="ai-form" class="flex items-end gap-2 text-sm">
                    <div class="flex gap-1 shrink-0 pb-0.5">
                        @auth
                            @if(Auth::user()->hasRole('disabled'))
                                <button type="button" id="ai-mic-btn" class="flex items-center justify-center w-11 h-11 bg-slate-50 text-slate-500 rounded-xl hover:bg-slate-100 hover:text-slate-800 transition-all border border-transparent hover:border-slate-200" title="Voice Command">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                </button>
                            @endif
                        @endauth
                        <button type="button" id="ai-attach-btn" class="flex items-center justify-center w-11 h-11 bg-slate-50 text-slate-500 rounded-xl hover:bg-slate-100 hover:text-slate-800 transition-all border border-transparent hover:border-slate-200" title="Attach File">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </button>
                    </div>
                    <input type="file" id="ai-file-input" class="hidden" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt">

                    <div class="relative flex-grow bg-slate-50 rounded-xl border border-transparent hover:border-slate-200 focus-within:border-slate-300 focus-within:ring-2 focus-within:ring-indigo-100/50 transition-all min-h-[44px]">
                        <textarea id="ai-input" rows="1" placeholder="Type a message..." 
                               class="w-full pl-4 pr-12 py-3 bg-transparent border-none focus:ring-0 text-slate-800 placeholder-slate-400 font-medium text-sm resize-none leading-relaxed block max-h-[120px]"
                               autocomplete="off"></textarea>
                        <button type="submit" class="absolute right-1.5 bottom-1.5 p-2 bg-slate-900 text-white rounded-lg hover:bg-black transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center w-8 h-8">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        class AbleBotAgent {
            constructor() {
                this.userInterfaceElements = {
                    toggleButton: document.getElementById('ai-toggle-btn'),
                    closeButton: document.getElementById('ai-close-btn'),
                    chatWindowContainer: document.getElementById('ai-chat-window'),
                    chatForm: document.getElementById('ai-form'),
                    messageInput: document.getElementById('ai-input'),
                    messageListContainer: document.getElementById('ai-messages'),
                    microphoneButton: document.getElementById('ai-mic-btn'),
                    attachFileButton: document.getElementById('ai-attach-btn'),
                    fileInputElement: document.getElementById('ai-file-input'),
                    filePreviewContainer: document.getElementById('ai-file-preview'),
                    filenameDisplay: document.getElementById('ai-filename'),
                    removeFileButton: document.getElementById('ai-remove-file')
                };

                this.sessionKeys = {
                    chatOpenStatus: 'ablebot_chat_open',
                    formSessionData: 'ablebot_interactive_form',
                    pendingActionData: 'ablebot_pending_action'
                };

                this.state = {
                    uploadedFileUrl: null,
                    uploadedFileName: null,
                    voiceRecognitionInstance: null
                };

                this.initializeApplicationLogic();
            }

            initializeApplicationLogic() {
                this.bindUserInterfaceEvents();
                this.initializeSpeechRecognitionSystem();
                this.restoreChatWindowStatus();
                this.exposeGlobalConfirmationFunction();
            }

            bindUserInterfaceEvents() {
                if (this.userInterfaceElements.toggleButton) {
                    this.userInterfaceElements.toggleButton.addEventListener('click', () => {
                        this.toggleChatWindowVisibility();
                    });
                }

                if (this.userInterfaceElements.closeButton) {
                    this.userInterfaceElements.closeButton.addEventListener('click', () => {
                        this.toggleChatWindowVisibility();
                    });
                }

                if (this.userInterfaceElements.messageInput) {
                    this.userInterfaceElements.messageInput.addEventListener('input', (event) => {
                        this.adjustInputAreaHeight(event.target);
                    });

                    this.userInterfaceElements.messageInput.addEventListener('keydown', (event) => {
                        this.handleInputKeydownEvent(event);
                    });
                }

                if (this.userInterfaceElements.chatForm) {
                    this.userInterfaceElements.chatForm.addEventListener('submit', (event) => {
                        this.handleChatFormSubmission(event);
                    });
                }

                if (this.userInterfaceElements.attachFileButton) {
                    this.userInterfaceElements.attachFileButton.addEventListener('click', () => {
                        this.triggerFileSystemDialog();
                    });
                }

                if (this.userInterfaceElements.fileInputElement) {
                    this.userInterfaceElements.fileInputElement.addEventListener('change', () => {
                        this.handleFileSelectionChange();
                    });
                }

                if (this.userInterfaceElements.removeFileButton) {
                    this.userInterfaceElements.removeFileButton.addEventListener('click', () => {
                        this.resetFileAttachmentState();
                    });
                }

                if (this.userInterfaceElements.microphoneButton) {
                    this.userInterfaceElements.microphoneButton.addEventListener('click', () => {
                        this.activateVoiceRecognition();
                    });
                }
            }

            exposeGlobalConfirmationFunction() {
                window.confirmPendingAction = async (isConfirmed) => {
                    await this.processPendingActionConfirmation(isConfirmed);
                };
            }

            restoreChatWindowStatus() {
                const isChatOpen = localStorage.getItem(this.sessionKeys.chatOpenStatus);
                
                if (isChatOpen === 'true') {
                    this.userInterfaceElements.chatWindowContainer.classList.remove('hidden', 'scale-95', 'opacity-0');
                    this.userInterfaceElements.chatWindowContainer.classList.add('scale-100', 'opacity-100');
                }
            }

            toggleChatWindowVisibility() {
                const container = this.userInterfaceElements.chatWindowContainer;
                const isHidden = container.classList.contains('hidden');

                if (isHidden) {
                    container.classList.remove('hidden');
                    localStorage.setItem(this.sessionKeys.chatOpenStatus, 'true');
                    
                    setTimeout(() => {
                        container.classList.remove('scale-95', 'opacity-0');
                        container.classList.add('scale-100', 'opacity-100');
                        this.userInterfaceElements.messageInput.focus();
                    }, 10);
                } else {
                    container.classList.remove('scale-100', 'opacity-100');
                    container.classList.add('scale-95', 'opacity-0');
                    localStorage.setItem(this.sessionKeys.chatOpenStatus, 'false');
                    
                    setTimeout(() => {
                        container.classList.add('hidden');
                    }, 300);
                }
            }

            adjustInputAreaHeight(inputElement) {
                inputElement.style.height = 'auto';
                inputElement.style.height = inputElement.scrollHeight + 'px';
                
                if (inputElement.value === '') {
                    inputElement.style.height = 'auto';
                }
            }

            handleInputKeydownEvent(event) {
                if (event.key === 'Enter') {
                    if (!event.shiftKey) {
                        event.preventDefault();
                        this.userInterfaceElements.chatForm.dispatchEvent(new Event('submit'));
                    }
                }
            }

            triggerFileSystemDialog() {
                this.userInterfaceElements.fileInputElement.click();
            }

            resetFileAttachmentState() {
                this.userInterfaceElements.fileInputElement.value = '';
                this.state.uploadedFileUrl = null;
                this.state.uploadedFileName = null;
                this.userInterfaceElements.filePreviewContainer.classList.add('hidden');
            }

            async handleFileSelectionChange() {
                const fileList = this.userInterfaceElements.fileInputElement.files;
                
                if (fileList.length > 0) {
                    const selectedFile = fileList[0];
                    this.userInterfaceElements.filenameDisplay.innerText = "Uploading " + selectedFile.name + "...";
                    this.userInterfaceElements.filePreviewContainer.classList.remove('hidden');

                    const formData = new FormData();
                    formData.append('file', selectedFile);

                    try {
                        const response = await fetch('{{ route('ai.upload') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: formData
                        });

                        if (response.ok) {
                            const responseData = await response.json();
                            this.state.uploadedFileUrl = responseData.url;
                            this.state.uploadedFileName = responseData.filename;
                            this.userInterfaceElements.filenameDisplay.innerText = this.state.uploadedFileName;
                        } else {
                            this.userInterfaceElements.filenameDisplay.innerText = "Upload failed.";
                        }
                    } catch (error) {
                        this.userInterfaceElements.filenameDisplay.innerText = "Error uploading.";
                        console.error(error);
                    }
                }
            }

            initializeSpeechRecognitionSystem() {
                const SpeechRecognitionReference = window.SpeechRecognition || window.webkitSpeechRecognition;
                
                if (SpeechRecognitionReference && this.userInterfaceElements.microphoneButton) {
                    this.state.voiceRecognitionInstance = new SpeechRecognitionReference();
                    this.state.voiceRecognitionInstance.continuous = false;
                    this.state.voiceRecognitionInstance.lang = 'en-US';

                    this.state.voiceRecognitionInstance.onstart = () => {
                        console.log('[AbleBot] Voice recognition started');
                        this.userInterfaceElements.microphoneButton.classList.add('bg-red-100', 'text-red-600', 'animate-pulse');
                        
                        // F6 - Suspend Hover Reader
                        if (window.VoiceAssistant) {
                             window.VoiceAssistant.suspend();
                        }
                    };

                    this.state.voiceRecognitionInstance.onend = () => {
                        console.log('[AbleBot] Voice recognition ended');
                        this.userInterfaceElements.microphoneButton.classList.remove('bg-red-100', 'text-red-600', 'animate-pulse');
                        
                        // F6 - Resume Hover Reader
                        if (window.VoiceAssistant) {
                             window.VoiceAssistant.resume();
                        }
                    };

                    this.state.voiceRecognitionInstance.onerror = (event) => {
                        console.error('[AbleBot] Voice recognition error:', event.error);
                        this.userInterfaceElements.microphoneButton.classList.remove('bg-red-100', 'text-red-600', 'animate-pulse');

                        // F6 - Resume Hover Reader on Error
                        if (window.VoiceAssistant) {
                             window.VoiceAssistant.resume();
                        }

                        if (event.error === 'no-speech') {
                            this.synthesizeVoiceResponse("I didn't hear anything. Please try again.");
                        } else if (event.error === 'not-allowed') {
                            alert('Microphone access denied. Please allow microphone in browser settings.');
                        } else if (event.error === 'network') {
                            alert('Network error. Please check your internet connection.');
                        }
                    };

                    this.state.voiceRecognitionInstance.onresult = (event) => {
                        const transcriptText = event.results[0][0].transcript;
                        console.log('[AbleBot] Voice recognized:', transcriptText);
                        this.userInterfaceElements.messageInput.value = transcriptText;
                        this.userInterfaceElements.chatForm.dispatchEvent(new Event('submit'));
                    };

                    this.userInterfaceElements.microphoneButton.addEventListener('click', () => {
                         this.state.voiceRecognitionInstance.start();
                    });
                } else {
                    if (!this.userInterfaceElements.microphoneButton) {
                         console.log('[AbleBot] Mic button not present (non-disabled user)');
                    } else {
                         console.warn('[AbleBot] Speech Recognition not supported'); 
                    }
                }
            }

            activateVoiceRecognition() {
                 if (this.state.voiceRecognitionInstance) {
                     this.state.voiceRecognitionInstance.start();
                 }
            }

            appendUserMessageToChat(messageText) {
                const messageContainer = document.createElement('div');
                messageContainer.className = 'flex items-start justify-end place-content-end';
                messageContainer.innerHTML = `
                    <div class="bg-slate-900 text-white p-4 rounded-xl rounded-tr-none shadow-sm text-sm leading-relaxed max-w-[85%] break-words">
                        ${messageText}
                    </div>
                `;
                this.userInterfaceElements.messageListContainer.appendChild(messageContainer);
                this.userInterfaceElements.messageListContainer.scrollTop = this.userInterfaceElements.messageListContainer.scrollHeight;
            }

            appendSystemMessageToChat(messageText, isTemporaryStatus = false) {
                const messageContainer = document.createElement('div');
                messageContainer.className = 'flex items-start';
                
                if (isTemporaryStatus) {
                    messageContainer.setAttribute('data-temporary', 'true');
                }

                messageContainer.innerHTML = `
                    <div class="w-6 h-6 rounded-md bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mr-3 text-[10px] font-bold uppercase tracking-wider mt-1">AI</div>
                    <div class="bg-white text-slate-700 p-4 rounded-xl rounded-tl-sm shadow-sm border border-slate-100 text-sm leading-relaxed max-w-[85%] break-words${isTemporaryStatus ? ' loading-dots' : ''}">
                        ${messageText}
                    </div>
                `;
                this.userInterfaceElements.messageListContainer.appendChild(messageContainer);
                this.userInterfaceElements.messageListContainer.scrollTop = this.userInterfaceElements.messageListContainer.scrollHeight;
            }

            removeTemporarySystemMessages() {
                const temporaryMessage = this.userInterfaceElements.messageListContainer.querySelector('[data-temporary="true"]');
                if (temporaryMessage) {
                    this.userInterfaceElements.messageListContainer.removeChild(temporaryMessage);
                }
            }

            serializeCurrentPageDocumentObjectModel() {
                const pageVisibleText = document.body.innerText.substring(0, 2000);
                const modalElements = document.querySelectorAll('[role="dialog"], .modal, .modal-content, [class*="modal"], [class*="popup"], [class*="dialog"]');
                let concatenatedModalText = '';
                
                modalElements.forEach((modalElement) => {
                    if (modalElement.offsetParent !== null) {
                        concatenatedModalText += modalElement.innerText.substring(0, 500) + '\n';
                    }
                });

                const interactiveElements = document.querySelectorAll('button, a, input, select, textarea, [role="button"]');
                let serializedElementsList = [];

                interactiveElements.forEach((element, index) => {
                    if (!element.id) {
                        element.id = 'ai-gen-' + index;
                    }

                    const boundingRectangle = element.getBoundingClientRect();
                    const computedStyle = window.getComputedStyle(element);

                    if (boundingRectangle.width > 0 && boundingRectangle.height > 0 && computedStyle.visibility !== 'hidden') {
                        let selectElementOptions = [];
                        
                        if (element.tagName.toLowerCase() === 'select') {
                            selectElementOptions = Array.from(element.options).map(option => ({
                                value: option.value,
                                text: option.innerText
                            }));
                        }

                        let checkboxCheckedState = null;
                        let descriptiveLabelText = element.innerText || element.placeholder || element.value || element.getAttribute('aria-label') || '';

                        if (element.type === 'checkbox') {
                            checkboxCheckedState = element.checked;
                            const labelElement = document.querySelector(`label[for="${element.id}"]`);
                            
                            if (labelElement) {
                                descriptiveLabelText = labelElement.innerText || descriptiveLabelText;
                            } else if (element.parentElement && element.parentElement.tagName === 'LABEL') {
                                descriptiveLabelText = element.parentElement.innerText || descriptiveLabelText;
                            } else if (element.name) {
                                descriptiveLabelText = element.name.replace(/_/g, ' ').replace(/([A-Z])/g, ' $1').trim();
                            }
                        }

                        serializedElementsList.push({
                            tag: element.tagName.toLowerCase(),
                            id: element.id,
                            text: descriptiveLabelText,
                            type: element.type || '',
                            name: element.name || '',
                            options: selectElementOptions,
                            required: element.required || false,
                            pattern: element.pattern || null,
                            minLength: element.minLength > 0 ? element.minLength : null,
                            maxLength: element.maxLength > 0 ? element.maxLength : null,
                            min: element.min || null,
                            max: element.max || null,
                            checked: checkboxCheckedState
                        });
                    }
                });

                return {
                    url: window.location.href,
                    pageTitle: document.title,
                    visibleText: pageVisibleText.substring(0, 1000),
                    modalContent: concatenatedModalText || null,
                    elements: serializedElementsList
                };
            }

            synthesizeVoiceResponse(textToSpeak) {
                if (!window.ableLinkIsDisabled) {
                    return;
                }

                const userPreferences = window.ableLinkPrefs || {};
                const isVoiceNavigationEnabled = userPreferences.voice_navigation_enabled !== false && userPreferences.voice_navigation_enabled !== 0 && userPreferences.voice_navigation_enabled !== '0';
                const isTextToSpeechEnabled = userPreferences.text_to_speech_enabled !== false && userPreferences.text_to_speech_enabled !== 0 && userPreferences.text_to_speech_enabled !== '0';

                if (!isVoiceNavigationEnabled || !isTextToSpeechEnabled) {
                     return;
                }

                if ('speechSynthesis' in window) {
                    window.speechSynthesis.cancel();
                    const synthesisUtterance = new SpeechSynthesisUtterance(textToSpeak);
                    window.speechSynthesis.speak(synthesisUtterance);
                }
            }

            async handleChatFormSubmission(event) {
                event.preventDefault();
                let userMessageText = this.userInterfaceElements.messageInput.value.trim();
                
                if (!userMessageText && !this.state.uploadedFileUrl) {
                    return;
                }

                if (this.state.uploadedFileUrl) {
                    userMessageText += `\n[Attached File: ${this.state.uploadedFileUrl}|${this.state.uploadedFileName}]`;
                }

                this.appendUserMessageToChat(userMessageText.replace(/\[Attached File: .*?\]/, '[File]'));
                
                this.userInterfaceElements.messageInput.value = '';
                this.resetFileAttachmentState();
                
                this.appendSystemMessageToChat('Thinking...', true);

                try {
                    const pageStructureSnapshot = this.serializeCurrentPageDocumentObjectModel();
                    const requestResult = await fetch('{{ route('ai.chat') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: userMessageText,
                            current_url: window.location.href,
                            page_structure: pageStructureSnapshot
                        })
                    });

                    const parsedResponseData = await requestResult.json();
                    this.removeTemporarySystemMessages();
                    this.appendSystemMessageToChat(parsedResponseData.reply);

                    if (parsedResponseData.voice_summary) {
                         this.synthesizeVoiceResponse(parsedResponseData.voice_summary);
                    } else {
                         this.synthesizeVoiceResponse(parsedResponseData.reply);
                    }

                    if (parsedResponseData.actions && parsedResponseData.actions.length > 0) {
                        for (const actionObject of parsedResponseData.actions) {
                            await this.delegatedActionExecution(actionObject.name, actionObject.args);
                            await new Promise(resolve => setTimeout(resolve, 100));
                        }
                    } else if (parsedResponseData.action && parsedResponseData.action !== 'message') {
                        await this.delegatedActionExecution(parsedResponseData.action, parsedResponseData.args);
                    }

                } catch (errorObject) {
                    console.error(errorObject); // Logging error is usually acceptable
                    this.removeTemporarySystemMessages();
                    this.appendSystemMessageToChat("Sorry, something went wrong.");
                }
            }

            async delegatedActionExecution(actionName, actionArguments) {
                console.log("Agent Executing:", actionName, actionArguments);

                if (actionName === 'navigate') {
                    window.location.href = actionArguments.url;
                    return;
                }

                if (actionName === 'click_element') {
                    await this.performElementClickAction(actionArguments.selector);
                    return;
                }

                if (actionName === 'store_field') {
                    await this.processFieldStorage(actionArguments);
                    return;
                }

                if (actionName === 'fill_form') {
                    await this.executeBatchFormFill(actionArguments.fields);
                    return;
                }

                if (actionName === 'confirm_action') {
                    await this.requestUserConfirmation(actionArguments);
                    return;
                }

                if (actionName === 'read_page') {
                    this.synthesizeVoiceResponse(actionArguments.summary);
                    return;
                }

                if (actionName === 'fill_input') {
                    await this.performInputFillAction(actionArguments.selector, actionArguments.value);
                    return;
                }

                if (actionName === 'toggle_checkbox') {
                    await this.performCheckboxToggleAction(actionArguments.selector, actionArguments.checked);
                    return;
                }

                if (actionName === 'scroll_to') {
                    this.performScrollToAction(actionArguments.selector);
                    return;
                }

                if (actionName === 'upload_file') {
                    await this.performFileUploadAction(actionArguments);
                    return;
                }
            }

            async performElementClickAction(elementSelector) {
                let targetElement = document.getElementById(elementSelector.replace('#', ''));
                
                if (!targetElement && elementSelector.startsWith('#')) {
                    targetElement = document.querySelector(elementSelector);
                }
                
                if (!targetElement) {
                    targetElement = document.querySelector(elementSelector);
                }

                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    const originalTransitionStyle = targetElement.style.transition;
                    targetElement.style.transition = 'all 0.2s';
                    targetElement.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                    targetElement.style.transform = "scale(1.05)";

                    setTimeout(() => {
                        targetElement.style.boxShadow = "";
                        targetElement.style.transform = "";
                        targetElement.style.transition = originalTransitionStyle;
                    }, 500);

                    targetElement.click();
                    targetElement.focus();
                } else {
                    console.error(`[AbleBot] Click element not found: ${elementSelector}`);
                }
            }

            async processFieldStorage(storageArguments) {
                let sessionData = sessionStorage.getItem(this.sessionKeys.formSessionData);
                let sessionObject = sessionData ? JSON.parse(sessionData) : { fields: {}, active: false };
                
                sessionObject.fields[storageArguments.selector] = storageArguments.value;
                sessionObject.active = true;
                sessionStorage.setItem(this.sessionKeys.formSessionData, JSON.stringify(sessionObject));

                if (storageArguments.all_collected) {
                    const storedFieldsCollection = sessionObject.fields;
                    let verificationStatus = true;
                    
                    for (const [fieldSelector, fieldValue] of Object.entries(storedFieldsCollection)) {
                        const sanitizedSelector = fieldSelector.replace('#', '');
                        let verificationElement = document.getElementById(sanitizedSelector);
                        
                        if (!verificationElement) {
                             verificationElement = document.querySelector(fieldSelector);
                        }

                        if (!verificationElement) {
                            verificationStatus = false;
                        }
                    }

                    if (verificationStatus) {
                         const batchFieldsList = Object.entries(storedFieldsCollection).map(([sel, val]) => ({
                             selector: sel,
                             value: val
                         }));

                         this.appendSystemMessageToChat(`📋 Filling ${batchFieldsList.length} fields...`);
                         await this.delegatedActionExecution('fill_form', { fields: batchFieldsList });
                         
                         sessionStorage.removeItem(this.sessionKeys.formSessionData);
                         this.appendSystemMessageToChat("✅ All fields filled successfully!");
                    } else {
                        this.appendSystemMessageToChat("⚠️ Some form elements were not found. Please check the form and try again.");
                    }
                }
            }

            async executeBatchFormFill(fieldsList) {
                for (const fieldObject of fieldsList) {
                    await this.delegatedActionExecution('fill_input', { selector: fieldObject.selector, value: fieldObject.value });
                    await new Promise(resolve => setTimeout(resolve, 150));
                }
            }

            async requestUserConfirmation(confirmationArguments) {
                sessionStorage.setItem(this.sessionKeys.pendingActionData, JSON.stringify({
                    action: confirmationArguments.pending_action,
                    args: confirmationArguments.pending_args
                }));

                const severityEmojiMap = {
                    'high': '🔴',
                    'medium': '🟡', 
                    'low': '🟢'
                };
                const displayEmoji = severityEmojiMap[confirmationArguments.severity] || '⚠️';
                
                this.appendSystemMessageToChat(`${displayEmoji} <strong>Confirm:</strong> ${confirmationArguments.action_description}<br><br>
                    <button onclick="confirmPendingAction(true)" class="px-3 py-1 bg-green-500 text-white rounded mr-2">Yes, do it</button>
                    <button onclick="confirmPendingAction(false)" class="px-3 py-1 bg-gray-300 text-gray-700 rounded">Cancel</button>`);
                
                this.synthesizeVoiceResponse(confirmationArguments.action_description + ". Do you want me to proceed?");
            }

            async processPendingActionConfirmation(isUserConfirmed) {
                const pendingActionJson = sessionStorage.getItem(this.sessionKeys.pendingActionData);
                sessionStorage.removeItem(this.sessionKeys.pendingActionData);

                if (isUserConfirmed && pendingActionJson) {
                    const pendingActionObject = JSON.parse(pendingActionJson);
                    this.appendSystemMessageToChat("✓ Confirmed. Executing action...");
                    this.synthesizeVoiceResponse("Confirmed. Executing now.");
                    await this.delegatedActionExecution(pendingActionObject.action, pendingActionObject.args);
                } else {
                    this.appendSystemMessageToChat("✗ Action cancelled.");
                    this.synthesizeVoiceResponse("Action cancelled.");
                }
            }

            async performInputFillAction(inputSelector, inputValue) {
                let targetInputElement = document.getElementById(inputSelector.replace('#', ''));
                if (!targetInputElement && inputSelector.startsWith('#')) {
                    targetInputElement = document.querySelector(inputSelector);
                }
                if (!targetInputElement) {
                    targetInputElement = document.querySelector(`[name="${inputSelector.replace('#', '')}"]`);
                }

                if (targetInputElement) {
                    targetInputElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    let sanitizedValue = String(inputValue).replace(/^['"]|['"]$/g, '').trim();

                    if (targetInputElement.type === 'checkbox') {
                         const shouldBeChecked = ['true', '1', 'yes', 'on', 'checked'].includes(sanitizedValue.toLowerCase());
                         targetInputElement.checked = shouldBeChecked;
                         targetInputElement.dispatchEvent(new Event('change', { bubbles: true }));
                         
                         const parentWrapper = targetInputElement.parentElement || targetInputElement;
                         parentWrapper.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                         setTimeout(() => parentWrapper.style.boxShadow = "", 1500);
                         return;
                    }

                    if (targetInputElement.tagName.toUpperCase() === 'SELECT') {
                         let optionMatchFound = false;

                         try {
                             targetInputElement.value = sanitizedValue;
                             if (targetInputElement.value === sanitizedValue) {
                                 optionMatchFound = true;
                             }
                         } catch (error) {
                             console.error(error);
                         }

                         if (!optionMatchFound) {
                             const lowerCaseTargetValue = sanitizedValue.toLowerCase();
                             for (let i = 0; i < targetInputElement.options.length; i++) {
                                 const optionValue = targetInputElement.options[i].value.toLowerCase();
                                 const optionText = targetInputElement.options[i].text.toLowerCase();
                                 
                                 if (optionValue === lowerCaseTargetValue || optionText === lowerCaseTargetValue || optionText.includes(lowerCaseTargetValue)) {
                                     targetInputElement.selectedIndex = i;
                                     optionMatchFound = true;
                                     break;
                                 }
                             }
                         }

                         const initialBoxShadow = targetInputElement.style.boxShadow;
                         if (optionMatchFound) {
                             targetInputElement.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                         } else {
                             targetInputElement.style.boxShadow = "0 0 0 4px #ef4444, 0 0 20px rgba(239, 68, 68, 0.5)";
                         }
                         
                         setTimeout(() => targetInputElement.style.boxShadow = initialBoxShadow, 2000);
                    } else if (targetInputElement.classList.contains('custom-select') || targetInputElement.getAttribute('role') === 'listbox') {
                        targetInputElement.click();
                        await new Promise(resolve => setTimeout(resolve, 300));
                        
                        const customOptionElement = document.querySelector(`[data-value="${sanitizedValue}"]`) || document.querySelector(`.dropdown-item:contains("${sanitizedValue}")`);
                        
                        if (customOptionElement) {
                            customOptionElement.click();
                            targetInputElement.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                        } else {
                            targetInputElement.style.boxShadow = "0 0 0 4px #ef4444, 0 0 20px rgba(239, 68, 68, 0.5)";
                        }
                        
                        setTimeout(() => targetInputElement.style.boxShadow = "", 2000);
                    } else {
                        targetInputElement.value = sanitizedValue;
                        targetInputElement.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                        setTimeout(() => targetInputElement.style.boxShadow = "", 1000);
                    }

                    targetInputElement.dispatchEvent(new Event('input', { bubbles: true }));
                    targetInputElement.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    this.synthesizeVoiceResponse("I could not find the field " + inputSelector);
                }
            }

            async performCheckboxToggleAction(checkboxSelector, explicitCheckedState = null) {
                let targetCheckboxElement = document.getElementById(checkboxSelector.replace('#', ''));
                if (!targetCheckboxElement && checkboxSelector.startsWith('#')) {
                    targetCheckboxElement = document.querySelector(checkboxSelector);
                }
                if (!targetCheckboxElement) {
                    targetCheckboxElement = document.querySelector(`[name="${checkboxSelector.replace('#', '')}"]`);
                }
                if (!targetCheckboxElement) {
                    targetCheckboxElement = document.querySelector(`input[type="checkbox"][name*="${checkboxSelector.replace('#', '')}"]`);
                }

                if (targetCheckboxElement && targetCheckboxElement.type === 'checkbox') {
                    targetCheckboxElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    if (typeof explicitCheckedState === 'boolean') {
                        targetCheckboxElement.checked = explicitCheckedState;
                    } else {
                        targetCheckboxElement.checked = !targetCheckboxElement.checked;
                    }
                    
                    targetCheckboxElement.dispatchEvent(new Event('change', { bubbles: true }));
                    targetCheckboxElement.dispatchEvent(new Event('click', { bubbles: true }));
                    
                    const elementWrapper = targetCheckboxElement.parentElement || targetCheckboxElement;
                    let feedbackColorString = "";
                    
                    if (targetCheckboxElement.checked) {
                        feedbackColorString = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                    } else {
                        feedbackColorString = "0 0 0 4px #f59e0b, 0 0 20px rgba(245, 158, 11, 0.5)";
                    }
                    
                    elementWrapper.style.boxShadow = feedbackColorString;
                    setTimeout(() => elementWrapper.style.boxShadow = "", 1500);
                } else {
                    this.synthesizeVoiceResponse("I could not find the checkbox " + checkboxSelector);
                }
            }

            performScrollToAction(scrollTargetSelector) {
                const targetScrollElement = document.getElementById(scrollTargetSelector.replace('#', ''));
                if (targetScrollElement) {
                    targetScrollElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            async performFileUploadAction(uploadArguments) {
                 if (!uploadArguments.url) {
                     this.synthesizeVoiceResponse("No file URL found. Please attach a file first.");
                     return;
                 }
                 
                 try {
                     const fileFetchResponse = await fetch(uploadArguments.url);
                     if (!fileFetchResponse.ok) {
                         throw new Error(`File not found (${fileFetchResponse.status}). Please attach a file first.`);
                     }
                     
                     const responseContentType = fileFetchResponse.headers.get('Content-Type') || '';
                     if (responseContentType.includes('text/html')) {
                         throw new Error("File expired or not found. Please attach a new file.");
                     }
                     
                     const fileBlobData = await fileFetchResponse.blob();
                     let finalFilename = uploadArguments.filename || uploadArguments.url.split('/').pop() || "upload.jpg";
                     
                     if (!finalFilename.includes('.')) {
                         finalFilename = finalFilename + '.jpg'; 
                     }
                     
                     const fileExtension = finalFilename.split('.').pop().toLowerCase();
                     const mimeTypeMap = {
                         'pdf': 'application/pdf',
                         'png': 'image/png',
                         'jpg': 'image/jpeg',
                         'jpeg': 'image/jpeg',
                         'txt': 'text/plain',
                         'gif': 'image/gif',
                         'webp': 'image/webp'
                     };
                     
                     let detectedMimeType = responseContentType;
                     if (!detectedMimeType || detectedMimeType === 'application/octet-stream') {
                         detectedMimeType = mimeTypeMap[fileExtension] || fileBlobData.type || 'application/octet-stream';
                     }

                     const constructedFile = new File([fileBlobData], finalFilename, { type: detectedMimeType });
                     
                     const dataTransferContainer = new DataTransfer();
                     dataTransferContainer.items.add(constructedFile);
                     
                     let fileInputElement = uploadArguments.selector ? document.getElementById(uploadArguments.selector.replace('#', '')) : null;
                     if (!fileInputElement) {
                         fileInputElement = document.querySelector('input[type="file"]');
                     }

                     if(fileInputElement) {
                         fileInputElement.files = dataTransferContainer.files;
                         fileInputElement.dispatchEvent(new Event('change', { bubbles: true }));
                         fileInputElement.dispatchEvent(new Event('input', { bubbles: true }));
                         
                         fileInputElement.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                         setTimeout(() => fileInputElement.style.boxShadow = "", 2000);
                         
                         this.synthesizeVoiceResponse("File attached successfully.");
                     } else {
                         this.synthesizeVoiceResponse("I could not find a file upload field on this page.");
                     }
                 } catch (exception) {
                     console.error(exception);
                     this.synthesizeVoiceResponse("Failed to attach file. " + exception.message);
                 }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
             window.AbleBotInstance = new AbleBotAgent();
        });
    </script>
</body>
</html>

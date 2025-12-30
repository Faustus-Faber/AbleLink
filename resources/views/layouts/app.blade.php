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

    <!-- Core Adaptive UI stylesheet (F5) -->
    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

    <!-- Load App Scripts (Voice Interaction) -->
    <!-- //F6 - Evan Yuvraj Munshi// -->
    <script>
        window.ableLinkPrefs = @json($prefs);
        window.ableLinkUserRole = @json(auth()->check() ? auth()->user()->role : 'guest');
        window.ableLinkIsDisabled = @json(auth()->check() && auth()->user()->hasRole('disabled'));
    </script>
    @vite(['resources/js/app.js'])

    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<a href="#main-content" class="skip-to-content">Skip to main content</a>
<body class="antialiased text-slate-900 bg-slate-50 {{ $bodyClassString }}">
    
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm/50 backdrop-blur-md bg-white/90">
        <div class="container mx-auto px-6 h-20 flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="group flex items-center space-x-3">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <!-- Premium SVG Logo -->
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
            
            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center space-x-2">
                @if (Route::has('login'))
                    @auth
                        <!-- Dashboard Link -->
                        <a href="{{ url('/dashboard') }}" 
                           class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-blue-600 hover:bg-blue-50 transition-all">
                           Dashboard
                        </a>

                        <!-- Services Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center">
                                Services
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-2 z-50">
                                <!-- Jobs Link -->
                                <a href="{{ Auth::user()->hasRole('employer') ? route('employer.jobs.index') : route('jobs.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium">
                                   Find Jobs
                                </a>
                                <!-- Learning Hub Link -->
                                <a href="{{ route('courses.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 font-medium">
                                   Learning Hub
                                </a>
                                <!-- Community Hub Link -->
                                <a href="{{ route('community.index') }}" 
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-700 font-medium">
                                   Community
                                </a>
                                <!-- Aid Directory Link -->
                                @if(Auth::user()->hasRole('disabled'))
                                    <a href="{{ route('aid.index') }}" 
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-green-50 hover:text-green-700 font-medium">
                                       Aid Directory
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Utilities Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="px-5 py-2.5 rounded-full font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all flex items-center">
                                Tools
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" style="display: none;" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-2 z-50">
                                <!-- PWA Install Trigger? -->
                                
                                <!-- OCR & Simplify Link -->
                                <a href="{{ route('documents.upload') }}"
                                   class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                   OCR & Simplify
                                </a>

                                <!-- Health Tracker Link (F19) - Disabled users only -->
                                @if(Auth::user()->hasRole('disabled'))
                                    <a href="{{ route('health.dashboard') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Health Tracker
                                    </a>
                                    
                                    <!-- F17 - User Appointments -->
                                    <a href="{{ route('user.appointments.index') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Doctor Appointments
                                    </a>
                                @endif

                                <!-- F17 - Caregiver Appointments (Caregiver only) -->
                                @if(Auth::user()->hasRole('caregiver'))
                                    <a href="{{ route('caregiver.appointments.index') }}"
                                       class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 font-medium">
                                       Manage Appointments
                                    </a>
                                @endif

                                <!-- Volunteer Requests / Get Help -->
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

                        <!-- Messages Icon (Moved to right) -->
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

                            <!-- Dropdown -->
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
                        <!-- User Profile Dropdown -->
                        <div class="ml-3 relative" x-data="{ open: false }">
                            <!-- Dropdown Trigger -->
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

                            <!-- Dropdown Menu -->
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
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-zinc-100 bg-zinc-50 rounded-t-xl mb-1">
                                    <p class="text-sm font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-zinc-500 truncate">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Menu Items -->
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
                        <!-- Jobs Link (Guest) -->
                        <a href="{{ route('jobs.index') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition-all">
                           Jobs
                        </a>
                        <!-- OCR & Simplify Link -->
                        <a href="{{ route('documents.upload') }}"
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 transition-all">
                           OCR & Simplify
                        </a>

                        <!-- Login -->
                        <a href="{{ route('login') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition-all">
                           Log in
                        </a>

                        <!-- Admin Link (FIXED: Consistent with other guest links) -->
                        <a href="{{ route('admin.login') }}" 
                           class="px-6 py-3 rounded-full font-bold text-slate-700 hover:text-blue-700 hover:bg-blue-50 transition-all">
                           Admin
                        </a>

                        <!-- Register -->
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
        <!-- Decoration -->
        <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
             <div class="absolute -top-[20%] -right-[10%] w-[800px] h-[800px] bg-blue-100/50 rounded-full blur-3xl opacity-60"></div>
             <div class="absolute top-[20%] -left-[10%] w-[600px] h-[600px] bg-purple-100/50 rounded-full blur-3xl opacity-60"></div>
        </div>

        <!-- Main Content -->
        <main class="relative z-10 flex-grow container mx-auto px-6 py-12">
            @yield('content')
        </main>

        <!-- Use Footer Partial -->
        @include('partials.footer')
    </div>
    <!-- F7 - Farhan Zarif -->
    <!-- AI Chat Widget -->
    <div id="ai-widget-container" class="fixed bottom-6 right-6 z-50 font-sans">
        <!-- Toggle Button -->
        <button id="ai-toggle-btn" class="w-14 h-14 bg-slate-900 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-105 transition-transform group relative hover:shadow-slate-500/20 border border-slate-700">
            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 rounded-full transition-opacity"></div>
            <!-- Icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        </button>

        <!-- Chat Window (Hidden by default) -->
        <div id="ai-chat-window" class="hidden absolute bottom-20 right-0 w-[400px] h-[500px] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden transition-all origin-bottom-right scale-95 opacity-0 ring-1 ring-black/5">
            <!-- Header -->
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
                <!-- Close Button -->
                <button id="ai-close-btn" class="text-slate-400 hover:text-slate-800 transition-colors p-1 rounded-md hover:bg-slate-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div id="ai-messages" class="flex-grow p-5 overflow-y-auto space-y-6 bg-slate-50/50 scroll-smooth">
                <!-- Welcome Message -->
                <div class="flex items-start">
                    <div class="w-6 h-6 rounded-md bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mr-3 text-[10px] font-bold uppercase tracking-wider mt-1">AI</div>
                    <div class="bg-white text-slate-700 p-4 rounded-xl rounded-tl-sm shadow-sm border border-slate-100 text-sm leading-relaxed max-w-[85%] break-words">
                        👋 Hi! I can help you navigate AbleLink. Try asking "Go to my profile" or "Find a caregiver".
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-slate-100 shrink-0">
                <!-- File Preview -->
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
                        <!-- File Upload Button & Input -->
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
        // Clear chat state on logout/reset
        function clearChatHistory() {
            localStorage.removeItem('ablebot_chat_open');
            sessionStorage.removeItem('ablebot_plan');
            sessionStorage.removeItem('ablebot_step');
            sessionStorage.removeItem('ablebot_pending_action');
        }
        
        const toggleBtn = document.getElementById('ai-toggle-btn');
        const closeBtn = document.getElementById('ai-close-btn');
        const chatWindow = document.getElementById('ai-chat-window');
        const form = document.getElementById('ai-form');
        const input = document.getElementById('ai-input');
        const messages = document.getElementById('ai-messages');
        const micBtn = document.getElementById('ai-mic-btn');
        const attachBtn = document.getElementById('ai-attach-btn');
        const fileInput = document.getElementById('ai-file-input');
        const filePreview = document.getElementById('ai-file-preview');
        const filenameSpan = document.getElementById('ai-filename');
        const removeFileBtn = document.getElementById('ai-remove-file');
        
        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            if(this.value === '') this.style.height = 'auto';
        });

        // Submit on Enter (without Shift)
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });
        
        let uploadedFileUrl = null;
        let uploadedFileName = null;

        attachBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', async () => {
             if (fileInput.files.length > 0) {
                 const file = fileInput.files[0];
                 filenameSpan.innerText = "Uploading " + file.name + "...";
                 filePreview.classList.remove('hidden');
                 
                 // Upload immediately to get temp URL
                 const formData = new FormData();
                 formData.append('file', file);
                 
                 try {
                     const res = await fetch('{{ route('ai.upload') }}', {
                         method: 'POST',
                         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                         body: formData
                     });
                     if (res.ok) {
                         const data = await res.json();
                         uploadedFileUrl = data.url;
                         uploadedFileName = data.filename;
                         filenameSpan.innerText = uploadedFileName;
                     } else {
                         filenameSpan.innerText = "Upload failed.";
                     }
                 } catch (e) {
                     filenameSpan.innerText = "Error uploading.";
                     console.error(e);
                 }
             }
        });

        removeFileBtn.addEventListener('click', () => {
            fileInput.value = '';
            uploadedFileUrl = null;
            uploadedFileName = null;
            filePreview.classList.add('hidden');
        });

        // Chat open/closed state only (no message history)
        const CHAT_OPEN_KEY = 'ablebot_chat_open';
        
        // Interactive Form Fill - SessionStorage helpers
        const FORM_SESSION_KEY = 'ablebot_interactive_form';
        
        function getFormSession() {
            const data = sessionStorage.getItem(FORM_SESSION_KEY);
            return data ? JSON.parse(data) : { fields: {}, active: false };
        }
        
        function setFormSession(data) {
            sessionStorage.setItem(FORM_SESSION_KEY, JSON.stringify(data));
        }
        
        function addFieldToSession(selector, value) {
            const session = getFormSession();
            session.fields[selector] = value;
            session.active = true;
            setFormSession(session);
            console.log('[AbleBot] Stored field:', selector, '=', value, 'All fields:', session.fields);
        }
        
        function clearFormSession() {
            sessionStorage.removeItem(FORM_SESSION_KEY);
            console.log('[AbleBot] Form session cleared');
        }
        
        function getAllStoredFields() {
            const session = getFormSession();
            return session.fields || {};
        }
        
        // Restore open/closed state on page load
        if (localStorage.getItem(CHAT_OPEN_KEY) === 'true') {
            chatWindow.classList.remove('hidden', 'scale-95', 'opacity-0');
            chatWindow.classList.add('scale-100', 'opacity-100');
        }

        // Toggle Chat
        function toggleChat() {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                localStorage.setItem(CHAT_OPEN_KEY, 'true');
                setTimeout(() => {
                    chatWindow.classList.remove('scale-95', 'opacity-0');
                    chatWindow.classList.add('scale-100', 'opacity-100');
                    input.focus();
                }, 10);
            } else {
                chatWindow.classList.remove('scale-100', 'opacity-100');
                chatWindow.classList.add('scale-95', 'opacity-0');
                localStorage.setItem(CHAT_OPEN_KEY, 'false');
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                }, 300);
            }
        }

        toggleBtn.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);

        // Add User Message
        function addUserMessage(text) {
            const div = document.createElement('div');
            div.className = 'flex items-start justify-end place-content-end';
            div.innerHTML = `
                <div class="bg-slate-900 text-white p-4 rounded-xl rounded-tr-none shadow-sm text-sm leading-relaxed max-w-[85%] break-words">
                    ${text}
                </div>
            `;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        // Add AI Message
        // isTemporary = true means this is a loading/placeholder message that should NOT be saved
        function addAiMessage(text, isTemporary = false) {
            const div = document.createElement('div');
            div.className = 'flex items-start';
            if (isTemporary) {
                div.setAttribute('data-temporary', 'true');
            }
            div.innerHTML = `
                <div class="w-6 h-6 rounded-md bg-slate-200 flex-shrink-0 flex items-center justify-center text-slate-600 mr-3 text-[10px] font-bold uppercase tracking-wider mt-1">AI</div>
                <div class="bg-white text-slate-700 p-4 rounded-xl rounded-tl-sm shadow-sm border border-slate-100 text-sm leading-relaxed max-w-[85%] break-words${isTemporary ? ' loading-dots' : ''}">
                    ${text}
                </div>
            `;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        /* --- AGENT: VISION (DOM SERIALIZATION) (F18 - Farhan Zarif) --- */
        function serializePage() {
            // AI Agent always needs to detect page elements for navigation/form filling
            // (Screen reader preference only affects voice output, not element detection)

            // Get ALL visible text for page context (including modals)
            const pageText = document.body.innerText.substring(0, 2000); // First 2000 chars of visible text
            
            // Get visible modals/dialogs
            const modals = document.querySelectorAll('[role="dialog"], .modal, .modal-content, [class*="modal"], [class*="popup"], [class*="dialog"]');
            let modalContent = '';
            modals.forEach(modal => {
                if (modal.offsetParent !== null) { // Is visible
                    modalContent += modal.innerText.substring(0, 500) + '\n';
                }
            });
            
            // Get interactive elements
            const interactive = document.querySelectorAll('button, a, input, select, textarea, [role="button"]');
            let elements = [];
            interactive.forEach((el, index) => {
                // Generate a temporary ID if none exists
                if (!el.id) el.id = 'ai-gen-' + index;
                
                // Visible check
                const rect = el.getBoundingClientRect();
                if (rect.width > 0 && rect.height > 0 && window.getComputedStyle(el).visibility !== 'hidden') {
                    let options = [];
                    if (el.tagName.toLowerCase() === 'select') {
                         options = Array.from(el.options).map(o => ({
                             value: o.value,
                             text: o.innerText
                         }));
                    }
                    
                    // Phase 4: Capture validation attributes
                    // Phase 6: Capture checkbox state
                    let isChecked = null;
                    let labelText = el.innerText || el.placeholder || el.value || el.getAttribute('aria-label') || '';
                    
                    // Special handling for checkboxes
                    if (el.type === 'checkbox') {
                        isChecked = el.checked;
                        // Try to find associated label
                        const label = document.querySelector(`label[for="${el.id}"]`);
                        if (label) {
                            labelText = label.innerText || labelText;
                        } else if (el.parentElement && el.parentElement.tagName === 'LABEL') {
                            labelText = el.parentElement.innerText || labelText;
                        } else if (el.name) {
                            // Use name as fallback, make it readable
                            labelText = el.name.replace(/_/g, ' ').replace(/([A-Z])/g, ' $1').trim();
                        }
                    }
                    
                    elements.push({
                        tag: el.tagName.toLowerCase(),
                        id: el.id,
                        text: labelText,
                        type: el.type || '',
                        name: el.name || '',
                        options: options,
                        // Validation attributes
                        required: el.required || false,
                        pattern: el.pattern || null,
                        minLength: el.minLength > 0 ? el.minLength : null,
                        maxLength: el.maxLength > 0 ? el.maxLength : null,
                        min: el.min || null,
                        max: el.max || null,
                        // Checkbox state (Phase 6)
                        checked: isChecked
                    });
                }
            });
            
            return {
                url: window.location.href,
                pageTitle: document.title,
                visibleText: pageText.substring(0, 1000), // Summary of visible text
                modalContent: modalContent || null,
                elements: elements
            };
        }


        // Phase 5: Confirmation handler
        window.confirmPendingAction = async function(confirmed) {
            const pending = sessionStorage.getItem('ablebot_pending_action');
            sessionStorage.removeItem('ablebot_pending_action');
            
            if (confirmed && pending) {
                const { action, args } = JSON.parse(pending);
                addAiMessage("✓ Confirmed. Executing action...");
                saveChatHistory();
                speak("Confirmed. Executing now.");
                await executeAction(action, args);
            } else {
                addAiMessage("✗ Action cancelled.");
                saveChatHistory();
                speak("Action cancelled.");
            }
        };

        /* --- AGENT: ACTIONS (HANDS) --- */
        async function executeAction(action, args) {
            console.log("Agent Executing:", action, args);

            if (action === 'navigate') {
                 window.location.href = args.url;
                 return;
            }

            if (action === 'click_element') {
                let el = document.getElementById(args.selector.replace('#', ''));
                if (!el && args.selector.startsWith('#')) el = document.querySelector(args.selector);
                if (!el) el = document.querySelector(args.selector); // Try as raw selector
                
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Visual feedback
                    const originalTransition = el.style.transition;
                    el.style.transition = 'all 0.2s';
                    el.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)"; // Green glow
                    el.style.transform = "scale(1.05)";
                    
                    setTimeout(() => {
                        el.style.boxShadow = "";
                        el.style.transform = "";
                        el.style.transition = originalTransition;
                    }, 500);

                    el.click();
                    el.focus();
                    console.log(`[AbleBot] Clicked ${args.selector}`);
                } else {
                    console.error(`[AbleBot] Click element not found: ${args.selector}`);
                }
                return;
            }

            // Interactive Form Fill - Store field value to sessionStorage
            if (action === 'store_field') {
                addFieldToSession(args.selector, args.value);
                
                // If all fields collected, verify and fill
                if (args.all_collected) {
                    console.log('[AbleBot] All fields collected, verifying before fill...');
                    const storedFields = getAllStoredFields();
                    
                    // VERIFICATION: Check all elements exist on page
                    let allVerified = true;
                    let verificationResults = [];
                    
                    for (const [selector, value] of Object.entries(storedFields)) {
                        const cleanSelector = selector.replace('#', '');
                        let el = document.getElementById(cleanSelector);
                        if (!el) el = document.querySelector(selector);
                        
                        if (el) {
                            verificationResults.push(`✓ ${selector}: "${value}"`);
                        } else {
                            verificationResults.push(`✗ ${selector}: ELEMENT NOT FOUND`);
                            allVerified = false;
                        }
                    }
                    
                    console.log('[AbleBot] Verification results:', verificationResults);
                    
                    if (allVerified) {
                        // Build fill_form fields array from stored data
                        const fields = Object.entries(storedFields).map(([selector, value]) => ({
                            selector: selector,
                            value: value
                        }));
                        
                        // Show summary of what we're filling
                        addAiMessage(`📋 Filling ${fields.length} fields...`);
                        
                        // Execute fill_form with all collected fields
                        await executeAction('fill_form', { fields: fields });
                        
                        // Clear the session after successful fill
                        clearFormSession();
                        addAiMessage("✅ All fields filled successfully!");
                    } else {
                        addAiMessage("⚠️ Some form elements were not found. Please check the form and try again.");
                        console.error('[AbleBot] Verification failed:', verificationResults);
                    }
                }
                return;
            }

            // Handle fill_form (used by both instant fill and interactive fill)
            if (action === 'fill_form') {
                console.log('[AbleBot] Filling form with fields:', args.fields);
                for (const field of args.fields) {
                    await executeAction('fill_input', { selector: field.selector, value: field.value });
                    await new Promise(r => setTimeout(r, 150)); // Small delay between fields
                }
                return;
            }

            // Phase 5: Confirmation Dialog
            if (action === 'confirm_action') {
                console.log('[AbleBot] Confirmation requested:', args);
                
                // Store pending action for later execution
                sessionStorage.setItem('ablebot_pending_action', JSON.stringify({
                    action: args.pending_action,
                    args: args.pending_args
                }));
                
                // Create confirmation message
                const severityColor = {
                    'high': '🔴',
                    'medium': '🟡', 
                    'low': '🟢'
                };
                const emoji = severityColor[args.severity] || '⚠️';
                
                addAiMessage(`${emoji} <strong>Confirm:</strong> ${args.action_description}<br><br>
                    <button onclick="confirmPendingAction(true)" class="px-3 py-1 bg-green-500 text-white rounded mr-2">Yes, do it</button>
                    <button onclick="confirmPendingAction(false)" class="px-3 py-1 bg-gray-300 text-gray-700 rounded">Cancel</button>`);
                speak(args.action_description + ". Do you want me to proceed?");
                return;
            }
            
            // Phase 4: Read Page (handled in backend, just display summary)
            if (action === 'read_page') {
                speak(args.summary);
                return;
            }



            if (action === 'fill_input') {
                // Robust Selector Strategy
                let el = document.getElementById(args.selector.replace('#', ''));
                if (!el && args.selector.startsWith('#')) {
                    el = document.querySelector(args.selector);
                }
                if (!el) {
                    el = document.querySelector(`[name="${args.selector.replace('#', '')}"]`);
                }

                if (el) {
                    // Scroll to it so user sees the action
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // DEBUG: Log element details
                    console.log(`[AbleBot] Filling ${args.selector} with "${args.value}"`);
                    console.log(`[AbleBot] Element Type: ${el.tagName}, Type: ${el.type}`);
                    
                    // Sanitize Value (Strip quotes if AI sent them)
                    let targetVal = String(args.value).replace(/^['"]|['"]$/g, '').trim();

                    // Phase 6: Handle Checkboxes in fill_input
                    if (el.type === 'checkbox') {
                        const shouldCheck = ['true', '1', 'yes', 'on', 'checked'].includes(targetVal.toLowerCase());
                        el.checked = shouldCheck;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        
                        // Visual feedback
                        const wrapper = el.parentElement || el;
                        wrapper.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                        setTimeout(() => wrapper.style.boxShadow = "", 1500);
                        
                        console.log(`[AbleBot] Checkbox ${args.selector} set to ${shouldCheck}`);
                        return;
                    }

                    // Special Handling for Select Dropdowns
                    if (el.tagName.toUpperCase() === 'SELECT') {
                         let matchFound = false;

                         // 1. Try Direct Assignment (Native Browser Logic)
                         try {
                             el.value = targetVal;
                             if (el.value === targetVal) matchFound = true;
                         } catch (e) {
                             console.error("[AbleBot] Direct assignment failed", e);
                         }

                         // 2. If Direct Assignment failed, Try Fuzzy Match
                         if (!matchFound) {
                             const lowerVal = targetVal.toLowerCase();
                             for (let i = 0; i < el.options.length; i++) {
                                 const optVal = el.options[i].value.toLowerCase();
                                 const optText = el.options[i].text.toLowerCase();
                                 if (optVal === lowerVal || optText === lowerVal || optText.includes(lowerVal)) {
                                     el.selectedIndex = i;
                                     matchFound = true;
                                     console.log(`[AbleBot] Fuzzy matched "${targetVal}" to option "${el.options[i].text}"`);
                                     break;
                                 }
                             }
                         }

                         // Visual Feedback (box-shadow is most reliable)
                         const originalShadow = el.style.boxShadow;
                         el.style.boxShadow = matchFound 
                             ? "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)" 
                             : "0 0 0 4px #ef4444, 0 0 20px rgba(239, 68, 68, 0.5)";
                         
                         setTimeout(() => el.style.boxShadow = originalShadow, 2000);
                         
                    } else if (el.classList.contains('custom-select') || el.getAttribute('role') === 'listbox') {
                        // Custom Dropdown Fallback (Vue/React components)
                        console.log("[AbleBot] Detected custom dropdown, attempting click strategy");
                        el.click();
                        await new Promise(r => setTimeout(r, 300));
                        
                        // Try to find and click the matching option
                        const opt = document.querySelector(`[data-value="${targetVal}"]`) 
                                 || document.querySelector(`.dropdown-item:contains("${targetVal}")`);
                        if (opt) {
                            opt.click();
                            el.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                        } else {
                            el.style.boxShadow = "0 0 0 4px #ef4444, 0 0 20px rgba(239, 68, 68, 0.5)";
                        }
                        setTimeout(() => el.style.boxShadow = "", 2000);
                    } else {
                        // Standard Input
                        el.value = targetVal;
                        el.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                        setTimeout(() => el.style.boxShadow = "", 1000);
                    }

                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    console.error(`[AbleBot] Element ${args.selector} not found for fill_input`);
                    speak("I could not find the field " + args.selector);
                }
                return;
            }

            // Phase 6: Toggle Checkbox Action
            if (action === 'toggle_checkbox') {
                let el = document.getElementById(args.selector.replace('#', ''));
                if (!el && args.selector.startsWith('#')) {
                    el = document.querySelector(args.selector);
                }
                if (!el) {
                    el = document.querySelector(`[name="${args.selector.replace('#', '')}"]`);
                }
                if (!el) {
                    // Try to find by partial name match (for arrays like skills[])
                    el = document.querySelector(`input[type="checkbox"][name*="${args.selector.replace('#', '')}"]`);
                }

                if (el && el.type === 'checkbox') {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Determine new state
                    if (typeof args.checked === 'boolean') {
                        el.checked = args.checked;
                    } else {
                        // Toggle current state
                        el.checked = !el.checked;
                    }
                    
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    el.dispatchEvent(new Event('click', { bubbles: true }));
                    
                    // Visual feedback on the checkbox or its container
                    const wrapper = el.parentElement || el;
                    const feedbackColor = el.checked 
                        ? "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)"  // Green for checked
                        : "0 0 0 4px #f59e0b, 0 0 20px rgba(245, 158, 11, 0.5)"; // Amber for unchecked
                    wrapper.style.boxShadow = feedbackColor;
                    setTimeout(() => wrapper.style.boxShadow = "", 1500);
                    
                    console.log(`[AbleBot] Checkbox ${args.selector} toggled to ${el.checked}`);
                } else {
                    console.error(`[AbleBot] Checkbox ${args.selector} not found`);
                    speak("I could not find the checkbox " + args.selector);
                }
                return;
            }

            if (action === 'scroll_to') {
                const el = document.getElementById(args.selector.replace('#', ''));
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            
            if (action === 'upload_file') {
                 console.log("[AbleBot] Attempting upload...", args);
                 
                 // Check if we have a URL
                 if (!args.url) {
                     speak("No file URL found. Please attach a file first.");
                     return;
                 }
                 
                 try {
                     const response = await fetch(args.url);
                     if (!response.ok) throw new Error(`File not found (${response.status}). Please attach a file first.`);
                     
                     // Check Content-Type to ensure we got a file, not an error page
                     const contentType = response.headers.get('Content-Type') || '';
                     if (contentType.includes('text/html')) {
                         throw new Error("File expired or not found. Please attach a new file.");
                     }
                     
                     const blob = await response.blob();
                     // Use provided filename via args (from backend -> user context) OR fallback to URL
                     let filename = args.filename || args.url.split('/').pop() || "upload.jpg";
                     
                     // Ensure filename has extension
                     if (!filename.includes('.')) {
                         filename = filename + '.jpg'; // Default to jpg
                     }
                     
                     // Use Content-Type from server if valid, otherwise infer from extension
                     const ext = filename.split('.').pop().toLowerCase();
                     const mimeMap = {
                         'pdf': 'application/pdf',
                         'png': 'image/png',
                         'jpg': 'image/jpeg',
                         'jpeg': 'image/jpeg',
                         'txt': 'text/plain',
                         'gif': 'image/gif',
                         'webp': 'image/webp'
                     };
                     // Priority: Server Content-Type > Extension map > blob.type
                     let mimeType = contentType;
                     if (!mimeType || mimeType === 'application/octet-stream') {
                         mimeType = mimeMap[ext] || blob.type || 'application/octet-stream';
                     }

                     const file = new File([blob], filename, { type: mimeType });
                     
                     console.log("[AbleBot] File created:", filename, "mime:", mimeType, "size:", file.size);
                     
                     const container = new DataTransfer();
                     container.items.add(file);
                     
                     // Find file input
                     let el = args.selector ? document.getElementById(args.selector.replace('#', '')) : null;
                     if (!el) {
                         // Fallback: Try to find the first file input on page
                         el = document.querySelector('input[type="file"]');
                     }

                     if(el) {
                         el.files = container.files;
                         el.dispatchEvent(new Event('change', { bubbles: true }));
                         el.dispatchEvent(new Event('input', { bubbles: true }));
                         
                         // Visual feedback
                         el.style.boxShadow = "0 0 0 4px #22c55e, 0 0 20px rgba(34, 197, 94, 0.5)";
                         setTimeout(() => el.style.boxShadow = "", 2000);
                         
                         speak("File attached successfully.");
                         console.log("[AbleBot] File attached successfully");
                     } else {
                         speak("I could not find a file upload field on this page.");
                         console.error("[AbleBot] No file input found for upload");
                     }
                 } catch (e) {
                     console.error("[AbleBot] Upload failed", e);
                     speak("Failed to attach file. " + e.message);
                 }
                 return;
            }
        }

        /* --- AGENT: VOICE (EARS & MOUTH) (F18 - Farhan Zarif) --- */
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let recognition;
        
        // Speech recognition - only for disabled users (mic button only rendered for them)
        if (SpeechRecognition && micBtn) {
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'en-US';
            
            recognition.onstart = () => { 
                console.log('[AbleBot] Voice recognition started');
                micBtn.classList.add('bg-red-100', 'text-red-600', 'animate-pulse'); 
            };
            recognition.onend = () => { 
                console.log('[AbleBot] Voice recognition ended');
                micBtn.classList.remove('bg-red-100', 'text-red-600', 'animate-pulse'); 
            };
            recognition.onerror = (event) => {
                console.error('[AbleBot] Voice recognition error:', event.error);
                micBtn.classList.remove('bg-red-100', 'text-red-600', 'animate-pulse');
                
                // Only alert for critical errors, not timeout issues
                if (event.error === 'no-speech') {
                    speak("I didn't hear anything. Please try again.");
                } else if (event.error === 'not-allowed') {
                    alert('Microphone access denied. Please allow microphone in browser settings.');
                } else if (event.error === 'network') {
                    alert('Network error. Please check your internet connection.');
                }
                // Other errors are logged but not alerted
            };
            
            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                console.log('[AbleBot] Voice recognized:', transcript);
                input.value = transcript;
                form.dispatchEvent(new Event('submit')); // Auto-submit
            };

            micBtn.addEventListener('click', () => {
                console.log('[AbleBot] Mic button clicked, starting recognition...');
                recognition.start();
            });
        } else if (!micBtn) {
            console.log('[AbleBot] Mic button not present (non-disabled user)');
        } else {
            console.warn('[AbleBot] Speech Recognition not supported');
        }

        function speak(text) {
            // ROLE CHECK: TTS only enabled for disabled users
            if (!window.ableLinkIsDisabled) {
                console.log("[AbleBot] TTS disabled for non-disabled users");
                return;
            }
            
            // User Preference Check:
            // 1. Voice Navigation Unchecked -> Disable Agent TTS service (as per user request)
            // 2. Text-to-Speech Unchecked -> Disable TTS Overall
            // So BOTH must be true/enabled (or undefined/default) for speech to work.
            
            const prefs = window.ableLinkPrefs || {};
            // Default to TRUE if undefined for disabled users
            
            // Note: prefs keys might be missing if new user, treat as true for disabled users.
            const voiceNav = prefs.voice_navigation_enabled !== false && prefs.voice_navigation_enabled !== 0 && prefs.voice_navigation_enabled !== '0';
            const ttsEnabled = prefs.text_to_speech_enabled !== false && prefs.text_to_speech_enabled !== 0 && prefs.text_to_speech_enabled !== '0';

            if (!voiceNav || !ttsEnabled) {
                console.log("[AbleBot] TTS blocked by user preferences (VoiceNav: " + voiceNav + ", TTS: " + ttsEnabled + ")");
                return;
            }

            if ('speechSynthesis' in window) {
                // Cancel any ongoing speech first
                window.speechSynthesis.cancel();
                
                const utterance = new SpeechSynthesisUtterance(text);
                window.speechSynthesis.speak(utterance);
            }
        }

        // Handle Submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            let text = input.value.trim();
            if (!text && !uploadedFileUrl) return;

            // Append file context if present
            if (uploadedFileUrl) {
                // Format: [Attached File: URL|Filename]
                text += `\n[Attached File: ${uploadedFileUrl}|${uploadedFileName}]`;
            }

            addUserMessage(text.replace(/\[Attached File: .*?\]/, '[File]'));
            input.value = '';
            // Reset file
            fileInput.value = '';
            uploadedFileUrl = null;
            uploadedFileName = null;
            filePreview.classList.add('hidden');
            
            // Show loading state (marked as temporary - won't be saved to localStorage)
            addAiMessage('Thinking...', true);

            try {
                // Capture Page State
                const pageSnapshot = serializePage();

                const response = await fetch('{{ route('ai.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        message: text,
                        current_url: window.location.href,
                        page_structure: pageSnapshot // Send "Eyes" data
                    })
                });

                const data = await response.json();
                
                // Remove the temporary "Thinking..." message
                const tempMsg = messages.querySelector('[data-temporary="true"]');
                if (tempMsg) {
                    messages.removeChild(tempMsg);
                }
                
                // Add the real AI response (this will auto-save to localStorage)
                addAiMessage(data.reply);

                // Speak Reply (Voice Copilot)
                if (data.voice_summary) {
                    speak(data.voice_summary);
                } else {
                    speak(data.reply);
                }

                // Handle One or Multiple Actions
                if (data.actions && data.actions.length > 0) {
                     for (const act of data.actions) {
                         try {
                             await executeAction(act.name, act.args);
                         } catch (err) {
                             console.error("Action Execution Error:", act.name, err);
                             alert("Error executing " + act.name + ": " + err.message);
                         }
                         // Small delay between actions for visual clarity
                         await new Promise(r => setTimeout(r, 100));
                     }
                } else if (data.action && data.action !== 'message') {
                    // Backwards compatibility
                    await executeAction(data.action, data.args);
                }

            } catch (error) {
                console.error(error);
                // Remove the temporary "Thinking..." message if it exists
                const tempMsg = messages.querySelector('[data-temporary="true"]');
                if (tempMsg) {
                    messages.removeChild(tempMsg);
                }
                // Show error message
                addAiMessage("Sorry, something went wrong.");
            }
        });
    </script>
</body>
</html>

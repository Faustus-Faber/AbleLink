<!DOCTYPE html>
@php
    $bodyClassString = '';
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $bodyClassString }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AbleLink') }}</title>

    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

    <script>
        window.ableLinkUserRole = @json(auth()->check() ? auth()->user()->role : 'guest');
    </script>
    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">
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
    </div>
</body>
</html>

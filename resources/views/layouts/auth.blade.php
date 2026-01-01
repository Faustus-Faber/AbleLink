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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $bodyClassString }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- //F6 - Evan Yuvraj Munshi// -->
    @vite(['resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<a href="#main-content" class="skip-to-content">Skip to main content</a>
<body class="antialiased text-slate-800 bg-white min-h-screen flex flex-col relative overflow-x-hidden {{ $bodyClassString }}">

    <div class="absolute top-0 inset-x-0 h-[400px] bg-gradient-to-br from-blue-600 to-purple-700 z-0 rounded-b-[3rem] shadow-2xl"></div>

    <div class="relative z-10 pt-12 pb-6 text-center">
        <a href="{{ route('home') }}" class="inline-block">
             <span class="text-4xl font-extrabold text-white tracking-tight drop-shadow-md">AbleLink</span>
        </a>
        <h2 class="mt-4 text-center text-xl text-blue-100 font-medium">
            @yield('title', 'Welcome Back')
        </h2>
    </div>

    <div class="flex-grow flex items-start justify-center px-4 sm:px-6 lg:px-8 relative z-20 -mt-4 pb-12">
        <div class="w-full max-w-[500px]">
            <div class="bg-white rounded-[2rem] shadow-2xl border border-slate-100 relative">
                <div class="p-10 sm:p-12">
                    
                    @if (session('status'))
                        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl text-sm font-medium flex items-center shadow-sm">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl text-sm font-medium shadow-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="space-y-6">
                        @yield('content')
                    </div>
                </div>
            </div>

        </div>
        
        </div>
    </div>

    @include('partials.footer')

    <style>
        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="number"],
        select {
            display: block;
            width: 100%;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            border-width: 2px;
            border-color: #e2e8f0;
            border-radius: 1rem;
            background-color: #f8fafc;
            color: #0f172a;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4f46e5; 
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        input::placeholder {
            color: #94a3b8;
        }

        label {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.75rem; 
            margin-left: 0.25rem;
        }
   
        button[type="submit"] {
            display: flex;
            width: 100%;
            justify-content: center;
            align-items: center;
            height: 3.5rem;
            border-radius: 1rem;
            background-image: linear-gradient(to right, #2563eb, #7c3aed);
            font-size: 1.125rem;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.4);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.5);
        }

        a.text-sm {
            color: #64748b;
            font-weight: 600;
            transition: color 0.2s;
        }
        a.text-sm:hover {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AbleLink') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        <header class="bg-blue-600 text-white shadow-md">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold">AbleLink</a>
                <nav>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-3 hover:underline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-3 hover:underline">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-3 hover:underline">Register</a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <main class="flex-grow container mx-auto px-4 py-8">
            @yield('content')
        </main>

        <footer class="bg-gray-800 text-white py-6 mt-auto">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} AbleLink. Accessible for Everyone.</p>
            </div>
        </footer>
    </div>
</body>
</html>

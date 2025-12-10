<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AbleLink</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your theme file (for disability colors etc.) -->
    <link rel="stylesheet" href="{{ asset('css/user-themes.css') }}">

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
    </style>
</head>

<body class="@auth theme-{{ Auth::user()->disability_type ?? 'default' }} @endauth">

    <!-- ✅ TOP BAR (gradient, not black) -->
    <nav class="px-4 d-flex align-items-center justify-content-between"
         style="height:60px;background:linear-gradient(135deg,#7fd3ff,#b194ff);color:#ffffff;">
        <span class="fw-bold">AbleLink</span>

        <div class="d-flex align-items-center gap-3">
            @auth
                <span class="badge bg-light text-dark text-uppercase">
                    {{ Auth::user()->role }}
                </span>

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </nav>

    <!-- ✅ FULLSCREEN LAYOUT (no borders, no extra container padding) -->
    <div style="width:100vw;height:calc(100vh - 60px);overflow:hidden;">
        <div class="d-flex h-100 w-100">
            {{-- Sidebar goes here --}}
            @yield('sidebar')

            {{-- Main content area --}}
            <div class="flex-fill h-100 overflow-auto">
                @yield('content')
            </div>
        </div>
    </div>

</body>
</html>


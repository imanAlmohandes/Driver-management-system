{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html> --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet">

    <style>
        body{
            background: radial-gradient(1200px circle at top, #eef4ff 0%, #f7f9fc 40%, #ffffff 100%);
            min-height: 100vh;
        }
        .auth-shell{
            min-height: calc(100vh - 120px);
            display:flex;
            align-items:center;
        }
        .brand-logo{
            height: 44px;
            width: auto;
        }
        .auth-card{
            border:0;
            border-radius: 18px;
            overflow:hidden;
        }
        .auth-card .card-header{
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border:0;
            padding: 18px 20px;
        }
        .form-control{
            border-radius: 12px;
            padding: 12px 14px;
        }
        .btn-lg{
            border-radius: 14px;
            padding: 12px 14px;
        }
        .hint{
            font-size: 12px;
            color: #6c757d;
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container justify-content-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <img src="{{ asset('logo.png') }}" class="brand-logo mr-2" alt="Logo">
                <strong>{{ config('app.name') }}</strong>
            </a>
        </div>
    </nav>

    <main class="auth-shell">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <footer class="text-center text-muted small pb-4">
        © {{ now()->year }} {{ config('app.name') }}
    </footer>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    @stack('scripts')
</body>

</html>

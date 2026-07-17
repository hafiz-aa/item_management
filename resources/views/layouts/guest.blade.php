<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Item Management System') }} - {{ $title ?? 'Authentication' }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 450px;">
            <div class="text-center mb-4">
                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                    style="width: 64px; height: 64px;">
                    <i class="bi bi-box-seam text-white fs-2"></i>
                </div>
                <h4 class="fw-bold">{{ config('app.name') }}</h4>
                <p class="text-muted">Item Management System</p>
            </div>
            @yield('content')
        </div>
    </div>
</body>

</html>

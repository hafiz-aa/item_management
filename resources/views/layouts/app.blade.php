<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Item Management System') }} - @yield('title', 'Dashboard')</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="wrapper d-flex">
        @include('layouts.sidebar')

        <div class="content-wrapper d-flex flex-column flex-grow-1 min-vh-100">
            @include('layouts.navbar')

            <main class="flex-grow-1 p-3 bg-light">
                <div class="container-fluid">
                    @include('components.breadcrumb')
                    @include('components.alerts')
                    @yield('content')
                </div>
            </main>

            @include('layouts.footer')
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElList = document.querySelectorAll('.toast');
            toastElList.forEach(function(el) {
                new bootstrap.Toast(el).show();
            });

            var tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            tooltipTriggerList.forEach(function(el) {
                new bootstrap.Tooltip(el);
            });

            var sidebar = document.querySelector('.sidebar');
            var sidebarToggle = document.getElementById('sidebarToggle');
            var STORAGE_KEY = 'sidebar_collapsed';

            if (localStorage.getItem(STORAGE_KEY) === 'true') {
                sidebar.classList.add('collapsed');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem(STORAGE_KEY, sidebar.classList.contains('collapsed'));

                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                        var tooltip = bootstrap.Tooltip.getInstance(el);
                        if (tooltip) {
                            if (sidebar.classList.contains('collapsed')) {
                                tooltip.enable();
                            } else {
                                tooltip.disable();
                            }
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Train4Best') }} Student</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/user-dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/js/app.js'])

    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Header -->
        <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
            @include('components.navbar.participant-navbar')
        </div>
        
        <!-- Sidebar -->
        <div class="fixed top-16 left-0 bottom-0 z-40">
            @include('components.sidebars.participant-sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-55 pt-16">
            <main class="py-7">
                <div class="px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 hidden lg:hidden"></div>

    <!-- Toast Notifications -->
    @include('components.toast-notification')

    @stack('scripts')

    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        sidebarToggle?.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // User menu dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        userMenuButton?.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuButton?.contains(e.target) && !userMenu?.contains(e.target)) {
                userMenu?.classList.add('hidden');
            }
        });
        


        // Toast notifications are now handled by the toast-notification component
    </script>
</body>
</html> 
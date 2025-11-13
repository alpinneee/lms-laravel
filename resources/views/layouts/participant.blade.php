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
        @include('components.sidebars.participant-sidebar')

        <!-- Main content -->
        <div class="lg:pl-56 pt-16">
            <main class="py-4 lg:py-7">
                <div class="px-3 sm:px-4 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-35 bg-black bg-opacity-50" style="display: none;" onclick="closeSidebar()"></div>

    <!-- Toast Notifications -->
    @include('components.toast-notification')

    @stack('scripts')

    <script>
        let sidebarOpen = false;
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.style.transform = 'translateX(-100%)';
            overlay.style.display = 'none';
            sidebarOpen = false;
        }

        function openSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.style.transform = 'translateX(0)';
            overlay.style.display = 'block';
            sidebarOpen = true;
        }

        function toggleSidebar() {
            // Only allow toggle on mobile
            if (window.innerWidth < 1024) {
                if (sidebarOpen) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        }

        // Initialize sidebar as closed
        document.addEventListener('DOMContentLoaded', function() {
            // Check if desktop or mobile
            if (window.innerWidth >= 1024) {
                // Desktop - show sidebar
                const sidebar = document.getElementById('sidebar');
                sidebar.style.transform = 'translateX(0)';
                sidebarOpen = true;
            } else {
                // Mobile - hide sidebar
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                // Desktop - always show sidebar
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                sidebar.style.transform = 'translateX(0)';
                overlay.style.display = 'none';
                sidebarOpen = true;
            } else {
                // Mobile - hide if not manually opened
                if (!sidebarOpen) {
                    closeSidebar();
                }
            }
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
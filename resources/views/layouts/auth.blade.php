<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Authentication') - {{ config('app.name', 'Train4Best') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800" rel="stylesheet" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/js/app.js'])
</head>
<body class="h-full font-sans ">
    <div class="min-h-full flex">
        <!-- Left side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Background image -->
            <img src="{{ asset('img/bg.png') }}" alt="Background" class="absolute h-full w-full object-cover">
            
            <div class="relative z-10 flex flex-col justify-center px-12 py-12 text-white">
                <!-- Logo -->
                <div class="mb-12">
                  <img src="{{ asset('img/LogoT4B.png') }}" alt="Train4Best Logo" class="h-20 mx-auto mb-6 ">
                </div>
            </div>
        </div>

        <!-- Right side - Form -->
        <div class="flex-1 flex flex-col justify-center px-6 py-12 lg:px-8">
            <div class="mx-auto w-full max-w-md">
                <!-- Mobile Logo -->
          

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

    <!-- Scripts -->
    <script>
        // Show toast notifications
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast animate-slide-down mb-2 max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto border-l-4 ${
                type === 'success' ? 'border-green-400' : 
                type === 'error' ? 'border-red-400' : 
                type === 'warning' ? 'border-yellow-400' : 'border-blue-400'
            }`;
            
            toast.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? 
                                '<svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                                '<svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
                            }
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm font-medium text-gray-900">${message}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button class="close-toast bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);
            
            // Close button handler
            toast.querySelector('.close-toast').addEventListener('click', () => {
                toast.remove();
            });
        }

        // Show Laravel session messages as toasts
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
    </script>
</body>
</html> 
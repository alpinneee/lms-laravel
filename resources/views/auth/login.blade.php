@extends('layouts.auth')

@section('title', 'Login')

@push('scripts')
<script>
    // Check for session flash messages and URL parameters on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Check for session messages
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
        
        // Check for logout message in URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('logout') === 'success') {
            const message = urlParams.get('message');
            if (message) {
                showToast(decodeURIComponent(message), 'success');
                
                // Clean up URL by removing the parameters without refreshing the page
                const url = new URL(window.location);
                url.searchParams.delete('logout');
                url.searchParams.delete('message');
                window.history.replaceState({}, '', url);
            }
        }
    });
</script>
@endpush

@section('content')
<div class="animate-fade-in max-w-md mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Welcome</h2>
        <p class="text-sm text-gray-600">Sign in to your Train4Best account</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        
        <!-- Email -->
        <div>
            <label for="email" class="form-label text-sm">Email</label>
            <div class="relative">
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email" 
                       required 
                       value="{{ old('email') }}"
                       class="form-input pl-10 py-2 text-sm @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Enter your email"> 
            </div>
            @error('email')
                <p class="form-error text-xs">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="form-label text-sm">Password</label>
            <div class="relative">
                <input id="password" 
                       name="password" 
                       type="password" 
                       autocomplete="current-password" 
                       required 
                       class="form-input pl-10 py-2 text-sm @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Enter your password">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="form-error text-xs">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember me & Forgot password -->
        <div class="flex items-center justify-between text-xs">
            <div class="flex items-center">
                <input id="remember" 
                       name="remember" 
                       type="checkbox" 
                       class="h-3 w-3 text-primary-800 focus:ring-primary-500 border-gray-300 rounded">
                <label for="remember" class="ml-1.5 text-gray-700">
                    Remember me
                </label>
            </div>

            <a href="{{ route('password.request') }}" class="font-medium text-primary-800 hover:text-primary-700 transition-colors duration-200">
                Forgot password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary w-full py-2 text-sm mt-2">
            Sign in
        </button>
    </form>

    <!-- Register Link -->
    <div class="mt-4 text-center text-xs">
        <span class="text-gray-500">Don't have an account?</span>
        <a href="{{ route('register') }}" class="font-medium text-primary-800 hover:text-primary-700 ml-1">
            Create new account
        </a>
    </div>

    <!-- Demo Accounts -->
    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg text-xs">
        <h4 class="font-medium text-yellow-800 mb-1">Demo Accounts:</h4>
        <div class="text-yellow-700 space-y-0.5">
            <div><strong>Admin:</strong> admin@train4best.com / password</div>
            <div><strong>Instructor:</strong> instructor@train4best.com / password</div>
            <div><strong>Participant:</strong> participant@train4best.com / password</div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.innerHTML = `
                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"/>
                    </svg>
                `;
            } else {
                input.type = 'password';
                this.innerHTML = `
                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                `;
            }
        });
    });
});
</script>
@endsection
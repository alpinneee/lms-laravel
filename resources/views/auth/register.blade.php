@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="animate-fade-in max-w-md mx-auto">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
        <p class="text-sm text-gray-600">Join Train4Best and start your learning journey</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-3">
        @csrf
        
        

        <!-- Username -->
        <div>
            <label for="username" class="form-label text-sm">Username</label>
            <div class="relative">
                <input id="username" 
                       name="username" 
                       type="text" 
                       autocomplete="username" 
                       required 
                       value="{{ old('username') }}"
                       class="form-input pl-10 py-2 text-sm @error('username') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Choose a username">
            </div>
            @error('username')
                <p class="form-error text-xs">{{ $message }}</p>
            @enderror
        </div>

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
                       autocomplete="new-password" 
                       required 
                       class="form-input pl-10 py-2 text-sm @error('password') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Create a password">
               
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

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="form-label text-sm">Confirm Password</label>
            <div class="relative">
                <input id="password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       autocomplete="new-password" 
                       required 
                       class="form-input pl-10 py-2 text-sm"
                       placeholder="Confirm your password">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-4 w-4 text-gray-400 hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="flex items-center">
            <input id="terms" 
                   name="terms" 
                   type="checkbox" 
                   required
                   class="h-3 w-3 text-primary-800 focus:ring-primary-500 border-gray-300 rounded">
            <label for="terms" class="ml-2 text-xs text-gray-700">
                I agree to the <a href="#" class="text-primary-800 hover:text-primary-700">Terms of Service</a> and <a href="#" class="text-primary-800 hover:text-primary-700">Privacy Policy</a>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-primary w-full py-2 text-sm mt-2">
            Create Account
        </button>
    </form>

    <!-- Login Link -->
    <div class="mt-4 text-center text-xs">
        <span class="text-gray-500">Already have an account?</span>
        <a href="{{ route('login') }}" class="font-medium text-primary-800 hover:text-primary-700 ml-1">
            Sign in
        </a>
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
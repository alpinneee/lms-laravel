@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img src="{{ asset('img/LogoT4B.png') }}" alt="Train4Best" class="h-12">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Reset Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Masukkan email Anda untuk reset password
            </p>
            @if(config('app.debug'))
                <div class="mt-4 p-3 bg-yellow-100 border border-yellow-400 text-yellow-700 text-xs rounded">
                    <strong>Debug Info:</strong><br>
                    Mail Driver: {{ config('mail.mailer') }}<br>
                    @if(config('mail.mailer') === 'log')
                        Email akan disimpan di: storage/logs/laravel.log
                    @elseif(config('mail.mailer') === 'smtp' && config('mail.host') === 'smtp.resend.com')
                        Resend SMTP Host: {{ config('mail.host') }}<br>
                        Port: {{ config('mail.port') }}<br>
                        Username: {{ config('mail.username') }}<br>
                        API Key: {{ config('mail.password') ? 'Configured' : 'Not Set' }}<br>
                        From: {{ config('mail.from.address') }}
                    @else
                        Mail Host: {{ config('mail.host') }}<br>
                        Mail Port: {{ config('mail.port') }}
                    @endif
                </div>
            @endif
        </div>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            
            <div>
                <label for="email" class="sr-only">Email address</label>
                <input id="email" name="email" type="email" required 
                       class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                       placeholder="Email address" value="{{ old('email') }}">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Link Reset Password
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
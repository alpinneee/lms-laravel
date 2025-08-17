<?php

use Illuminate\Support\Facades\Route;

// Debug routes - hanya untuk development
if (app()->environment('local')) {
    Route::get('/debug/google-config', function () {
        return response()->json([
            'google_client_id' => config('services.google.client_id'),
            'google_client_secret' => config('services.google.client_secret') ? 'SET' : 'NOT SET',
            'google_redirect' => config('services.google.redirect'),
            'app_url' => config('app.url'),
            'socialite_installed' => class_exists('Laravel\Socialite\Facades\Socialite'),
        ]);
    });
    
    Route::get('/debug/test-google', function () {
        try {
            $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
            return response()->json([
                'status' => 'OK',
                'message' => 'Google driver berhasil diinisialisasi',
                'redirect_url' => $driver->getTargetUrl() ?? 'Not available'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    });
    
    Route::get('/debug/callback-test', function () {
        return response()->json([
            'message' => 'Callback route accessible',
            'auth_check' => \Auth::check(),
            'user' => \Auth::user() ? \Auth::user()->toArray() : null,
            'session_id' => session()->getId()
        ]);
    });
}
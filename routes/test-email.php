<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/send-test-email', function () {
    try {
        Mail::raw('Test email dari Train4Best Laravel', function ($message) {
            $message->to('m.alfin.z117@gmail.com')
                    ->subject('Test Email - Train4Best')
                    ->from('m.alfin.z117@gmail.com', 'Train4Best');
        });
        
        return 'Email test berhasil dikirim! Cek inbox dan folder spam.';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
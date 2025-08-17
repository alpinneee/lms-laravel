<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Set HTTP client with SSL verification disabled for development
        if (app()->environment('local')) {
            $httpClient = new Client([
                'verify' => false,
                'timeout' => 30,
            ]);
            
            return Socialite::driver('google')
                ->setHttpClient($httpClient)
                ->redirect();
        }
        
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Set HTTP client with SSL verification disabled for development
            $driver = Socialite::driver('google')->stateless();
            
            if (app()->environment('local')) {
                $httpClient = new Client([
                    'verify' => false,
                    'timeout' => 30,
                ]);
                $driver->setHttpClient($httpClient);
            }
            
            $googleUser = $driver->user();
            
            // Cek apakah user sudah ada berdasarkan google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if ($user) {
                // Pastikan profil participant ada jika user adalah participant
                if ($user->isParticipant() && !$user->participant) {
                    \App\Models\Participant::create([
                        'user_id' => $user->id,
                        'full_name' => $user->name,
                    ]);
                }
                
                Auth::login($user);
                
                // Redirect berdasarkan role
                if ($user->isAdmin()) {
                    return redirect('/admin/dashboard')->with('success', 'Login berhasil!');
                } elseif ($user->isInstructor()) {
                    return redirect('/instructor/dashboard')->with('success', 'Login berhasil!');
                } else {
                    return redirect('/participant/dashboard')->with('success', 'Login berhasil!');
                }
            }
            
            // Cek apakah email sudah terdaftar
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                $existingUser->update(['google_id' => $googleUser->id]);
                
                // Pastikan profil participant ada jika user adalah participant
                if ($existingUser->isParticipant() && !$existingUser->participant) {
                    \App\Models\Participant::create([
                        'user_id' => $existingUser->id,
                        'full_name' => $existingUser->name,
                    ]);
                }
                
                Auth::login($existingUser);
                
                // Redirect berdasarkan role
                if ($existingUser->isAdmin()) {
                    return redirect('/admin/dashboard')->with('success', 'Login berhasil!');
                } elseif ($existingUser->isInstructor()) {
                    return redirect('/instructor/dashboard')->with('success', 'Login berhasil!');
                } else {
                    return redirect('/participant/dashboard')->with('success', 'Login berhasil!');
                }
            }
            
            // Buat user baru dengan role participant
            $participantType = UserType::where('usertype', 'participant')->first();
            
            if (!$participantType) {
                return redirect('/login')->with('error', 'User type participant tidak ditemukan.');
            }
            
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'username' => $this->generateUsername($googleUser->email),
                'user_type_id' => $participantType->id,
                'status' => 'active',
            ]);
            
            // Buat profil participant
            \App\Models\Participant::create([
                'user_id' => $newUser->id,
                'full_name' => $googleUser->name,
            ]);
            
            Auth::login($newUser);
            return redirect('/participant/dashboard')->with('success', 'Akun berhasil dibuat dan login!');
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Terjadi kesalahan saat login dengan Google.');
        }
    }
    
    private function generateUsername($email)
    {
        $username = explode('@', $email)[0];
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = explode('@', $email)[0] . $counter;
            $counter++;
        }
        
        return $username;
    }

}
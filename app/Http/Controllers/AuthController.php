<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\PasswordResetEmail;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Update last login
            $user->update(['last_login' => now()]);
            
            // Pastikan profil participant ada jika user adalah participant
            if (($user->isParticipant() || $user->isUnassigned()) && !$user->participant) {
                \App\Models\Participant::create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                ]);
            }
            
            // Get user role for welcome message
            $userRole = $user->getUserRole();
            $welcomeMessage = 'Welcome back, ' . $user->name . '! You have successfully logged in.';
            
            // Redirect based on user type with success message
            return $this->redirectByUserType($user)->with('success', $welcomeMessage);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect to login without any notification
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get the unassigned user type
        $unassignedType = UserType::where('usertype', 'unassigned')->first();
        
        if (!$unassignedType) {
            // Fallback to participant if unassigned doesn't exist
            $unassignedType = UserType::where('usertype', 'participant')->first();
        }

        $user = User::create([
            'name' => $request->username, // Use username as temporary name
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type_id' => $unassignedType->id,
        ]);

        // Buat profil participant jika user type adalah participant atau unassigned
        if ($unassignedType->usertype === 'participant' || $unassignedType->usertype === 'unassigned') {
            \App\Models\Participant::create([
                'user_id' => $user->id,
                'full_name' => $request->username,
            ]);
        }

        Auth::login($user);

        return $this->redirectByUserType($user);
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();
        $token = Str::random(60);
        
        // Store token in database
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );
        
        // Store reset URL first
        \DB::table('password_reset_tokens')->where('email', $request->email)->update([
            'reset_url' => url('/reset-password/' . $token . '?email=' . urlencode($request->email))
        ]);
        
        // Send email with error handling
        try {
            Mail::to($user->email)->send(new PasswordResetEmail($user, $token));
            return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            
            // Return with detailed error for debugging
            $errorMessage = 'Gagal mengirim email reset password. ';
            
            if (config('mail.mailer') === 'log') {
                $errorMessage .= 'Email disimpan di log file (storage/logs/laravel.log). ';
            } elseif (config('mail.mailer') === 'smtp' && config('mail.host') === 'smtp.resend.com') {
                $errorMessage .= 'Error Resend SMTP: ' . $e->getMessage() . '. Periksa MAIL_PASSWORD (API Key)';
            } else {
                $errorMessage .= 'Error: ' . $e->getMessage() . '. ';
            }
            
            if (config('mail.mailer') !== 'log') {
                $errorMessage .= '. Periksa konfigurasi email di file .env';
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }
    
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-form', ['token' => $token, 'email' => $request->email]);
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $resetRecord = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
            
        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return redirect()->back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }
        
        // Check if token is expired (24 hours)
        if (now()->diffInHours($resetRecord->created_at) > 24) {
            return redirect()->back()->withErrors(['email' => 'Token reset password sudah expired.']);
        }
        
        // Update password
        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);
        
        // Delete token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    private function redirectByUserType($user)
    {
        $userRole = $user->getUserRole();
        
        switch ($userRole) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'instructor':
                return redirect()->intended('/instructor/dashboard');
            case 'participant':
                return redirect()->intended('/participant/dashboard');
            case 'unassigned':
                // Redirect unassigned users to participant dashboard by default
                return redirect()->intended('/participant/dashboard');
            default:
                return redirect()->intended('/admin/dashboard');
        }
    }

    // API Methods for mobile/frontend
    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->update(['last_login' => now()]);
            
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user->load('userType'),
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function apiRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the unassigned user type
        $unassignedType = UserType::where('usertype', 'unassigned')->first();
        
        if (!$unassignedType) {
            // Fallback to participant if unassigned doesn't exist
            $unassignedType = UserType::where('usertype', 'participant')->first();
        }

        $user = User::create([
            'name' => $request->username, // Use username as temporary name
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type_id' => $unassignedType->id,
        ]);

        // Buat profil participant jika user type adalah participant atau unassigned
        if ($unassignedType->usertype === 'participant' || $unassignedType->usertype === 'unassigned') {
            \App\Models\Participant::create([
                'user_id' => $user->id,
                'full_name' => $request->username,
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user->load('userType'),
            'token' => $token,
        ], 201);
    }
}

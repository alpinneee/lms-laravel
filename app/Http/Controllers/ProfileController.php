<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Participant;
use App\Models\Instructure;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = null;
        
        if ($user->isParticipant()) {
            $profile = $user->participant;
        } elseif ($user->isInstructor()) {
            $profile = $user->instructure;
        }
        
        return view('profile.show', compact('user', 'profile'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'full_name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'birth_date' => 'nullable|date',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female',
            'proficiency' => 'nullable|string',
        ]);
        
        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];
        
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            if ($user->isParticipant() && $user->participant?->photo) {
                Storage::disk('public')->delete($user->participant->photo);
            } elseif ($user->isInstructor() && $user->instructure?->photo) {
                Storage::disk('public')->delete($user->instructure->photo);
            }
            
            $photoPath = $request->file('photo')->store('profiles', 'public');
        }
        
        // Update profile data based on user type
        if ($user->isParticipant()) {
            $profileData = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'job_title' => $request->job_title,
                'company' => $request->company,
                'gender' => $request->gender,
            ];
            
            if ($photoPath) {
                $profileData['photo'] = $photoPath;
            }
            
            if ($user->participant) {
                $user->participant->update($profileData);
            } else {
                $profileData['user_id'] = $user->id;
                Participant::create($profileData);
            }
        } elseif ($user->isInstructor()) {
            $profileData = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'proficiency' => $request->proficiency,
            ];
            
            if ($photoPath) {
                $profileData['photo'] = $photoPath;
            }
            
            if ($user->instructure) {
                $user->instructure->update($profileData);
            }
        }
        
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }
}
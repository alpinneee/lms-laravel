<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $participants = Participant::with(['user', 'registrations', 'certificates'])->get();
        return view('admin.participants.index', compact('participants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.participants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Find participant user type
            $participantType = UserType::where('usertype', 'participant')->first();
            
            if (!$participantType) {
                return redirect()->back()->with('error', 'Participant user type not found.');
            }

            // Create user
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type_id' => $participantType->id,
                'status' => 'active',
            ]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('participants', 'public');
            }

            // Create participant profile
            $participant = Participant::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'photo' => $photoPath,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'job_title' => $request->job_title,
                'company' => $request->company,
            ]);

            DB::commit();

            return redirect()->route('admin.participants.index')
                ->with('success', 'Participant created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        $participant->load(['user', 'registrations.class.course', 'certificates']);
        
        // Group registrations by status
        $activeRegistrations = $participant->registrations->filter(function ($registration) {
            return $registration->reg_status === 'approved' && 
                   $registration->class->start_date <= now() && 
                   $registration->class->end_date >= now();
        });
        
        $completedRegistrations = $participant->registrations->filter(function ($registration) {
            return $registration->reg_status === 'approved' && 
                   $registration->class->end_date < now();
        });
        
        $pendingRegistrations = $participant->registrations->filter(function ($registration) {
            return $registration->reg_status === 'pending';
        });
        
        return view('admin.participants.show', compact(
            'participant', 
            'activeRegistrations', 
            'completedRegistrations', 
            'pendingRegistrations'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        $participant->load('user');
        return view('admin.participants.edit', compact('participant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participant $participant)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($participant->photo) {
                    Storage::disk('public')->delete($participant->photo);
                }
                
                $photoPath = $request->file('photo')->store('participants', 'public');
                $participant->photo = $photoPath;
            }

            // Update participant profile
            $participant->full_name = $request->full_name;
            $participant->phone_number = $request->phone_number;
            $participant->address = $request->address;
            $participant->birth_date = $request->birth_date;
            $participant->gender = $request->gender;
            $participant->job_title = $request->job_title;
            $participant->company = $request->company;
            $participant->save();

            // Update associated user if email or password is changed
            if ($request->filled('email') || $request->filled('password')) {
                $user = $participant->user;
                
                if ($user) {
                    if ($request->filled('email') && $request->email !== $user->email) {
                        $request->validate([
                            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                        ]);
                        
                        $user->email = $request->email;
                    }
                    
                    if ($request->filled('password')) {
                        $request->validate([
                            'password' => 'required|string|min:8',
                        ]);
                        
                        $user->password = Hash::make($request->password);
                    }
                    
                    $user->name = $request->full_name;
                    $user->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.participants.index')
                ->with('success', 'Participant updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        // Check if participant has active registrations
        $hasActiveRegistrations = $participant->registrations()
            ->whereHas('class', function($query) {
                $query->where('end_date', '>=', now());
            })
            ->where('reg_status', 'approved')
            ->exists();
            
        if ($hasActiveRegistrations) {
            return redirect()->route('admin.participants.index')
                ->with('error', 'Cannot delete participant because they have active course registrations.');
        }

        DB::beginTransaction();

        try {
            // Delete associated user
            if ($participant->user) {
                $participant->user->delete();
            }

            // Delete photo if exists
            if ($participant->photo) {
                Storage::disk('public')->delete($participant->photo);
            }

            // Delete participant
            $participant->delete();

            DB::commit();

            return redirect()->route('admin.participants.index')
                ->with('success', 'Participant deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the status of the participant's user account.
     */
    public function toggleStatus(Participant $participant)
    {
        $user = $participant->user;
        
        if ($user) {
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();
            
            return redirect()->route('admin.participants.index')
                ->with('success', 'Participant status updated successfully.');
        }
        
        return redirect()->route('admin.participants.index')
            ->with('error', 'Participant user account not found.');
    }
}

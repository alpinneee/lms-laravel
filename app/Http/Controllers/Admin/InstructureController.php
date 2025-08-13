<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructure;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructors = Instructure::with('users')->get();
        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.instructors.create');
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
            'proficiency' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Find instructor user type
            $instructorType = UserType::where('usertype', 'instructor')->first();
            
            if (!$instructorType) {
                return redirect()->back()->with('error', 'Instructor user type not found.');
            }

            // Create user
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_type_id' => $instructorType->id,
                'status' => 'active',
            ]);

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('instructors', 'public');
            }

            // Create instructor profile
            $instructor = Instructure::create([
                'full_name' => $request->full_name,
                'photo' => $photoPath,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'proficiency' => $request->proficiency,
            ]);

            // Associate user with instructor
            $user->instructure_id = $instructor->id;
            $user->save();

            DB::commit();

            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Instructure $instructor)
    {
        $instructor->load(['users', 'classes.course', 'certificates']);
        return view('admin.instructors.show', compact('instructor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Instructure $instructor)
    {
        $instructor->load('users');
        return view('admin.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Instructure $instructor)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string',
            'proficiency' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($instructor->photo) {
                    Storage::disk('public')->delete($instructor->photo);
                }
                
                $photoPath = $request->file('photo')->store('instructors', 'public');
                $instructor->photo = $photoPath;
            }

            // Update instructor profile
            $instructor->full_name = $request->full_name;
            $instructor->phone_number = $request->phone_number;
            $instructor->address = $request->address;
            $instructor->proficiency = $request->proficiency;
            $instructor->save();

            // Update associated user if email or password is changed
            if ($request->filled('email') || $request->filled('password')) {
                $user = $instructor->users()->first();
                
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

            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Instructure $instructor)
    {
        // Check if instructor has associated classes
        if ($instructor->classes()->count() > 0) {
            return redirect()->route('admin.instructors.index')
                ->with('error', 'Cannot delete instructor because they have associated classes.');
        }

        DB::beginTransaction();

        try {
            // Delete associated user
            $user = $instructor->users()->first();
            if ($user) {
                $user->delete();
            }

            // Delete photo if exists
            if ($instructor->photo) {
                Storage::disk('public')->delete($instructor->photo);
            }

            // Delete instructor
            $instructor->delete();

            DB::commit();

            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the status of the instructor's user account.
     */
    public function toggleStatus(Instructure $instructor)
    {
        $user = $instructor->users()->first();
        
        if ($user) {
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();
            
            return redirect()->route('admin.instructors.index')
                ->with('success', 'Instructor status updated successfully.');
        }
        
        return redirect()->route('admin.instructors.index')
            ->with('error', 'Instructor user account not found.');
    }
}

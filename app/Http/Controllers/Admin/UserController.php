<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use App\Models\Instructure;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['userType', 'instructure', 'participant']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('username', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by user type
        if ($request->has('user_type') && !empty($request->user_type)) {
            $query->whereHas('userType', function ($q) use ($request) {
                $q->where('usertype', $request->user_type);
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15);
        $userTypes = UserType::all();

        return view('admin.users.index', compact('users', 'userTypes'));
    }

    public function create()
    {
        $userTypes = UserType::all();
        $instructures = Instructure::all();
        
        return view('admin.users.create', compact('userTypes', 'instructures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'instructure_id' => 'nullable|exists:instructures,id',
            'status' => 'required|in:active,inactive',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
            'email_verified_at' => now(),
            'status' => $request->status,
        ];

        if ($request->instructure_id) {
            $userData['instructure_id'] = $request->instructure_id;
        }

        $user = User::create($userData);

        // Create participant profile if user type is participant
        $userType = UserType::find($request->user_type_id);
        if ($userType && $userType->usertype === 'participant') {
            Participant::create([
                'user_id' => $user->id,
                'full_name' => $request->name,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $user->load(['userType', 'instructure', 'participant']);
        
        // Get additional statistics based on user type
        $stats = [];
        
        if ($user->isInstructor() && $user->instructure) {
            $stats = [
                'total_classes' => $user->instructure->classes()->count(),
                'total_students' => $user->instructure->getTotalStudents(),
                'certificates_issued' => $user->instructure->certificates()->count(),
            ];
        } elseif ($user->isParticipant() && $user->participant) {
            $stats = [
                'enrolled_courses' => $user->participant->registrations()->where('reg_status', 'approved')->count(),
                'completed_courses' => $user->participant->registrations()
                    ->where('reg_status', 'approved')
                    ->whereHas('class', function ($q) {
                        $q->where('end_date', '<', now());
                    })->count(),
                'certificates_earned' => $user->participant->certificates()->count(),
            ];
        }

        return view('admin.users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $userTypes = UserType::all();
        $instructures = Instructure::all();
        
        return view('admin.users.edit', compact('user', 'userTypes', 'instructures'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'instructure_id' => 'nullable|exists:instructures,id',
            'status' => 'required|in:active,inactive',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'user_type_id' => $request->user_type_id,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->instructure_id) {
            $userData['instructure_id'] = $request->instructure_id;
        } else {
            $userData['instructure_id'] = null;
        }

        $user->update($userData);

        // Handle participant profile creation/deletion based on user type change
        $userType = UserType::find($request->user_type_id);
        if ($userType && $userType->usertype === 'participant') {
            if (!$user->participant) {
                Participant::create([
                    'user_id' => $user->id,
                    'full_name' => $request->name,
                ]);
            }
        } else {
            // If user type is changed from participant to something else, we might want to keep the participant record
            // for historical data, so we don't delete it here
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Check if user has any active enrollments or important data
        if ($user->isParticipant() && $user->participant) {
            $activeRegistrations = $user->participant->registrations()
                ->where('reg_status', 'approved')
                ->whereHas('class', function ($q) {
                    $q->where('end_date', '>', now());
                })->count();
                
            if ($activeRegistrations > 0) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Cannot delete user with active course enrollments.');
            }
        }

        if ($user->isInstructor() && $user->instructure) {
            $activeClasses = $user->instructure->classes()
                ->where('status', 'active')
                ->where('end_date', '>', now())->count();
                
            if ($activeClasses > 0) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Cannot delete instructor with active classes.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Toggle the user's status between active and inactive
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);
        
        $statusText = ucfirst($newStatus);
        return redirect()->back()
            ->with('success', "User status changed to {$statusText} successfully.");
    }
} 
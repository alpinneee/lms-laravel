<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserType;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userTypes = UserType::all();
        return view('admin.user-types.index', compact('userTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usertype' => 'required|string|max:255|unique:user_types',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        UserType::create($request->all());

        return redirect()->route('admin.user-types.index')
            ->with('success', 'User type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserType $userType)
    {
        return view('admin.user-types.show', compact('userType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserType $userType)
    {
        return view('admin.user-types.edit', compact('userType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserType $userType)
    {
        $request->validate([
            'usertype' => 'required|string|max:255|unique:user_types,usertype,' . $userType->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $userType->update($request->all());

        return redirect()->route('admin.user-types.index')
            ->with('success', 'User type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserType $userType)
    {
        // Check if the user type has associated users
        if ($userType->users()->count() > 0) {
            return redirect()->route('admin.user-types.index')
                ->with('error', 'Cannot delete user type because it has associated users.');
        }

        $userType->delete();

        return redirect()->route('admin.user-types.index')
            ->with('success', 'User type deleted successfully.');
    }

    /**
     * Toggle the status of the specified resource.
     */
    public function toggleStatus(UserType $userType)
    {
        $userType->status = $userType->status === 'active' ? 'inactive' : 'active';
        $userType->save();

        return redirect()->route('admin.user-types.index')
            ->with('success', 'User type status updated successfully.');
    }
}

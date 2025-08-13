<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use Illuminate\Http\Request;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseTypes = CourseType::withCount('courses')->get();
        return view('admin.course-types.index', compact('courseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.course-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_type' => 'required|string|max:255|unique:course_types',
            'description' => 'nullable|string',
        ]);

        $courseType = CourseType::create([
            'course_type' => $request->course_type,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Course type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseType $courseType)
    {
        $courseType->load('courses');
        return view('admin.course-types.show', compact('courseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseType $courseType)
    {
        return view('admin.course-types.edit', compact('courseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseType $courseType)
    {
        $request->validate([
            'course_type' => 'required|string|max:255|unique:course_types,course_type,' . $courseType->id,
            'description' => 'nullable|string',
        ]);

        $courseType->update([
            'course_type' => $request->course_type,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Course type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseType $courseType)
    {
        // Check if there are courses using this course type
        if ($courseType->courses()->exists()) {
            return redirect()->route('admin.course-types.index')
                ->with('error', 'Cannot delete course type because it is being used by one or more courses.');
        }

        $courseType->delete();

        return redirect()->route('admin.course-types.index')
            ->with('success', 'Course type deleted successfully.');
    }
}

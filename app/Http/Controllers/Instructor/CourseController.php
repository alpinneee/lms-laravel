<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\ClassModel;
use App\Models\Instructure;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses assigned to the instructor.
     */
    public function index()
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Get courses assigned to the instructor through classes
        $myCourses = Course::whereHas('classes.instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->with(['classes' => function($query) use ($instructor) {
            $query->whereHas('instructures', function($q) use ($instructor) {
                $q->where('instructures.id', $instructor->id);
            });
        }])->get();
        
        // Get all available courses with classes and their instructors
        $allCourses = Course::with(['classes.instructures'])->get();
        
        return view('instructor.courses.index', compact('myCourses', 'allCourses', 'instructor'));
    }
    
    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Check if the instructor is assigned to any class of this course
        $isAssigned = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->exists();
        
        // Get classes of this course that are assigned to the instructor
        $classes = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->with(['registrations.participant.user', 'instructures.user'])->get();
        
        return view('instructor.courses.show', compact('course', 'classes', 'isAssigned'));
    }
    
    /**
     * Display attendance management page for a course.
     */
    public function attendance(Course $course)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Check if the instructor is assigned to any class of this course
        $isAssigned = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->exists();
        
        if (!$isAssigned) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to any class of this course.');
        }
        
        // Get classes of this course that are assigned to the instructor
        $classes = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->with(['registrations.participant.user', 'attendances'])->get();
        
        return view('instructor.courses.attendance', compact('course', 'classes'));
    }
    
    /**
     * Display materials management page for a course.
     */
    public function materials(Course $course)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Check if the instructor is assigned to any class of this course
        $isAssigned = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->exists();
        
        if (!$isAssigned) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to any class of this course.');
        }
        
        // For now, just pass an empty collection as materials
        $materials = collect();
        
        return view('instructor.courses.materials', compact('course', 'materials'));
    }
}

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
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Get classes assigned to this instructor
        $classes = $course->classes()->whereHas('instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->with('materials')->get();
        
        if ($classes->isEmpty()) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to any class of this course.');
        }
        
        return view('instructor.courses.materials', compact('course', 'classes'));
    }

    public function addMaterial(Course $course, ClassModel $class)
    {
        $user = Auth::user();
        $instructor = $user->instructure;
        
        // Check if instructor is assigned to this class
        if (!$class->instructures()->where('instructures.id', $instructor->id)->exists()) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to this class.');
        }
        
        return view('instructor.courses.add-material', compact('course', 'class'));
    }

    public function storeMaterial(Request $request, Course $course, ClassModel $class)
    {
        $user = Auth::user();
        $instructor = $user->instructure;
        
        // Check if instructor is assigned to this class
        if (!$class->instructures()->where('instructures.id', $instructor->id)->exists()) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to this class.');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'day' => 'required|integer|min:1|max:' . $class->duration_day,
            'upload_method' => 'required|in:file,google_drive',
            'file' => 'required_if:upload_method,file|file|max:10240',
            'google_drive_url' => 'required_if:upload_method,google_drive|url',
        ]);

        $materialData = [
            'course_schedule_id' => $class->id,
            'title' => $request->title,
            'description' => $request->description,
            'day' => $request->day,
            'is_google_drive' => $request->upload_method === 'google_drive',
        ];

        if ($request->upload_method === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');
            $materialData['file_url'] = asset('storage/' . $filePath);
            $materialData['size'] = $this->formatFileSize($file->getSize());
        } else {
            $materialData['file_url'] = $request->google_drive_url;
            $materialData['size'] = 'N/A';
        }

        \App\Models\CourseMaterial::create($materialData);

        return redirect()->route('instructor.courses.materials', $course)
            ->with('success', 'Material added successfully.');
    }

    public function removeMaterial(Course $course, ClassModel $class, \App\Models\CourseMaterial $material)
    {
        $user = Auth::user();
        $instructor = $user->instructure;
        
        // Check if instructor is assigned to this class
        if (!$class->instructures()->where('instructures.id', $instructor->id)->exists()) {
            return redirect()->route('instructor.courses.index')
                ->with('error', 'You are not assigned to this class.');
        }
        
        // Delete file if it's not a Google Drive link
        if (!$material->is_google_drive && $material->file_url) {
            $filePath = str_replace(asset('storage/'), '', $material->file_url);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
        }

        $material->delete();

        return redirect()->route('instructor.courses.materials', $course)
            ->with('success', 'Material removed successfully.');
    }

    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}

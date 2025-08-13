<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\ClassModel;
use App\Models\Instructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['courseType', 'classes']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('course_name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by course type
        if ($request->has('course_type_id') && !empty($request->course_type_id)) {
            $query->where('course_type_id', $request->course_type_id);
        }

        $courses = $query->latest()->paginate(10);
        $courseTypes = CourseType::all();

        return view('admin.courses.index', compact('courses', 'courseTypes'));
    }

    public function create()
    {
        $courseTypes = CourseType::all();
        return view('admin.courses.create', compact('courseTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'course_type_id' => 'required|exists:course_types,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $courseData = [
            'course_name' => $request->course_name,
            'description' => $request->description,
            'course_type_id' => $request->course_type_id,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $courseData['image'] = $imagePath;
        }

        Course::create($courseData);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['courseType', 'classes.instructures', 'classes.registrations']);
        
        // Statistics
        $stats = [
            'total_classes' => $course->classes->count(),
            'active_classes' => $course->classes->where('status', 'active')->count(),
            'total_students' => $course->getTotalStudentsAttribute(),
            'revenue' => $course->classes->sum(function ($class) {
                return $class->registrations->where('payment_status', 'paid')->sum('payment');
            }),
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    public function edit(Course $course)
    {
        $courseTypes = CourseType::all();
        return view('admin.courses.edit', compact('course', 'courseTypes'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'course_type_id' => 'required|exists:course_types,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $courseData = [
            'course_name' => $request->course_name,
            'description' => $request->description,
            'course_type_id' => $request->course_type_id,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('courses', $imageName, 'public');
            $courseData['image'] = $imagePath;
        }

        $course->update($courseData);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        // Check if course has active classes
        if ($course->classes()->where('status', 'active')->exists()) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Cannot delete course with active classes.');
        }

        // Delete course image if exists
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    // Course Classes Management
    public function classes(Course $course)
    {
        $classes = $course->classes()->with(['instructures', 'registrations'])->latest()->paginate(10);
        $instructures = Instructure::all();
        
        return view('admin.courses.classes', compact('course', 'classes', 'instructures'));
    }

    public function createClass(Course $course)
    {
        $instructures = Instructure::all();
        return view('admin.courses.create-class', compact('course', 'instructures'));
    }

    public function storeClass(Request $request, Course $course)
    {
        $request->validate([
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_reg_date' => 'required|date',
            'end_reg_date' => 'required|date|after:start_reg_date',
            'duration_day' => 'required|integer|min:1',
            'start_date' => 'required|date|after:end_reg_date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,completed',
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'exists:instructures,id',
        ]);

        $class = $course->classes()->create([
            'quota' => $request->quota,
            'price' => $request->price,
            'start_reg_date' => $request->start_reg_date,
            'end_reg_date' => $request->end_reg_date,
            'duration_day' => $request->duration_day,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'room' => $request->room,
            'status' => $request->status,
        ]);

        // Attach instructors
        $class->instructures()->attach($request->instructors);

        return redirect()->route('admin.courses.classes', $course)
            ->with('success', 'Class created successfully.');
    }

    public function updateClassStatus(Request $request, Course $course, ClassModel $class)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,completed',
        ]);

        $class->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Class status updated successfully.');
    }

    public function editClass(Course $course, ClassModel $class)
    {
        $instructures = Instructure::all();
        $selectedInstructors = $class->instructures->pluck('id')->toArray();
        
        return view('admin.courses.edit-class', compact('course', 'class', 'instructures', 'selectedInstructors'));
    }

    public function updateClass(Request $request, Course $course, ClassModel $class)
    {
        $request->validate([
            'quota' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_reg_date' => 'required|date',
            'end_reg_date' => 'required|date|after:start_reg_date',
            'duration_day' => 'required|integer|min:1',
            'start_date' => 'required|date|after:end_reg_date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'room' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,completed',
            'instructors' => 'required|array|min:1',
            'instructors.*' => 'exists:instructures,id',
        ]);

        $class->update([
            'quota' => $request->quota,
            'price' => $request->price,
            'start_reg_date' => $request->start_reg_date,
            'end_reg_date' => $request->end_reg_date,
            'duration_day' => $request->duration_day,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'room' => $request->room,
            'status' => $request->status,
        ]);

        // Sync instructors
        $class->instructures()->sync($request->instructors);

        return redirect()->route('admin.courses.classes', $course)
            ->with('success', 'Class updated successfully.');
    }

    public function showClass(Course $course, ClassModel $class)
    {
        $class->load(['instructures', 'registrations.participant.user']);
        
        return view('admin.courses.show-class', compact('course', 'class'));
    }

    public function addParticipant(Course $course, ClassModel $class)
    {
        $participants = \App\Models\Participant::with('user')->get();
        
        return view('admin.courses.add-participant', compact('course', 'class', 'participants'));
    }

    public function storeParticipant(Request $request, Course $course, ClassModel $class)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'payment_status' => 'required|in:paid,pending',
            'payment' => 'required|numeric|min:0',
            'reg_status' => 'required|in:approved,pending,rejected',
        ]);

        // Check if participant is already registered
        $existingRegistration = \App\Models\CourseRegistration::where([
            'class_id' => $class->id,
            'participant_id' => $request->participant_id
        ])->first();

        if ($existingRegistration) {
            return redirect()->back()->with('error', 'Participant is already registered for this class.');
        }

        // Check if class is full
        if ($class->isFull() && $request->reg_status === 'approved') {
            return redirect()->back()->with('error', 'Class is already full.');
        }

        // Create registration
        $registration = new \App\Models\CourseRegistration([
            'participant_id' => $request->participant_id,
            'class_id' => $class->id,
            'payment_status' => $request->payment_status,
            'payment' => $request->payment,
            'reg_status' => $request->reg_status,
            'reg_date' => now(),
        ]);

        $registration->save();

        return redirect()->route('admin.courses.classes', $course)
            ->with('success', 'Participant added successfully.');
    }
} 
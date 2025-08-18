<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Instructure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseScheduleController extends Controller
{
    /**
     * Display the course schedule list view.
     */
    public function index(Request $request)
    {
        $query = ClassModel::with(['course', 'instructures']);
        
        // Apply search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('course', function($q2) use ($search) {
                    $q2->where('course_name', 'like', '%' . $search . '%');
                })
                ->orWhere('location', 'like', '%' . $search . '%')
                ->orWhere('room', 'like', '%' . $search . '%');
            });
        }
        
        $classes = $query->latest()->paginate(10);
        
        return view('admin.course-schedule.index', compact('classes'));
    }
    
    /**
     * Get the background color for a class based on its status.
     */
    private function getStatusColor($status)
    {
        switch ($status) {
            case 'active':
                return '#4CAF50'; // Green
            case 'inactive':
                return '#FFC107'; // Yellow
            case 'completed':
                return '#9E9E9E'; // Gray
            default:
                return '#2196F3'; // Blue
        }
    }
    
    /**
     * Display the detailed view for a specific class.
     */
    public function show($courseId, $classId)
    {
        $class = ClassModel::with(['course', 'instructures', 'registrations.participant.user', 'registrations.payments'])
            ->where('course_id', $courseId)
            ->findOrFail($classId);
        
        return view('admin.course-schedule.show', compact('class'));
    }
    
    /**
     * Display the detailed view for a specific day.
     */
    public function dayView(Request $request, $date)
    {
        $selectedDate = Carbon::parse($date);
        
        // Get classes for the selected day
        $classes = ClassModel::with(['course', 'instructures'])
            ->where(function ($q) use ($selectedDate) {
                $q->whereDate('start_date', '<=', $selectedDate)
                  ->whereDate('end_date', '>=', $selectedDate);
            })
            ->get();
        
        return view('admin.course-schedule.day', compact('classes', 'selectedDate'));
    }
}

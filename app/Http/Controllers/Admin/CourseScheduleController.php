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
     * Display the course schedule calendar view.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $courseId = $request->input('course_id');
        $instructorId = $request->input('instructor_id');
        $location = $request->input('location');
        
        // Set date range for the selected month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Query classes
        $query = ClassModel::with(['course', 'instructures'])
            ->where(function ($q) use ($startDate, $endDate) {
                // Classes that start or end in the selected month
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  // Classes that span across the selected month
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<', $startDate)
                         ->where('end_date', '>', $endDate);
                  });
            });
        
        // Apply filters
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        if ($instructorId) {
            $query->whereHas('instructures', function ($q) use ($instructorId) {
                $q->where('instructure_id', $instructorId);
            });
        }
        
        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }
        
        // Get classes
        $classes = $query->get();
        
        // Format classes for calendar display
        $events = [];
        foreach ($classes as $class) {
            $events[] = [
                'id' => $class->id,
                'title' => $class->course->course_name,
                'start' => $class->start_date->format('Y-m-d'),
                'end' => $class->end_date->addDay()->format('Y-m-d'), // Add a day for inclusive display
                'location' => $class->location,
                'room' => $class->room,
                'instructors' => $class->instructures->pluck('full_name')->join(', '),
                'status' => $class->status,
                'url' => route('admin.courses.classes', $class->course_id),
                'backgroundColor' => $this->getStatusColor($class->status),
            ];
        }
        
        // Get filter options
        $courses = Course::orderBy('course_name')->get();
        $instructors = Instructure::orderBy('full_name')->get();
        $locations = ClassModel::select('location')->distinct()->orderBy('location')->pluck('location');
        
        // Get month navigation
        $currentDate = Carbon::createFromDate($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        
        return view('admin.course-schedule.index', compact(
            'events',
            'courses',
            'instructors',
            'locations',
            'month',
            'year',
            'courseId',
            'instructorId',
            'location',
            'currentDate',
            'prevMonth',
            'nextMonth'
        ));
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

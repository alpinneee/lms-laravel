<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\ClassModel;
use App\Models\Certificate;
use App\Models\CourseRegistration;
use App\Models\Attendance;
use App\Models\ValueReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $instructor = Auth::user();
        $instructure = $instructor->instructure;

        if (!$instructure) {
            return redirect()->route('login')->with('error', 'Instructor profile not found.');
        }

        // Get instructor's classes
        $instructorClasses = ClassModel::whereHas('instructures', function ($query) use ($instructure) {
            $query->where('instructure_id', $instructure->id);
        })->with(['course', 'registrations.participant.user'])->get();

        // Statistics for instructor
        $stats = [
            'total_classes' => $instructorClasses->count(),
            'total_students' => CourseRegistration::whereIn('class_id', $instructorClasses->pluck('id'))
                ->where('reg_status', 'approved')
                ->distinct('participant_id')
                ->count(),
            'certificates_issued' => Certificate::where('instructure_id', $instructure->id)->count(),
            'pending_assessments' => ValueReport::where('instructure_id', $instructure->id)
                ->whereNull('value')
                ->count(),
        ];

        // Recent classes
        $recentClasses = $instructorClasses->sortByDesc('start_date')->take(5);

        // Upcoming classes
        $upcomingClasses = $instructorClasses->where('start_date', '>', now())->sortBy('start_date')->take(5);

        // Student progress for current classes
        $currentClasses = $instructorClasses->where('start_date', '<=', now())
            ->where('end_date', '>=', now());

        $studentProgress = [];
        foreach ($currentClasses as $class) {
            $registrations = $class->registrations->where('reg_status', 'approved');
            foreach ($registrations as $registration) {
                $attendanceCount = Attendance::where('registration_id', $registration->id)
                    ->where('status', 'present')
                    ->count();
                
                $totalSessions = $class->duration_day; // Assuming each day is a session
                $attendancePercentage = $totalSessions > 0 ? ($attendanceCount / $totalSessions) * 100 : 0;

                $studentProgress[] = [
                    'student_name' => $registration->participant->user->name,
                    'course_name' => $class->course->course_name,
                    'attendance_percentage' => round($attendancePercentage, 1),
                    'registration_id' => $registration->id,
                    'class_id' => $class->id
                ];
            }
        }

        // Certificate requests (assessments pending)
        $certificateRequests = CourseRegistration::whereIn('class_id', $instructorClasses->pluck('id'))
            ->where('reg_status', 'approved')
            ->whereDoesntHave('valueReports', function ($query) use ($instructure) {
                $query->where('instructure_id', $instructure->id);
            })
            ->with(['participant.user', 'class.course'])
            ->get();

        // Recent activities
        $recentActivities = collect([
            // Recent Assessments
            ValueReport::where('instructure_id', $instructure->id)
                ->with(['registration.participant.user', 'registration.class.course'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($report) {
                    return [
                        'type' => 'assessment',
                        'message' => 'Assessed ' . $report->registration->participant->user->name . ' for ' . $report->registration->class->course->course_name . ' - Score: ' . $report->value,
                        'time' => $report->created_at,
                        'icon' => 'clipboard-check',
                        'color' => 'green'
                    ];
                }),
            
            // Recent Certificates
            Certificate::where('instructure_id', $instructure->id)
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($certificate) {
                    return [
                        'type' => 'certificate',
                        'message' => 'Issued certificate ' . $certificate->certificate_number . ' to ' . $certificate->name,
                        'time' => $certificate->created_at,
                        'icon' => 'award',
                        'color' => 'blue'
                    ];
                })
        ])
        ->flatten(1)
        ->sortByDesc('time')
        ->take(10)
        ->values();

        return view('instructor.dashboard', compact(
            'stats',
            'recentClasses',
            'upcomingClasses',
            'studentProgress',
            'certificateRequests',
            'recentActivities',
            'instructorClasses'
        ));
    }
}

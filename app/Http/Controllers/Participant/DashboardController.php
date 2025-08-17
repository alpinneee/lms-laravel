<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $participant = $user->participant;

        // Buat profil participant jika belum ada
        if (!$participant && ($user->isParticipant() || $user->isUnassigned())) {
            $participant = \App\Models\Participant::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
            ]);
            // Refresh user relationship
            $user->load('participant');
            $participant = $user->participant;
        }

        if (!$participant) {
            return redirect()->route('login')->with('error', 'Participant profile not found.');
        }

        // Get participant's registrations
        $registrations = CourseRegistration::where('participant_id', $participant->id)
            ->with(['class.course', 'payments'])
            ->get();

        // Statistics for participant
        $stats = [
            'enrolled_courses' => $registrations->where('reg_status', 'approved')->count(),
            'completed_courses' => $registrations->where('reg_status', 'approved')
                ->filter(function ($registration) {
                    return $registration->class->end_date < now();
                })->count(),
            'certificates_earned' => Certificate::where('participant_id', $participant->id)->count(),
            'pending_payments' => $registrations->filter(function ($registration) {
                return $registration->payment_status !== 'paid';
            })->count(),
        ];

        // Current courses (ongoing)
        $currentCourses = $registrations->filter(function ($registration) {
            return $registration->reg_status === 'approved' && 
                   $registration->class->start_date <= now() && 
                   $registration->class->end_date >= now();
        });

        // Upcoming courses
        $upcomingCourses = $registrations->filter(function ($registration) {
            return $registration->reg_status === 'approved' && 
                   $registration->class->start_date > now();
        });

        // Completed courses
        $completedCourses = $registrations->filter(function ($registration) {
            return $registration->reg_status === 'approved' && 
                   $registration->class->end_date < now();
        });

        // Available courses for enrollment
        $availableCourses = ClassModel::where('status', 'active')
            ->where('end_reg_date', '>=', now())
            ->whereDoesntHave('registrations', function ($query) use ($participant) {
                $query->where('participant_id', $participant->id);
            })
            ->with(['course', 'registrations'])
            ->limit(6)
            ->get();

        // Payment history
        $paymentHistory = Payment::whereHas('registration', function($query) use ($participant) {
            $query->where('participant_id', $participant->id);
        })
        ->with(['registration.class.course'])
        ->latest()
        ->limit(5)
        ->get();

        // My certificates
        $myCertificates = Certificate::where('participant_id', $participant->id)
            ->latest()
            ->limit(3)
            ->get();

        // Learning progress
        $learningProgress = [];
        foreach ($currentCourses as $registration) {
            $totalDays = $registration->class->duration_day;
            $daysPassed = now()->diffInDays($registration->class->start_date);
            $daysPassed = min($daysPassed, $totalDays);
            $progressPercentage = $totalDays > 0 ? ($daysPassed / $totalDays) * 100 : 0;

            $learningProgress[] = [
                'course_name' => $registration->class->course->course_name,
                'progress_percentage' => round($progressPercentage, 1),
                'start_date' => $registration->class->start_date,
                'end_date' => $registration->class->end_date,
                'location' => $registration->class->location,
                'room' => $registration->class->room,
                'registration_id' => $registration->id
            ];
        }

        // Recent activities
        $recentActivities = collect([
            // Recent Registrations
            $registrations->take(3)->map(function ($registration) {
                return [
                    'type' => 'registration',
                    'message' => 'Enrolled in ' . $registration->class->course->course_name,
                    'time' => $registration->created_at,
                    'icon' => 'book-open',
                    'color' => 'blue'
                ];
            }),
            
            // Recent Payments
            $paymentHistory->take(3)->map(function ($payment) {
                $courseName = $payment->registration?->class?->course?->course_name ?? 'Unknown Course';
                return [
                    'type' => 'payment',
                    'message' => 'Payment of $' . number_format($payment->amount, 2) . ' for ' . $courseName,
                    'time' => $payment->created_at,
                    'icon' => 'credit-card',
                    'color' => 'green'
                ];
            }),

            // Recent Certificates
            $myCertificates->map(function ($certificate) {
                return [
                    'type' => 'certificate',
                    'message' => 'Received certificate: ' . $certificate->certificate_number,
                    'time' => $certificate->created_at,
                    'icon' => 'award',
                    'color' => 'yellow'
                ];
            })
        ])
        ->flatten(1)
        ->sortByDesc('time')
        ->take(8)
        ->values();

        return view('participant.dashboard', compact(
            'stats',
            'currentCourses',
            'upcomingCourses',
            'completedCourses',
            'availableCourses',
            'paymentHistory',
            'myCertificates',
            'learningProgress',
            'recentActivities'
        ));
    }
}

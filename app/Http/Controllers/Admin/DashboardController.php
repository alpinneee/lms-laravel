<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\CourseRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_certificates' => Certificate::count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
            'active_registrations' => CourseRegistration::where('reg_status', 'approved')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        // Monthly Registration Chart Data (last 6 months)
        $monthlyRegistrations = CourseRegistration::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Revenue Chart Data (last 6 months)
        $monthlyRevenue = Payment::select(
            DB::raw('MONTH(payment_date) as month'),
            DB::raw('YEAR(payment_date) as year'),
            DB::raw('SUM(amount) as total')
        )
        ->where('status', 'verified')
        ->where('payment_date', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Course Popularity (top 5)
        $popularCourses = Course::select(
                'courses.id',
                'courses.course_name',
                'courses.course_type_id',
                'courses.description',
                'courses.image',
                'courses.created_at',
                'courses.updated_at',
                DB::raw('COUNT(course_registrations.id) as registrations_count')
            )
            ->leftJoin('classes', 'courses.id', '=', 'classes.course_id')
            ->leftJoin('course_registrations', 'classes.id', '=', 'course_registrations.class_id')
            ->groupBy(
                'courses.id',
                'courses.course_name',
                'courses.course_type_id',
                'courses.description',
                'courses.image',
                'courses.created_at',
                'courses.updated_at'
            )
            ->orderByDesc('registrations_count')
            ->limit(5)
            ->get();

        // Recent Activities (last 10)
        $recentActivities = collect([
            // Recent Registrations
            CourseRegistration::with(['participant.user', 'class.course'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($registration) {
                    return [
                        'type' => 'registration',
                        'message' => $registration->participant->user->name . ' registered for ' . $registration->class->course->course_name,
                        'time' => $registration->created_at,
                        'icon' => 'user-plus',
                        'color' => 'blue'
                    ];
                }),
            
            // Recent Payments
            Payment::with(['registration.participant.user'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($payment) {
                    return [
                        'type' => 'payment',
                        'message' => 'Payment of $' . number_format($payment->amount, 2) . ' from ' . $payment->registration->participant->user->name,
                        'time' => $payment->created_at,
                        'icon' => 'credit-card',
                        'color' => 'green'
                    ];
                })
        ])
        ->flatten(1)
        ->sortByDesc('time')
        ->take(10)
        ->values();

        // User Type Distribution
        $userDistribution = User::select('user_types.usertype', DB::raw('COUNT(*) as count'))
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->groupBy('user_types.usertype')
            ->get();

        // Payment Status Overview
        $paymentOverview = Payment::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('status')
            ->get();
            
        // Expiring Certificates (next 30 days)
        $expiringCertificates = Certificate::where('expiry_date', '>=', Carbon::now())
            ->where('expiry_date', '<=', Carbon::now()->addDays(30))
            ->where('status', 'valid')
            ->with(['participant', 'course'])
            ->orderBy('expiry_date', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRegistrations',
            'monthlyRevenue',
            'popularCourses',
            'recentActivities',
            'userDistribution',
            'paymentOverview',
            'expiringCertificates'
        ));
    }
}

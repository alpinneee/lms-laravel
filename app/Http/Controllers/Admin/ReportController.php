<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Participant;
use App\Models\Instructure;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display certificate expiration report.
     */
    public function certificateExpired(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status', 'expired');
        $courseId = $request->input('course_id');
        $participantId = $request->input('participant_id');
        $instructorId = $request->input('instructor_id');
        $dateRange = $request->input('date_range');
        $expiryDays = (int) $request->input('expiry_days', 30); // Konversi ke integer
        
        // Query certificates
        $query = Certificate::with(['participant.user', 'course', 'instructure']);
        
        // Apply filters
        if ($status === 'expired') {
            $query->expired();
        } elseif ($status === 'expiring_soon') {
            $query->expiringSoon($expiryDays);
        } elseif ($status === 'all_expiring') {
            $query->where(function($q) use ($expiryDays) {
                $q->expired()->orWhere(function($q2) use ($expiryDays) {
                    $q2->expiringSoon($expiryDays);
                });
            });
        }
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        if ($participantId) {
            $query->where('participant_id', $participantId);
        }
        
        if ($instructorId) {
            $query->where('instructure_id', $instructorId);
        }
        
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
                $query->whereBetween('expiry_date', [$startDate, $endDate]);
            }
        }
        
        // Get certificates
        $certificates = $query->latest()->paginate(10);
        
        // Get filter options
        $courses = Course::orderBy('course_name')->get();
        $participants = Participant::with('user')->get();
        $instructors = Instructure::orderBy('full_name')->get();
        
        // Get statistics
        $stats = [
            'total_expired' => Certificate::expired()->count(),
            'expiring_30_days' => Certificate::expiringSoon(30)->count(),
            'expiring_60_days' => Certificate::expiringSoon(60)->count(),
            'expiring_90_days' => Certificate::expiringSoon(90)->count(),
        ];
        
        return view('admin.reports.certificate-expired', compact(
            'certificates',
            'courses',
            'participants',
            'instructors',
            'stats',
            'status',
            'courseId',
            'participantId',
            'instructorId',
            'dateRange',
            'expiryDays'
        ));
    }

    public function bankAccounts()
    {
        $bankAccounts = \App\Models\BankAccount::latest()->get();
        return view('admin.reports.bank-accounts', compact('bankAccounts'));
    }

    public function storeBankAccount(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255'
        ]);

        \App\Models\BankAccount::create([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Bank account added successfully.');
    }

    public function toggleBankAccount($id)
    {
        $bank = \App\Models\BankAccount::findOrFail($id);
        $bank->update(['is_active' => !$bank->is_active]);
        return redirect()->back()->with('success', 'Bank account status updated.');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,verified,rejected']);
        
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => $request->status]);
        
        // Update registration status if payment is verified
        if ($request->status === 'verified') {
            $payment->registration->update([
                'payment_status' => 'paid',
                'reg_status' => 'approved'
            ]);
        }
        
        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }

    /**
     * Display payment report.
     */
    public function paymentReport(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status');
        $courseId = $request->input('course_id');
        $participantId = $request->input('participant_id');
        $paymentMethod = $request->input('payment_method');
        $dateRange = $request->input('date_range');
        
        // Query payments
        $query = Payment::with(['registration.participant.user', 'registration.class.course', 'bankAccount']);
        
        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        if ($participantId) {
            $query->where('participant_id', $participantId);
        }
        
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        
        if ($dateRange) {
            $dates = explode(' - ', $dateRange);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay();
                $query->whereBetween('payment_date', [$startDate, $endDate]);
            }
        }
        
        // Get payments
        $payments = $query->latest()->paginate(10);
        
        // Get filter options
        $courses = Course::orderBy('course_name')->get();
        $participants = Participant::with('user')->get();
        
        // Get payment methods from existing data
        $paymentMethods = Payment::select('payment_method')
            ->distinct()
            ->whereNotNull('payment_method')
            ->pluck('payment_method');
        
        // Get statistics
        $stats = [
            'total_payments' => Payment::count(),
            'total_amount' => Payment::sum('amount'),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'completed_payments' => Payment::where('status', 'completed')->count(),
            'monthly_revenue' => $this->getMonthlyRevenue(),
        ];
        
        return view('admin.reports.payment-report', compact(
            'payments',
            'courses',
            'participants',
            'paymentMethods',
            'stats',
            'status',
            'courseId',
            'participantId',
            'paymentMethod',
            'dateRange'
        ));
    }

    /**
     * Get monthly revenue data for the chart.
     */
    private function getMonthlyRevenue()
    {
        $currentYear = Carbon::now()->year;
        
        $monthlyRevenue = Payment::select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->whereYear('payment_date', $currentYear)
            ->groupBy(DB::raw('MONTH(payment_date)'))
            ->orderBy(DB::raw('MONTH(payment_date)'))
            ->get();
        
        // Initialize array with all months
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[$i] = 0;
        }
        
        // Fill in actual data
        foreach ($monthlyRevenue as $record) {
            $revenueData[$record->month] = $record->total;
        }
        
        return $revenueData;
    }
}
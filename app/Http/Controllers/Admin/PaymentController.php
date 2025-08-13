<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\CourseRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['registration.participant', 'registration.class.course']);

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        // Search by reference number or participant name
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('reference_number', 'like', "%{$searchTerm}%")
                  ->orWhereHas('registration.participant', function ($q2) use ($searchTerm) {
                      $q2->where('full_name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(15);

        // Get statistics
        $stats = [
            'total_payments' => Payment::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'verified_payments' => Payment::where('status', 'verified')->count(),
            'rejected_payments' => Payment::where('status', 'rejected')->count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
        ];

        // Get payment methods for filter
        $paymentMethods = Payment::select('payment_method')
            ->distinct()
            ->pluck('payment_method');

        return view('admin.payments.index', compact('payments', 'stats', 'paymentMethods'));
    }

    /**
     * Display the specified payment details.
     */
    public function show(Payment $payment)
    {
        $payment->load(['registration.participant', 'registration.class.course']);
        
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Verify a payment.
     */
    public function verify(Payment $payment)
    {
        $payment->status = 'verified';
        $payment->save();

        // Update registration payment status
        $registration = $payment->registration;
        $totalPaid = $registration->payments()->where('status', 'verified')->sum('amount');
        
        if ($totalPaid >= $registration->payment) {
            $registration->payment_status = 'paid';
            $registration->save();
        } else {
            $registration->payment_status = 'partial';
            $registration->save();
        }

        // TODO: Send email notification to participant

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment verified successfully.');
    }

    /**
     * Reject a payment.
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->status = 'rejected';
        $payment->notes = $request->rejection_reason;
        $payment->save();

        // Update registration payment status if needed
        $registration = $payment->registration;
        $totalPaid = $registration->payments()->where('status', 'verified')->sum('amount');
        
        if ($totalPaid <= 0) {
            $registration->payment_status = 'unpaid';
        } else if ($totalPaid < $registration->payment) {
            $registration->payment_status = 'partial';
        }
        $registration->save();

        // TODO: Send email notification to participant

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment rejected successfully.');
    }

    /**
     * Download the payment proof.
     */
    public function downloadProof(Payment $payment)
    {
        if (!$payment->payment_proof || !Storage::disk('public')->exists($payment->payment_proof)) {
            return redirect()->back()->with('error', 'Payment proof not found.');
        }

        return Storage::disk('public')->download($payment->payment_proof);
    }

    /**
     * Generate payment report.
     */
    public function report(Request $request)
    {
        $query = Payment::with(['registration.participant', 'registration.class.course']);

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('from_date') && !empty($request->from_date)) {
            $query->whereDate('payment_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && !empty($request->to_date)) {
            $query->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $query->latest()->get();

        // Summary statistics
        $summary = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->where('status', 'verified')->sum('amount'),
            'payment_methods' => $payments->groupBy('payment_method')
                ->map(function ($items, $method) {
                    return [
                        'count' => $items->count(),
                        'amount' => $items->where('status', 'verified')->sum('amount')
                    ];
                }),
            'status_breakdown' => $payments->groupBy('status')
                ->map(function ($items) {
                    return $items->count();
                }),
        ];

        // Group by course
        $courseBreakdown = $payments->where('status', 'verified')
            ->groupBy('registration.class.course.course_name')
            ->map(function ($items, $course) {
                return [
                    'count' => $items->count(),
                    'amount' => $items->sum('amount')
                ];
            });

        // Monthly trend
        $monthlyTrend = $payments->where('status', 'verified')
            ->groupBy(function ($item) {
                return $item->payment_date->format('Y-m');
            })
            ->map(function ($items, $month) {
                return [
                    'count' => $items->count(),
                    'amount' => $items->sum('amount')
                ];
            });

        return view('admin.payments.report', compact('payments', 'summary', 'courseBreakdown', 'monthlyTrend'));
    }
} 
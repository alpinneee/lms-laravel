<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\CourseRegistration;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index()
    {
        $participant = auth()->user()->participant;
        
        if (!$participant) {
            return redirect()->route('participant.dashboard')
                ->with('error', 'Participant profile not found.');
        }

        $payments = Payment::whereHas('registration', function ($query) use ($participant) {
                $query->where('participant_id', $participant->id);
            })
            ->with(['registration.class.course', 'bankAccount'])
            ->latest()
            ->get();

        return view('participant.payment.index', compact('payments'));
    }

    /**
     * Show the form for uploading a payment proof.
     */
    public function showUploadForm($registrationId)
    {
        $registration = CourseRegistration::where('id', $registrationId)
            ->whereHas('participant', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['class.course', 'payments'])
            ->firstOrFail();

        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('participant.payment.upload', compact('registration', 'bankAccounts'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:course_registrations,id',
            'payment_proof' => 'required|image|max:2048',
            'bank_account_id' => 'required|exists:bank_accounts,id'
        ]);
        
        $registration = CourseRegistration::findOrFail($request->registration_id);
        
        // Store payment proof
        $path = $request->file('payment_proof')->store('payments', 'public');
        
        // Create payment record
        Payment::create([
            'registration_id' => $registration->id,
            'bank_account_id' => $request->bank_account_id,
            'payment_proof' => $path,
            'amount' => $registration->payment,
            'payment_date' => now(),
            'status' => 'pending'
        ]);
        
        // Update registration status to pending verification
        $registration->update([
            'payment_status' => 'pending_verification',
            'reg_status' => 'pending_verification'
        ]);
        
        return redirect()->route('participant.courses.index')
            ->with('success', 'Payment proof uploaded successfully! Waiting for admin verification.');
    }

    /**
     * Store a newly created payment proof in storage.
     */
    public function store(Request $request, $registrationId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255|unique:payments',
            'bank_account_id' => 'required|exists:bank_accounts,id',
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB max
        ]);

        $registration = CourseRegistration::where('id', $registrationId)
            ->whereHas('participant', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        // Handle payment proof upload
        $file = $request->file('payment_proof');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $filename, 'public');

        // Create payment record
        $payment = Payment::create([
            'registration_id' => $registration->id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'status' => 'pending',
            'payment_proof' => $path,
        ]);

        // Update registration payment status if needed
        if ($registration->payment_status !== 'paid') {
            $registration->payment_status = 'pending';
            $registration->save();
        }

        return redirect()->route('participant.payment.index')
            ->with('success', 'Payment proof uploaded successfully. It will be verified by our team.');
    }

    /**
     * Display the specified payment details.
     */
    public function show($paymentId)
    {
        $payment = Payment::whereHas('registration.participant', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['registration.class.course'])
            ->findOrFail($paymentId);

        return view('participant.payment.show', compact('payment'));
    }

    /**
     * Show the payment history for the participant.
     */
    public function history()
    {
        $participant = auth()->user()->participant;
        
        if (!$participant) {
            return redirect()->route('participant.dashboard')
                ->with('error', 'Participant profile not found.');
        }

        $payments = Payment::whereHas('registration', function ($query) use ($participant) {
                $query->where('participant_id', $participant->id);
            })
            ->with(['registration.class.course'])
            ->latest()
            ->get();

        $stats = [
            'total_payments' => $payments->count(),
            'verified_payments' => $payments->where('status', 'verified')->count(),
            'pending_payments' => $payments->where('status', 'pending')->count(),
            'rejected_payments' => $payments->where('status', 'rejected')->count(),
            'total_amount' => $payments->where('status', 'verified')->sum('amount'),
        ];

        return view('participant.payment.history', compact('payments', 'stats'));
    }

    /**
     * Download the payment proof.
     */
    public function downloadProof($paymentId)
    {
        $payment = Payment::whereHas('registration.participant', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->findOrFail($paymentId);

        if (!$payment->payment_proof || !Storage::disk('public')->exists($payment->payment_proof)) {
            return redirect()->back()->with('error', 'Payment proof not found.');
        }

        return Storage::disk('public')->download($payment->payment_proof);
    }
} 
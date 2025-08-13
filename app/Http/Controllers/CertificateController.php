<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CourseRegistration;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Generate a certificate for a course registration.
     */
    public function generate(CourseRegistration $registration)
    {
        // Check if registration is eligible for certification
        if ($registration->reg_status !== 'approved') {
            return redirect()->back()->with('error', 'Registration is not approved.');
        }

        if ($registration->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Payment is not complete.');
        }

        // Check if participant has completed the course
        $class = $registration->class;
        $participant = $registration->participant;
        $course = $class->course;
        $instructure = $class->instructures->first(); // Get the first instructor

        if (!$instructure) {
            return redirect()->back()->with('error', 'No instructor assigned to this class.');
        }

        // Check if certificate already exists
        $existingCertificate = Certificate::where('participant_id', $participant->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingCertificate) {
            return redirect()->back()->with('error', 'Certificate already exists for this course.');
        }

        // Get system settings for certificate validity
        $validityYears = SystemSetting::where('setting_key', 'certificate_validity_years')->first();
        $validityYears = $validityYears ? (int) $validityYears->value : 2; // Default to 2 years

        // Generate certificate data
        $certificateNumber = Certificate::generateCertificateNumber();
        $issueDate = Carbon::now();
        $expiryDate = Carbon::now()->addYears($validityYears);

        // Create certificate record
        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'name' => $participant->full_name,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'status' => 'valid',
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'instructure_id' => $instructure->id,
        ]);

        // Generate PDF certificate
        $pdf = $this->generatePDF($certificate);

        // Save PDF to storage
        $filename = 'certificate_' . $certificateNumber . '.pdf';
        $path = 'certificates/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Update certificate with PDF URL
        $certificate->pdf_url = $path;
        $certificate->save();

        // Create certification record
        $certification = $registration->certifications()->create([
            'certificate_number' => $certificateNumber,
            'issue_date' => $issueDate,
            'valid_date' => $expiryDate,
            'file_pdf' => $path,
        ]);

        return redirect()->back()->with('success', 'Certificate generated successfully.');
    }

    /**
     * Generate PDF certificate.
     */
    private function generatePDF(Certificate $certificate)
    {
        $participant = $certificate->participant;
        $course = $certificate->course;
        $instructure = $certificate->instructure;

        $data = [
            'certificate' => $certificate,
            'participant' => $participant,
            'course' => $course,
            'instructure' => $instructure,
        ];

        $pdf = PDF::loadView('certificates.template', $data);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf;
    }

    /**
     * Download a certificate.
     */
    public function download(Certificate $certificate)
    {
        // Check if user is authorized to download this certificate
        if (auth()->user()->isParticipant()) {
            $participant = auth()->user()->participant;
            if (!$participant || $certificate->participant_id !== $participant->id) {
                return redirect()->back()->with('error', 'You are not authorized to download this certificate.');
            }
        }

        if (!$certificate->pdf_url || !Storage::disk('public')->exists($certificate->pdf_url)) {
            return redirect()->back()->with('error', 'Certificate PDF not found.');
        }

        return Storage::disk('public')->download(
            $certificate->pdf_url, 
            'Certificate_' . $certificate->certificate_number . '.pdf'
        );
    }

    /**
     * Verify a certificate by certificate number.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'certificate_number' => 'required|string',
        ]);

        $certificate = Certificate::where('certificate_number', $request->certificate_number)->first();

        if (!$certificate) {
            return view('certificates.verify', [
                'certificate' => null,
                'status' => 'not_found',
                'message' => 'Certificate not found.',
            ]);
        }

        $status = $certificate->isValid() ? 'valid' : 'expired';
        $message = $certificate->isValid() 
            ? 'Certificate is valid.' 
            : 'Certificate has expired on ' . $certificate->expiry_date->format('d M Y') . '.';

        return view('certificates.verify', [
            'certificate' => $certificate,
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Display a list of certificates for the authenticated user.
     */
    public function index()
    {
        if (auth()->user()->isParticipant()) {
            $participant = auth()->user()->participant;
            
            if (!$participant) {
                return redirect()->route('participant.dashboard')
                    ->with('error', 'Participant profile not found.');
            }

            $certificates = Certificate::where('participant_id', $participant->id)
                ->with(['course', 'instructure'])
                ->latest()
                ->get();

            return view('participant.certificates.index', compact('certificates'));
        } 
        
        if (auth()->user()->isInstructor()) {
            $instructure = auth()->user()->instructure;
            
            if (!$instructure) {
                return redirect()->route('instructor.dashboard')
                    ->with('error', 'Instructor profile not found.');
            }

            $certificates = Certificate::where('instructure_id', $instructure->id)
                ->with(['participant', 'course'])
                ->latest()
                ->get();

            return view('instructor.certificates.index', compact('certificates'));
        }

        // Admin view
        $certificates = Certificate::with(['participant', 'course', 'instructure'])
            ->latest()
            ->paginate(15);

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create()
    {
        // Only instructors and admins can create certificates
        if (auth()->user()->isParticipant()) {
            return redirect()->back()->with('error', 'You are not authorized to create certificates.');
        }

        if (auth()->user()->isInstructor()) {
            $instructure = auth()->user()->instructure;
            
            if (!$instructure) {
                return redirect()->route('instructor.dashboard')
                    ->with('error', 'Instructor profile not found.');
            }

            // Get classes taught by this instructor with approved registrations
            $classes = $instructure->classes()
                ->with(['course', 'registrations.participant'])
                ->whereHas('registrations', function ($query) {
                    $query->where('reg_status', 'approved')
                        ->where('payment_status', 'paid');
                })
                ->get();

            return view('instructor.certificates.create', compact('classes'));
        }

        // Admin view - can create certificates for any course/participant
        $courses = Course::all();
        $participants = Participant::all();
        $instructures = Instructure::all();

        return view('admin.certificates.create', compact('courses', 'participants', 'instructures'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'course_id' => 'required|exists:courses,id',
            'instructure_id' => 'required|exists:instructures,id',
            'issue_date' => 'required|date|before_or_equal:today',
            'validity_years' => 'required|integer|min:1|max:10',
        ]);

        // Check if certificate already exists
        $existingCertificate = Certificate::where('participant_id', $request->participant_id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingCertificate) {
            return redirect()->back()->with('error', 'Certificate already exists for this course and participant.');
        }

        $participant = Participant::findOrFail($request->participant_id);
        $course = Course::findOrFail($request->course_id);
        $instructure = Instructure::findOrFail($request->instructure_id);

        // Generate certificate data
        $certificateNumber = Certificate::generateCertificateNumber();
        $issueDate = Carbon::parse($request->issue_date);
        $expiryDate = $issueDate->copy()->addYears($request->validity_years);

        // Create certificate record
        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'name' => $participant->full_name,
            'issue_date' => $issueDate,
            'expiry_date' => $expiryDate,
            'status' => 'valid',
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'instructure_id' => $instructure->id,
        ]);

        // Generate PDF certificate
        $pdf = $this->generatePDF($certificate);

        // Save PDF to storage
        $filename = 'certificate_' . $certificateNumber . '.pdf';
        $path = 'certificates/' . $filename;
        Storage::disk('public')->put($path, $pdf->output());

        // Update certificate with PDF URL
        $certificate->pdf_url = $path;
        $certificate->save();

        // Find registration if exists
        $registration = CourseRegistration::whereHas('class', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })
            ->where('participant_id', $participant->id)
            ->first();

        // Create certification record if registration exists
        if ($registration) {
            $certification = $registration->certifications()->create([
                'certificate_number' => $certificateNumber,
                'issue_date' => $issueDate,
                'valid_date' => $expiryDate,
                'file_pdf' => $path,
            ]);
        }

        if (auth()->user()->isInstructor()) {
            return redirect()->route('instructor.certificates.index')
                ->with('success', 'Certificate generated successfully.');
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate generated successfully.');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        // Check if user is authorized to view this certificate
        if (auth()->user()->isParticipant()) {
            $participant = auth()->user()->participant;
            if (!$participant || $certificate->participant_id !== $participant->id) {
                return redirect()->back()->with('error', 'You are not authorized to view this certificate.');
            }
        }

        if (auth()->user()->isInstructor()) {
            $instructure = auth()->user()->instructure;
            if (!$instructure || $certificate->instructure_id !== $instructure->id) {
                return redirect()->back()->with('error', 'You are not authorized to view this certificate.');
            }
        }

        $certificate->load(['participant', 'course', 'instructure']);

        if (auth()->user()->isAdmin()) {
            return view('admin.certificates.show', compact('certificate'));
        } elseif (auth()->user()->isInstructor()) {
            return view('instructor.certificates.show', compact('certificate'));
        } else {
            return view('participant.certificates.show', compact('certificate'));
        }
    }

    /**
     * Revoke a certificate.
     */
    public function revoke(Certificate $certificate)
    {
        // Only admins can revoke certificates
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You are not authorized to revoke certificates.');
        }

        $certificate->status = 'revoked';
        $certificate->save();

        return redirect()->back()->with('success', 'Certificate revoked successfully.');
    }

    /**
     * Renew an expired certificate.
     */
    public function renew(Certificate $certificate)
    {
        // Only admins can renew certificates
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You are not authorized to renew certificates.');
        }

        // Get system settings for certificate validity
        $validityYears = SystemSetting::where('setting_key', 'certificate_validity_years')->first();
        $validityYears = $validityYears ? (int) $validityYears->value : 2; // Default to 2 years

        $certificate->issue_date = Carbon::now();
        $certificate->expiry_date = Carbon::now()->addYears($validityYears);
        $certificate->status = 'valid';
        $certificate->save();

        return redirect()->back()->with('success', 'Certificate renewed successfully.');
    }
} 
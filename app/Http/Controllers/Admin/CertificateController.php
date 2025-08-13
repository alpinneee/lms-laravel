<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Participant;
use App\Models\Instructure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Display a listing of the certificates.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $status = $request->input('status');
        $courseId = $request->input('course_id');
        $participantId = $request->input('participant_id');
        $instructorId = $request->input('instructor_id');
        $dateRange = $request->input('date_range');
        
        // Query certificates
        $query = Certificate::with(['participant.user', 'course', 'instructure']);
        
        // Apply filters
        if ($status) {
            if ($status === 'valid') {
                $query->valid();
            } elseif ($status === 'expired') {
                $query->expired();
            } elseif ($status === 'expiring_soon') {
                $query->expiringSoon();
            }
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
                $query->whereBetween('issue_date', [$startDate, $endDate]);
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
            'total' => Certificate::count(),
            'valid' => Certificate::valid()->count(),
            'expired' => Certificate::expired()->count(),
            'expiring_soon' => Certificate::expiringSoon()->count(),
        ];
        
        return view('admin.certificates.index', compact(
            'certificates',
            'courses',
            'participants',
            'instructors',
            'stats',
            'status',
            'courseId',
            'participantId',
            'instructorId',
            'dateRange'
        ));
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['participant.user', 'course', 'instructure']);
        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create()
    {
        $courses = Course::orderBy('course_name')->get();
        $participants = Participant::with('user')->get();
        $instructors = Instructure::orderBy('full_name')->get();
        
        return view('admin.certificates.create', compact('courses', 'participants', 'instructors'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'participant_id' => 'required|exists:participants,id',
            'instructure_id' => 'required|exists:instructures,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        // Generate certificate number
        $certificateNumber = Certificate::generateCertificateNumber();
        
        // Handle certificate file upload
        $pdfUrl = null;
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $filename = $certificateNumber . '.' . $file->getClientOriginalExtension();
            $pdfUrl = $file->storeAs('certificates', $filename, 'public');
        }
        
        // Create certificate
        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'name' => $request->name,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'status' => 'valid',
            'participant_id' => $request->participant_id,
            'course_id' => $request->course_id,
            'instructure_id' => $request->instructure_id,
            'pdf_url' => $pdfUrl,
            'drive_link' => $request->drive_link,
        ]);
        
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificate created successfully.');
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Certificate $certificate)
    {
        $certificate->load(['participant.user', 'course', 'instructure']);
        $courses = Course::orderBy('course_name')->get();
        $participants = Participant::with('user')->get();
        $instructors = Instructure::orderBy('full_name')->get();
        
        return view('admin.certificates.edit', compact('certificate', 'courses', 'participants', 'instructors'));
    }

    /**
     * Update the specified certificate in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'participant_id' => 'required|exists:participants,id',
            'instructure_id' => 'required|exists:instructures,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
            'status' => 'required|in:valid,revoked',
            'certificate_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        // Handle certificate file upload
        if ($request->hasFile('certificate_file')) {
            // Delete old file if exists
            if ($certificate->pdf_url) {
                Storage::disk('public')->delete($certificate->pdf_url);
            }
            
            $file = $request->file('certificate_file');
            $filename = $certificate->certificate_number . '.' . $file->getClientOriginalExtension();
            $pdfUrl = $file->storeAs('certificates', $filename, 'public');
            $certificate->pdf_url = $pdfUrl;
        }
        
        // Update certificate
        $certificate->name = $request->name;
        $certificate->course_id = $request->course_id;
        $certificate->participant_id = $request->participant_id;
        $certificate->instructure_id = $request->instructure_id;
        $certificate->issue_date = $request->issue_date;
        $certificate->expiry_date = $request->expiry_date;
        $certificate->status = $request->status;
        $certificate->drive_link = $request->drive_link;
        $certificate->save();
        
        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificate updated successfully.');
    }

    /**
     * Remove the specified certificate from storage.
     */
    public function destroy(Certificate $certificate)
    {
        // Delete certificate file if exists
        if ($certificate->pdf_url) {
            Storage::disk('public')->delete($certificate->pdf_url);
        }
        
        $certificate->delete();
        
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully.');
    }

    /**
     * Download the certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        // Get the raw pdf_url value from the database
        $pdfUrl = $certificate->getRawOriginal('pdf_url');
        
        if (!$pdfUrl) {
            return redirect()->back()->with('error', 'Certificate PDF not available.');
        }
        
        $filePath = storage_path('app/public/' . $pdfUrl);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Certificate file not found.');
        }
        
        return response()->download($filePath, $certificate->certificate_number . '.pdf');
    }
}

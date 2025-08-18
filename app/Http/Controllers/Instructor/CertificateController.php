<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Participant;
use App\Models\Instructure;
use App\Models\ClassModel;
use App\Models\CourseRegistration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    /**
     * Display a listing of certificates issued by the instructor.
     */
    public function index(Request $request)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        $query = Certificate::with(['participant.user', 'course'])
            ->where('instructure_id', $instructor->id);
        
        // Apply search filter
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('certificate_number', 'like', '%' . $search . '%')
                  ->orWhereHas('participant.user', function($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('course', function($q2) use ($search) {
                      $q2->where('course_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply date filters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($startDate) {
            $query->whereDate('issue_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('issue_date', '<=', $endDate);
        }
        
        $certificates = $query->latest()->paginate(10);
        
        // Get courses taught by the instructor for the filter
        $courses = Course::whereHas('classes.instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->orderBy('course_name')->get();
        
        // Get participants from the instructor's classes for the filter
        $participants = Participant::whereHas('registrations.class.instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->with('user')->get();
        
        // Calculate statistics
        $stats = [
            'total_certificates' => Certificate::where('instructure_id', $instructor->id)->count(),
            'valid_certificates' => Certificate::where('instructure_id', $instructor->id)->valid()->count(),
            'expired_certificates' => Certificate::where('instructure_id', $instructor->id)->expired()->count(),
            'expiring_soon' => Certificate::where('instructure_id', $instructor->id)->expiringSoon(30)->count(),
        ];
        
        return view('instructor.certificates.index', compact(
            'certificates'
        ));
    }
    
    /**
     * Show the form for creating a new certificate.
     */
    public function create(Request $request)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Get courses taught by the instructor
        $courses = Course::whereHas('classes.instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })->orderBy('course_name')->get();
        
        // Pre-select course and participant if provided in the request
        $selectedCourseId = $request->input('course_id');
        $selectedParticipantId = $request->input('participant_id');
        
        // If a course is selected, get participants from that course
        $participants = collect();
        if ($selectedCourseId) {
            $participants = Participant::whereHas('registrations', function($query) use ($selectedCourseId, $instructor) {
                $query->whereHas('class', function($q) use ($selectedCourseId, $instructor) {
                    $q->where('course_id', $selectedCourseId)
                      ->whereHas('instructures', function($q2) use ($instructor) {
                          $q2->where('instructures.id', $instructor->id);
                      });
                });
            })->with('user')->get();
        }
        
        return view('instructor.certificates.create', compact(
            'courses', 
            'participants', 
            'selectedCourseId', 
            'selectedParticipantId',
            'instructor'
        ));
    }
    
    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Validate the request
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'course_id' => 'required|exists:courses,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'certificate_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);
        
        // Check if the instructor is assigned to the course
        $courseHasInstructor = Course::where('id', $request->course_id)
            ->whereHas('classes.instructures', function($query) use ($instructor) {
                $query->where('instructures.id', $instructor->id);
            })->exists();
            
        if (!$courseHasInstructor) {
            return redirect()->route('instructor.certificates.create')
                ->with('error', 'You are not authorized to issue certificates for this course.');
        }
        
        // Check if the participant is enrolled in the course
        $participantEnrolled = CourseRegistration::whereHas('class', function($query) use ($request) {
            $query->where('course_id', $request->course_id);
        })->where('participant_id', $request->participant_id)
          ->where('reg_status', 'approved')
          ->exists();
          
        if (!$participantEnrolled) {
            return redirect()->route('instructor.certificates.create')
                ->with('error', 'The selected participant is not enrolled in this course.');
        }
        
        // Generate a unique certificate number
        $certificateNumber = Certificate::generateCertificateNumber();
        
        // Handle file upload if provided
        $pdfUrl = null;
        if ($request->hasFile('certificate_file')) {
            $file = $request->file('certificate_file');
            $pdfUrl = $file->store('certificates', 'public');
        }
        
        // Create the certificate
        $certificate = Certificate::create([
            'certificate_number' => $certificateNumber,
            'participant_id' => $request->participant_id,
            'course_id' => $request->course_id,
            'instructure_id' => $instructor->id,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'pdf_url' => $pdfUrl,
        ]);
        
        return redirect()->route('instructor.certificates.index')
            ->with('success', 'Certificate has been issued successfully.');
    }
    
    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Check if the certificate was issued by this instructor
        if ($certificate->instructure_id !== $instructor->id) {
            return redirect()->route('instructor.certificates.index')
                ->with('error', 'You are not authorized to view this certificate.');
        }
        
        return view('instructor.certificates.show', compact('certificate'));
    }
    
    /**
     * Download the certificate PDF.
     */
    public function download(Certificate $certificate)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Check if the certificate was issued by this instructor
        if ($certificate->instructure_id !== $instructor->id) {
            return redirect()->route('instructor.certificates.index')
                ->with('error', 'You are not authorized to download this certificate.');
        }
        
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
    
    /**
     * Show the list of certificate requests.
     */
    public function requests()
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor) {
            return redirect()->route('instructor.dashboard')
                ->with('error', 'Instructor profile not found.');
        }
        
        // Get certificate requests from participants in the instructor's classes
        $requests = CourseRegistration::whereHas('class.instructures', function($query) use ($instructor) {
            $query->where('instructures.id', $instructor->id);
        })
        ->where('certificate_requested', true)
        ->whereNull('certificate_issued_at')
        ->with(['participant.user', 'class.course'])
        ->latest()
        ->paginate(10);
        
        return view('instructor.certificates.requests', compact('requests'));
    }
    
    /**
     * Get participants for a specific course (AJAX).
     */
    public function getParticipants(Request $request)
    {
        // Get the current user and their instructor profile
        $user = Auth::user();
        $instructor = $user->instructure;
        
        if (!$instructor || !$request->course_id) {
            return response()->json([]);
        }
        
        // Get participants from the selected course
        $participants = Participant::whereHas('registrations', function($query) use ($request, $instructor) {
            $query->whereHas('class', function($q) use ($request, $instructor) {
                $q->where('course_id', $request->course_id)
                  ->whereHas('instructures', function($q2) use ($instructor) {
                      $q2->where('instructures.id', $instructor->id);
                  });
            });
        })->with('user')->get();
        
        return response()->json($participants);
    }
}

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserTypeController;
use App\Http\Controllers\Admin\InstructureController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\CourseScheduleController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\CertificateController as InstructorCertificateController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication routes (no middleware to avoid conflicts)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/forgot-password', [AuthController::class, 'showResetPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

// Google OAuth routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// Root route - handle redirects based on authentication
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        } elseif ($user->isInstructor()) {
            return redirect('/instructor/dashboard');
        } else {
            return redirect('/participant/dashboard');
        }
    }
    return redirect('/login');
})->name('home');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile routes (available for all authenticated users)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });
    
    // User Types Management
    Route::resource('user-types', UserTypeController::class);
    Route::post('/user-types/{userType}/toggle-status', [UserTypeController::class, 'toggleStatus'])->name('user-types.toggle-status');
    
    // Instructors Management
    Route::resource('instructors', InstructureController::class);
    Route::post('/instructors/{instructor}/toggle-status', [InstructureController::class, 'toggleStatus'])->name('instructors.toggle-status');
    
    // Participants Management
    Route::resource('participants', ParticipantController::class);
    Route::post('/participants/{participant}/toggle-status', [ParticipantController::class, 'toggleStatus'])->name('participants.toggle-status');
    
    // Course Management
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/', [CourseController::class, 'store'])->name('store');
        Route::get('/{course}', [CourseController::class, 'show'])->name('show');
        Route::get('/{course}/edit', [CourseController::class, 'edit'])->name('edit');
        Route::put('/{course}', [CourseController::class, 'update'])->name('update');
        Route::delete('/{course}', [CourseController::class, 'destroy'])->name('destroy');
        
        // Course Classes
        Route::get('/{course}/classes', [CourseController::class, 'classes'])->name('classes');
        Route::get('/{course}/classes/create', [CourseController::class, 'createClass'])->name('create-class');
        Route::post('/{course}/classes', [CourseController::class, 'storeClass'])->name('store-class');
        Route::post('/{course}/classes/{class}/status', [CourseController::class, 'updateClassStatus'])->name('update-class-status');
        Route::get('/{course}/classes/{class}', [CourseController::class, 'showClass'])->name('show-class');
        Route::get('/{course}/classes/{class}/edit', [CourseController::class, 'editClass'])->name('edit-class');
        Route::put('/{course}/classes/{class}', [CourseController::class, 'updateClass'])->name('update-class');
        Route::get('/{course}/classes/{class}/add-participant', [CourseController::class, 'addParticipant'])->name('add-participant');
        Route::post('/{course}/classes/{class}/participants', [CourseController::class, 'storeParticipant'])->name('store-participant');
        Route::get('/{course}/classes/{class}/add-instructor', [CourseController::class, 'addInstructor'])->name('add-instructor');
        Route::post('/{course}/classes/{class}/instructors', [CourseController::class, 'storeInstructor'])->name('store-instructor');
        Route::delete('/{course}/classes/{class}/instructors/{instructor}', [CourseController::class, 'removeInstructor'])->name('remove-instructor');
        Route::get('/{course}/classes/{class}/add-material', [CourseController::class, 'addMaterial'])->name('add-material');
        Route::post('/{course}/classes/{class}/materials', [CourseController::class, 'storeMaterial'])->name('store-material');
        Route::delete('/{course}/classes/{class}/materials/{material}', [CourseController::class, 'removeMaterial'])->name('remove-material');
    });
    
    // Course Schedule Management
    Route::get('/course-schedule', [CourseScheduleController::class, 'index'])->name('course-schedule.index');
    Route::get('/course-schedule/{course}/{class}', [CourseScheduleController::class, 'show'])->name('course-schedule.show');
    Route::get('/course-schedule/day/{date}', [CourseScheduleController::class, 'dayView'])->name('course-schedule.day');
    
    // Course Types Management
    Route::resource('course-types', CourseTypeController::class);
    
    // Certificates Management
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{certificate}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');
    
    // Reset Password Links
    Route::get('/reset-links', function () {
        $resetTokens = \DB::table('password_reset_tokens')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reset-links', compact('resetTokens'));
    })->name('reset-links');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/certificate-expired', [\App\Http\Controllers\Admin\ReportController::class, 'certificateExpired'])->name('certificate-expired');
        Route::get('/payment-report', [\App\Http\Controllers\Admin\ReportController::class, 'paymentReport'])->name('payment-report');
        Route::get('/bank-accounts', [\App\Http\Controllers\Admin\ReportController::class, 'bankAccounts'])->name('bank-accounts');
        Route::post('/bank-accounts', [\App\Http\Controllers\Admin\ReportController::class, 'storeBankAccount'])->name('store-bank-account');
        Route::post('/bank-accounts/{id}/toggle', [\App\Http\Controllers\Admin\ReportController::class, 'toggleBankAccount'])->name('toggle-bank-account');
    });
    
    // Payment management
    Route::post('/payments/{id}/update-status', [\App\Http\Controllers\Admin\ReportController::class, 'updatePaymentStatus'])->name('update-payment-status');
});

// Instructor Routes
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    
    // My Courses
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [InstructorCourseController::class, 'index'])->name('index');
        Route::get('/{course}', [InstructorCourseController::class, 'show'])->name('show');
        Route::get('/{course}/attendance', [InstructorCourseController::class, 'attendance'])->name('attendance');
        Route::get('/{course}/materials', [InstructorCourseController::class, 'materials'])->name('materials');
        Route::get('/{course}/classes/{class}/add-material', [InstructorCourseController::class, 'addMaterial'])->name('add-material');
        Route::post('/{course}/classes/{class}/materials', [InstructorCourseController::class, 'storeMaterial'])->name('store-material');
        Route::delete('/{course}/classes/{class}/materials/{material}', [InstructorCourseController::class, 'removeMaterial'])->name('remove-material');
    });
    
    // Certificates
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', [InstructorCertificateController::class, 'index'])->name('index');
        Route::get('/create', [InstructorCertificateController::class, 'create'])->name('create');
        Route::post('/', [InstructorCertificateController::class, 'store'])->name('store');
        Route::get('/requests', [InstructorCertificateController::class, 'requests'])->name('requests');
        Route::get('/participants', [InstructorCertificateController::class, 'getParticipants'])->name('get-participants');
        Route::get('/{certificate}', [InstructorCertificateController::class, 'show'])->name('show');
        Route::get('/{certificate}/download', [InstructorCertificateController::class, 'download'])->name('download');
    });
});

// Participant Routes
Route::middleware(['auth', 'role:participant'])->prefix('participant')->name('participant.')->group(function () {
    Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
    
    // My Courses
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Participant\CourseController::class, 'index'])->name('index');
        Route::post('/{class}/register', [\App\Http\Controllers\Participant\CourseController::class, 'register'])->name('register');
        Route::get('/{class}/detail', [\App\Http\Controllers\Participant\CourseController::class, 'detail'])->name('detail');
        
        Route::get('/browse', function () {
            return view('participant.placeholder', [
                'title' => 'Browse Courses',
                'description' => 'Discover and enroll in available training courses.',
                'breadcrumbs' => ['Browse Courses']
            ]);
        })->name('browse');
        
        Route::get('/{course}', function () { return view('participant.placeholder', ['title' => 'Course Details']); })->name('show');
        Route::post('/{course}/enroll', function () { return redirect()->route('participant.courses.index')->with('success', 'Enrolled successfully'); })->name('enroll');
    });
    
    // My Certificates
    Route::prefix('certificates')->name('certificates.')->group(function () {
        Route::get('/', function () {
            return view('participant.placeholder', [
                'title' => 'My Certificates',
                'description' => 'View and download your earned certificates.',
                'breadcrumbs' => ['My Certificates']
            ]);
        })->name('index');
        
        Route::get('/request', function () {
            return redirect()->route('participant.courses.index')
                ->with('success', 'Certificate request submitted successfully.');
        })->name('request');
        
        Route::get('/{certificate}/download', function () {
            return response()->json(['message' => 'Certificate download feature coming soon']);
        })->name('download');
    });
    
    // Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Participant\PaymentController::class, 'index'])->name('index');
        Route::post('/upload', [\App\Http\Controllers\Participant\PaymentController::class, 'upload'])->name('upload');
    });
});

// API Routes
Route::prefix('api')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'apiLogin']);
    Route::post('/auth/register', [AuthController::class, 'apiRegister']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'apiLogout']);
        
        // Courses API
        Route::get('/courses', function () {
            return response()->json(['message' => 'Courses API endpoint']);
        });
        
        // Users API
        Route::get('/users', function () {
            return response()->json(['message' => 'Users API endpoint']);
        });
        
        // Certificates API
        Route::get('/certificates', function () {
            return response()->json(['message' => 'Certificates API endpoint']);
        });
        
        // Payments API
        Route::get('/payments', function () {
            return response()->json(['message' => 'Payments API endpoint']);
        });
    });
});

// Test email route
Route::get('/send-test-email', function () {
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email dari Train4Best Laravel', function ($message) {
            $message->to('m.alfin.z117@gmail.com')
                    ->subject('Test Email - Train4Best')
                    ->from('noreply@train4best.com', 'Train4Best');
        });
        
        return 'Email test berhasil dikirim! Cek inbox dan folder spam.';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});



// Test email route (only for development)
if (app()->environment('local')) {
    Route::get('/test-email', function () {
        $logContent = '';
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logContent = tail($logFile, 50);
        }
        return view('test-email', compact('logContent'));
    });
    
    Route::post('/test-email', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user) {
            $token = \Illuminate\Support\Str::random(60);
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\PasswordResetEmail($user, $token));
                return back()->with('success', 'Email berhasil dikirim! Cek log di bawah.');
            } catch (\Exception $e) {
                return back()->with('success', 'Email disimpan di log: ' . $e->getMessage());
            }
        }
        return back()->with('success', 'User tidak ditemukan');
    });
}

// Include debug routes
if (app()->environment('local')) {
    include __DIR__ . '/debug.php';
}

// Helper function for reading log file
if (!function_exists('tail')) {
    function tail($file, $lines = 10) {
        $handle = fopen($file, 'r');
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = array();
        while ($linecounter > 0) {
            $t = ' ';
            while ($t != "\n") {
                if (fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($handle);
            }
            $text[$lines - $linecounter - 1] = fgets($handle);
            if ($beginning) break;
        }
        fclose($handle);
        return array_reverse($text);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CourseRegistration;
use App\Models\Payment;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Mail\RegistrationConfirmationEmail;
use App\Mail\PaymentConfirmationEmail;
use App\Mail\CertificateIssuedEmail;
use App\Mail\PasswordResetEmail;

class EmailController extends Controller
{
    /**
     * Send welcome email to a new user.
     */
    public function sendWelcomeEmail(User $user)
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send registration confirmation email.
     */
    public function sendRegistrationConfirmationEmail(CourseRegistration $registration)
    {
        try {
            $user = $registration->participant->user;
            Mail::to($user->email)->send(new RegistrationConfirmationEmail($registration));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send registration confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment confirmation email.
     */
    public function sendPaymentConfirmationEmail(Payment $payment)
    {
        try {
            $user = $payment->registration->participant->user;
            Mail::to($user->email)->send(new PaymentConfirmationEmail($payment));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send certificate issued email.
     */
    public function sendCertificateIssuedEmail(Certificate $certificate)
    {
        try {
            $user = $certificate->participant->user;
            Mail::to($user->email)->send(new CertificateIssuedEmail($certificate));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate issued email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send password reset email.
     */
    public function sendPasswordResetEmail(User $user, string $token)
    {
        try {
            Mail::to($user->email)->send(new PasswordResetEmail($user, $token));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk emails to participants of a class.
     */
    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $class = \App\Models\ClassModel::findOrFail($request->class_id);
        $registrations = $class->registrations()->where('reg_status', 'approved')->get();
        
        $successCount = 0;
        $failCount = 0;

        foreach ($registrations as $registration) {
            try {
                $user = $registration->participant->user;
                Mail::to($user->email)->send(new \App\Mail\CustomEmail(
                    $user,
                    $request->subject,
                    $request->message,
                    $class
                ));
                $successCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send bulk email: ' . $e->getMessage());
                $failCount++;
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $registrations->count(),
        ];
    }

    /**
     * Send certificate expiry notification.
     */
    public function sendCertificateExpiryNotification(Certificate $certificate)
    {
        try {
            $user = $certificate->participant->user;
            Mail::to($user->email)->send(new \App\Mail\CertificateExpiryEmail($certificate));
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate expiry notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk certificate expiry notifications.
     */
    public function sendBulkCertificateExpiryNotifications()
    {
        // Get certificates expiring in the next 30 days
        $certificates = Certificate::expiringSoon(30)->get();
        
        $successCount = 0;
        $failCount = 0;

        foreach ($certificates as $certificate) {
            $result = $this->sendCertificateExpiryNotification($certificate);
            if ($result) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $certificates->count(),
        ];
    }
} 
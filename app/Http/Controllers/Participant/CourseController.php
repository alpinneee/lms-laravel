<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use Illuminate\Http\Request;
use App\Models\CourseRegistration;

class CourseController extends Controller
{
    public function index()
    {
        $participant = auth()->user()->participant;
        
        $classes = ClassModel::with(['course', 'instructures'])
            ->where('status', 'active')
            ->where('end_reg_date', '>=', now())
            ->latest()
            ->paginate(12);
        
        // Get registered classes for this participant
        $registeredClasses = [];
        if ($participant) {
            $registeredClasses = CourseRegistration::where('participant_id', $participant->id)
                ->whereIn('reg_status', ['approved', 'pending_verification'])
                ->pluck('class_id')
                ->toArray();
        }
            
        return view('participant.courses.index', compact('classes', 'registeredClasses'));
    }

    public function register($classId)
    {
        $class = ClassModel::findOrFail($classId);
        $participant = auth()->user()->participant;
        
        if (!$participant) {
            return redirect()->back()->with('error', 'Participant profile not found.');
        }
        
        // Check if already registered with payment
        $existingRegistration = CourseRegistration::where([
            'class_id' => $classId,
            'participant_id' => $participant->id
        ])->whereIn('payment_status', ['pending_verification', 'approved'])->first();
        
        if ($existingRegistration) {
            return redirect()->back()->with('error', 'You are already registered for this course.');
        }
        
        // Delete incomplete registration (payment_required without payment)
        CourseRegistration::where([
            'class_id' => $classId,
            'participant_id' => $participant->id,
            'payment_status' => 'payment_required'
        ])->delete();
        
        // Check if class is full
        if ($class->isFull()) {
            return redirect()->back()->with('error', 'This class is already full.');
        }
        
        // Create registration with payment_required status
        $registration = CourseRegistration::create([
            'participant_id' => $participant->id,
            'class_id' => $classId,
            'payment_status' => 'payment_required',
            'payment' => $class->price,
            'reg_status' => 'payment_required',
            'reg_date' => now(),
        ]);
        
        return redirect()->back()->with('payment_modal', $registration->id);
    }

    public function detail($classId)
    {
        $class = ClassModel::with(['course', 'instructures', 'materials', 'registrations.participant.user'])->findOrFail($classId);
        $participant = auth()->user()->participant;
        
        // Check if participant is registered for this class
        $registration = CourseRegistration::where([
            'participant_id' => $participant->id,
            'class_id' => $classId
        ])->whereIn('reg_status', ['approved', 'pending_verification'])->first();
        
        if (!$registration) {
            return redirect()->route('participant.courses.index')
                ->with('error', 'You are not registered for this course.');
        }
        
        // Get other participants in this class
        $otherParticipants = CourseRegistration::where('class_id', $classId)
            ->where('reg_status', 'approved')
            ->with('participant.user')
            ->get();
        
        return view('participant.courses.detail', compact('class', 'registration', 'otherParticipants'));
    }
}
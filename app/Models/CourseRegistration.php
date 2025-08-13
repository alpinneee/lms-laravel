<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'participant_id',
        'reg_date',
        'reg_status',
        'payment',
        'payment_status',
        'payment_method',
        'present_day',
        'certificate_requested',
        'certificate_requested_at',
        'certificate_issued_at',
    ];

    protected $casts = [
        'reg_date' => 'date',
        'payment' => 'decimal:2',
        'certificate_requested' => 'boolean',
        'certificate_requested_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'registration_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'registration_id');
    }

    public function valueReports()
    {
        return $this->hasMany(ValueReport::class, 'registration_id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'registration_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('reg_status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('reg_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Helper methods
    public function getTotalPaymentsAttribute()
    {
        return $this->payments()->where('status', 'verified')->sum('amount');
    }

    public function getRemainingPaymentAttribute()
    {
        return $this->payment - $this->getTotalPaymentsAttribute();
    }

    public function isFullyPaid()
    {
        return $this->getTotalPaymentsAttribute() >= $this->payment;
    }
}

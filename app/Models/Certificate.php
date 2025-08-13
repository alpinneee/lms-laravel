<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'name',
        'issue_date',
        'expiry_date',
        'status',
        'participant_id',
        'course_id',
        'instructure_id',
        'pdf_url',
        'drive_link',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    /**
     * Get the participant that owns the certificate.
     */
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    /**
     * Get the course that owns the certificate.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the instructure that owns the certificate.
     */
    public function instructure()
    {
        return $this->belongsTo(Instructure::class);
    }

    /**
     * Check if the certificate is valid.
     */
    public function isValid()
    {
        return $this->status === 'valid' && $this->expiry_date->isFuture();
    }

    /**
     * Check if the certificate is expired.
     */
    public function isExpired()
    {
        return $this->expiry_date->isPast();
    }

    /**
     * Get the certificate PDF URL.
     */
    public function getPdfUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return asset('storage/' . $value);
    }

    /**
     * Generate a unique certificate number.
     */
    public static function generateCertificateNumber()
    {
        $prefix = 'CERT';
        $year = Carbon::now()->format('Y');
        $random = strtoupper(Str::random(6));
        $count = self::count() + 1;
        
        return $prefix . '-' . $year . '-' . $random . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope a query to only include valid certificates.
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'valid')
            ->where('expiry_date', '>=', Carbon::now());
    }

    /**
     * Scope a query to only include expired certificates.
     */
    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', Carbon::now());
    }

    /**
     * Scope a query to only include certificates that will expire soon.
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        $now = Carbon::now();
        $future = Carbon::now()->addDays($days);
        
        return $query->where('status', 'valid')
            ->whereBetween('expiry_date', [$now, $future]);
    }
}

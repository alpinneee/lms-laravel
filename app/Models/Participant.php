<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'photo',
        'address',
        'phone_number',
        'birth_date',
        'job_title',
        'company',
        'gender',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Helper methods
    public function getFullName()
    {
        return $this->full_name ?: $this->user->name;
    }

    public function getActiveRegistrations()
    {
        return $this->registrations()->where('reg_status', 'approved')->get();
    }
}

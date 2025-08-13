<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'course_id',
        'quota',
        'price',
        'status',
        'start_reg_date',
        'end_reg_date',
        'duration_day',
        'start_date',
        'end_date',
        'location',
        'room',
    ];

    protected $casts = [
        'start_reg_date' => 'date',
        'end_reg_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructures()
    {
        return $this->belongsToMany(Instructure::class, 'instructure_classes', 'class_id', 'instructure_id');
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class, 'class_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class, 'course_schedule_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('end_reg_date', '>=', now());
    }

    // Helper methods
    public function getAvailableSpotsAttribute()
    {
        return $this->quota - $this->registrations()->where('reg_status', 'approved')->count();
    }

    public function isFull()
    {
        return $this->getAvailableSpotsAttribute() <= 0;
    }

    public function isRegistrationOpen()
    {
        return now()->between($this->start_reg_date, $this->end_reg_date);
    }
}

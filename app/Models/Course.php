<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_type_id',
        'course_name',
        'description',
        'image',
    ];

    // Relationships
    public function courseType()
    {
        return $this->belongsTo(CourseType::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    // Helper methods
    public function getActiveClassesAttribute()
    {
        return $this->classes()->active()->get();
    }

    public function getTotalStudentsAttribute()
    {
        return CourseRegistration::whereHas('class', function ($query) {
            $query->where('course_id', $this->id);
        })->where('reg_status', 'approved')->count();
    }
}

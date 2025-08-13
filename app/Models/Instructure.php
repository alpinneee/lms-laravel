<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'photo',
        'phone_number',
        'address',
        'proficiency',
    ];

    // Relationships
    public function user()
    {
        return $this->hasOne(User::class, 'instructure_id');
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'instructure_classes', 'instructure_id', 'class_id');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function valueReports()
    {
        return $this->hasMany(ValueReport::class);
    }

    // Helper methods
    public function getActiveClasses()
    {
        return $this->classes()->active()->get();
    }

    public function getTotalStudents()
    {
        return CourseRegistration::whereHas('class.instructures', function ($query) {
            $query->where('instructure_id', $this->id);
        })->where('reg_status', 'approved')->count();
    }
}

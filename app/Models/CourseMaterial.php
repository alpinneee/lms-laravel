<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_schedule_id',
        'title',
        'description',
        'file_url',
        'day',
        'size',
        'is_google_drive'
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'course_schedule_id');
    }
}

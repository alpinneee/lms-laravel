<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\ClassModel;
use App\Models\CourseType;
use Carbon\Carbon;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample courses
        $courseType = CourseType::first();
        
        if (!$courseType) {
            $courseType = CourseType::create([
                'type_name' => 'Programming',
                'description' => 'Programming courses'
            ]);
        }

        $courses = [
            [
                'course_name' => 'Laravel Web Development',
                'description' => 'Learn Laravel framework for web development',
                'course_type_id' => $courseType->id
            ],
            [
                'course_name' => 'React.js Frontend Development',
                'description' => 'Master React.js for modern frontend development',
                'course_type_id' => $courseType->id
            ],
            [
                'course_name' => 'Database Design & Management',
                'description' => 'Learn database design and management principles',
                'course_type_id' => $courseType->id
            ]
        ];

        foreach ($courses as $courseData) {
            $course = Course::create($courseData);
            
            // Create classes for each course
            ClassModel::create([
                'course_id' => $course->id,
                'quota' => 20,
                'price' => 1500000,
                'status' => 'active',
                'start_reg_date' => Carbon::now(),
                'end_reg_date' => Carbon::now()->addDays(30),
                'duration_day' => 5,
                'start_date' => Carbon::now()->addDays(35),
                'end_date' => Carbon::now()->addDays(40),
                'location' => 'Jakarta Training Center',
                'room' => 'Room A-' . rand(1, 5)
            ]);
            
            ClassModel::create([
                'course_id' => $course->id,
                'quota' => 15,
                'price' => 1500000,
                'status' => 'inactive',
                'start_reg_date' => Carbon::now()->addDays(60),
                'end_reg_date' => Carbon::now()->addDays(90),
                'duration_day' => 5,
                'start_date' => Carbon::now()->addDays(95),
                'end_date' => Carbon::now()->addDays(100),
                'location' => 'Bandung Training Center',
                'room' => 'Room B-' . rand(1, 5)
            ]);
        }
    }
}
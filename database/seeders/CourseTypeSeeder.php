<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('course_types')->insert([
            [
                'course_type' => 'Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_type' => 'Management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_type' => 'Finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_type' => 'Marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'course_type' => 'HR & Leadership',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

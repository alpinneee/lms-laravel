<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            [
                'usertype' => 'admin',
                'description' => 'System Administrator with full access',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usertype' => 'instructor',
                'description' => 'Training Instructor',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'usertype' => 'participant',
                'description' => 'Training Participant',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

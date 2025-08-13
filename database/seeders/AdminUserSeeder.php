<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserType;
use App\Models\Instructure;
use App\Models\Participant;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get user types
        $adminType = UserType::where('usertype', 'admin')->first();
        $instructorType = UserType::where('usertype', 'instructor')->first();
        $participantType = UserType::where('usertype', 'participant')->first();

        // Create Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@train4best.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'user_type_id' => $adminType->id,
            'email_verified_at' => now(),
        ]);

        // Create Instructor
        $instructure = Instructure::create([
            'full_name' => 'Dr. John Smith',
            'photo' => null,
            'phone_number' => '+1234567890',
            'address' => '123 Training Street, Education City',
            'proficiency' => 'Technology, Leadership, Project Management',
        ]);

        $instructor = User::create([
            'name' => 'John Smith',
            'email' => 'instructor@train4best.com',
            'username' => 'johnsmith',
            'password' => Hash::make('password'),
            'user_type_id' => $instructorType->id,
            'instructure_id' => $instructure->id,
            'email_verified_at' => now(),
        ]);

        // Create Participant User
        $participant = User::create([
            'name' => 'Jane Doe',
            'email' => 'participant@train4best.com',
            'username' => 'janedoe',
            'password' => Hash::make('password'),
            'user_type_id' => $participantType->id,
            'email_verified_at' => now(),
        ]);

        // Create Participant Profile
        Participant::create([
            'user_id' => $participant->id,
            'full_name' => 'Jane Doe',
            'photo' => null,
            'address' => '456 Learning Avenue, Study Town',
            'phone_number' => '+0987654321',
            'birth_date' => '1990-05-15',
            'job_title' => 'Marketing Specialist',
            'company' => 'TechCorp Inc.',
            'gender' => 'female',
        ]);

        // Create additional demo users
        $demoUsers = [
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@train4best.com',
                'username' => 'mikejohnson',
                'type' => 'participant'
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah@train4best.com',
                'username' => 'sarahwilson',
                'type' => 'participant'
            ],
            [
                'name' => 'David Brown',
                'email' => 'david@train4best.com',
                'username' => 'davidbrown',
                'type' => 'instructor'
            ]
        ];

        foreach ($demoUsers as $userData) {
            $userType = UserType::where('usertype', $userData['type'])->first();
            
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'username' => $userData['username'],
                'password' => Hash::make('password'),
                'user_type_id' => $userType->id,
                'email_verified_at' => now(),
            ]);

            if ($userData['type'] === 'instructor') {
                $instructure = Instructure::create([
                    'full_name' => $userData['name'],
                    'phone_number' => '+1' . rand(1000000000, 9999999999),
                    'address' => 'Demo Address for ' . $userData['name'],
                    'proficiency' => 'Software Development, Testing, DevOps',
                ]);

                $user->update(['instructure_id' => $instructure->id]);
            }

            if ($userData['type'] === 'participant') {
                Participant::create([
                    'user_id' => $user->id,
                    'full_name' => $userData['name'],
                    'address' => 'Demo Address for ' . $userData['name'],
                    'phone_number' => '+1' . rand(1000000000, 9999999999),
                    'birth_date' => '1985-' . rand(1,12) . '-' . rand(1,28),
                    'job_title' => 'Professional',
                    'company' => 'Demo Company',
                    'gender' => rand(0,1) ? 'male' : 'female',
                ]);
            }
        }
    }
}

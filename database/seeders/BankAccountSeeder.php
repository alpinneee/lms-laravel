<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bank_accounts')->insert([
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_name' => 'Train4Best Training Center',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'Train4Best Training Center',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'bank_name' => 'Bank BNI',
                'account_number' => '5555666677',
                'account_name' => 'Train4Best Training Center',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

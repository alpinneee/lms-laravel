<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('system_settings')->insert([
            [
                'setting_key' => 'app_name',
                'value' => 'Train4Best',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'app_logo',
                'value' => '/images/logo.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'primary_color',
                'value' => '#373A8D',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'certificate_validity_years',
                'value' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'max_upload_size',
                'value' => '5120',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'admin_email',
                'value' => 'admin@train4best.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'smtp_host',
                'value' => 'smtp.gmail.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'setting_key' => 'smtp_port',
                'value' => '587',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add unassigned user type
        DB::table('user_types')->insert([
            'usertype' => 'unassigned',
            'description' => 'New user with no specific role',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove unassigned user type
        DB::table('user_types')->where('usertype', 'unassigned')->delete();
    }
};

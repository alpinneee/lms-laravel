<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->boolean('certificate_requested')->default(false)->after('reg_status');
            $table->timestamp('certificate_requested_at')->nullable()->after('certificate_requested');
            $table->timestamp('certificate_issued_at')->nullable()->after('certificate_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->dropColumn(['certificate_requested', 'certificate_requested_at', 'certificate_issued_at']);
        });
    }
};

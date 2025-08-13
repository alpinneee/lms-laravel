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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('email');
            $table->foreignId('instructure_id')->nullable()->constrained('instructures')->nullOnDelete()->after('username');
            $table->foreignId('user_type_id')->constrained('user_types')->after('instructure_id');
            $table->timestamp('last_login')->nullable()->after('user_type_id');
            $table->string('token')->nullable()->after('last_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['instructure_id']);
            $table->dropForeign(['user_type_id']);
            $table->dropColumn(['username', 'instructure_id', 'user_type_id', 'last_login', 'token']);
        });
    }
};

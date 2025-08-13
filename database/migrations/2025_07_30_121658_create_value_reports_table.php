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
        Schema::create('value_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('course_registrations');
            $table->foreignId('instructure_id')->constrained();
            $table->decimal('value', 5, 2);
            $table->string('value_type');
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('value_reports');
    }
};

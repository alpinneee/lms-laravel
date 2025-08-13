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
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained();
            $table->foreignId('participant_id')->constrained();
            $table->date('reg_date');
            $table->enum('reg_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->decimal('payment', 10, 2);
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->integer('present_day')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_registrations');
    }
};

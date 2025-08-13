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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->integer('quota');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['active', 'inactive', 'full', 'completed'])->default('active');
            $table->date('start_reg_date');
            $table->date('end_reg_date');
            $table->integer('duration_day');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location');
            $table->string('room');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coach_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('training_sessions')->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('scheduled_start_time');
            $table->time('scheduled_end_time');
            $table->time('actual_start_time')->nullable(); // When coach actually started
            $table->time('actual_end_time')->nullable(); // When coach actually ended
            $table->boolean('is_late')->default(false);
            $table->integer('late_minutes')->default(0);
            $table->text('notes')->nullable(); // Notes about lateness, pool entry, etc.
            $table->decimal('hours_worked', 5, 2)->nullable(); // Calculated hours
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['coach_id', 'session_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_attendances');
    }
};

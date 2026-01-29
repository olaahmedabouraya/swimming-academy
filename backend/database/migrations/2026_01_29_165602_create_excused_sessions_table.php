<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excused_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('original_attendance_id')->nullable()->constrained('attendances')->onDelete('set null');
            $table->foreignId('original_session_id')->nullable()->constrained('training_sessions')->onDelete('set null');
            $table->date('original_date'); // Date of the missed session
            $table->text('excuse_reason'); // Reason for the excuse
            $table->enum('status', ['pending', 'makeup_taken', 'discounted', 'expired'])->default('pending');
            $table->foreignId('makeup_attendance_id')->nullable()->constrained('attendances')->onDelete('set null');
            $table->foreignId('makeup_session_id')->nullable()->constrained('training_sessions')->onDelete('set null');
            $table->date('makeup_date')->nullable(); // Date when makeup session was taken
            $table->boolean('discounted_from_fee')->default(false); // Whether discounted from next month's fee
            $table->foreignId('discounted_fee_id')->nullable()->constrained('fees')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excused_sessions');
    }
};

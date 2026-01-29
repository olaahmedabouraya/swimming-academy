<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('session_id')->nullable()->constrained('training_sessions')->onDelete('set null');
            $table->time('actual_start_time')->nullable(); // When player actually started
            $table->time('actual_end_time')->nullable(); // When player actually ended
            $table->text('coach_notes')->nullable(); // Notes about coach lateness, etc.
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn(['session_id', 'actual_start_time', 'actual_end_time', 'coach_notes']);
        });
    }
};

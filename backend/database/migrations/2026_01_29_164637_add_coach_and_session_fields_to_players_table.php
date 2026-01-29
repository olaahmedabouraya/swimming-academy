<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->foreignId('current_session_id')->nullable()->constrained('training_sessions')->onDelete('set null');
            $table->foreignId('coach_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('enrollment_type', ['monthly', 'per_session'])->default('monthly');
            $table->date('period_start_date')->nullable(); // Start of current payment period
            $table->date('period_end_date')->nullable(); // End of current payment period
            $table->integer('sessions_per_month')->default(8);
            $table->integer('sessions_used')->default(0);
            $table->text('sports_manager_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['current_session_id']);
            $table->dropForeign(['coach_id']);
            $table->dropColumn([
                'current_session_id',
                'coach_id',
                'enrollment_type',
                'period_start_date',
                'period_end_date',
                'sessions_per_month',
                'sessions_used',
                'sports_manager_notes'
            ]);
        });
    }
};

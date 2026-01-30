<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->decimal('revenue', 15, 2)->default(0);
            $table->integer('new_enrollments')->default(0);
            $table->integer('total_active_players')->default(0);
            $table->decimal('selling_rate', 5, 2)->default(0); // percentage
            $table->integer('total_sessions_conducted')->default(0);
            $table->integer('total_attendance')->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['branch_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_records');
    }
};




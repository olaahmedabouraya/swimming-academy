<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'professional'])->default('beginner');
            $table->date('enrollment_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('medical_notes')->nullable();
            $table->text('emergency_contact')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};



<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('day_of_week'); // Sunday, Monday, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date')->nullable(); // When this session configuration starts
            $table->date('end_date')->nullable(); // When this session configuration ends (null = ongoing)
            $table->boolean('is_active')->default(true);
            $table->integer('max_capacity')->default(20);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('rated_by')->constrained('users')->onDelete('cascade');
            $table->integer('technique_score')->default(0); // 0-100
            $table->integer('endurance_score')->default(0); // 0-100
            $table->integer('speed_score')->default(0); // 0-100
            $table->integer('attitude_score')->default(0); // 0-100
            $table->integer('overall_score')->default(0); // 0-100
            $table->text('comments')->nullable();
            $table->date('rating_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_ratings');
    }
};




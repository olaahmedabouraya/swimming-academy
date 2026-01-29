<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_player_id')->constrained('players')->onDelete('cascade');
            $table->enum('relationship_type', ['sibling', 'parent'])->default('sibling');
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->timestamps();
            
            $table->unique(['player_id', 'related_player_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_relationships');
    }
};

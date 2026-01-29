<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            // This was already added in the previous migration, so we'll just ensure it exists
            if (!Schema::hasColumn('players', 'sports_manager_notes')) {
                $table->text('sports_manager_notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            if (Schema::hasColumn('players', 'sports_manager_notes')) {
                $table->dropColumn('sports_manager_notes');
            }
        });
    }
};

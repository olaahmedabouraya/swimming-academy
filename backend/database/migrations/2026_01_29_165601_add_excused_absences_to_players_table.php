<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('excused_absences_allowed')->default(0)->after('sessions_used');
            $table->integer('excused_absences_used')->default(0)->after('excused_absences_allowed');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['excused_absences_allowed', 'excused_absences_used']);
        });
    }
};

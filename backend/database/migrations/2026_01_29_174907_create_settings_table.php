<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, date, number, boolean
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default period dates (current month)
        $now = now();
        \DB::table('settings')->insert([
            [
                'key' => 'period_start_date',
                'value' => $now->copy()->startOfMonth()->format('Y-m-d'),
                'type' => 'date',
                'description' => 'Start date of the billing/enrollment period',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'period_end_date',
                'value' => $now->copy()->endOfMonth()->format('Y-m-d'),
                'type' => 'date',
                'description' => 'End date of the billing/enrollment period',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

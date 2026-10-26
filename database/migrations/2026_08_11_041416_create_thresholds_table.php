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
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            // Sukat para sa Water Level (cm)
            $table->float('water_warning_cm')->default(100);
            $table->float('water_danger_cm')->default(200);

            // Sukat para sa Lakas ng Ulan (mm)
            $table->float('rain_warning_mm')->default(15);
            $table->float('rain_danger_mm')->default(30);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thresholds');
    }
};

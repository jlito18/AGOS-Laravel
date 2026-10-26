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
        Schema::create('readings', function (Blueprint $table) {
            $table->id();

            // Ultrasonic Sensor
            $table->decimal('water_level_cm', 8, 2)->nullable();

            // Tipping Bucket / Rain Gauge
            $table->decimal('rainfall_mm', 8, 2)->nullable();

            // Water Flow Sensor
            $table->decimal('water_flow_rate', 8, 2)->nullable();

            // status (Safe, Warning, Danger)
            $table->string('status')->default('Safe');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('readings');
    }
};

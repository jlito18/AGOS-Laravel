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
        Schema::table('thresholds', function (Blueprint $table) {
            // Default sa 500 meters
            $table->integer('hazard_radius_meters')->default(500)->after('rain_danger_mm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thresholds', function (Blueprint $table) {
            //
        });
    }
};

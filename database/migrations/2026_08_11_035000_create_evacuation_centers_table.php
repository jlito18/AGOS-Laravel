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
        Schema::create('evacuation_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Hal. "San Juan Barangay Hall"
            $table->string('barangay'); // Hal. "Barangay San Juan"
            $table->integer('capacity'); // Ilang tao ang kasya
            $table->string('status')->default('Available'); // Available o Full
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evacuation_centers');
    }

    
};

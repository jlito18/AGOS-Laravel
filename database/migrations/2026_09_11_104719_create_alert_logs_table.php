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
        Schema::create('alert_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level'); // Dito natin ilalagay kung 'CRITICAL', 'WARNING', o 'NORMAL'
            $table->text('message'); // Dito yung mensahe: "Water level reached 30.50cm..."
            $table->timestamps(); // Automatic na nagtatala ng petsa at oras
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_logs');
    }
};

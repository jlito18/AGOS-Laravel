<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // <-- Idadagdag ang deleted_at sa users
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->softDeletes(); // <-- Idadagdag ang deleted_at sa residents
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('residents', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

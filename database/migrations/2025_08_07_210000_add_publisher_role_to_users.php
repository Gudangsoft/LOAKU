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
        // Update users table to support publisher role
        Schema::table('users', function (Blueprint $table) {
            // Update role enum to include publisher
            $table->enum('role', ['admin', 'member', 'publisher'])->default('member')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'member'])->default('member')->change();
        });
    }
};

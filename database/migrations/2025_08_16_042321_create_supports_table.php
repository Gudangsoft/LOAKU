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
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama organisasi/instansi
            $table->string('logo')->nullable(); // Path file logo
            $table->string('website')->nullable(); // Website URL
            $table->text('description')->nullable(); // Deskripsi singkat
            $table->integer('order')->default(0); // Urutan tampil
            $table->boolean('is_active')->default(true); // Status aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supports');
    }
};

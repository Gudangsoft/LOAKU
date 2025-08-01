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
        Schema::create('loa_validated', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loa_request_id')->constrained()->onDelete('cascade');
            $table->string('loa_code')->unique(); // Kode LOA untuk verifikasi
            $table->string('pdf_path_id')->nullable(); // Path PDF Bahasa Indonesia
            $table->string('pdf_path_en')->nullable(); // Path PDF Bahasa Inggris
            $table->string('verification_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loa_validated');
    }
};

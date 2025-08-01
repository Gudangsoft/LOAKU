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
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('e_issn')->nullable();
            $table->string('p_issn')->nullable();
            $table->string('chief_editor');
            $table->string('logo')->nullable();
            $table->string('ttd_stample')->nullable(); // TTD STAMPLE
            $table->string('website')->nullable();
            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journals');
    }
};

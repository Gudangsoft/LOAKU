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
        Schema::create('loa_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Template name
            $table->text('description')->nullable(); // Template description
            $table->enum('language', ['id', 'en', 'both'])->default('both'); // Language
            $table->enum('format', ['html', 'pdf'])->default('html'); // Format type
            $table->longText('header_template'); // Header HTML template
            $table->longText('body_template'); // Body HTML template  
            $table->longText('footer_template'); // Footer HTML template
            $table->json('variables')->nullable(); // Available template variables
            $table->text('css_styles')->nullable(); // Custom CSS styles
            $table->boolean('is_active')->default(true); // Active status
            $table->boolean('is_default')->default(false); // Default template
            $table->unsignedBigInteger('publisher_id')->nullable(); // Publisher specific template
            $table->timestamps();
            
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');
            $table->index(['is_active', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loa_templates');
    }
};

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
        Schema::create('loa_requests', function (Blueprint $table) {
            $table->id();
            $table->string('no_reg')->unique(); // Format: LOASIP.{ArticleID}.{Sequential}
            $table->string('article_id');
            $table->integer('volume');
            $table->integer('number');
            $table->string('month'); // Bulan (combo 12 bulan)
            $table->integer('year'); // Tahun
            $table->string('article_title');
            $table->string('author');
            $table->string('author_email');
            $table->foreignId('journal_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loa_requests');
    }
};

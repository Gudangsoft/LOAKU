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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->enum('role', ['administrator', 'publisher'])->default('publisher');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->foreignId('publisher_id')->nullable()->constrained('publishers')->onDelete('set null');
            $table->text('permissions')->nullable(); // JSON field for specific permissions
            $table->timestamp('last_login_at')->nullable();
            $table->string('avatar')->nullable();
            $table->text('notes')->nullable();
            $table->rememberToken();
            $table->timestamps();
            
            // Add indexes
            $table->index(['role', 'status']);
            $table->index('publisher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

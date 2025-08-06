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
        // Create roles table first
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->json('permissions')->nullable(); // Store permissions as JSON
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create role_users pivot table
        if (!Schema::hasTable('role_users')) {
            Schema::create('role_users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->timestamp('assigned_at')->useCurrent();
                $table->timestamp('expires_at')->nullable();
                $table->json('additional_permissions')->nullable(); // Extra permissions for this user-role
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                // Prevent duplicate user-role assignments
                $table->unique(['user_id', 'role_id']);
            });
        }

        // Create permissions table for detailed permission management
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('display_name');
                $table->text('description')->nullable();
                $table->string('module')->nullable(); // e.g., 'journals', 'publishers', 'loa-requests'
                $table->string('action')->nullable(); // e.g., 'create', 'read', 'update', 'delete'
                $table->timestamps();
            });
        }

        // Create role_permissions pivot table
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('role_id')->constrained()->onDelete('cascade');
                $table->foreignId('permission_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['role_id', 'permission_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('roles');
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            // Add role and status columns if they don't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['administrator', 'publisher', 'user'])->default('user')->after('email_verified_at');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('role');
            }
            
            if (!Schema::hasColumn('users', 'publisher_id')) {
                $table->foreignId('publisher_id')->nullable()->constrained('publishers')->onDelete('set null')->after('status');
            }
            
            if (!Schema::hasColumn('users', 'permissions')) {
                $table->json('permissions')->nullable()->after('publisher_id');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('permissions');
            }
            
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('full_name');
            }
            
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('last_login_at');
            }
            
            // Add indexes
            $table->index(['role', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'status']);
            $table->dropColumn(['role', 'status', 'publisher_id', 'permissions', 'last_login_at', 'full_name', 'phone', 'avatar']);
        });
    }
};

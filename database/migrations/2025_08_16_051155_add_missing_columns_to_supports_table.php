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
        Schema::table('supports', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('supports', 'logo')) {
                $table->string('logo')->nullable()->after('name');
            }
            if (!Schema::hasColumn('supports', 'website')) {
                $table->string('website')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('supports', 'description')) {
                $table->text('description')->nullable()->after('website');
            }
            if (!Schema::hasColumn('supports', 'order')) {
                $table->integer('order')->default(0)->after('description');
            }
            if (!Schema::hasColumn('supports', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supports', function (Blueprint $table) {
            $table->dropColumn(['logo', 'website', 'description', 'order', 'is_active']);
        });
    }
};

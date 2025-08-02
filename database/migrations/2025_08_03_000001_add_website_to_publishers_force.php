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
        // Force add website column if not exists
        if (!Schema::hasColumn('publishers', 'website')) {
            Schema::table('publishers', function (Blueprint $table) {
                $table->string('website', 255)->nullable()->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('publishers', 'website')) {
            Schema::table('publishers', function (Blueprint $table) {
                $table->dropColumn('website');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loa_validated', function (Blueprint $table) {
            if (!Schema::hasColumn('loa_validated', 'journal_id')) {
                $table->unsignedBigInteger('journal_id')->nullable()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loa_validated', function (Blueprint $table) {
            if (Schema::hasColumn('loa_validated', 'journal_id')) {
                $table->dropColumn('journal_id');
            }
        });
    }
};

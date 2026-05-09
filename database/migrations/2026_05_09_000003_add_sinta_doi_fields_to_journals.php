<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->string('sinta_id')->nullable()->after('website');
            $table->string('doi_prefix')->nullable()->after('sinta_id');
            $table->string('garuda_id')->nullable()->after('doi_prefix');
            $table->enum('accreditation_level', ['none','S1','S2','S3','S4','S5','S6'])
                  ->default('none')->after('garuda_id');
        });
    }

    public function down(): void
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['sinta_id', 'doi_prefix', 'garuda_id', 'accreditation_level']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loa_validated', function (Blueprint $table) {
            $table->string('digital_signature', 64)->nullable()->after('verification_url');
            $table->timestamp('signed_at')->nullable()->after('digital_signature');
        });
    }

    public function down(): void
    {
        Schema::table('loa_validated', function (Blueprint $table) {
            $table->dropColumn(['digital_signature', 'signed_at']);
        });
    }
};

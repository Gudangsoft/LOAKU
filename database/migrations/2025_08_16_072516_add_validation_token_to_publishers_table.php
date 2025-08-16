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
        Schema::table('publishers', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'suspended'])->default('pending')->after('website');
            $table->string('validation_token', 8)->nullable()->after('status');
            $table->timestamp('validated_at')->nullable()->after('validation_token');
            $table->unsignedBigInteger('validated_by')->nullable()->after('validated_at');
            $table->text('validation_notes')->nullable()->after('validated_by');
            
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['status', 'validation_token', 'validated_at', 'validated_by', 'validation_notes']);
        });
    }
};

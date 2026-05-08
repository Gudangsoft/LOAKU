<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publisher_id')->constrained('publishers')->onDelete('cascade');
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->onDelete('restrict');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending_payment', 'proof_uploaded', 'confirmed', 'rejected'])
                  ->default('pending_payment');
            $table->text('admin_notes')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['publisher_id', 'status']);
        });

        Schema::table('publishers', function (Blueprint $table) {
            $table->foreignId('selected_plan_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('subscription_plans')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->dropForeign(['selected_plan_id']);
            $table->dropColumn('selected_plan_id');
        });
        Schema::dropIfExists('subscription_payments');
    }
};

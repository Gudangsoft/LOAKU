<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom features ke subscription_plans
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->json('features')->nullable()->after('sort_order')
                  ->comment('Array fitur: export_csv, custom_template, custom_domain, priority_support, analytics, white_label, api_access');
        });

        // Tambah kolom domain ke publishers
        Schema::table('publishers', function (Blueprint $table) {
            $table->string('subdomain', 100)->nullable()->unique()->after('selected_plan_id')
                  ->comment('Subdomain: namajournal.loa.siptenan.org');
            $table->string('custom_domain', 255)->nullable()->unique()->after('subdomain')
                  ->comment('Domain kustom: loa.namajournal.ac.id');
            $table->enum('domain_status', ['none', 'pending', 'active', 'rejected'])
                  ->default('none')->after('custom_domain');
            $table->text('domain_notes')->nullable()->after('domain_status');
            $table->timestamp('domain_approved_at')->nullable()->after('domain_notes');
            $table->foreignId('domain_approved_by')->nullable()->after('domain_approved_at')
                  ->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('features');
        });
        Schema::table('publishers', function (Blueprint $table) {
            $table->dropForeign(['domain_approved_by']);
            $table->dropColumn(['subdomain', 'custom_domain', 'domain_status', 'domain_notes', 'domain_approved_at', 'domain_approved_by']);
        });
    }
};

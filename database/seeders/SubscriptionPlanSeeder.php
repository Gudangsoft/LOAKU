<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'              => 'Gratis',
                'description'       => 'Cocok untuk mencoba sistem, tanpa biaya.',
                'price'             => 0,
                'max_journals'      => 1,
                'max_loa_per_month' => 10,
                'duration_months'   => 1,
                'is_active'         => true,
                'sort_order'        => 1,
                'features'          => [],
            ],
            [
                'name'              => 'Starter',
                'description'       => 'Ideal untuk publisher kecil dengan beberapa jurnal.',
                'price'             => 99000,
                'max_journals'      => 3,
                'max_loa_per_month' => 50,
                'duration_months'   => 12,
                'is_active'         => true,
                'sort_order'        => 2,
                'features'          => ['export_csv'],
            ],
            [
                'name'              => 'Professional',
                'description'       => 'Untuk publisher aktif yang butuh fleksibilitas dan branding sendiri.',
                'price'             => 299000,
                'max_journals'      => 10,
                'max_loa_per_month' => 200,
                'duration_months'   => 12,
                'is_active'         => true,
                'sort_order'        => 3,
                'features'          => ['export_csv', 'custom_template', 'custom_domain', 'analytics'],
            ],
            [
                'name'              => 'Enterprise',
                'description'       => 'Solusi lengkap untuk lembaga besar tanpa batas.',
                'price'             => 599000,
                'max_journals'      => null,
                'max_loa_per_month' => null,
                'duration_months'   => 12,
                'is_active'         => true,
                'sort_order'        => 4,
                'features'          => ['export_csv', 'custom_template', 'custom_domain', 'priority_support', 'analytics', 'white_label', 'api_access'],
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['name' => $plan['name']], $plan);
        }

        $this->command->info('✓ 4 contoh paket langganan berhasil dibuat.');
    }
}

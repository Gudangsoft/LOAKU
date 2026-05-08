<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    // Daftar semua fitur yang tersedia
    const FEATURES = [
        'export_csv'       => ['label' => 'Export Data CSV/Excel',          'icon' => 'fas fa-file-csv',        'color' => 'success'],
        'custom_template'  => ['label' => 'Template LOA Kustom',             'icon' => 'fas fa-file-code',       'color' => 'info'],
        'custom_domain'    => ['label' => 'Subdomain / Domain Kustom',       'icon' => 'fas fa-globe',           'color' => 'primary'],
        'priority_support' => ['label' => 'Prioritas Dukungan Admin',        'icon' => 'fas fa-headset',         'color' => 'warning'],
        'analytics'        => ['label' => 'Laporan & Analitik',              'icon' => 'fas fa-chart-bar',       'color' => 'purple'],
        'white_label'      => ['label' => 'Tanpa Branding LOA SIPTENAN',     'icon' => 'fas fa-tag',             'color' => 'secondary'],
        'api_access'       => ['label' => 'Akses API (Integrasi Sistem)',    'icon' => 'fas fa-code',            'color' => 'dark'],
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'max_journals',
        'max_loa_per_month',
        'duration_months',
        'is_active',
        'sort_order',
        'features',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'is_active'        => 'boolean',
        'max_journals'     => 'integer',
        'max_loa_per_month'=> 'integer',
        'duration_months'  => 'integer',
        'sort_order'       => 'integer',
        'features'         => 'array',
    ];

    public function subscriptions()
    {
        return $this->hasMany(PublisherSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasFeature(string $key): bool
    {
        return in_array($key, $this->features ?? []);
    }

    public function allowsCustomDomain(): bool
    {
        return $this->hasFeature('custom_domain');
    }

    public function getEnabledFeatures(): array
    {
        $enabled = [];
        foreach (self::FEATURES as $key => $meta) {
            if ($this->hasFeature($key)) {
                $enabled[$key] = $meta;
            }
        }
        return $enabled;
    }

    public function formattedPrice(): string
    {
        return $this->price == 0
            ? 'Gratis'
            : 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function maxJournalsLabel(): string
    {
        return $this->max_journals === null ? 'Tidak terbatas' : (string) $this->max_journals;
    }

    public function maxLoaPerMonthLabel(): string
    {
        return $this->max_loa_per_month === null ? 'Tidak terbatas' : (string) $this->max_loa_per_month;
    }
}

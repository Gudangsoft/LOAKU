<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'max_journals',
        'max_loa_per_month',
        'duration_months',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'max_journals' => 'integer',
        'max_loa_per_month' => 'integer',
        'duration_months' => 'integer',
        'sort_order' => 'integer',
    ];

    public function subscriptions()
    {
        return $this->hasMany(PublisherSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function formattedPrice(): string
    {
        if ($this->price == 0) {
            return 'Gratis';
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
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

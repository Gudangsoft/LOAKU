<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PublisherSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'publisher_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'status',
        'amount_paid',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    public function daysRemaining(): int
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('end_date', '>=', now()->toDateString());
    }

    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'expired')
              ->orWhere(function ($q2) {
                  $q2->where('status', 'active')->where('end_date', '<', now()->toDateString());
              });
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publisher extends Model
{
    use HasFactory;

    protected $table = 'publishers';

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'whatsapp',
        'email',
        'website',
        'logo',
        'selected_plan_id',
        'subdomain',
        'custom_domain',
        'domain_status',
        'domain_notes',
    ];

    protected static function booted(): void
    {
        static::creating(function (Publisher $publisher) {
            if (empty($publisher->slug)) {
                $publisher->slug = static::generateUniqueSlug($publisher->name, $publisher->id);
            }
        });

        static::updating(function (Publisher $publisher) {
            if ($publisher->isDirty('name') && empty($publisher->slug)) {
                $publisher->slug = static::generateUniqueSlug($publisher->name, $publisher->id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name) ?: 'publisher';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    protected $attributes = [
        'status' => 'pending',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(PublisherSubscription::class);
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function selectedPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'selected_plan_id');
    }

    public function latestPayment()
    {
        return $this->hasOne(SubscriptionPayment::class)->latest();
    }

    public function domainApprovedBy()
    {
        return $this->belongsTo(User::class, 'domain_approved_by');
    }

    public function hasDomainActive(): bool
    {
        return $this->domain_status === 'active' &&
               ($this->subdomain || $this->custom_domain);
    }

    public function getPublicDomainUrl(): ?string
    {
        if ($this->domain_status !== 'active') return null;
        if ($this->custom_domain) return 'https://' . $this->custom_domain;
        if ($this->subdomain) {
            $main = parse_url(config('app.url'), PHP_URL_HOST) ?? 'loa.siptenan.org';
            return 'https://' . $this->subdomain . '.' . $main;
        }
        return null;
    }

    public function canRequestDomain(): bool
    {
        $sub = $this->activeSubscription;
        return $sub && $sub->plan->allowsCustomDomain();
    }

    public function activeSubscription()
    {
        return $this->hasOne(PublisherSubscription::class)
            ->where('status', 'active')
            ->where('end_date', '>=', now()->toDateString())
            ->orderByDesc('end_date');
    }

    public function currentPlan(): ?SubscriptionPlan
    {
        return $this->activeSubscription?->plan;
    }

    public function canAddJournal(): bool
    {
        $sub = $this->activeSubscription;
        if (!$sub) {
            return false;
        }
        $max = $sub->plan->max_journals;
        if ($max === null) {
            return true;
        }
        return $this->journals()->count() < $max;
    }

    public function canSubmitLoa(): bool
    {
        $sub = $this->activeSubscription;
        if (!$sub) {
            return false;
        }
        $max = $sub->plan->max_loa_per_month;
        if ($max === null) {
            return true;
        }
        $journalIds = $this->journals()->pluck('id');
        $usedThisMonth = LoaRequest::whereIn('journal_id', $journalIds)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        return $usedThisMonth < $max;
    }

    /**
     * Validator relationship
     */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Check if publisher is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if publisher is pending validation
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if publisher is suspended
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Generate validation token
     */
    public function generateValidationToken()
    {
        $this->validation_token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        $this->save();
        return $this->validation_token;
    }

    /**
     * Activate publisher
     */
    public function activate($validatorId = null, $notes = null)
    {
        $this->status = 'active';
        $this->validated_at = now();
        $this->validated_by = $validatorId;
        $this->validation_notes = $notes;
        $this->save();
    }

    /**
     * Suspend publisher
     */
    public function suspend($validatorId = null, $reason = null)
    {
        $this->status = 'suspended';
        $this->validated_at = now();
        $this->validated_by = $validatorId;
        $this->validation_notes = $reason;
        $this->save();
    }
}

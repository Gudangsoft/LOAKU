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

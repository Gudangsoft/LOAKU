<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role_id',
        'assigned_at',
        'expires_at',
        'additional_permissions',
        'is_active'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'expires_at' => 'datetime',
        'additional_permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * User that has this role
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Role that is assigned
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if role assignment is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if role assignment is active and not expired
     */
    public function isActiveAndValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Get additional permissions for this user-role assignment
     */
    public function getAdditionalPermissions(): array
    {
        return $this->additional_permissions ?? [];
    }

    /**
     * Check if has specific additional permission
     */
    public function hasAdditionalPermission(string $permission): bool
    {
        return in_array($permission, $this->getAdditionalPermissions());
    }

    /**
     * Scope for active assignments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for non-expired assignments
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for valid assignments (active and not expired)
     */
    public function scopeValid($query)
    {
        return $query->active()->notExpired();
    }
}

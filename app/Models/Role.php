<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name', 
        'description',
        'permissions',
        'is_active'
    ];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Users that belong to this role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_users')
                    ->withPivot(['assigned_at', 'expires_at', 'additional_permissions', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Permissions that belong to this role
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * Role assignments (pivot records)
     */
    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Check in JSON permissions field
        if ($this->permissions && in_array($permission, $this->permissions)) {
            return true;
        }

        // Check in related permissions table
        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get all permissions (from JSON + related table)
     */
    public function getAllPermissions(): array
    {
        $jsonPermissions = $this->permissions ?? [];
        $relatedPermissions = $this->permissions()->pluck('name')->toArray();
        
        return array_unique(array_merge($jsonPermissions, $relatedPermissions));
    }

    /**
     * Scope for active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Default roles
     */
    public static function getDefaultRoles(): array
    {
        return [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Akses penuh ke semua fitur sistem',
                'permissions' => ['*'] // All permissions
            ],
            'administrator' => [
                'display_name' => 'Administrator', 
                'description' => 'Akses penuh untuk mengelola LOA dan data master',
                'permissions' => [
                    'loa-requests.view', 'loa-requests.create', 'loa-requests.edit', 'loa-requests.delete',
                    'loa-requests.approve', 'loa-requests.reject',
                    'journals.view', 'journals.create', 'journals.edit', 'journals.delete',
                    'publishers.view', 'publishers.create', 'publishers.edit', 'publishers.delete',
                    'templates.view', 'templates.create', 'templates.edit', 'templates.delete',
                    'users.view', 'users.create', 'users.edit', 'users.delete'
                ]
            ],
            'member' => [
                'display_name' => 'Member (Editor Jurnal)',
                'description' => 'Kelola publisher, jurnal, dan validasi LOA jurnal sendiri',
                'permissions' => [
                    'loa-requests.view', 'loa-requests.approve', 'loa-requests.reject',
                    'journals.view', 'journals.create', 'journals.edit',
                    'publishers.view', 'publishers.create', 'publishers.edit',
                    'templates.view'
                ]
            ],
            'viewer' => [
                'display_name' => 'Viewer',
                'description' => 'Hanya dapat melihat data tanpa mengubah',
                'permissions' => [
                    'loa-requests.view',
                    'journals.view', 
                    'publishers.view',
                    'templates.view'
                ]
            ]
        ];
    }
}

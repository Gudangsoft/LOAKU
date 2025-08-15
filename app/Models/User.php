<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'role', // Keep for backward compatibility
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Roles assigned to this user
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users')
                    ->withPivot(['assigned_at', 'expires_at', 'additional_permissions', 'is_active'])
                    ->withTimestamps();
    }

    /**
     * Active roles assigned to this user
     */
    public function activeRoles(): BelongsToMany
    {
        return $this->roles()->wherePivot('is_active', true)
                            ->where(function ($query) {
                                $query->whereNull('role_users.expires_at')
                                      ->orWhere('role_users.expires_at', '>', now());
                            });
    }

    /**
     * Role assignments (pivot records)
     */
    public function roleAssignments(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    /**
     * Active role assignments
     */
    public function activeRoleAssignments(): HasMany
    {
        return $this->roleAssignments()->valid();
    }

    /**
     * Publishers owned by this user
     */
    public function publishers(): HasMany
    {
        return $this->hasMany(Publisher::class);
    }

    /**
     * Journals owned by this user
     */
    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    /**
     * LOA requests for journals owned by this user
     */
    public function memberLoaRequests(): HasMany
    {
        return $this->hasManyThrough(LoaRequest::class, Journal::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        // Simple role check - prioritize is_admin and role field over complex relations
        if ($roleName === 'super_admin') {
            return $this->role === 'super_admin';
        }
        
        if ($roleName === 'administrator' || $roleName === 'admin') {
            return $this->is_admin || $this->role === 'administrator' || $this->role === 'admin';
        }
        
        if ($roleName === 'editor') {
            return $this->role === 'editor';
        }
        
        if ($roleName === 'viewer') {
            return $this->role === 'viewer';
        }
        
        if ($roleName === 'member') {
            return $this->role === 'member' || (!$this->is_admin && empty($this->role));
        }
        
        // Fallback: check direct role match
        return $this->role === $roleName;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given roles
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Check if user is super admin (has all permissions)
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // Check through roles
        foreach ($this->activeRoles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        // Check additional permissions in pivot table
        $roleAssignments = $this->activeRoleAssignments;
        foreach ($roleAssignments as $assignment) {
            if ($assignment->hasAdditionalPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has any of the given permissions
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
     * Check if user has all of the given permissions
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
     * Assign role to user
     */
    public function assignRole(string $roleName, array $options = []): RoleUser
    {
        $role = Role::where('name', $roleName)->firstOrFail();
        
        return RoleUser::firstOrCreate([
            'user_id' => $this->id,
            'role_id' => $role->id,
        ], array_merge([
            'assigned_at' => now(),
            'is_active' => true,
        ], $options));
    }

    /**
     * Remove role from user
     */
    public function removeRole(string $roleName): bool
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return false;
        }

        return $this->roleAssignments()
                    ->where('role_id', $role->id)
                    ->delete() > 0;
    }

    /**
     * Sync user roles
     */
    public function syncRoles(array $roles): void
    {
        // Remove all current roles
        $this->roleAssignments()->delete();
        
        // Add new roles
        foreach ($roles as $roleName) {
            $this->assignRole($roleName);
        }
    }

    /**
     * Get all user permissions
     */
    public function getAllPermissions(): array
    {
        $permissions = [];
        
        // Get permissions from roles
        foreach ($this->activeRoles as $role) {
            $permissions = array_merge($permissions, $role->getAllPermissions());
        }
        
        // Get additional permissions from pivot table
        foreach ($this->activeRoleAssignments as $assignment) {
            $permissions = array_merge($permissions, $assignment->getAdditionalPermissions());
        }
        
        return array_unique($permissions);
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Check if user is administrator (backward compatibility)
     */
    public function isAdministrator(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is admin (alias for isAdministrator)
     */
    public function isAdmin(): bool
    {
        return $this->isAdministrator();
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Get role badge CSS class for UI display
     */
    public function getRoleBadgeClass(): string
    {
        switch ($this->role) {
            case 'admin':
                return 'bg-danger';
            case 'publisher':
                return 'bg-warning';
            case 'member':
                return 'bg-primary';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Get role icon for UI display
     */
    public function getRoleIcon(): string
    {
        switch ($this->role) {
            case 'admin':
                return 'fas fa-user-shield';
            case 'publisher':
                return 'fas fa-newspaper';
            case 'member':
                return 'fas fa-user';
            default:
                return 'fas fa-question-circle';
        }
    }

    /**
     * Get role display name for UI
     */
    public function getRoleDisplayName(): string
    {
        switch ($this->role) {
            case 'admin':
                return 'Administrator';
            case 'publisher':
                return 'Publisher';
            case 'member':
                return 'Member';
            default:
                return 'Unknown';
        }
    }

    /**
     * Get primary role name
     */
    public function getPrimaryRole(): ?string
    {
        $activeRole = $this->activeRoles()->first();
        return $activeRole ? $activeRole->display_name : null;
    }

    /**
     * Get primary role name for display
     */
    public function getRoleDisplayNameAttribute(): string
    {
        return $this->getRoleDisplayName();
    }

    /**
     * Check if user is publisher
     */
    public function isPublisher(): bool
    {
        return $this->role === 'publisher';
    }

    /**
     * Check if user can manage publications
     */
    public function canManagePublications(): bool
    {
        return $this->isPublisher() || $this->isAdmin() || $this->is_admin;
    }

    /**
     * Check if user can validate LOA requests
     */
    public function canValidateLoaRequests(): bool
    {
        return $this->isPublisher() || $this->isAdmin() || $this->is_admin;
    }
}



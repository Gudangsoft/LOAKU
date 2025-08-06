<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'accounts';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'role',
        'status',
        'publisher_id',
        'permissions',
        'last_login_at',
        'avatar',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    // Role and Permission Methods
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdministrator()
    {
        return $this->role === 'administrator';
    }

    public function isPublisher()
    {
        return $this->role === 'publisher';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasPermission($permission)
    {
        if ($this->isAdministrator()) {
            return true; // Administrators have all permissions
        }

        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public function canManage($resource)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->isPublisher()) {
            // Publishers can only manage their own resources
            switch ($resource) {
                case 'journals':
                    return $this->hasPermission('manage_journals');
                case 'loa_requests':
                    return $this->hasPermission('manage_loa_requests');
                case 'templates':
                    return $this->hasPermission('manage_templates');
                default:
                    return false;
            }
        }

        return false;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAdministrators($query)
    {
        return $query->where('role', 'administrator');
    }

    public function scopePublishers($query)
    {
        return $query->where('role', 'publisher');
    }

    public function scopeByPublisher($query, $publisherId)
    {
        return $query->where('publisher_id', $publisherId);
    }

    // Accessors
    public function getRoleNameAttribute()
    {
        return match($this->role) {
            'administrator' => 'Administrator',
            'publisher' => 'Publisher',
            default => 'User'
        };
    }

    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'suspended' => 'Ditangguhkan',
            default => 'Unknown'
        };
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Default avatar based on role
        return match($this->role) {
            'administrator' => asset('images/default-admin-avatar.png'),
            'publisher' => asset('images/default-publisher-avatar.png'),
            default => asset('images/default-user-avatar.png')
        };
    }

    // Static methods for role management
    public static function getAvailableRoles()
    {
        return [
            'administrator' => 'Administrator',
            'publisher' => 'Publisher',
        ];
    }

    public static function getAvailableStatuses()
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'suspended' => 'Ditangguhkan',
        ];
    }

    public static function getDefaultPermissions($role)
    {
        return match($role) {
            'administrator' => [
                'manage_users',
                'manage_accounts',
                'manage_publishers', 
                'manage_journals',
                'manage_loa_requests',
                'manage_templates',
                'view_analytics',
                'system_settings'
            ],
            'publisher' => [
                'manage_journals',
                'manage_loa_requests',
                'manage_templates',
                'view_publisher_analytics'
            ],
            default => []
        };
    }
}

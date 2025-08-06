<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description', 
        'module',
        'action'
    ];

    /**
     * Roles that have this permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withTimestamps();
    }

    /**
     * Get default permissions for the system
     */
    public static function getDefaultPermissions(): array
    {
        return [
            // LOA Requests
            ['name' => 'loa-requests.view', 'display_name' => 'View LOA Requests', 'module' => 'loa-requests', 'action' => 'view'],
            ['name' => 'loa-requests.create', 'display_name' => 'Create LOA Requests', 'module' => 'loa-requests', 'action' => 'create'],
            ['name' => 'loa-requests.edit', 'display_name' => 'Edit LOA Requests', 'module' => 'loa-requests', 'action' => 'edit'],
            ['name' => 'loa-requests.delete', 'display_name' => 'Delete LOA Requests', 'module' => 'loa-requests', 'action' => 'delete'],
            ['name' => 'loa-requests.approve', 'display_name' => 'Approve LOA Requests', 'module' => 'loa-requests', 'action' => 'approve'],
            ['name' => 'loa-requests.reject', 'display_name' => 'Reject LOA Requests', 'module' => 'loa-requests', 'action' => 'reject'],

            // Journals
            ['name' => 'journals.view', 'display_name' => 'View Journals', 'module' => 'journals', 'action' => 'view'],
            ['name' => 'journals.create', 'display_name' => 'Create Journals', 'module' => 'journals', 'action' => 'create'],
            ['name' => 'journals.edit', 'display_name' => 'Edit Journals', 'module' => 'journals', 'action' => 'edit'],
            ['name' => 'journals.delete', 'display_name' => 'Delete Journals', 'module' => 'journals', 'action' => 'delete'],

            // Publishers
            ['name' => 'publishers.view', 'display_name' => 'View Publishers', 'module' => 'publishers', 'action' => 'view'],
            ['name' => 'publishers.create', 'display_name' => 'Create Publishers', 'module' => 'publishers', 'action' => 'create'],
            ['name' => 'publishers.edit', 'display_name' => 'Edit Publishers', 'module' => 'publishers', 'action' => 'edit'],
            ['name' => 'publishers.delete', 'display_name' => 'Delete Publishers', 'module' => 'publishers', 'action' => 'delete'],

            // Templates
            ['name' => 'templates.view', 'display_name' => 'View Templates', 'module' => 'templates', 'action' => 'view'],
            ['name' => 'templates.create', 'display_name' => 'Create Templates', 'module' => 'templates', 'action' => 'create'],
            ['name' => 'templates.edit', 'display_name' => 'Edit Templates', 'module' => 'templates', 'action' => 'edit'],
            ['name' => 'templates.delete', 'display_name' => 'Delete Templates', 'module' => 'templates', 'action' => 'delete'],

            // Users Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users', 'action' => 'delete'],

            // System Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings', 'action' => 'view'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings', 'action' => 'edit'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports', 'action' => 'view'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'module' => 'reports', 'action' => 'export'],
        ];
    }
}

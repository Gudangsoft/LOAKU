<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating permissions...');
        
        // Create all permissions
        $permissions = Permission::getDefaultPermissions();
        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                $permissionData
            );
        }

        $this->command->info('Created ' . count($permissions) . ' permissions.');

        $this->command->info('Creating roles...');

        // Create roles
        $roles = Role::getDefaultRoles();
        foreach ($roles as $roleName => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                    'permissions' => $roleData['permissions'],
                    'is_active' => true
                ]
            );

            // Attach permissions to role (if not using wildcard)
            if ($roleData['permissions'] !== ['*']) {
                $permissionIds = Permission::whereIn('name', $roleData['permissions'])->pluck('id');
                $role->permissions()->sync($permissionIds);
            }

            $this->command->info("Created role: {$roleData['display_name']}");
        }

        $this->command->info('Assigning roles to existing users...');

        // Assign roles to existing admin users
        $adminUsers = User::where('is_admin', true)->get();
        foreach ($adminUsers as $user) {
            // Determine role based on existing role field or default to administrator
            $roleName = match($user->role ?? 'administrator') {
                'super_admin' => 'super_admin',
                'administrator' => 'administrator',
                'admin' => 'administrator',
                'member' => 'member',
                default => 'administrator'
            };

            try {
                $user->assignRole($roleName);
                $this->command->info("Assigned role '$roleName' to user: {$user->email}");
            } catch (\Exception $e) {
                $this->command->warn("Failed to assign role to {$user->email}: " . $e->getMessage());
            }
        }

        // Create default super admin if not exists
        $superAdmin = User::where('email', 'admin@loasiptenan.com')->first();
        if (!$superAdmin) {
            $superAdmin = User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@loasiptenan.com',
                'password' => \Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'super_admin',
                'email_verified_at' => now()
            ]);
            
            $superAdmin->assignRole('super_admin');
            $this->command->info('Created Super Admin: admin@loasiptenan.com / admin123');
        }

        $this->command->info('Role and Permission seeding completed!');
        
        // Display summary
        $this->command->table(
            ['Role', 'Display Name', 'Users Count', 'Permissions Count'],
            Role::with(['users', 'permissions'])->get()->map(function ($role) {
                return [
                    $role->name,
                    $role->display_name,
                    $role->users()->count(),
                    $role->permissions === ['*'] ? 'ALL' : count($role->permissions ?? [])
                ];
            })
        );
    }
}

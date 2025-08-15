<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::getDefaultPermissions();
        foreach ($permissions as $perm) {
            Permission::updateOrCreate([
                'name' => $perm['name']
            ], $perm);
        }
    }
}

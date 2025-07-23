<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions grouped by role
        $permissionsByRole = [
            'Accounts Manager' => [
                // Setting Permissions
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
                // Category Permissions
                ['name' => 'category-menu', 'group' => 'category'],
                ['name' => 'category-list', 'group' => 'category'],
                ['name' => 'category-create', 'group' => 'category'],
                ['name' => 'category-edit', 'group' => 'category'],
                ['name' => 'category-view', 'group' => 'category'],
                ['name' => 'category-delete', 'group' => 'category'],

            ],
            'Inventory Manager' => [
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
                // Category Permissions
                ['name' => 'category-menu', 'group' => 'category'],
                ['name' => 'category-list', 'group' => 'category'],
                ['name' => 'category-create', 'group' => 'category'],
                ['name' => 'category-edit', 'group' => 'category'],
                ['name' => 'category-view', 'group' => 'category'],
                ['name' => 'category-delete', 'group' => 'category'],
            ],
            'HR Manager' => [
                ['name' => 'dashboard-menu', 'group' => 'settings'],
                ['name' => 'setting-menu', 'group' => 'settings'],
                ['name' => 'setting-information', 'group' => 'settings'],
                ['name' => 'setting-information-edit', 'group' => 'settings'],
                ['name' => 'profile-view', 'group' => 'settings'],
                ['name' => 'password-change', 'group' => 'settings'],
            ],
        ];

        // Loop through and assign permissions to roles
        foreach ($permissionsByRole as $roleName => $permissions) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName]);

            $permissionNames = [];

            foreach ($permissions as $permissionData) {
                // Create or update permission
                $permission = \Spatie\Permission\Models\Permission::updateOrCreate(
                    ['name' => $permissionData['name']],
                    ['group' => $permissionData['group']]
                );

                $permissionNames[] = $permission->name;
            }

            // Sync all permissions to this role
            $role->syncPermissions($permissionNames);
        }
    }
}

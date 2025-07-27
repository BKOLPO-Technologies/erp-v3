<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add the admin user
        $usersData = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@bkolpo.com',
                'password' => '12345678',
                'role' => 'superadmin'
            ],
            [
                'name' => 'Accounts Manager',
                'email' => 'accounts@bkolpo.com',
                'password' => 'accounts@123',
                'role' => 'accounts'
            ],
            [
                'name' => 'Inventory Manager',
                'email' => 'inventory@bkolpo.com',
                'password' => 'inventory@123',
                'role' => 'inventory'
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr@bkolpo.com',
                'password' => 'hr@123',
                'role' => 'hr'
            ],
            [
                'name' => 'Payroll Officer',
                'email' => 'payroll@bkolpo.com',
                'password' => 'payroll@103',
                'role' => 'payroll'
            ],
            [
                'name' => 'Ecommerce Admin',
                'email' => 'ecommerce@bkolpo.com',
                'password' => 'ecommerce@123',
                'role' => 'ecommerce'
            ],
            [
                'name' => 'Process Admin',
                'email' => 'process@bkolpo.com',
                'password' => 'process@123',
                'role' => 'process'
            ],
        ];


        // Define the roles
        $roles = [
            ['id' => 1, 'name' => 'superadmin'],
            ['id' => 2, 'name' => 'accounts'],
            ['id' => 3, 'name' => 'inventory'],
            ['id' => 4, 'name' => 'hr'],
            ['id' => 5, 'name' => 'payroll'],
            ['id' => 6, 'name' => 'ecommerce'],
            ['id' => 7, 'name' => 'process'],
        ];


        // Create or update roles
        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']]
            );
        }

        // Create users and assign roles
        foreach ($usersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => $data['role'], 
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'show_password' => $data['password'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
            
            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }
        }

        // Assign all permissions to Super Admin role
        $adminRole = Role::where('name', 'superadmin')->where('guard_name', 'web')->first();

        if ($adminRole) {
            $permissions = Permission::pluck('id', 'id')->all();
            $adminRole->syncPermissions($permissions);
        }

        // Create Nazrul user
        $nazrulUser = User::updateOrCreate(
            ['email' => 'nsuzon02@gmail.com'],
            [
                'name' => 'Nazrul Islam',
                'email' => 'nsuzon02@gmail.com',
                'password' => Hash::make('12345678'),
                'show_password' => '12345678',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Find or create the "dashboard-menu" permission
        $dashboardPermission = Permission::firstOrCreate(['name' => 'dashboard-menu']);

        // Remove all existing permissions for Nazrul
        $nazrulUser->permissions()->detach();

        // Assign only "dashboard-menu" permission to Nazrul
        $nazrulUser->givePermissionTo($dashboardPermission);
    }
}
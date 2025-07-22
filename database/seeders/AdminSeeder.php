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
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Accounts Manager',
                'email' => 'accounts@bkolpo.com',
                'password' => 'accounts@123',
                'role' => 'Accounts Manager'
            ],
            [
                'name' => 'Inventory Manager',
                'email' => 'inventory@bkolpo.com',
                'password' => 'inventory@123',
                'role' => 'Inventory Manager'
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr@bkolpo.com',
                'password' => 'hr@123',
                'role' => 'HR Manager'
            ],
            [
                'name' => 'Payroll Officer',
                'email' => 'payroll@bkolpo.com',
                'password' => 'payroll@103',
                'role' => 'Payroll Officer'
            ],
            [
                'name' => 'Ecommerce Admin',
                'email' => 'ecommerce@bkolpo.com',
                'password' => 'ecommerce@123',
                'role' => 'Ecommerce Admin'
            ],
            [
                'name' => 'Process Admin',
                'email' => 'process@bkolpo.com',
                'password' => 'process@123',
                'role' => 'Process Admin'
            ],
        ];

        // Define the roles
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Accounts Manager'],
            ['id' => 3, 'name' => 'Inventory Manager'],
            ['id' => 4, 'name' => 'HR Manager'],
            ['id' => 5, 'name' => 'Payroll Officer'],
            ['id' => 6, 'name' => 'Ecommerce Admin'],
            ['id' => 7, 'name' => 'Process Admin'],
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
        $adminRole = Role::findByName('Super Admin');
        $permissions = Permission::pluck('id','id')->all();
        $adminRole->syncPermissions($permissions);

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
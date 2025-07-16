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
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@bkolpo.com',
                'password' => 'Admin@123#!',
            ],
            [
                'name' => 'Accounts Manager',
                'email' => 'accounts@bkolpo.com',
                'password' => 'accounts@123',
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr@bkolpo.com',
                'password' => 'hr@123',
            ],
            [
                'name' => 'Payroll Officer',
                'email' => 'payroll@bkolpo.com',
                'password' => 'payroll@103',
            ],
            [
                'name' => 'Inventory Manager',
                'email' => 'inventory@bkolpo.com',
                'password' => 'inventory@123',
            ],
            [
                'name' => 'Ecommerce Admin',
                'email' => 'ecommerce@bkolpo.com',
                'password' => 'ecommerce@123',
            ],
            [
                'name' => 'Process Admin',
                'email' => 'process@bkolpo.com',
                'password' => 'process@123',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'show_password' => $data['password'], // Optional, for testing/admin viewing
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }


        // $user = User::updateOrCreate(
        //     ['email' => 'superadmin@bkolpo.com'],
        //     [
        //         'name' => 'Super Admin',
        //         'email' => 'superadmin@bkolpo.com',
        //         'password' => Hash::make('Admin@123#!'),
        //         'show_password' => 'Admin@123#!',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ]
        // );

        
        // Define the roles
        $roles = [
            ['id' => 1, 'name' => 'Super Admin'],
            ['id' => 2, 'name' => 'Admin'],
            ['id' => 3, 'name' => 'Inventory Manager'],
            ['id' => 4, 'name' => 'Sales Team'],
            ['id' => 5, 'name' => 'Sales Manager'],
            ['id' => 6, 'name' => 'Business Manager'],
            ['id' => 7, 'name' => 'Business Owner'],
            ['id' => 8, 'name' => 'Project Manager'],
            
        ];

        // Create or update roles
        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['id' => $roleData['id']],
                ['name' => $roleData['name']]
            );
        }

        // Assign permissions to the Admin role
        $adminRole = Role::findByName('Super Admin');
        $permissions = Permission::pluck('id','id')->all();
        $adminRole->syncPermissions($permissions);

        // Assign the Admin role to the user
        $user->assignRole($adminRole);

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

        // ❌ Remove all existing permissions for Nazrul (if any)
        $nazrulUser->permissions()->detach();

        // ✅ Assign only "dashboard-menu" permission to Nazrul
        $nazrulUser->givePermissionTo($dashboardPermission);
    }
}

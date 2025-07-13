<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        // Define sample leave types
        $leaveTypes = [
            [
                'name' => 'Sick Leave',
                'days_allowed' => 10,
                'status' => 1, // Active leave type
            ],
            [
                'name' => 'Casual Leave',
                'days_allowed' => 7,
                'status' => 1, // Active leave type
            ],
            [
                'name' => 'Annual Leave',
                'days_allowed' => 15,
                'status' => 1, // Active leave type
            ],
            [
                'name' => 'Maternity Leave',
                'days_allowed' => 90,
                'status' => 0, // Inactive leave type
            ],
            [
                'name' => 'Paternity Leave',
                'days_allowed' => 5,
                'status' => 1, // Active leave type
            ],
        ];

        // Insert or update the leave types
        foreach ($leaveTypes as $leaveType) {
            DB::table('leave_types')->updateOrInsert(
                ['name' => $leaveType['name']], // Search condition
                [ // Values to insert or update
                    'days_allowed' => $leaveType['days_allowed'],
                    'status' => $leaveType['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}


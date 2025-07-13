<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample shift types
        $shiftTypes = [
            [
                'name' => 'Morning',
                'status' => 1, // Active shift type
            ],
            [
                'name' => 'Evening',
                'status' => 1, // Active shift type
            ],
            [
                'name' => 'Night',
                'status' => 1, // Active shift type
            ],
        ];

        // Insert or update the shift types
        foreach ($shiftTypes as $shiftType) {
            DB::table('shifts')->updateOrInsert(
                ['name' => $shiftType['name']], // Search condition
                [ // Values to insert or update
                    'status' => $shiftType['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

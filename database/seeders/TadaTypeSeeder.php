<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TadaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define sample ta da types
        $tadaTypes = [
            [
                'name' => 'Transport',
                'status' => 1, 
            ],
            [
                'name' => 'Medical',
                'status' => 1,
            ],
            [
                'name' => 'Food',
                'status' => 1,
            ],
            [
                'name' => 'Others',
                'status' => 1,
            ]
        ];

        // Insert or update the ta da types
        foreach ($tadaTypes as $tadaType) {
            DB::table('ta_da_types')->updateOrInsert(
                ['name' => $tadaType['name']], // Search condition
                [ // Values to insert or update
                    'status' => $tadaType['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}

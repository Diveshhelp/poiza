<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $departments = [
            'Human Resources',
            'Information Technology',
            'Finance',
            'Marketing',
            'Sales',
            'Operations',
            'Research & Development',
            'Customer Service',
            'Legal',
            'Administration'
        ];

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'uuid' => (string) Str::uuid(),
                'department_name' => $department,
                'row_status' => 1, // 1 for active
                'created_by' => 1, // Assuming admin user has ID 1
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}

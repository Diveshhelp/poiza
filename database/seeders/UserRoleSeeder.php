<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
   
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => '1'],
            ['name' => 'Admin', 'slug' => '2'],
            ['name' => 'Department Head', 'slug' => '3'],
            ['name' => 'Department Work', 'slug' => '4'],
            ['name' => 'Employee', 'slug' => '5']
        ];
        DB::table('roles')->truncate();
        foreach ($roles as $role) {
            DB::table('roles')->insert([
                'name' => $role['name'],
                'slug' => $role['slug'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
    }
}
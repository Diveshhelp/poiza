<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Hash;
use Illuminate\Database\Seeder;
use Str;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'current_team_id' => null,
            'profile_photo_path' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'uuid' => (string) Str::uuid(),
            'security_code' => Str::random(6),
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        // Create multiple test users using factory
        User::factory()->count(10)->create()->each(function ($user) {
            $user->update([
                'uuid' => (string) Str::uuid(),
                'security_code' => Str::random(6)
            ]);
        });
    }
}

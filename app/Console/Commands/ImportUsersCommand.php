<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

class ImportUsersCommand extends Command
{
    protected $signature = 'import:users';
    protected $description = 'Import users from the source database';

    public function handle()
    {
        $this->info('Starting user import...');
        
        // Get all users from the source database
        $sourceUsers = DB::connection('second_db')
            ->table('users')
            ->get();
            
        $this->info("Found {$sourceUsers->count()} users to import.");
        
        $imported = 0;
        $skipped = 0;
        
        // Process each user
        foreach ($sourceUsers as $sourceUser) {
            // Check if user already exists (by email)
            $existingUser = User::where('email', $sourceUser->email)->first();
            
            // Create data array with proper handling of missing fields
            $userData = [
                'name' => $sourceUser->name,
                'email' => $sourceUser->email,
                'email_verified_at' => $sourceUser->email_verified_at,
                'password' => $sourceUser->password, // Assuming password is already hashed
                'remember_token' => $sourceUser->remember_token,
                'created_at' => $sourceUser->created_at,
                'updated_at' => now(),
            ];
            
            // Handle two factor fields
            if (property_exists($sourceUser, 'two_factor_secret')) {
                $userData['two_factor_secret'] = $sourceUser->two_factor_secret;
            }
            
            if (property_exists($sourceUser, 'two_factor_recovery_codes')) {
                $userData['two_factor_recovery_codes'] = $sourceUser->two_factor_recovery_codes;
            }
            
            if (property_exists($sourceUser, 'two_factor_confirmed_at')) {
                $userData['two_factor_confirmed_at'] = $sourceUser->two_factor_confirmed_at;
            }
            
            // Handle fields that exist in destination but may not exist in source
            if (Schema::hasColumn('users', 'current_team_id') && property_exists($sourceUser, 'current_team_id')) {
                $userData['current_team_id'] = $sourceUser->current_team_id;
            }
            // Handle fields that exist only in source (if they exist in destination schema now)
            if (Schema::hasColumn('users', 'mobile') && property_exists($sourceUser, 'mobile')) {
                $userData['mobile'] = $sourceUser->mobile;
            }
            
            if (Schema::hasColumn('users', 'status') && property_exists($sourceUser, 'status')) {
                $userData['status'] = $sourceUser->status;
            }
            
            
            if (Schema::hasColumn('users', 'security_code')) {
                $userData['security_code'] = property_exists($sourceUser, 'security_code') ? $sourceUser->security_code : Str::random(6);
            }
            
            if (Schema::hasColumn('users', 'user_role')) {
                $userData['user_role'] = property_exists($sourceUser, 'user_role') ? $sourceUser->user_role : 'user';
            }
                
            if ($existingUser) {
                // Update existing user
                $existingUser->update($userData);
                
                $skipped++;
                $this->line("Updated existing user: {$sourceUser->email}");
            } else {
                // Create new user
                User::create($userData);
                
                $imported++;
                
                if ($imported % 100 === 0) {
                    $this->info("Imported {$imported} users so far...");
                }
            }
        }
        
        $this->info("Import completed! Imported {$imported} new users, updated {$skipped} existing users.");
    }
}
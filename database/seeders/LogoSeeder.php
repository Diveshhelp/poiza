<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LogoSeeder extends Seeder
{
   
    public function run(): void
    {
        $roles = [
            ['logo_url' => 'https://pfconsultantraj.com/wp-content/uploads/2020/09/Logo-1.svg', 'active' => '1'],
            ['logo_url' => 'https://www.adityajewellers.com/media/full.png', 'active' => '1'],
            ['logo_url' => 'https://cdn.prod.website-files.com/6422f4c111e30d1daa9dd518/6458f75dc19e765418cce46c_logo_color_bgtransparent_h_cropped.svg', 'active' => '1'],
            ['logo_url' => 'https://cdn.prod.website-files.com/611514aa1abaaf105e65a3dc/611516cdaf542cf3f579c356_ShoppingResult-logo-light.svg', 'active' => '1']
        ];
        DB::table('client_logos')->truncate();
        foreach ($roles as $role) {
            DB::table('client_logos')->insert([
                'logo_url' => $role['logo_url'],
                'active' => $role['active'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
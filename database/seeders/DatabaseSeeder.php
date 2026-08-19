<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user (Super Admin - Pemilik buyle.id)
        User::updateOrCreate(['email' => 'admin@buyle.id'], [
            'name'     => 'Super Admin buyle.id',
            'password' => Hash::make('Admin@buyle.id2026'),
            'role'     => 'super_admin',
            'is_active'=> true,
        ]);

        // Akun Creator/Seller (Contoh)
        User::updateOrCreate(['email' => 'creator@buyle.id'], [
            'name'     => 'Digital Creator',
            'password' => Hash::make('Creator123!'),
            'role'     => 'seller',
            'is_active'=> true,
        ]);

        // Akun Buyer/Pembeli (Contoh)
        User::updateOrCreate(['email' => 'pembeli@buyle.id'], [
            'name'     => 'John Doe',
            'password' => Hash::make('Pembeli123!'),
            'role'     => 'buyer',
            'is_active'=> true,
        ]);

        // Settings
        $settings = [
            // Identitas Utama
            ['key'=>'site_name','value'=>'buyle.id','type'=>'text','group'=>'general','label'=>'Site Name'],
            ['key'=>'domain','value'=>'buyle.id','type'=>'text','group'=>'general','label'=>'Domain'],
            ['key'=>'tagline','value'=>'The Multi-Creator Marketplace','type'=>'text','group'=>'general','label'=>'Tagline'],
            
            // Theme Colors
            ['key'=>'color_accent','value'=>'#6366f1','type'=>'text','group'=>'theme','label'=>'Accent Color'],
            ['key'=>'color_main','value'=>'#FFFFFF','type'=>'text','group'=>'theme','label'=>'Main Background Color'],
            ['key'=>'color_text','value'=>'#0f172a','type'=>'text','group'=>'theme','label'=>'Main Text Color'],
        ];
        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // =====================================================================
        // Marketplace Seeders
        // =====================================================================
        $this->call([
            ProductCategorySeeder::class,
        ]);
    }
}


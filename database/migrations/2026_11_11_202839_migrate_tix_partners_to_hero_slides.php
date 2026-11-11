<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate existing tix_partner_1/2/3 from settings table into hero_slides table
     * with position='partner'. Skips if already migrated (idempotent).
     */
    public function up(): void
    {
        // Skip if already migrated (ada partner di hero_slides)
        $alreadyExists = DB::table('hero_slides')
            ->where('position', 'partner')
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $order = 1;
        for ($p = 1; $p <= 3; $p++) {
            $name = DB::table('settings')->where('key', "tix_partner_{$p}_name")->value('value');
            $logo = DB::table('settings')->where('key', "tix_partner_{$p}_logo")->value('value');
            $url  = DB::table('settings')->where('key', "tix_partner_{$p}_url")->value('value') ?? '#';

            if (!empty($name)) {
                DB::table('hero_slides')->insert([
                    'position'   => 'partner',
                    'title'      => $name,
                    'image'      => $logo ?: null,
                    'button_url' => $url,
                    'order'      => $order++,
                    'is_active'  => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Tidak dihapus saat rollback — data partner tetap aman
    }
};

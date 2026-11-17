<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Singkronkan users.username dan media_partners.code dengan creator_profiles.store_slug (slug valid)
     */
    public function up(): void
    {
        $profiles = DB::table('creator_profiles')
            ->whereNotNull('store_slug')
            ->where('store_slug', '!=', '')
            ->get(['user_id', 'store_slug']);

        foreach ($profiles as $p) {
            $slug = strtolower(trim($p->store_slug));
            if (empty($slug)) {
                continue;
            }

            DB::table('users')
                ->where('id', $p->user_id)
                ->update([
                    'username'      => $slug,
                    'referral_code' => $slug,
                ]);

            DB::table('media_partners')
                ->where('user_id', $p->user_id)
                ->update([
                    'code' => $slug,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};

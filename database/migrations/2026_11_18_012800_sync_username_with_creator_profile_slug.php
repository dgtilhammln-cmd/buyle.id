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

            // Jika ada user lain yang menduduki username ini, lepaskan agar pemilik store_slug valid yang memegang
            $otherUser = DB::table('users')
                ->where('username', $slug)
                ->where('id', '!=', $p->user_id)
                ->first();

            if ($otherUser) {
                DB::table('users')
                    ->where('id', $otherUser->id)
                    ->update([
                        'username'      => $slug . '_' . $otherUser->id,
                        'referral_code' => $slug . '_' . $otherUser->id,
                    ]);
            }

            // Jika ada media partner lain yang menduduki code ini, lepaskan
            $otherMp = DB::table('media_partners')
                ->where('code', $slug)
                ->where('user_id', '!=', $p->user_id)
                ->first();

            if ($otherMp) {
                DB::table('media_partners')
                    ->where('id', $otherMp->id)
                    ->update([
                        'code' => $slug . '_' . $otherMp->id,
                    ]);
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

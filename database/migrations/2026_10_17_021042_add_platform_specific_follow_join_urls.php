<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('follow_instagram_url', 500)->nullable()->after('community_join_url');
            $table->string('follow_tiktok_url', 500)->nullable()->after('follow_instagram_url');
            $table->string('follow_x_url', 500)->nullable()->after('follow_tiktok_url');
            $table->string('follow_youtube_url', 500)->nullable()->after('follow_x_url');
            $table->string('join_whatsapp_url', 500)->nullable()->after('follow_youtube_url');
            $table->string('join_telegram_url', 500)->nullable()->after('join_whatsapp_url');
            $table->string('join_discord_url', 500)->nullable()->after('join_telegram_url');
        });

        // Migrate existing legacy data if any
        try {
            $products = DB::table('products')->whereNotNull('social_follow_url')->orWhereNotNull('community_join_url')->get();
            foreach ($products as $p) {
                $updates = [];
                if (!empty($p->social_follow_url)) {
                    $url = strtolower($p->social_follow_url);
                    if (str_contains($url, 'tiktok')) {
                        $updates['follow_tiktok_url'] = $p->social_follow_url;
                    } elseif (str_contains($url, 'twitter') || str_contains($url, 'x.com')) {
                        $updates['follow_x_url'] = $p->social_follow_url;
                    } elseif (str_contains($url, 'youtube') || str_contains($url, 'youtu.be')) {
                        $updates['follow_youtube_url'] = $p->social_follow_url;
                    } else {
                        $updates['follow_instagram_url'] = $p->social_follow_url;
                    }
                }
                if (!empty($p->community_join_url)) {
                    $url = strtolower($p->community_join_url);
                    if (str_contains($url, 't.me') || str_contains($url, 'telegram')) {
                        $updates['join_telegram_url'] = $p->community_join_url;
                    } elseif (str_contains($url, 'discord')) {
                        $updates['join_discord_url'] = $p->community_join_url;
                    } else {
                        $updates['join_whatsapp_url'] = $p->community_join_url;
                    }
                }
                if (!empty($updates)) {
                    DB::table('products')->where('id', $p->id)->update($updates);
                }
            }
        } catch (\Throwable $e) {
            // Ignore during silent migration
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'follow_instagram_url',
                'follow_tiktok_url',
                'follow_x_url',
                'follow_youtube_url',
                'join_whatsapp_url',
                'join_telegram_url',
                'join_discord_url',
            ]);
        });
    }
};

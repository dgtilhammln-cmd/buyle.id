<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CreatorBioBlock;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CacheBioVideoThumbnails extends Command
{
    protected $signature = 'bio:cache-video-thumbs {--force : Force re-download even if already cached}';
    protected $description = 'Download and permanently cache thumbnails for TikTok and Instagram bio video blocks';

    public function handle(): int
    {
        $this->info('Scanning bio video blocks for TikTok & Instagram...');

        // Ensure target directory exists
        Storage::disk('public')->makeDirectory('bio/video_thumbs');

        $blocks = CreatorBioBlock::whereIn('type', ['tiktok', 'reels'])
            ->orWhere('url', 'LIKE', '%tiktok.com%')
            ->orWhere('url', 'LIKE', '%instagram.com%')
            ->get();

        $this->info("Found {$blocks->count()} video blocks to check.");
        $successCount = 0;

        foreach ($blocks as $block) {
            $url = trim($block->url ?? '');
            if (empty($url)) continue;

            $data = $block->data_json ?? [];
            $existing = $data['image'] ?? null;
            $isTiktok = ($block->type === 'tiktok') || str_contains($url, 'tiktok.com');
            $prefix = $isTiktok ? 'tt' : 'ig';
            $hash = md5($url);
            $localRel = "bio/video_thumbs/{$prefix}_{$hash}.jpg";

            // If already cached locally and not forcing
            if (!$this->option('force') && !empty($existing) && !Str::startsWith($existing, 'http') && Storage::disk('public')->exists($existing)) {
                $this->line("  [SKIP] Block #{$block->id} already cached: {$existing}");
                continue;
            }

            if (!$this->option('force') && Storage::disk('public')->exists($localRel)) {
                $data['image'] = $localRel;
                $block->update(['data_json' => $data]);
                $this->line("  [ATTACH] Block #{$block->id} attached existing local file: {$localRel}");
                $successCount++;
                continue;
            }

            $thumbUrl = null;

            if ($isTiktok) {
                // Method 1: TikTok oEmbed
                try {
                    $res = Http::timeout(10)->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Accept'     => 'application/json',
                    ])->get('https://www.tiktok.com/oembed', ['url' => $url]);

                    if ($res->successful()) {
                        $thumbUrl = $res->json('thumbnail_url');
                        if (empty($block->title) && $res->json('title')) {
                            $block->update(['title' => Str::limit($res->json('title'), 100)]);
                        }
                    }
                } catch (\Throwable $e) {}

                // Method 2: Fallback crawler
                if (!$thumbUrl) {
                    try {
                        $cRes = Http::timeout(8)->withHeaders([
                            'User-Agent' => 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)',
                        ])->get($url);
                        if ($cRes->ok() && preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](https?:[^"\']+)["\']/', $cRes->body(), $m)) {
                            $thumbUrl = html_entity_decode($m[1]);
                        }
                    } catch (\Throwable $e) {}
                }
            } else {
                // Instagram Reels
                if (preg_match('/instagram\.com\/(?:reel|reels|p)\/([A-Za-z0-9_-]+)/i', $url, $m)) {
                    $shortcode = $m[1];
                    try {
                        $embedRes = Http::timeout(8)->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.5 Mobile/15E148 Safari/604.1',
                        ])->get("https://www.instagram.com/p/{$shortcode}/embed/captioned/");

                        if ($embedRes->ok() && preg_match_all('#https?:\\\\?/\\\\?/[^"\'\s>]*(?:fbcdn|scontent|cdninstagram)[^"\'\s>]*\.jpg[^"\'\s>]*#i', $embedRes->body(), $matches)) {
                            foreach ($matches[0] as $match) {
                                $clean = stripslashes(html_entity_decode($match));
                                if (!str_contains($clean, 'profile_pic') && !str_contains($clean, 's100x100')) {
                                    $thumbUrl = $clean;
                                    break;
                                }
                            }
                        }
                    } catch (\Throwable $e) {}

                    if (!$thumbUrl) {
                        $thumbUrl = 'https://images.weserv.nl/?url=' . urlencode("https://www.instagram.com/p/{$shortcode}/media/?size=l");
                    }
                }
            }

            if ($thumbUrl) {
                try {
                    $dl = Http::timeout(10)->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                        'Referer'    => $isTiktok ? 'https://www.tiktok.com/' : 'https://www.instagram.com/',
                    ])->get($thumbUrl);

                    if ($dl->successful() && strlen($dl->body()) > 500) {
                        Storage::disk('public')->put($localRel, $dl->body());
                        $data['image'] = $localRel;
                        $block->update(['data_json' => $data]);
                        $this->info("  [OK] Cached Block #{$block->id} ({$block->type}) -> {$localRel}");
                        $successCount++;
                        continue;
                    }
                } catch (\Throwable $e) {
                    $this->warn("  [FAIL] Error downloading thumbnail for Block #{$block->id}: " . $e->getMessage());
                }
            } else {
                $this->warn("  [FAIL] Could not resolve thumbnail for Block #{$block->id} ({$url})");
            }
        }

        $this->info("Finished! Cached {$successCount} thumbnails.");
        return Command::SUCCESS;
    }
}


<?php

namespace App\Jobs;

use App\Models\AutomationCampaign;
use App\Models\AutomationShortLink;
use App\Models\CampaignLog;
use App\Models\InstagramAccount;
use App\Services\InstagramApiService;
use App\Services\SpintaxService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessInstagramCommentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $payload;

    /**
     * Create a new job instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(InstagramApiService $apiService): void
    {
        $mediaId        = $this->payload['media_id'] ?? null;
        $commentId      = $this->payload['comment_id'] ?? null;
        $senderIgId     = $this->payload['sender_ig_id'] ?? null;
        $senderUsername = $this->payload['sender_username'] ?? 'Kak';
        $commentText    = $this->payload['comment_text'] ?? '';
        $igAccountId    = $this->payload['instagram_account_id'] ?? null;

        if (!$commentId || !$senderIgId) {
            return;
        }

        // Ambil akun IG
        $igAccount = InstagramAccount::find($igAccountId);
        if (!$igAccount || !$igAccount->is_connected) {
            return;
        }

        // Deteksi jika yang berkomentar adalah akun IG kreator itu sendiri / balasan dari bot Buyle
        $isOwnAccount = false;
        if (!empty($igAccount->username) && strcasecmp($senderUsername, $igAccount->username) === 0) {
            $isOwnAccount = true;
        }
        if (!empty($igAccount->instagram_business_id) && (string)$senderIgId === (string)$igAccount->instagram_business_id) {
            $isOwnAccount = true;
        }
        if (!empty($igAccount->page_id) && (string)$senderIgId === (string)$igAccount->page_id) {
            $isOwnAccount = true;
        }

        if ($isOwnAccount) {
            Log::info('[ProcessInstagramCommentJob] Mengabaikan event komentar dari akun bot/pemilik sendiri agar tidak menduplikasi audit log.', [
                'sender'  => $senderUsername,
                'comment' => $commentText,
            ]);
            return;
        }

        // Cek rate limit harian
        if (!$igAccount->canSendDm()) {
            CampaignLog::create([
                'user_id'         => $igAccount->user_id,
                'sender_ig_id'    => $senderIgId,
                'sender_username' => $senderUsername,
                'comment_id'      => $commentId,
                'media_id'        => $mediaId,
                'comment_text'    => $commentText,
                'status'          => 'rate_limited',
                'error_message'   => 'Batas pengiriman DM harian (' . $igAccount->daily_dm_limit . ') telah tercapai.',
            ]);
            return;
        }

        // Cari campaign aktif yang cocok
        $campaigns = AutomationCampaign::where('user_id', $igAccount->user_id)
            ->where('is_active', true)
            ->get();

        $matchedCampaign = null;
        foreach ($campaigns as $campaign) {
            // Cek target postingan (all atau spesifik)
            if ($campaign->target_type === 'specific' && !empty($campaign->target_media_id)) {
                $target = trim($campaign->target_media_id);
                $isMatch = ($target === (string)$mediaId);

                if (!$isMatch && !empty($mediaId)) {
                    // 1. Cek kecocokan substring ID langsung
                    if (str_contains($target, (string)$mediaId) || str_contains((string)$mediaId, $target)) {
                        $isMatch = true;
                    } else {
                        // 2. Ekstrak shortcode dari link target campaign jika berbentuk URL (misal https://www.instagram.com/reel/DEv8f_ySxyz/)
                        $targetShortcode = null;
                        if (preg_match('/instagram\.com\/(?:reel|p|tv)\/([A-Za-z0-9_-]+)/', $target, $matches)) {
                            $targetShortcode = $matches[1];
                        } elseif (!str_contains($target, '/') && !is_numeric($target)) {
                            $targetShortcode = $target;
                        }

                        // Query permalink dari mediaId webhook via Graph API (hanya cache jika berhasil, jangan cache string kosong)
                        $cacheKey = "ig_media_permalink_{$mediaId}";
                        $mediaPermalink = cache()->get($cacheKey);
                        if (empty($mediaPermalink)) {
                            $mediaPermalink = $apiService->getMediaPermalink($igAccount->access_token, (string)$mediaId);
                            if (!empty($mediaPermalink)) {
                                cache()->put($cacheKey, $mediaPermalink, 86400);
                            }
                        }

                        if (!empty($mediaPermalink)) {
                            if (!empty($targetShortcode)) {
                                if (str_contains($mediaPermalink, $targetShortcode)) {
                                    $isMatch = true;
                                }
                            } else {
                                if (str_contains($target, $mediaPermalink) || str_contains($mediaPermalink, $target)) {
                                    $isMatch = true;
                                }
                            }
                        }
                    }
                }

                if (!$isMatch) {
                    continue;
                }
            }

            // Cek keyword match
            if ($campaign->matchesComment($commentText)) {
                $matchedCampaign = $campaign;
                break;
            }
        }

        if (!$matchedCampaign) {
            CampaignLog::create([
                'user_id'         => $igAccount->user_id,
                'sender_ig_id'    => $senderIgId,
                'sender_username' => $senderUsername,
                'comment_id'      => $commentId,
                'media_id'        => $mediaId,
                'comment_text'    => $commentText,
                'status'          => 'skipped',
                'error_message'   => 'Komentar masuk tidak cocok dengan kata kunci atau target campaign yang aktif.',
            ]);
            return;
        }

        // Increment deteksi komentar
        $matchedCampaign->increment('total_comments_detected');

        // Setup Short Link Checkout Dinamis / Link Eksternal
        $shortUrl = url('/');
        $targetUrl = null;
        if ($matchedCampaign->product) {
            $targetUrl = route('products.show', $matchedCampaign->product->slug) . '?utm_source=instagram&utm_medium=auto_dm&utm_campaign=' . urlencode($matchedCampaign->name);
        } elseif (!empty($matchedCampaign->custom_link)) {
            $targetUrl = $matchedCampaign->custom_link;
        }

        if ($targetUrl) {
            $shortLink = AutomationShortLink::firstOrCreate(
                [
                    'campaign_id' => $matchedCampaign->id,
                ],
                [
                    'product_id'  => $matchedCampaign->product_id,
                    'code'        => 'c' . Str::lower(Str::random(6)),
                    'target_url'  => $targetUrl,
                ]
            );
            $shortUrl = $shortLink->short_url;
        }

        // Variabel Spintax
        $firstName = explode(' ', trim($senderUsername))[0] ?? 'Kak';
        $vars = [
            'first_name'   => $firstName,
            'username'     => '@' . $senderUsername,
            'link_product' => $shortUrl,
            'post_title'   => $matchedCampaign->target_media_caption ?: 'Produk Digital Pilihan',
            'creator_name' => $matchedCampaign->user->name ?? 'Buyle.id Creator',
        ];

        // Format pesan DM
        $dmText = SpintaxService::pickAndParse($matchedCampaign->spintax_variants ?? [], $vars);

        // Anti-Spam Safety Layer: Random Delay
        $delay = $matchedCampaign->delay_seconds ?: rand(3, 8);
        if ($delay > 0) {
            sleep($delay);
        }

        // Siapkan Tombol Tautan Rapi (Button Card) jika terdapat link produk
        $buttonData = null;
        if (!empty($shortUrl)) {
            $productTitle = $matchedCampaign->product->name ?? 'Produk Digital';
            
            // Bersihkan URL mentah dari dmText agar rapi dan tidak dobel dengan tombol
            $cleanGreeting = trim(preg_replace('/https?:\/\/[^\s]+/', '', $dmText));
            $cleanGreeting = trim(preg_replace('/\s+/', ' ', $cleanGreeting));

            // Judul di atas: Sapaan ramah personal (font besar tebal)
            $cardTitle = "Halo kak {$firstName}! ✨";
            
            // Keterangan di bawah: Keterangan ucapan / produk + ajakan klik
            $cardSubtitle = !empty($cleanGreeting)
                ? mb_substr($cleanGreeting, 0, 80)
                : mb_substr("{$productTitle} — Ketuk tombol di bawah untuk klaim akses:", 0, 80);

            $buttonData = [
                'card_title' => $cardTitle,
                'subtitle'   => $cardSubtitle,
                'title'      => 'Buka Akses 🚀',
                'url'        => $shortUrl,
            ];
        }

        // 1. Eksekusi Auto DM (Menggunakan Private Reply resmi Meta via comment_id)
        $businessId = $igAccount->page_id ?: $igAccount->instagram_business_id;
        $dmResult = $apiService->sendDirectMessage($igAccount->access_token, $businessId, $senderIgId, $dmText, $commentId, $buttonData);

        $publicReplyText = null;
        // 2. Eksekusi Public Reply Comment jika aktif
        if ($matchedCampaign->auto_comment_enabled && !empty($matchedCampaign->auto_comment_variants)) {
            $publicReplyText = SpintaxService::pickAndParse($matchedCampaign->auto_comment_variants, $vars);
            $apiService->replyPublicComment($igAccount->access_token, $commentId, $publicReplyText);
        }

        // Update kuota dan counter
        if ($dmResult['success']) {
            $matchedCampaign->increment('total_dms_sent');
            $igAccount->increment('daily_dm_count');
            $igAccount->update(['last_dm_sent_at' => now()]);

            CampaignLog::create([
                'campaign_id'       => $matchedCampaign->id,
                'user_id'           => $igAccount->user_id,
                'sender_ig_id'      => $senderIgId,
                'sender_username'   => $senderUsername,
                'comment_id'        => $commentId,
                'media_id'          => $mediaId,
                'comment_text'      => $commentText,
                'dm_text_sent'      => $dmText,
                'public_reply_sent' => $publicReplyText,
                'status'            => 'success',
            ]);
        } else {
            CampaignLog::create([
                'campaign_id'     => $matchedCampaign->id,
                'user_id'         => $igAccount->user_id,
                'sender_ig_id'    => $senderIgId,
                'sender_username' => $senderUsername,
                'comment_id'      => $commentId,
                'media_id'        => $mediaId,
                'comment_text'    => $commentText,
                'dm_text_sent'    => $dmText,
                'status'          => 'failed',
                'error_message'   => $dmResult['error'] ?? 'Gagal mengirim DM melalui Graph API.',
            ]);
        }
    }
}

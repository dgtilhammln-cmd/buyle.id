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

        // Cek jika komentar ini adalah balasan bot publik Buyle sendiri yang terpantul dari webhook
        if (!empty($commentText) && CampaignLog::where('public_reply_sent', $commentText)->exists()) {
            Log::info('[ProcessInstagramCommentJob] Mengabaikan event balasan bot publik yang sudah tercatat', ['text' => $commentText]);
            return;
        }

        // Deteksi jika yang berkomentar adalah akun IG kreator itu sendiri
        if (!empty($igAccount->username) && strcasecmp($senderUsername, $igAccount->username) === 0) {
            CampaignLog::create([
                'user_id'         => $igAccount->user_id,
                'sender_ig_id'    => $senderIgId,
                'sender_username' => $senderUsername,
                'comment_id'      => $commentId,
                'media_id'        => $mediaId,
                'comment_text'    => $commentText,
                'status'          => 'skipped',
                'error_message'   => 'Uji coba komentar dari akun pemilik sendiri (@' . $senderUsername . ') terdeteksi. Kebijakan Meta melarang bot kirim DM otomatis ke akun sendiri. Harap uji coba menggunakan akun Instagram lain (@wetewe_, @avham, dll).',
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
                $isMatch = false;

                // 1. Cek kecocokan numeric ID langsung
                if ($target === (string)$mediaId || str_contains($target, (string)$mediaId) || str_contains((string)$mediaId, $target)) {
                    $isMatch = true;
                }

                // 2. Ekstrak shortcode jika target berupa URL (contoh: https://www.instagram.com/reel/C8XYZ123/)
                if (!$isMatch && !empty($mediaId)) {
                    $targetShortcode = null;
                    if (preg_match('/instagram\.com\/(?:reel|p|tv)\/([A-Za-z0-9_-]+)/', $target, $matches)) {
                        $targetShortcode = $matches[1];
                    } elseif (!str_contains($target, '/') && !is_numeric($target)) {
                        $targetShortcode = $target;
                    }

                    if (!empty($targetShortcode)) {
                        // A. Cocokkan target shortcode dengan hasil konversi matematis mediaId webhook
                        $convertedShortcode = self::mediaIdToShortcode((string)$mediaId);
                        if (!empty($convertedShortcode) && strcasecmp($targetShortcode, $convertedShortcode) === 0) {
                            $isMatch = true;
                        }

                        // B. Cocokkan target shortcode yang dikonversi ke numeric ID dengan mediaId webhook
                        if (!$isMatch) {
                            $convertedTargetId = self::shortcodeToMediaId($targetShortcode);
                            if (!empty($convertedTargetId) && $convertedTargetId === (string)$mediaId) {
                                $isMatch = true;
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
            $product = $matchedCampaign->product;
            
            // Bersihkan URL mentah dari dmText agar rapi dan tidak dobel dengan tombol
            $cleanGreeting = trim(preg_replace('/https?:\/\/[^\s]+/', '', $dmText));
            $cleanGreeting = trim(preg_replace('/\s+/', ' ', $cleanGreeting));

            // Tentukan Judul Kartu (Card Title) & Keterangan (Subtitle) agar TIDAK DOBEL NAMA:
            if ($product) {
                // Judul tebal di atas: Nama Produk
                $cardTitle = mb_substr($product->name, 0, 80);
                // Keterangan di bawah: Pesan sapaan personal dari variasi Spintax
                $cardSubtitle = !empty($cleanGreeting)
                    ? mb_substr($cleanGreeting, 0, 80)
                    : "Halo kak {$firstName}! Ketuk tombol di bawah untuk klaim akses:";
            } else {
                // Jika link custom tanpa produk: Gunakan sapaan di atas, dan bersihkan awalan greeting dari subtitle agar tidak dobel nama
                $cardTitle = "Halo kak {$firstName}!";
                $strippedGreeting = preg_replace('/^(halo|hai|hi|hei|selamat\s+\w+)\s+kak\s+[^\s!.,]+[!.,\s]*/i', '', $cleanGreeting);
                $cardSubtitle = !empty($strippedGreeting)
                    ? mb_substr(trim($strippedGreeting), 0, 80)
                    : "Tautan akses spesial Anda sudah siap di tombol bawah ini:";
            }

            // Tentukan URL Foto Produk (jika ada) untuk ditampilkan pada kartu DM
            $imageUrl = null;
            if ($product && !empty($product->image)) {
                $imageUrl = Str::startsWith($product->image, ['http://', 'https://'])
                    ? $product->image
                    : asset('storage/' . $product->image);
            }

            // Teks Tombol Kustom (Maks. 20 Karakter sesuai aturan Meta)
            $buttonTitle = !empty($matchedCampaign->button_text)
                ? mb_substr(trim($matchedCampaign->button_text), 0, 20)
                : 'Buka Akses';

            $buttonData = [
                'card_title' => $cardTitle,
                'subtitle'   => $cardSubtitle,
                'image_url'  => $imageUrl,
                'title'      => $buttonTitle,
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

    /**
     * Konversi Instagram Shortcode (dari URL Reels/Postingan) ke numeric Media ID secara matematis (Base64)
     */
    public static function shortcodeToMediaId(string $code): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $id = '0';
        for ($i = 0; $i < strlen($code); $i++) {
            $char = $code[$i];
            $val = strpos($alphabet, $char);
            if ($val === false) continue;
            $id = bcadd(bcmul($id, '64'), (string)$val);
        }
        return $id;
    }

    /**
     * Konversi numeric Media ID dari webhook ke Instagram Shortcode secara matematis (Base64)
     */
    public static function mediaIdToShortcode(string $id): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
        $code = '';
        while (bccomp($id, '0') > 0) {
            $rem = bcmod($id, '64');
            $code = $alphabet[(int)$rem] . $code;
            $id = bcdiv($id, '64', 0);
        }
        return $code;
    }
}

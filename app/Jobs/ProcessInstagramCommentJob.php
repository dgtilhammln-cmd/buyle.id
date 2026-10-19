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
            if ($campaign->target_type === 'specific' && $campaign->target_media_id) {
                if ($campaign->target_media_id !== $mediaId) {
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
            return;
        }

        // Increment deteksi komentar
        $matchedCampaign->increment('total_comments_detected');

        // Setup Short Link Checkout Dinamis
        $shortUrl = url('/');
        if ($matchedCampaign->product) {
            $shortLink = AutomationShortLink::firstOrCreate(
                [
                    'campaign_id' => $matchedCampaign->id,
                    'product_id'  => $matchedCampaign->product_id,
                ],
                [
                    'code'        => 'c' . Str::lower(Str::random(6)),
                    'target_url'  => route('products.show', $matchedCampaign->product->slug) . '?utm_source=instagram&utm_medium=auto_dm&utm_campaign=' . urlencode($matchedCampaign->name),
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

        // 1. Eksekusi Auto DM
        $businessId = $igAccount->page_id ?: $igAccount->instagram_business_id;
        $dmResult = $apiService->sendDirectMessage($igAccount->access_token, $businessId, $senderIgId, $dmText);

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

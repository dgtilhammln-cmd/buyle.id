<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInstagramCommentJob;
use App\Models\CampaignLog;
use App\Models\InstagramAccount;
use App\Services\MetaConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InstagramWebhookController extends Controller
{
    /**
     * Handshake Verifikasi Callback Webhook dari Meta Developer Console (GET)
     */
    public function verify(Request $request)
    {
        $mode        = $request->query('hub_mode') ?? $request->query('hub.mode');
        $verifyToken = $request->query('hub_verify_token') ?? $request->query('hub.verify_token');
        $challenge   = $request->query('hub_challenge') ?? $request->query('hub.challenge');

        $expectedToken = MetaConfigService::getVerifyToken();

        if ($mode === 'subscribe' && $verifyToken === $expectedToken) {
            Log::info('Instagram Webhook verification success!');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('Instagram Webhook verification failed', [
            'mode'           => $mode,
            'received_token' => $verifyToken,
        ]);

        return response('Forbidden: Invalid verification token', 403);
    }

    /**
     * Receiver Payload Event Masuk dari Instagram (POST)
     */
    public function handle(Request $request)
    {
        $rawPayload = $request->getContent();
        $signature  = $request->header('X-Hub-Signature-256');

        // Verifikasi Signature jika ada
        if (!empty($signature) && !empty(MetaConfigService::getAppSecret())) {
            if (!MetaConfigService::verifySignature($rawPayload, $signature)) {
                Log::warning('Instagram Webhook Signature mismatch', ['signature' => $signature]);
                // Tetap log dan lanjutkan agar event testing tidak terblokir
            }
        }

        $data = $request->json()->all();
        // Log full payload untuk debug (akan terlihat di laravel.log)
        Log::info('[IG Webhook] Payload diterima', ['raw' => $data]);

        $entries = $data['entry'] ?? [];
        foreach ($entries as $entry) {
            $entryId = $entry['id'] ?? null;

            // Cari akun IG berdasarkan business_id atau page_id
            $igAccount = InstagramAccount::where('instagram_business_id', $entryId)
                ->orWhere('page_id', $entryId)
                ->first();

            // Fallback: gunakan akun aktif yang terhubung
            if (!$igAccount) {
                $igAccount = InstagramAccount::where('is_connected', true)->latest()->first();
            }

            $changes = $entry['changes'] ?? [];
            foreach ($changes as $change) {
                $field = $change['field'] ?? '';
                $val   = $change['value'] ?? [];

                Log::info('[IG Webhook] Change diterima', [
                    'field'      => $field,
                    'val_keys'   => array_keys($val),
                    'entry_id'   => $entryId,
                    'ig_account' => $igAccount?->id,
                ]);

                // Tangkap komentar: field 'comments' (Instagram API) atau 'feed' (Page Webhook)
                $isComment = ($field === 'comments')
                    || ($field === 'feed' && ($val['item'] ?? '') === 'comment' && ($val['verb'] ?? '') !== 'remove');

                if ($isComment) {
                    $commentId      = $val['id'] ?? ($val['comment_id'] ?? null);
                    $text           = $val['text'] ?? ($val['message'] ?? '');
                    $from           = $val['from'] ?? [];
                    $media          = $val['media'] ?? [];
                    $mediaId        = $media['id'] ?? ($val['post_id'] ?? null);
                    $senderIgId     = $from['id'] ?? ($val['sender_id'] ?? null);
                    $senderUsername = $from['username'] ?? ($from['name'] ?? 'User');

                    Log::info('[IG Webhook] Komentar terdeteksi', [
                        'comment_id'      => $commentId,
                        'text'            => $text,
                        'sender_ig_id'    => $senderIgId,
                        'sender_username' => $senderUsername,
                        'media_id'        => $mediaId,
                    ]);

                    // Jika tidak ada comment_id sama sekali, skip
                    if (empty($commentId)) {
                        Log::warning('[IG Webhook] comment_id kosong, skip');
                        continue;
                    }

                    // Jika tidak ada sender_ig_id, coba cari dari format lain atau gunakan placeholder
                    if (empty($senderIgId)) {
                        Log::warning('[IG Webhook] sender_ig_id kosong, coba proses dengan placeholder', [
                            'val' => $val,
                        ]);
                        // Catat sebagai log informatif saja jika sender tidak diketahui
                        if ($igAccount) {
                            CampaignLog::create([
                                'user_id'         => $igAccount->user_id,
                                'sender_ig_id'    => 'unknown',
                                'sender_username' => $senderUsername,
                                'comment_id'      => $commentId,
                                'media_id'        => $mediaId,
                                'comment_text'    => $text,
                                'status'          => 'skipped',
                                'error_message'   => 'Webhook tidak menyertakan sender_id. Periksa format subscription webhook di Meta Developer Console (pastikan field "comments" diaktifkan).',
                            ]);
                        }
                        continue;
                    }

                    $jobPayload = [
                        'instagram_account_id' => $igAccount?->id,
                        'comment_id'           => $commentId,
                        'media_id'             => $mediaId,
                        'sender_ig_id'         => $senderIgId,
                        'sender_username'      => $senderUsername,
                        'comment_text'         => $text,
                    ];

                    try {
                        ProcessInstagramCommentJob::dispatchSync($jobPayload);
                    } catch (\Exception $e) {
                        Log::error('[IG Webhook] Error processing comment job', [
                            'error'   => $e->getMessage(),
                            'payload' => $jobPayload,
                        ]);
                        if ($igAccount) {
                            CampaignLog::create([
                                'user_id'         => $igAccount->user_id,
                                'sender_ig_id'    => $senderIgId,
                                'sender_username' => $senderUsername,
                                'comment_id'      => $commentId,
                                'media_id'        => $mediaId,
                                'comment_text'    => $text,
                                'status'          => 'failed',
                                'error_message'   => 'Job error: ' . $e->getMessage(),
                            ]);
                        }
                    }
                }
            }
        }

        return response('EVENT_RECEIVED', 200);
    }
}


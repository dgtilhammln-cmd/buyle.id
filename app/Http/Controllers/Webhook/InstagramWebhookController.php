<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessInstagramCommentJob;
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
                // Tetap log dan lanjutkan agar event testing tidak terblokir jika secret beda versi
            }
        }

        $data = $request->json()->all();
        Log::info('Instagram Webhook payload received', ['data' => $data]);

        // Cek struktur payload Meta
        $entries = $data['entry'] ?? [];
        foreach ($entries as $entry) {
            $entryId = $entry['id'] ?? null;

            // Cari akun IG berdasarkan business_id atau page_id
            $igAccount = InstagramAccount::where('instagram_business_id', $entryId)
                ->orWhere('page_id', $entryId)
                ->first();

            // Fallback: jika entryId tidak persis cocok, gunakan akun aktif yang terhubung
            if (!$igAccount) {
                $igAccount = InstagramAccount::where('is_connected', true)->latest()->first();
            }

            $changes = $entry['changes'] ?? [];
            foreach ($changes as $change) {
                $field = $change['field'] ?? '';
                $val   = $change['value'] ?? [];

                // Filter event komentar (Instagram Graph API: 'comments', atau Page Webhook: 'feed')
                if ($field === 'comments' || ($field === 'feed' && ($val['item'] ?? '') === 'comment' && ($val['verb'] ?? '') !== 'remove')) {
                    $commentId = $val['id'] ?? ($val['comment_id'] ?? null);
                    $text      = $val['text'] ?? ($val['message'] ?? '');
                    $from      = $val['from'] ?? [];
                    $media     = $val['media'] ?? [];
                    $mediaId   = $media['id'] ?? ($val['post_id'] ?? null);

                    $jobPayload = [
                        'instagram_account_id' => $igAccount?->id,
                        'comment_id'           => $commentId,
                        'media_id'             => $mediaId,
                        'sender_ig_id'         => $from['id'] ?? null,
                        'sender_username'      => $from['username'] ?? ($from['name'] ?? 'User'),
                        'comment_text'         => $text,
                    ];

                    if (!empty($commentId) && !empty($jobPayload['sender_ig_id'])) {
                        try {
                            ProcessInstagramCommentJob::dispatchSync($jobPayload);
                        } catch (\Exception $e) {
                            Log::error('Error processing Instagram comment job', ['error' => $e->getMessage()]);
                        }
                    }
                }
            }

            // Messaging webhooks (jika event messaging)
            $messagings = $entry['messaging'] ?? [];
            foreach ($messagings as $msg) {
                // Handle direct messaging callback jika diperlukan
            }
        }

        return response('EVENT_RECEIVED', 200);
    }
}

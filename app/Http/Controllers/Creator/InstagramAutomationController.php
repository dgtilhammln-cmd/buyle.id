<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\AutomationCampaign;
use App\Models\AutomationShortLink;
use App\Models\CampaignLog;
use App\Models\InstagramAccount;
use App\Models\Product;
use App\Services\InstagramApiService;
use App\Services\MetaConfigService;
use App\Services\SpintaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InstagramAutomationController extends Controller
{
    /**
     * Dashboard Utama Buyle Automation Reply
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Akun Instagram yang terhubung
        $account = InstagramAccount::where('user_id', $user->id)->first();

        // 2. Daftar Campaign
        $campaigns = AutomationCampaign::where('user_id', $user->id)
            ->with(['product', 'shortLinks'])
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Log Aktivitas dengan Filter Dinamis
        $logQuery = CampaignLog::where('user_id', $user->id)->with('campaign');

        // Filter Campaign
        if ($request->filled('campaign_id') && $request->campaign_id !== 'all') {
            $logQuery->where('campaign_id', $request->campaign_id);
        }

        // Filter Status
        if ($request->filled('status') && $request->status !== 'all') {
            $logQuery->where('status', $request->status);
        }

        // Pencarian Username IG / Komentar / DM / Pesan Error
        if ($request->filled('q')) {
            $search = trim($request->q);
            $cleanUser = ltrim($search, '@');
            $logQuery->where(function ($q) use ($search, $cleanUser) {
                $q->where('sender_username', 'like', "%{$cleanUser}%")
                  ->orWhere('comment_text', 'like', "%{$search}%")
                  ->orWhere('dm_text_sent', 'like', "%{$search}%")
                  ->orWhere('error_message', 'like', "%{$search}%");
            });
        }

        // Filter Periode
        if ($request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $logQuery->whereDate('created_at', now()->today());
                    break;
                case 'week':
                    $logQuery->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $logQuery->where('created_at', '>=', now()->subDays(30));
                    break;
            }
        }

        $logs = $logQuery->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // 4. Hitung Notifikasi Merah Log Baru (Hilang saat tab dibuka)
        $activeTab = $request->query('tab', 'campaigns');
        if ($activeTab === 'logs') {
            session(['last_viewed_automation_logs' => now()->toDateTimeString()]);
            $unreadLogsCount = 0;
        } else {
            $lastViewed = session('last_viewed_automation_logs');
            $unreadLogsCount = CampaignLog::where('user_id', $user->id)
                ->when($lastViewed, fn($q) => $q->where('created_at', '>', $lastViewed))
                ->count();
        }

        // 5. Produk Digital Kreator (untuk dropdown generator shortlink)
        $products = Product::where('seller_id', $user->id)
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'slug', 'price', 'product_type']);

        // 6. Metrik & Analitik
        $totalComments = $campaigns->sum('total_comments_detected');
        $totalDms      = $campaigns->sum('total_dms_sent');
        $totalClicks   = $campaigns->sum('total_clicks');
        $totalOrders   = $campaigns->sum('total_orders');
        $totalRevenue  = $campaigns->sum('total_revenue');
        $overallCtr    = $totalDms > 0 ? round(($totalClicks / $totalDms) * 100, 1) : 0;

        return view('creator.automation.index', compact(
            'account',
            'campaigns',
            'logs',
            'products',
            'totalComments',
            'totalDms',
            'totalClicks',
            'totalOrders',
            'totalRevenue',
            'overallCtr',
            'unreadLogsCount'
        ));
    }

    /**
     * Return URL OAuth Dialog Meta sebagai JSON (digunakan oleh JS popup window).
     * Tidak melakukan redirect agar halaman dashboard tidak berpindah.
     */
    public function connect(Request $request)
    {
        $redirectUri = route('creator.automation-reply.callback');
        $state = csrf_token();

        $oauthUrl = MetaConfigService::getOAuthDialogUrl($redirectUri, $state);

        // Jika request dari AJAX/fetch → return JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['url' => $oauthUrl]);
        }

        // Fallback jika diakses langsung via browser (buka di tab baru)
        return redirect()->away($oauthUrl);
    }

    /**
     * Handle Callback setelah user menyetujui permissions Meta OAuth
     */
    public function callback(Request $request, InstagramApiService $apiService)
    {
        $code  = $request->query('code');
        $error = $request->query('error_description') ?? $request->query('error');

        if ($error || empty($code)) {
            return redirect()->route('creator.automation-reply.index')
                ->with('error', 'Koneksi ke Instagram dibatalkan atau gagal: ' . ($error ?? 'Kode otorisasi tidak ditemukan.'));
        }

        try {
            $redirectUri = route('creator.automation-reply.callback');

            // 1. Tukar Code dengan Short-Lived Token
            $shortTokenData = $apiService->exchangeCodeForToken($code, $redirectUri);
            $shortLivedToken = $shortTokenData['access_token'];

            // 2. Tukar dengan 60-Day Long-Lived Token
            $longTokenData = $apiService->exchangeForLongLivedToken($shortLivedToken);
            $longLivedToken = $longTokenData['access_token'];
            $expiresIn = $longTokenData['expires_in'] ?? (60 * 86400);

            // 3. Ambil Akun Facebook Page & Instagram Bisnis
            $accountData = $apiService->fetchConnectedAccount($longLivedToken);

            $user = auth()->user();

            // Simpan akun ke database
            $account = InstagramAccount::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'instagram_business_id' => $accountData['ig_id'],
                    'page_id'               => $accountData['page_id'],
                    'username'              => $accountData['ig_username'],
                    'name'                  => $accountData['ig_name'],
                    'profile_picture_url'   => $accountData['ig_profile_picture'],
                    'access_token'          => $longLivedToken,
                    'token_expires_at'      => now()->addSeconds($expiresIn),
                    'account_type'          => 'BUSINESS',
                    'is_connected'          => true,
                    'raw_metadata'          => $accountData,
                ]
            );

            // 4. Daftarkan webhook subscription untuk page
            if (!empty($accountData['page_id'])) {
                $pageAccessToken = $accountData['page_access_token'] ?? $longLivedToken;
                $apiService->subscribeWebhooks($accountData['page_id'], $pageAccessToken);
            }

            session()->flash('success', "Akun Instagram @{$account->username} berhasil dihubungkan!");
            return view('creator.automation.oauth_finish', [
                'success'  => true,
                'message'  => "Akun Instagram @{$account->username} berhasil dihubungkan!",
                'redirect' => route('creator.automation-reply.index', ['tab' => 'campaigns']),
            ]);

        } catch (\Exception $e) {
            Log::error('Instagram OAuth Callback Exception', ['message' => $e->getMessage()]);
            session()->flash('error', 'Gagal menghubungkan Instagram: ' . $e->getMessage());
            return view('creator.automation.oauth_finish', [
                'success'  => false,
                'message'  => 'Gagal menghubungkan Instagram: ' . $e->getMessage(),
                'redirect' => route('creator.automation-reply.index', ['tab' => 'campaigns']),
            ]);
        }
    }

    /**
     * Putuskan koneksi Instagram
     */
    public function disconnect(Request $request)
    {
        $account = InstagramAccount::where('user_id', auth()->id())->first();
        if ($account) {
            $account->update([
                'is_connected' => false,
                'access_token' => null,
            ]);
        }

        return redirect()->route('creator.automation-reply.index')
            ->with('success', 'Akun Instagram berhasil diputuskan.');
    }

    /**
     * Perbarui Access Token secara manual
     */
    public function refreshToken(Request $request, InstagramApiService $apiService)
    {
        $account = InstagramAccount::where('user_id', auth()->id())->first();

        if (!$account || empty($account->access_token)) {
            return redirect()->route('creator.automation-reply.index')
                ->with('error', 'Akun Instagram belum terhubung.');
        }

        try {
            $tokenData = $apiService->exchangeForLongLivedToken($account->access_token);
            $expiresIn = $tokenData['expires_in'] ?? (60 * 86400);

            $account->update([
                'access_token'     => $tokenData['access_token'],
                'token_expires_at' => now()->addSeconds($expiresIn),
            ]);

            return redirect()->route('creator.automation-reply.index')
                ->with('success', 'Masa berlaku token Instagram berhasil diperpanjang 60 hari ke depan.');
        } catch (\Exception $e) {
            return redirect()->route('creator.automation-reply.index')
                ->with('error', 'Gagal memperbarui token: ' . $e->getMessage());
        }
    }

    /**
     * Simpan Campaign Baru
     */
    public function storeCampaign(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:150',
            'target_type'           => 'required|in:all,specific',
            'target_media_id'       => 'nullable|string|max:255',
            'trigger_type'          => 'nullable|in:exact,contains,any',
            'trigger_keywords'      => 'nullable|string',
            'spintax_variants'      => 'required|array|min:1',
            'spintax_variants.*'    => 'required|string',
            'auto_comment_enabled'  => 'nullable|boolean',
            'auto_comment_variants' => 'nullable|array',
            'product_id'            => 'nullable|exists:products,id',
            'custom_link'           => 'nullable|string|max:1000',
            'delay_seconds'         => 'required|integer|min:3|max:8',
            'daily_limit'           => 'nullable|integer|min:10|max:1000',
        ]);

        $user = auth()->user();
        $account = InstagramAccount::where('user_id', $user->id)->first();

        // Parse trigger keywords (pisahkan tanda koma)
        $keywords = [];
        if (!empty($request->trigger_keywords)) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->trigger_keywords))));
        }

        // Filter varian pesan yang tidak kosong
        $spintaxVariants = array_values(array_filter(array_map('trim', $request->spintax_variants ?? [])));
        if (count($spintaxVariants) === 0) {
            $spintaxVariants = ["Halo kak {first_name}, ini linknya: {link_product}"];
        }

        // Filter komentar publik
        $commentVariants = [];
        if ($request->boolean('auto_comment_enabled') && !empty($request->auto_comment_variants)) {
            $commentVariants = array_values(array_filter(array_map('trim', $request->auto_comment_variants)));
        }

        // Update daily limit pada akun jika diinputkan
        if ($account && $request->filled('daily_limit')) {
            $account->update(['daily_dm_limit' => (int) $request->daily_limit]);
        }

        $campaign = AutomationCampaign::create([
            'user_id'               => $user->id,
            'instagram_account_id'  => $account?->id,
            'product_id'            => $request->product_id,
            'custom_link'           => $request->custom_link,
            'name'                  => $request->name,
            'target_type'           => $request->target_type,
            'target_media_id'       => $request->target_media_id,
            'target_media_caption'  => $request->target_media_caption,
            'target_media_url'      => $request->target_media_url,
            'trigger_type'          => $request->input('trigger_type', 'contains') ?: 'contains',
            'trigger_keywords'      => $keywords,
            'spintax_variants'      => $spintaxVariants,
            'auto_comment_enabled'  => $request->boolean('auto_comment_enabled'),
            'auto_comment_variants' => $commentVariants,
            'delay_seconds'         => (int) $request->delay_seconds,
            'auto_follow_check'     => $request->boolean('auto_follow_check'),
            'is_active'             => true,
        ]);

        // Generate Short Link Checkout / External Link Otomatis
        $targetUrl = null;
        if ($campaign->product_id && $campaign->product) {
            $targetUrl = route('products.show', $campaign->product->slug) . '?utm_source=instagram&utm_medium=auto_dm&utm_campaign=' . urlencode($campaign->name);
        } elseif (!empty($campaign->custom_link)) {
            $targetUrl = $campaign->custom_link;
        }

        if ($targetUrl) {
            AutomationShortLink::create([
                'campaign_id' => $campaign->id,
                'product_id'  => $campaign->product_id,
                'code'        => 'c' . Str::lower(Str::random(6)),
                'target_url'  => $targetUrl,
            ]);
        }

        return redirect()->route('creator.automation-reply.index', ['tab' => 'campaigns'])
            ->with('success', 'Campaign Automation Reply "' . $campaign->name . '" berhasil dibuat dan diaktifkan!');
    }

    /**
     * Update Campaign yang sudah ada
     */
    public function updateCampaign(Request $request, $id)
    {
        $campaign = AutomationCampaign::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'name'                  => 'required|string|max:150',
            'target_type'           => 'required|in:all,specific',
            'target_media_id'       => 'nullable|string|max:255',
            'trigger_type'          => 'nullable|in:exact,contains,any',
            'trigger_keywords'      => 'nullable|string',
            'spintax_variants'      => 'required|array|min:1',
            'product_id'            => 'nullable|exists:products,id',
            'custom_link'           => 'nullable|string|max:1000',
            'delay_seconds'         => 'required|integer|min:3|max:8',
        ]);

        $keywords = [];
        if (!empty($request->trigger_keywords)) {
            $keywords = array_values(array_filter(array_map('trim', explode(',', $request->trigger_keywords))));
        }

        $spintaxVariants = array_values(array_filter(array_map('trim', $request->spintax_variants ?? [])));
        $commentVariants = [];
        if ($request->boolean('auto_comment_enabled') && !empty($request->auto_comment_variants)) {
            $commentVariants = array_values(array_filter(array_map('trim', $request->auto_comment_variants)));
        }

        $campaign->update([
            'name'                  => $request->name,
            'target_type'           => $request->target_type,
            'target_media_id'       => $request->target_media_id,
            'trigger_type'          => $request->input('trigger_type', 'contains') ?: 'contains',
            'trigger_keywords'      => $keywords,
            'spintax_variants'      => $spintaxVariants,
            'auto_comment_enabled'  => $request->boolean('auto_comment_enabled'),
            'auto_comment_variants' => $commentVariants,
            'product_id'            => $request->product_id,
            'custom_link'           => $request->custom_link,
            'delay_seconds'         => (int) $request->delay_seconds,
            'auto_follow_check'     => $request->boolean('auto_follow_check'),
        ]);

        // Perbarui target url di short link jika ada
        $targetUrl = null;
        if ($campaign->product_id && $campaign->product) {
            $targetUrl = route('products.show', $campaign->product->slug) . '?utm_source=instagram&utm_medium=auto_dm&utm_campaign=' . urlencode($campaign->name);
        } elseif (!empty($campaign->custom_link)) {
            $targetUrl = $campaign->custom_link;
        }

        if ($targetUrl) {
            $shortLink = AutomationShortLink::where('campaign_id', $campaign->id)->first();
            if ($shortLink) {
                $shortLink->update([
                    'product_id' => $campaign->product_id,
                    'target_url' => $targetUrl,
                ]);
            } else {
                AutomationShortLink::create([
                    'campaign_id' => $campaign->id,
                    'product_id'  => $campaign->product_id,
                    'code'        => 'c' . Str::lower(Str::random(6)),
                    'target_url'  => $targetUrl,
                ]);
            }
        }

        return redirect()->route('creator.automation-reply.index', ['tab' => 'campaigns'])
            ->with('success', 'Campaign "' . $campaign->name . '" berhasil diperbarui!');
    }

    /**
     * Toggle status aktif/pause campaign
     */
    public function toggleCampaign(Request $request, $id)
    {
        $campaign = AutomationCampaign::where('user_id', auth()->id())->findOrFail($id);
        $campaign->update(['is_active' => !$campaign->is_active]);

        $statusText = $campaign->is_active ? 'diaktifkan' : 'dinonaktifkan (pause)';
        return back()->with('success', "Campaign \"{$campaign->name}\" berhasil {$statusText}.");
    }

    /**
     * Hapus campaign
     */
    public function destroyCampaign(Request $request, $id)
    {
        $campaign = AutomationCampaign::where('user_id', auth()->id())->findOrFail($id);
        $campaign->delete();

        return back()->with('success', 'Campaign berhasil dihapus.');
    }

    /**
     * Simulasi Test Generator Spintax & Direct Checkout Link
     */
    public function testSimulation(Request $request)
    {
        $template  = $request->input('template', "{Halo|Hai|Hello} kak {first_name}, ini link produknya: {link_product}");
        $productId = $request->input('product_id');

        $product = Product::find($productId);
        $testUrl = $product ? route('products.show', $product->slug) . '?utm_source=test_simulation' : url('/produk/demo');

        $vars = [
            'first_name'   => 'Alex',
            'username'     => '@alex_digital',
            'link_product' => $testUrl,
            'post_title'   => 'Panduan Creator Buyle',
            'creator_name' => auth()->user()->name,
        ];

        $generatedDm = SpintaxService::parse($template, $vars);

        return response()->json([
            'success'   => true,
            'preview'   => $generatedDm,
            'variables' => $vars,
        ]);
    }
}

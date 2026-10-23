<?php

namespace App\Http\Controllers;

use App\Models\AutomationCampaign;
use App\Models\AutomationShortLink;
use Illuminate\Http\Request;

class ShortLinkRedirectController extends Controller
{
    /**
     * Redirect short link direct checkout (misal: /c/{code})
     */
    public function redirect(Request $request, string $code)
    {
        $shortLink = AutomationShortLink::where('code', $code)->first();

        if (!$shortLink) {
            return redirect('/')->with('error', 'Link produk tidak ditemukan atau sudah kedaluwarsa.');
        }

        // Catat klik CTR
        $shortLink->increment('clicks_count');

        if ($shortLink->campaign_id) {
            AutomationCampaign::where('id', $shortLink->campaign_id)->increment('total_clicks');
            session()->put('ig_automation_campaign_id', $shortLink->campaign_id);
            session()->put('ig_automation_short_link_id', $shortLink->id);
            session()->put('utm_source', 'instagram');
            session()->put('utm_medium', 'auto_dm');
            session()->put('utm_campaign', 'ig_camp_' . $shortLink->campaign_id);
        }

        return redirect()->away($shortLink->target_url);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    private array $allowedTypes = ['wa_click', 'phone_click', 'email_click', 'contact_form'];

    public function track(Request $request, string $type)
    {
        if (!in_array($type, $this->allowedTypes)) {
            return response()->json(['ok' => false], 400);
        }

        AnalyticsEvent::record($type, $request->input('url', $request->header('referer')));

        return response()->json(['ok' => true]);
    }

    /**
     * Record a bio link block click event (AJAX via sendBeacon, public).
     * sendBeacon sends Content-Type: application/json — must decode manually.
     */
    public function bioClick(Request $request)
    {
        // sendBeacon POSTs raw JSON — merge into request so validate() can read it
        if ($request->isJson() || str_contains($request->header('Content-Type', ''), 'application/json')) {
            $json = json_decode($request->getContent(), true) ?? [];
            $request->merge($json);
        }

        $blockId    = $request->input('block_id');
        $creatorId  = $request->input('creator_id');
        $url        = $request->input('url');
        $title      = $request->input('title');
        $utmSource  = $request->input('utm_source');
        $utmMedium  = $request->input('utm_medium');
        $utmCampaign= $request->input('utm_campaign');
        $utmContent = $request->input('utm_content');

        AnalyticsEvent::record('bio_link_click', $url ?? request()->header('referer'), [
            'page_title'     => $title,
            'bio_block_id'   => $blockId  ? (int) $blockId  : null,
            'bio_creator_id' => $creatorId ? (int) $creatorId : null,
            'utm_source'     => $utmSource,
            'utm_medium'     => $utmMedium,
            'utm_campaign'   => $utmCampaign,
            'utm_content'    => $utmContent,
        ]);

        return response()->json(['ok' => true]);
    }
}


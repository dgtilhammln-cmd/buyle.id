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
     * Record a bio link block click event (AJAX, public).
     */
    public function bioClick(Request $request)
    {
        $validated = $request->validate([
            'block_id'    => 'nullable|integer',
            'creator_id'  => 'nullable|integer',
            'url'         => 'nullable|string|max:2000',
            'title'       => 'nullable|string|max:255',
            'utm_source'  => 'nullable|string|max:100',
            'utm_medium'  => 'nullable|string|max:100',
            'utm_campaign'=> 'nullable|string|max:200',
            'utm_content' => 'nullable|string|max:200',
        ]);

        AnalyticsEvent::record('bio_link_click', $validated['url'] ?? request()->header('referer'), [
            'page_title'    => $validated['title'] ?? null,
            'bio_block_id'  => $validated['block_id'] ?? null,
            'bio_creator_id'=> $validated['creator_id'] ?? null,
            'utm_source'    => $validated['utm_source'] ?? null,
            'utm_medium'    => $validated['utm_medium'] ?? null,
            'utm_campaign'  => $validated['utm_campaign'] ?? null,
            'utm_content'   => $validated['utm_content'] ?? null,
        ]);

        return response()->json(['ok' => true]);
    }
}


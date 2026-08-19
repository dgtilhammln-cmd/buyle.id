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
}

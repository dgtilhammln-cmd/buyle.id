<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\WaSetting;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|min:2|max:100',
            'company' => 'nullable|max:150',
            'email'   => 'nullable|email|max:100',
            'phone'   => 'required|min:7|max:20',
            'product' => 'nullable|max:200',
            'message' => 'nullable|max:2000',
        ]);

        // Get primary WA
        $wa = WaSetting::primary();

        // Build WA message
        $msg = "Halo buyle.id,\n\n";
        $msg .= "Nama: {$validated['name']}\n";
        if (!empty($validated['company'])) $msg .= "Perusahaan: {$validated['company']}\n";
        if (!empty($validated['email']))   $msg .= "Email: {$validated['email']}\n";
        $msg .= "Telepon: {$validated['phone']}\n";
        if (!empty($validated['product'])) $msg .= "Produk: {$validated['product']}\n";
        if (!empty($validated['message'])) $msg .= "\nPesan: {$validated['message']}\n";
        $msg .= "\nTerima kasih.";

        // Build WA URL
        $nomor = $wa ? preg_replace('/[^0-9]/', '', $wa->nomor_wa) : '6281331148731';
        if (str_starts_with($nomor, '0')) $nomor = '62' . substr($nomor, 1);
        $waUrl = 'https://wa.me/' . $nomor . '?text=' . urlencode($msg);

        // Save lead
        $lead = Lead::create(array_merge($validated, [
            'source'       => $request->input('source', 'Website'),
            'page_url'     => $request->header('Referer'),
            'ip_address'   => $request->ip(),
            'device_type'  => \App\Models\AnalyticsEvent::detectDevice($request->userAgent() ?? ''),
            'wa_number'    => $nomor,
            'utm_source'   => $request->session()->get('utm_source'),
            'utm_medium'   => $request->session()->get('utm_medium'),
            'utm_campaign' => $request->session()->get('utm_campaign'),
            'utm_term'     => $request->session()->get('utm_term'),
            'utm_content'  => $request->session()->get('utm_content'),
        ]));

        // Track analytics
        AnalyticsEvent::record('lead', $request->header('Referer'), [
            'page_title' => 'Request Order - ' . $validated['name'],
        ]);

        // Return WA redirect URL to frontend
        return response()->json([
            'success' => true,
            'wa_url'  => $waUrl,
            'lead_id' => $lead->id,
        ]);
    }
}

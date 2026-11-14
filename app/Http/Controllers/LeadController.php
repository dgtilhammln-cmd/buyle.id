<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\WaSetting;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id'    => 'nullable|integer',
            'name'         => 'required|string|max:150',
            'phone'        => 'required|string|max:30',
            'city_company' => 'nullable|string|max:150',
            'company'      => 'nullable|string|max:150',
            'kebutuhan'    => 'nullable|string|max:2000',
            'message'      => 'nullable|string|max:2000',
        ]);

        $company = $request->input('company', $request->input('city_company', ''));
        $message = $request->input('message', $request->input('kebutuhan', ''));
        $sellerId = $request->input('seller_id', 1);

        // Get primary WA
        $wa = WaSetting::primary();
        $nomor = $wa ? preg_replace('/[^0-9]/', '', $wa->nomor_wa) : '6285179982373';
        if (str_starts_with($nomor, '0')) $nomor = '62' . substr($nomor, 1);

        $msg = "Halo Tim Buyle TIX,\n\n";
        $msg .= "Saya ingin konsultasi / kerja sama event:\n";
        $msg .= "• Nama: {$validated['name']}\n";
        $msg .= "• WhatsApp: {$validated['phone']}\n";
        if (!empty($company)) $msg .= "• EO / Penyelenggara: {$company}\n";
        if (!empty($message)) $msg .= "• Rencana / Kebutuhan: {$message}\n";
        $msg .= "\nMohon infonya. Terima kasih!";

        $waUrl = 'https://wa.me/' . $nomor . '?text=' . urlencode($msg);

        $lead = Lead::create([
            'user_id'     => $sellerId,
            'seller_id'   => $sellerId,
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'company'     => $company,
            'city'        => $company,
            'message'     => $message,
            'product'     => $message,
            'source'      => $request->input('source', 'Buyle TIX'),
            'page_url'    => $request->header('referer'),
            'ip_address'  => $request->ip(),
            'device_type' => $request->header('User-Agent'),
            'status'      => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead berhasil disimpan!',
            'wa_url'  => $waUrl,
            'lead'    => $lead
        ]);
    }
}

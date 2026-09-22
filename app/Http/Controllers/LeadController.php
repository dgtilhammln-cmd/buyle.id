<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seller_id'    => 'required|integer',
            'name'         => 'required|string|max:150',
            'phone'        => 'required|string|max:30',
            'city_company' => 'required|string|max:150',
            'kebutuhan'    => 'required|string|max:2000',
        ]);

        $lead = Lead::create([
            'user_id'     => $validated['seller_id'],
            'seller_id'   => $validated['seller_id'],
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'company'     => $validated['city_company'],
            'city'        => $validated['city_company'],
            'message'     => $validated['kebutuhan'],
            'product'     => $validated['kebutuhan'],
            'source'      => 'theme5_footer',
            'page_url'    => $request->header('referer'),
            'ip_address'  => $request->ip(),
            'device_type' => $request->header('User-Agent'),
            'status'      => 'new',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead berhasil disimpan!',
            'lead'    => $lead
        ]);
    }
}

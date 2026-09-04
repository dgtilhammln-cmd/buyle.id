<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportAbuseController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'report_type'    => 'required|string|max:50',
            'target_url'     => 'required|url|max:500',
            'target_name'    => 'nullable|string|max:255',
            'reason'         => 'required|string|in:penipuan,hak_cipta,konten_ilegal,spam,lainnya',
            'description'    => 'nullable|string|max:1000',
            'reporter_email' => 'nullable|email|max:255',
        ]);

        Report::create([
            'report_type'    => $validated['report_type'],
            'target_url'     => $validated['target_url'],
            'target_name'    => $validated['target_name'] ?? null,
            'reason'         => $validated['reason'],
            'description'    => $validated['description'] ?? null,
            'reporter_email' => $validated['reporter_email'] ?? null,
            'reporter_ip'    => $request->ip(),
            'status'         => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan Anda telah berhasil dikirim. Tim buyle.id akan segera menindaklanjutinya.',
        ]);
    }
}

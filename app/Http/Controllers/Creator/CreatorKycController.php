<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorKyc;
use App\Services\NikParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatorKycController extends Controller
{
    public function status()
    {
        $kyc = CreatorKyc::where('user_id', auth()->id())->first();
        return response()->json([
            'status'           => $kyc->status ?? 'none',
            'rejection_reason' => $kyc->rejection_reason ?? null,
            'submitted_at'     => $kyc?->submitted_at?->format('d M Y H:i'),
            'reviewed_at'      => $kyc?->reviewed_at?->format('d M Y H:i'),
            'full_name'        => $kyc->full_name ?? null,
            'nik'              => $kyc ? substr($kyc->nik ?? '', 0, 6) . str_repeat('*', max(0, strlen($kyc->nik ?? '') - 10)) . substr($kyc->nik ?? '', -4) : null,
        ]);
    }

    public function parseNik(Request $request)
    {
        $nik = $request->input('nik', '');
        return response()->json(NikParserService::parse($nik));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nik'              => 'required|string|size:16|regex:/^\d{16}$/',
            'full_name'        => 'required|string|min:3|max:255',
            'ktp_photo'        => 'required|string', // base64 WebP
            'selfie_photo'     => 'required|string', // base64 WebP
            'liveness_token'   => 'required|string',
        ]);

        $userId = auth()->id();

        // Check for existing pending/verified KYC
        $existing = CreatorKyc::where('user_id', $userId)->first();
        if ($existing && in_array($existing->status, ['pending', 'verified'])) {
            return response()->json(['success' => false, 'message' => 'Verifikasi sudah ' . ($existing->status === 'verified' ? 'disetujui.' : 'dalam proses peninjauan.')], 422);
        }

        // Parse NIK
        $parsed = NikParserService::parse($request->nik);
        if (!$parsed['valid']) {
            return response()->json(['success' => false, 'message' => $parsed['error']], 422);
        }

        // Save KTP photo
        $ktpPath    = $this->saveBase64Image($request->ktp_photo, "kyc/ktp/{$userId}_" . time() . "_ktp.webp");
        $selfiePath = $this->saveBase64Image($request->selfie_photo, "kyc/selfie/{$userId}_" . time() . "_selfie.webp");

        if (!$ktpPath || !$selfiePath) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan foto. Coba ulangi.'], 500);
        }

        CreatorKyc::updateOrCreate(
            ['user_id' => $userId],
            [
                'status'           => 'pending',
                'nik'              => $request->nik,
                'full_name'        => $request->full_name,
                'dob'              => $parsed['dob_raw'],
                'gender'           => $parsed['gender'],
                'province'         => $parsed['province'],
                'city'             => null,
                'ktp_photo_path'   => $ktpPath,
                'selfie_photo_path' => $selfiePath,
                'nik_parsed_data'  => $parsed,
                'liveness_token'   => $request->liveness_token,
                'submitted_at'     => now(),
                'reviewed_at'      => null,
                'reviewed_by'      => null,
                'rejection_reason' => null,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Pengajuan verifikasi berhasil dikirim. Admin akan meninjau dalam 1x24 jam.']);
    }

    private function saveBase64Image(string $base64, string $path): string|null
    {
        try {
            // Strip header if present (data:image/webp;base64,...)
            $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $data = base64_decode($data);
            if (!$data) return null;
            Storage::disk('private')->put($path, $data);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}

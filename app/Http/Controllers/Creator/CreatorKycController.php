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

    public function extractKtp(Request $request, \App\Services\AiVisionService $aiService)
    {
        $request->validate([
            'ktp_photo' => 'required',
        ]);

        try {
            if ($request->hasFile('ktp_photo')) {
                $file = $request->file('ktp_photo');
                $result = $aiService->extractKtpData($file->getRealPath());
            } else {
                $base64 = $request->input('ktp_photo');
                $result = $aiService->extractKtpData($base64);
            }

            // Also parse NIK if 16 digits extracted
            $parsedNik = null;
            if (!empty($result['nik']) && strlen($result['nik']) === 16) {
                $parsedNik = NikParserService::parse($result['nik']);
            }

            return response()->json([
                'success'    => true,
                'data'       => $result,
                'parsed_nik' => $parsedNik,
                'message'    => 'Data e-KTP berhasil diekstrak dengan AI!',
            ]);
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $isLimit = str_contains(strtolower($msg), 'limit') || 
                       str_contains(strtolower($msg), 'quota') || 
                       str_contains(strtolower($msg), '429') ||
                       str_contains(strtolower($msg), 'rate') ||
                       str_contains(strtolower($msg), 'credits');

            \Illuminate\Support\Facades\Log::warning('KYC AI Extraction failed: ' . $msg);

            return response()->json([
                'success'        => false,
                'quota_exceeded' => $isLimit,
                'message'        => $isLimit
                    ? 'Batas kuota layanan AI sementara sedang penuh. Sistem beralih ke Mode Verifikasi Mandiri yang aman: Cukup masukkan 16 digit NIK Anda, sistem lokal akan otomatis mendekode data wilayah KTP.'
                    : 'AI belum dapat membaca data e-KTP secara otomatis (' . substr($msg, 0, 120) . '). Anda dapat melengkapi formulir secara langsung.'
            ], 200);
        }
    }

    public function submit(Request $request)
    {
        $request->validate([
            'nik'                  => 'required|string|size:16|regex:/^\d{16}$/',
            'full_name'            => 'required|string|min:3|max:255',
            'ktp_photo'            => 'required|string', // base64 WebP
            'selfie_photo'         => 'required|string', // base64 WebP
            'liveness_token'       => 'required|string',
            'is_domicile_same'     => 'required',
            'ktp_address'          => 'nullable|string|max:500',
            'ktp_rt_rw'            => 'nullable|string|max:50',
            'ktp_subdistrict'      => 'nullable|string|max:100',
            'ktp_district'         => 'nullable|string|max:100',
            'ktp_city'             => 'nullable|string|max:100',
            'ktp_province'         => 'nullable|string|max:100',
            'domicile_province'    => 'nullable|string|max:100',
            'domicile_city'        => 'nullable|string|max:100',
            'domicile_district'    => 'nullable|string|max:100',
            'domicile_subdistrict' => 'nullable|string|max:100',
            'domicile_rt_rw'       => 'nullable|string|max:50',
            'domicile_postal_code' => 'nullable|string|max:20',
            'domicile_address'     => 'nullable|string|max:1000',
            'ai_extracted_data'    => 'nullable|array',
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

        $isSame = filter_var($request->is_domicile_same, FILTER_VALIDATE_BOOLEAN);

        if (!$isSame) {
            if (empty($request->domicile_province) || empty($request->domicile_city) || empty($request->domicile_district) || empty($request->domicile_address)) {
                return response()->json(['success' => false, 'message' => 'Data alamat domisili berbeda belum lengkap (Provinsi, Kota/Kab, Kecamatan, dan Detail Alamat wajib diisi).'], 422);
            }
        }

        // Save KTP photo
        $ktpPath    = $this->saveBase64Image($request->ktp_photo, "kyc/ktp/{$userId}_" . time() . "_ktp.webp");
        $selfiePath = $this->saveBase64Image($request->selfie_photo, "kyc/selfie/{$userId}_" . time() . "_selfie.webp");

        if (!$ktpPath || !$selfiePath) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan foto. Coba ulangi.'], 500);
        }

        $provinceKtp = $request->ktp_province ?: $parsed['province'];
        $cityKtp     = $request->ktp_city ?: null;

        CreatorKyc::updateOrCreate(
            ['user_id' => $userId],
            [
                'status'               => 'pending',
                'nik'                  => $request->nik,
                'full_name'            => $request->full_name,
                'dob'                  => $parsed['dob_raw'],
                'gender'               => $parsed['gender'],
                'province'             => $provinceKtp,
                'city'                 => $cityKtp,
                'ktp_address'          => $request->ktp_address,
                'ktp_rt_rw'            => $request->ktp_rt_rw,
                'ktp_subdistrict'      => $request->ktp_subdistrict,
                'ktp_district'         => $request->ktp_district,
                'is_domicile_same'     => $isSame,
                'domicile_province'    => $isSame ? $provinceKtp : $request->domicile_province,
                'domicile_city'        => $isSame ? $cityKtp : $request->domicile_city,
                'domicile_district'    => $isSame ? $request->ktp_district : $request->domicile_district,
                'domicile_subdistrict' => $isSame ? $request->ktp_subdistrict : $request->domicile_subdistrict,
                'domicile_rt_rw'       => $isSame ? $request->ktp_rt_rw : $request->domicile_rt_rw,
                'domicile_postal_code' => $isSame ? null : $request->domicile_postal_code,
                'domicile_address'     => $isSame ? $request->ktp_address : $request->domicile_address,
                'ktp_photo_path'       => $ktpPath,
                'selfie_photo_path'    => $selfiePath,
                'nik_parsed_data'      => $parsed,
                'ai_extracted_data'    => $request->ai_extracted_data,
                'liveness_token'       => $request->liveness_token,
                'submitted_at'         => now(),
                'reviewed_at'          => null,
                'reviewed_by'          => null,
                'rejection_reason'     => null,
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $imageKeys = ['hero_bg_image', 'hero_main_image', 'hero_secondary_image', 'about_image', 'about_c3_image', 'og_image_default', 'logo', 'favicon', 'coverage_map', 'ad_product_sidebar_1_image', 'ad_product_sidebar_2_image'];
        $data      = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            // Jika array, ubah menjadi JSON string agar bisa disimpan di DB (kecuali untuk file upload yang tidak ada di $data)
            if (is_array($value)) {
                // Filter array kosong dan reset index (array_values) untuk menghindari format object JSON yang salah
                $value = json_encode(array_values(array_filter($value)));
            }
            
            $existing = Setting::where('key', $key)->first();
            $type     = $existing?->type ?? 'text';
            if ($type !== 'image') {
                Setting::set($key, $value ?? '', $type);
            }
        }

        // Handle image uploads
        foreach ($request->allFiles() as $key => $file) {
            if (!$file->isValid()) continue;

            // Handle favicon separately — store original file as-is to preserve transparency
            if ($key === 'favicon') {
                $ext      = strtolower($file->getClientOriginalExtension());
                $rawData  = file_get_contents($file->getRealPath());

                if ($ext === 'svg') {
                    $filename = 'favicon_' . time() . '.svg';
                } elseif ($ext === 'ico') {
                    // Store ico as-is, also save a PNG copy from it
                    $filename = 'favicon_' . time() . '.ico';
                } else {
                    // PNG, WebP, JPG — store as PNG to ensure browser compatibility
                    // Re-encode via GD only to guarantee PNG format, preserving transparency
                    $src = $this->gdLoadRaw($file->getRealPath(), $file->getMimeType());
                    if ($src) {
                        $w = imagesx($src); $h = imagesy($src);
                        // Create fresh true-colour canvas with full alpha support
                        $out = imagecreatetruecolor($w, $h);
                        imagealphablending($out, false);
                        imagesavealpha($out, true);
                        // Fill with transparent
                        $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
                        imagefilledrectangle($out, 0, 0, $w, $h, $transparent);
                        imagealphablending($out, true);
                        imagecopy($out, $src, 0, 0, 0, 0, $w, $h);
                        imagedestroy($src);
                        ob_start();
                        imagealphablending($out, false);
                        imagesavealpha($out, true);
                        imagepng($out, null, 9);
                        $rawData = ob_get_clean();
                        imagedestroy($out);
                    }
                    $filename = 'favicon_' . time() . '.png';
                }

                $path = 'settings/' . $filename;
                Storage::disk('public')->put($path, $rawData);

                // Sync favicon file to all public root locations
                $storedFullPath = storage_path('app/public/' . $path);
                $syncTargets = [
                    public_path('favicon.ico'),
                    public_path('favicon.png'),
                    base_path('public_html/favicon.ico'),
                    base_path('public_html/favicon.png'),
                ];
                foreach ($syncTargets as $target) {
                    try {
                        $dir = dirname($target);
                        if (!is_dir($dir)) @mkdir($dir, 0755, true);
                        copy($storedFullPath, $target);
                    } catch (\Exception $e) {}
                }

                Setting::clearCache();
                Setting::set($key, $path, 'image');
                continue;
            }


            // Handle compro (PDF/Doc) separately
            if ($key === 'compro') {
                $path = 'settings/compro_' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->put($path, file_get_contents($file->getRealPath()));
                Setting::set($key, $path, 'file');
                continue;
            }

            if ($key === 'logo') {
                // NO AUTO-CROP: preserve 100% full logo aspect ratio!
                $path = $this->storeWebPNoCrop($file, 'settings', 1600, 800, 95);
                Setting::set($key, $path, 'image');
                continue;
            }

            if ($key === 'coverage_map') {
                $path = $this->storeWebP($file, 'settings', 2400, 1200, 90);
                Setting::set($key, $path, 'image');
                continue;
            }

            $path = $this->storeWebP($file, 'settings', 1920, 1080, 85);
            Setting::set($key, $path, 'image');
        }

        // Sync & Apply Mail configuration if present
        \App\Services\MailConfigService::updateEnv($data);
        \App\Services\MailConfigService::apply();

        Setting::clearCache();
        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Test Send Email via configured SMTP settings
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ], [
            'test_email.required' => 'Email tujuan tes wajib diisi.',
            'test_email.email'    => 'Format email tujuan tes tidak valid.',
        ]);

        try {
            \App\Services\MailConfigService::apply();

            $targetEmail = $request->test_email;
            $fromName    = config('mail.from.name', 'buyle.id');

            \Illuminate\Support\Facades\Mail::raw("Halo!\n\nIni adalah email pengujian konfigurasi SMTP dari {$fromName}.\n\nJika Anda menerima pesan ini, artinya pengiriman email (SMTP Hostinger) di buyle.id sudah AKTIF dan BERHASIL terhubung tanpa kendala! 🎉\n\nWaktu Tes: " . now()->format('Y-m-d H:i:s T'), function ($message) use ($targetEmail, $fromName) {
                $message->to($targetEmail)
                        ->subject("✅ Tes Koneksi SMTP Email — {$fromName}");
            });

            return back()->with('success', "✅ Tes Koneksi Berhasil! Email pengujian telah sukses dikirim ke: {$targetEmail}");
        } catch (\Throwable $e) {
            return back()->with('error', "❌ Gagal Mengirim Email: " . $e->getMessage());
        }
    }

    public function license()
    {
        $status     = Setting::where('key', 'site_license_status')->value('value') ?? 'active';
        $expiry     = Setting::where('key', 'site_license_expiry')->value('value') ?? '';
        $clientName = Setting::where('key', 'site_name')->value('value') ?? 'Klien';
        return view('admin.settings.license', compact('status', 'expiry', 'clientName'));
    }

    public function updateLicense(Request $request)
    {
        $request->validate([
            'status' => 'required|in:active,suspended',
            'expiry' => 'nullable|date',
        ]);

        Setting::updateOrCreate(
            ['key' => 'site_license_status'],
            ['value' => $request->status, 'type' => 'text', 'group' => 'license']
        );

        if ($request->expiry) {
            Setting::updateOrCreate(
                ['key' => 'site_license_expiry'],
                ['value' => $request->expiry, 'type' => 'text', 'group' => 'license']
            );
        }

        // Hapus cache license agar perubahan langsung berlaku
        \Illuminate\Support\Facades\Cache::forget('site_license_status');
        \Illuminate\Support\Facades\Cache::forget('site_license_expiry');
        Setting::clearCache();

        $msg = $request->status === 'suspended'
            ? 'Website berhasil dibekukan. Klien tidak dapat mengakses halaman depan.'
            : 'Website berhasil diaktifkan kembali! Periode lisensi telah diperbarui.';

        return back()->with('success', $msg);
    }
}


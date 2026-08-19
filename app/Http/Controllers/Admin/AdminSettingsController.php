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
        $imageKeys = ['hero_bg_image', 'hero_main_image', 'hero_secondary_image', 'about_image', 'about_c3_image', 'og_image_default', 'logo', 'favicon', 'coverage_map'];
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

            // Handle favicon separately — convert to PNG via GD for max compatibility
            if ($key === 'favicon') {
                $ext      = strtolower($file->getClientOriginalExtension());
                $realPath = $file->getRealPath();

                // Always save as PNG for browser favicon compatibility (unless SVG)
                if ($ext === 'svg') {
                    $filename = 'favicon_' . time() . '.svg';
                    $path     = 'settings/' . $filename;
                    Storage::disk('public')->put($path, file_get_contents($realPath));
                } else {
                    // Convert to PNG via GD
                    $img = $this->gdLoad($file);
                    ob_start();
                    imagepng($img, null, 9);
                    $pngData = ob_get_clean();
                    imagedestroy($img);

                    $filename = 'favicon_' . time() . '.png';
                    $path     = 'settings/' . $filename;
                    Storage::disk('public')->put($path, $pngData);

                    // Copy PNG to public_html root as favicon.ico (browsers accept PNG too)
                    $storedFullPath = storage_path('app/public/' . $path);
                    $publicHtmlPath = base_path('public_html/favicon.ico');
                    $publicPath     = public_path('favicon.ico');
                    try { copy($storedFullPath, $publicHtmlPath); } catch (\Exception $e) {}
                    try { copy($storedFullPath, $publicPath); } catch (\Exception $e) {}
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

        Setting::clearCache();
        return back()->with('success', 'Pengaturan berhasil disimpan!');
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


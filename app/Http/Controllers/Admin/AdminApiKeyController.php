<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminApiKeyController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.apikeys.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        
        foreach ($data as $key => $value) {
            $existing = Setting::where('key', $key)->first();
            $type     = $existing?->type ?? 'text';
            Setting::set($key, $value ?? '', $type);
        }

        return back()->with('success', 'API Keys berhasil diperbarui.');
    }
}

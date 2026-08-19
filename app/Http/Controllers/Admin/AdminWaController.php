<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaSetting;
use Illuminate\Http\Request;

class AdminWaController extends Controller
{
    public function index()
    {
        $waSettings = WaSetting::ordered()->get();
        return view('admin.wa.index', compact('waSettings'));
    }

    public function update(Request $request)
    {
        $ids = $request->input('ids', []);
        foreach ($ids as $id) {
            $wa = WaSetting::find($id);
            if ($wa) {
                $wa->update([
                    'label'          => $request->input("label.$id"),
                    'nomor_wa'       => preg_replace('/[^0-9]/', '', $request->input("nomor_wa.$id")),
                    'template_pesan' => $request->input("template_pesan.$id"),
                    'is_active'      => $request->boolean("is_active.$id"),
                    'is_primary'     => $request->input("primary") == $id,
                    'order'          => $request->input("order.$id", 0),
                ]);
            }
        }
        return back()->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'          => 'required|max:100',
            'nomor_wa'       => 'required',
            'template_pesan' => 'required',
        ]);

        WaSetting::create([
            'label'          => $request->label,
            'nomor_wa'       => preg_replace('/[^0-9]/', '', $request->nomor_wa),
            'template_pesan' => $request->template_pesan,
            'is_active'      => true,
            'is_primary'     => false,
            'order'          => WaSetting::max('order') + 1,
        ]);

        return back()->with('success', 'Nomor WhatsApp baru berhasil ditambahkan.');
    }

    public function destroy(int $id)
    {
        WaSetting::findOrFail($id)->delete();
        return back()->with('success', 'Nomor WhatsApp berhasil dihapus.');
    }
}

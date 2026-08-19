<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class AdminCourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::orderBy('order')->get();
        return view('admin.couriers.index', compact('couriers'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name'  => 'required|string|max:100',
            'code'  => 'required|string|max:50|unique:couriers,code',
            'type'  => 'required|in:expedition,custom',
        ]);
        $v['order'] = Courier::max('order') + 1;
        Courier::create($v);
        return back()->with('success', 'Kurir berhasil ditambahkan.');
    }

    public function update(Request $request, Courier $courier)
    {
        $v = $request->validate([
            'name'      => 'required|string|max:100',
        ]);
        $v['is_active'] = $request->boolean('is_active');
        $courier->update($v);
        return back()->with('success', 'Kurir berhasil diperbarui.');
    }

    public function toggleActive(Courier $courier)
    {
        $courier->update(['is_active' => !$courier->is_active]);
        return back()->with('success', $courier->is_active ? 'Kurir diaktifkan.' : 'Kurir dinonaktifkan.');
    }

    public function destroy(Courier $courier)
    {
        $courier->delete();
        return back()->with('success', 'Kurir berhasil dihapus.');
    }
}


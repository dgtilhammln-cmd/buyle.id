<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class AdminClientController extends Controller
{
    use HandlesImageUpload;

    public function index()
    {
        $clients = Client::ordered()->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|max:200',
            'city'      => 'nullable|max:100',
            'industry'  => 'nullable|max:100',
            'alt_text'  => 'nullable|max:200',
            'logo'      => 'nullable|image|max:2048',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $this->storeWebPNoCrop($request->file('logo'), 'clients', 600, 300);
        }

        Client::create($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name'      => 'required|max:200',
            'city'      => 'nullable|max:100',
            'industry'  => 'nullable|max:100',
            'alt_text'  => 'nullable|max:200',
            'logo'      => 'nullable|image|max:2048',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $this->deleteStorageFile($client->logo);
            $validated['logo'] = $this->storeWebPNoCrop($request->file('logo'), 'clients', 600, 300);
        }

        $client->update($validated);
        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        $this->deleteStorageFile($client->logo ?? null);
        $client->delete();
        return back()->with('success', 'Klien berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminAdminUserController extends Controller
{
    public static array $menuOptions = [
        'dashboard'   => 'Dashboard & Analytics',
        'articles'    => 'Artikel & FAQ',
        'orders'      => 'Pesanan & Omset',
        'products'    => 'Kategori, Whitelabel & Kupon',
        'buyers'      => 'Pengguna (Buyer)',
        'leads'       => 'Laporan Chat & Leads',
        'reports'     => 'Laporan Penyalahgunaan',
        'banners'     => 'Banner Hero, USP & Promo',
        'settings'    => 'Pengaturan, Notifikasi & API',
        'admin_users' => 'Manajemen Akun Admin',
    ];

    public function index(Request $request)
    {
        $q = trim($request->get('q'));

        $admins = User::whereIn('role', ['admin', 'super_admin'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.admin-users.index', [
            'admins'      => $admins,
            'q'           => $q,
            'menuOptions' => self::$menuOptions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:150',
            'email'            => 'required|email|max:150|unique:users,email',
            'password'         => 'required|string|min:6',
            'role'             => 'required|in:admin,super_admin',
            'menu_permissions' => 'nullable|array',
            'menu_permissions.*' => 'string|in:' . implode(',', array_keys(self::$menuOptions)),
        ]);

        $menuPermissions = $request->input('role') === 'super_admin'
            ? null
            : ($request->input('menu_permissions') ?? []);

        User::create([
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'password'         => Hash::make($validated['password']),
            'role'             => $validated['role'],
            'menu_permissions' => $menuPermissions,
            'is_active'        => true,
        ]);

        return back()->with('success', "Akun Admin \"{$validated['name']}\" berhasil dibuat!");
    }

    public function update(Request $request, User $adminUser)
    {
        abort_unless(in_array($adminUser->role, ['admin', 'super_admin']), 404);

        $validated = $request->validate([
            'name'             => 'required|string|max:150',
            'email'            => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($adminUser->id)],
            'role'             => 'required|in:admin,super_admin',
            'menu_permissions' => 'nullable|array',
            'menu_permissions.*' => 'string|in:' . implode(',', array_keys(self::$menuOptions)),
        ]);

        $menuPermissions = $request->input('role') === 'super_admin'
            ? null
            : ($request->input('menu_permissions') ?? []);

        $adminUser->update([
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'role'             => $validated['role'],
            'menu_permissions' => $menuPermissions,
        ]);

        return back()->with('success', "Data akun \"{$adminUser->name}\" berhasil diperbarui.");
    }

    public function updatePassword(Request $request, User $adminUser)
    {
        abort_unless(in_array($adminUser->role, ['admin', 'super_admin']), 404);

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $adminUser->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', "Password untuk akun \"{$adminUser->name}\" berhasil diubah!");
    }

    public function toggleActive(User $adminUser)
    {
        abort_unless(in_array($adminUser->role, ['admin', 'super_admin']), 404);

        if (auth()->id() === $adminUser->id) {
            return back()->with('error', 'Anda tidak dapat me-nonaktifkan akun Anda sendiri!');
        }

        $adminUser->update([
            'is_active' => !$adminUser->is_active,
        ]);

        $statusStr = $adminUser->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun \"{$adminUser->name}\" berhasil {$statusStr}.");
    }

    public function destroy(User $adminUser)
    {
        abort_unless(in_array($adminUser->role, ['admin', 'super_admin']), 404);

        if (auth()->id() === $adminUser->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $name = $adminUser->name;
        $adminUser->delete();

        return back()->with('success', "Akun admin \"{$name}\" berhasil dihapus.");
    }
}

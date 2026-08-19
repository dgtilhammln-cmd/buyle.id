<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('search');
        $users = User::where('role', '!=', 'admin')
            ->when($q, fn($query) => $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('phone', 'like', "%{$q}%");
            }))
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users', 'q'));
    }

    public function show(User $user)
    {
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->get();
        $addresses = Address::where('user_id', $user->id)->get();
        return view('admin.users.show', compact('user', 'orders', 'addresses'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password pengguna berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', $user->is_active ? 'Pengguna diaktifkan.' : 'Pengguna dinonaktifkan.');
    }

    public function destroyAddress(User $user, Address $address)
    {
        abort_unless($address->user_id === $user->id, 403);
        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\TicketPass;
use App\Exports\AllUsersExport;
use App\Exports\UserActivityExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('search');
        $persona = $request->get('persona');
        $source = $request->get('source');

        $users = User::where('role', '!=', 'admin')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%")
                        ->orWhere('nik', 'like', "%{$q}%")
                        ->orWhere('referral_source_name', 'like', "%{$q}%")
                        ->orWhere('media_partner_name', 'like', "%{$q}%");
                });
            })
            ->when($persona, fn($query) => $query->where('business_persona', $persona))
            ->when($source, fn($query) => $query->where('referral_source_type', $source))
            ->withCount(['orders', 'ticketPasses'])
            ->with(['referrer'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'persona', 'source'));
    }

    public function show(User $user)
    {
        $user->loadMissing(['referrer', 'creatorProfile']);

        // Riwayat Order beserta items produk & payment
        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'payment'])
            ->latest()
            ->get();

        // Alamat Pengiriman
        $addresses = Address::where('user_id', $user->id)->get();

        // Riwayat Tiket / E-Ticket Passes
        $tickets = TicketPass::where('user_id', $user->id)
            ->with(['product', 'order'])
            ->latest()
            ->get();

        // Total spending
        $totalSpending = $orders->sum('total');

        return view('admin.users.show', compact('user', 'orders', 'addresses', 'tickets', 'totalSpending'));
    }

    /**
     * Download seluruh data pengguna (All Users) ke format Excel (.xlsx) atau CSV
     */
    public function exportAll(Request $request)
    {
        $search = $request->get('search');
        $format = strtolower($request->get('format', 'xlsx'));
        $dateStr = date('Y-m-d_His');
        $export = new AllUsersExport($search);

        if ($format === 'csv') {
            return Excel::download($export, "buyle_all_users_{$dateStr}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download($export, "buyle_all_users_{$dateStr}.xlsx");
    }

    /**
     * Download seluruh aktivitas dan data spesifik 1 pengguna ke format Excel (.xlsx) atau CSV
     */
    public function exportUser(User $user, Request $request)
    {
        $format = strtolower($request->get('format', 'xlsx'));
        $dateStr = date('Y-m-d_His');
        $slug = Str::slug($user->username ?: $user->name, '_') ?: 'user';
        $export = new UserActivityExport($user);

        if ($format === 'csv') {
            return Excel::download($export, "buyle_activity_{$user->id}_{$slug}_{$dateStr}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        return Excel::download($export, "buyle_activity_{$user->id}_{$slug}_{$dateStr}.xlsx");
    }

    /**
     * Tampilan cetak / print preview ramah dokumen PDF untuk aktivitas pengguna
     */
    public function printUser(User $user)
    {
        $user->loadMissing(['referrer', 'creatorProfile']);

        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'payment'])
            ->latest()
            ->get();

        $addresses = Address::where('user_id', $user->id)->get();

        $tickets = TicketPass::where('user_id', $user->id)
            ->with(['product', 'order'])
            ->latest()
            ->get();

        $totalSpending = $orders->sum('total');

        return view('admin.users.print', compact('user', 'orders', 'addresses', 'tickets', 'totalSpending'));
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

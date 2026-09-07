<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Lead;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminNotificationController extends Controller
{
    /**
     * Tampilkan halaman pusat notifikasi lengkap.
     */
    public function index(Request $request)
    {
        $filterType = $request->query('type', 'all');

        // 1. Akun Baru (User)
        $newUsers = User::latest()
            ->take(30)
            ->get()
            ->map(function ($u) {
                return (object) [
                    'id'          => 'user_' . $u->id,
                    'type'        => 'user',
                    'category'    => 'Akun Baru',
                    'title'       => 'Pendaftaran User Baru: ' . $u->name,
                    'subtitle'    => $u->email . ($u->phone ? ' • ' . $u->phone : ''),
                    'created_at'  => $u->created_at,
                    'link'        => route('admin.users.show', $u->id),
                    'icon_bg'     => '#dcfce7',
                    'icon_color'  => '#1eb349',
                    'badge_class' => 'green',
                ];
            });

        // 2. Pembayaran Baru (Order Paid)
        $newPayments = Order::whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success->value))
            ->with(['user', 'items'])
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($o) {
                $itemNames = $o->items->pluck('product_name')->implode(', ');
                return (object) [
                    'id'          => 'order_' . $o->id,
                    'type'        => 'payment',
                    'category'    => 'Pembayaran Baru',
                    'title'       => 'Pembayaran Sukses #' . ($o->order_number ?? $o->id),
                    'subtitle'    => 'Rp ' . number_format($o->total, 0, ',', '.') . ' dari ' . ($o->user->name ?? 'Guest') . ($itemNames ? ' (' . $itemNames . ')' : ''),
                    'created_at'  => $o->created_at,
                    'link'        => route('admin.orders.show', $o->id),
                    'icon_bg'     => '#e0e7ff',
                    'icon_color'  => '#4f46e5',
                    'badge_class' => 'indigo',
                ];
            });

        // 3. Approval Whitelabel
        $whitelabelRequests = Product::where('is_whitelabel', true)
            ->where('whitelabel_approval_status', 'pending')
            ->with('seller')
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($p) {
                return (object) [
                    'id'          => 'wl_' . $p->id,
                    'type'        => 'whitelabel',
                    'category'    => 'Approval Whitelabel',
                    'title'       => 'Permintaan Approval Whitelabel: ' . $p->name,
                    'subtitle'    => 'Dari Seller: ' . ($p->seller->name ?? 'Seller') . ' • Harga WL: Rp ' . number_format($p->whitelabel_price ?? 0, 0, ',', '.'),
                    'created_at'  => $p->updated_at ?? $p->created_at,
                    'link'        => route('admin.whitelabel.index', ['status' => 'pending']),
                    'icon_bg'     => '#fef3c7',
                    'icon_color'  => '#d97706',
                    'badge_class' => 'amber',
                ];
            });

        // 4. Leads
        $leads = Lead::latest()
            ->take(30)
            ->get()
            ->map(function ($l) {
                return (object) [
                    'id'          => 'lead_' . $l->id,
                    'type'        => 'lead',
                    'category'    => 'Permintaan Lead',
                    'title'       => 'Lead Baru: ' . $l->name,
                    'subtitle'    => 'Produk: ' . ($l->product ?? 'Umum') . ' • No: ' . $l->phone,
                    'created_at'  => $l->created_at,
                    'link'        => route('admin.leads.show', $l->id),
                    'icon_bg'     => '#f1f5f9',
                    'icon_color'  => '#475569',
                    'badge_class' => 'slate',
                ];
            });

        // Merge & Sort chronologically
        $allNotifs = collect()
            ->concat($newUsers)
            ->concat($newPayments)
            ->concat($whitelabelRequests)
            ->concat($leads)
            ->sortByDesc('created_at');

        if ($filterType !== 'all') {
            $filteredNotifs = $allNotifs->where('type', $filterType)->values();
        } else {
            $filteredNotifs = $allNotifs->values();
        }

        $counts = [
            'all'        => $allNotifs->count(),
            'user'       => $newUsers->count(),
            'payment'    => $newPayments->count(),
            'whitelabel' => $whitelabelRequests->count(),
            'lead'       => $leads->count(),
        ];

        return view('admin.notifications.index', compact('filteredNotifs', 'filterType', 'counts'));
    }
}

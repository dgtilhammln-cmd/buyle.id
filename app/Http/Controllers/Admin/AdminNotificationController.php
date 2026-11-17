<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Lead;
use App\Models\PayoutRequest;
use App\Models\Wedding;
use App\Models\WeddingGift;
use App\Models\DomainOrder;
use App\Models\PosDeposit;
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

        // 2. Pembayaran Baru: E-Commerce, Produk Digital, Tiket Event, Resto POS
        $orderPayments = Order::where(function ($query) {
                $query->whereHas('payment', fn($q) => $q->whereIn('status', [
                    \App\Enums\PaymentStatus::Success->value,
                    'success',
                    'settlement',
                    'capture'
                ]))
                ->orWhereIn('status', [
                    \App\Enums\OrderStatus::Confirmed->value,
                    \App\Enums\OrderStatus::Processing->value,
                    \App\Enums\OrderStatus::Shipped->value,
                    \App\Enums\OrderStatus::Delivered->value,
                    'confirmed',
                    'processing',
                    'shipped',
                    'delivered'
                ]);
            })
            ->with(['user', 'items.product', 'payment'])
            ->latest()
            ->take(40)
            ->get()
            ->map(function ($o) {
                $firstItem = $o->items->first();
                $itemCount = $o->items->count();
                $itemName = $firstItem ? ($firstItem->product->title ?? $firstItem->product_name ?? 'Item') : 'Pesanan';
                if ($itemCount > 1) {
                    $itemName .= ' (+' . ($itemCount - 1) . ' item lainnya)';
                }

                $buyerName = $o->user->name ?? ($o->shipping_address['receiver_name'] ?? 'Pembeli');
                $method = strtoupper($o->payment->method ?? ($o->payment_method ?? 'Midtrans'));

                return (object) [
                    'id'          => 'order_' . $o->id,
                    'type'        => 'payment',
                    'subtype'     => 'order',
                    'category'    => 'Order E-Commerce & Tiket',
                    'title'       => 'Pembayaran Sukses #' . ($o->order_number ?? $o->id),
                    'subtitle'    => 'Rp ' . number_format($o->total, 0, ',', '.') . ' dari ' . $buyerName . ' via ' . $method . ' • ' . $itemName,
                    'created_at'  => $o->payment->paid_at ?? $o->created_at,
                    'link'        => route('admin.orders.show', $o->id),
                    'icon_bg'     => '#e0e7ff',
                    'icon_color'  => '#4f46e5',
                    'badge_class' => 'indigo',
                ];
            });

        // 3. Pembayaran Paket Undangan Digital (Wedding)
        $weddingPayments = Wedding::where(function ($q) {
                $q->where('payment_status', 'paid')
                  ->orWhereNotNull('paid_at')
                  ->orWhereNotNull('midtrans_order_id');
            })
            ->with('user')
            ->latest('updated_at')
            ->take(30)
            ->get()
            ->map(function ($w) {
                $couple = trim(($w->groom_nickname ?: $w->groom_name) . ' & ' . ($w->bride_nickname ?: $w->bride_name));
                $title = $couple ? "Aktivasi Undangan: {$couple}" : ($w->title ?: "Undangan Digital #{$w->id}");
                $creatorName = $w->user->name ?? 'Creator';
                $amount = (float) ($w->paid_amount ?: 250000);
                $amountStr = 'Rp ' . number_format($amount, 0, ',', '.');
                $date = $w->paid_at ?? $w->updated_at ?? $w->created_at;

                return (object) [
                    'id'          => 'wedding_' . $w->id,
                    'type'        => 'payment',
                    'subtype'     => 'wedding',
                    'category'    => 'Paket Undangan Digital',
                    'title'       => $title,
                    'subtitle'    => "Paket Undangan {$amountStr} dari Creator {$creatorName} • buyle.id/wedding/{$w->slug}",
                    'created_at'  => $date,
                    'link'        => url('/wedding/' . $w->slug),
                    'icon_bg'     => '#fce7f3',
                    'icon_color'  => '#be185d',
                    'badge_class' => 'pink',
                ];
            });

        // 4. Pembayaran Amplop Digital Undangan (Wedding Gift)
        $envelopePayments = WeddingGift::whereIn('payment_status', ['settlement', 'paid', 'success'])
            ->with(['wedding.user'])
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($g) {
                $weddingCouple = $g->wedding ? trim(($g->wedding->groom_nickname ?: $g->wedding->groom_name) . ' & ' . ($g->wedding->bride_nickname ?: $g->wedding->bride_name)) : 'Undangan';
                $amountStr = 'Rp ' . number_format($g->amount, 0, ',', '.');
                $feeStr = $g->platform_fee > 0 ? ' (Fee Buyle: Rp ' . number_format($g->platform_fee, 0, ',', '.') . ')' : '';

                return (object) [
                    'id'          => 'gift_' . $g->id,
                    'type'        => 'payment',
                    'subtype'     => 'wedding',
                    'category'    => 'Amplop Digital Tamu',
                    'title'       => "Amplop Digital {$amountStr} untuk {$weddingCouple}",
                    'subtitle'    => "Dari {$g->sender_name} via " . strtoupper($g->payment_method ?? 'Midtrans') . "{$feeStr}",
                    'created_at'  => $g->created_at,
                    'link'        => $g->wedding ? url('/wedding/' . $g->wedding->slug) : '#',
                    'icon_bg'     => '#fdf2f8',
                    'icon_color'  => '#db2777',
                    'badge_class' => 'pink',
                ];
            });

        // 5. Pembayaran Custom Domain Creator
        $domainPayments = DomainOrder::where('status', 'paid')
            ->with('user')
            ->latest('updated_at')
            ->take(30)
            ->get()
            ->map(function ($d) {
                $amountStr = 'Rp ' . number_format($d->amount, 0, ',', '.');
                $creatorName = $d->user->name ?? 'Creator';

                return (object) [
                    'id'          => 'domain_' . $d->id,
                    'type'        => 'payment',
                    'subtype'     => 'domain',
                    'category'    => 'Domain Creator',
                    'title'       => "Pembelian Domain: {$d->domain_name}",
                    'subtitle'    => "{$amountStr} dari {$creatorName} • Transaksi Midtrans: " . ($d->midtrans_transaction_id ?: 'Lunas'),
                    'created_at'  => $d->updated_at ?? $d->created_at,
                    'link'        => route('admin.orders.index', ['tab' => 'omset', 'tx_type' => 'domain']),
                    'icon_bg'     => '#eff6ff',
                    'icon_color'  => '#2563eb',
                    'badge_class' => 'blue',
                ];
            });

        // 6. Pembayaran Top-up Saldo Deposit POS
        $posDepositPayments = PosDeposit::where('status', 'success')
            ->with('creator.user')
            ->latest('paid_at')
            ->take(30)
            ->get()
            ->map(function ($dep) {
                $amountStr = 'Rp ' . number_format($dep->amount, 0, ',', '.');
                $creatorName = $dep->creator->user->name ?? ($dep->creator->brand_name ?? 'Kasir');

                return (object) [
                    'id'          => 'pos_dep_' . $dep->id,
                    'type'        => 'payment',
                    'subtype'     => 'pos',
                    'category'    => 'Deposit Saldo POS',
                    'title'       => "Top-up Saldo POS: {$amountStr}",
                    'subtitle'    => "Dari {$creatorName} (#{$dep->deposit_number}) via " . strtoupper($dep->payment_method ?? 'QRIS'),
                    'created_at'  => $dep->paid_at ?? $dep->created_at,
                    'link'        => route('admin.orders.index'),
                    'icon_bg'     => '#f0fdf4',
                    'icon_color'  => '#16a34a',
                    'badge_class' => 'green',
                ];
            });

        // Gabungkan seluruh jenis pembayaran Midtrans
        $allPayments = collect()
            ->concat($orderPayments)
            ->concat($weddingPayments)
            ->concat($envelopePayments)
            ->concat($domainPayments)
            ->concat($posDepositPayments)
            ->sortByDesc('created_at');

        // Khusus transaksi wedding untuk tab filter terpisah
        $weddingOnly = collect()
            ->concat($weddingPayments)
            ->concat($envelopePayments)
            ->sortByDesc('created_at');

        // 7. Approval Whitelabel
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

        // 8. Leads
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

        // 9. Permintaan Pencairan Dana (Payout)
        $payoutRequests = PayoutRequest::with('seller')
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($p) {
                $statusLabel = match($p->status) {
                    'pending'   => 'Menunggu Transfer',
                    'approved'  => 'Disetujui',
                    'processed' => 'Sudah Cair',
                    'rejected'  => 'Ditolak',
                    default     => ucfirst($p->status)
                };
                return (object) [
                    'id'          => 'payout_' . $p->id,
                    'type'        => 'payout',
                    'category'    => 'Pencairan Dana (' . $statusLabel . ')',
                    'title'       => 'Permintaan Pencairan Rp ' . number_format($p->amount, 0, ',', '.') . ' - ' . ($p->seller->name ?? 'Creator'),
                    'subtitle'    => 'Rekening ' . $p->bank_name . ' (' . $p->bank_account_number . ') a.n ' . $p->bank_account_name,
                    'created_at'  => $p->created_at,
                    'link'        => route('admin.payouts.index'),
                    'icon_bg'     => $p->status === 'pending' ? '#fef3c7' : '#dcfce7',
                    'icon_color'  => $p->status === 'pending' ? '#d97706' : '#16a34a',
                    'badge_class' => $p->status === 'pending' ? 'amber' : 'green',
                ];
            });

        // Merge & Sort chronologically
        $allNotifs = collect()
            ->concat($payoutRequests)
            ->concat($newUsers)
            ->concat($allPayments)
            ->concat($whitelabelRequests)
            ->concat($leads)
            ->sortByDesc('created_at');

        if ($filterType === 'payment') {
            $filteredNotifs = $allPayments->values();
        } elseif ($filterType === 'wedding') {
            $filteredNotifs = $weddingOnly->values();
        } elseif ($filterType !== 'all') {
            $filteredNotifs = $allNotifs->where('type', $filterType)->values();
        } else {
            $filteredNotifs = $allNotifs->values();
        }

        $counts = [
            'all'        => $allNotifs->count(),
            'payment'    => $allPayments->count(),
            'wedding'    => $weddingOnly->count(),
            'payout'     => $payoutRequests->count(),
            'user'       => $newUsers->count(),
            'whitelabel' => $whitelabelRequests->count(),
            'lead'       => $leads->count(),
        ];

        return view('admin.notifications.index', compact('filteredNotifs', 'filterType', 'counts'));
    }
}

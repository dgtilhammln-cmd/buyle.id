<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Courier;
use App\Models\Shipment;
use App\Models\DomainOrder;
use App\Models\PayoutRequest;
use App\Models\PosDeposit;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminOrderController extends Controller
{
    // Tab config: [label, status values or null for all, key]
    private array $tabs = [
        'all'        => ['label' => 'Semua',         'statuses' => null],
        'processing' => ['label' => 'Perlu Dikirim', 'statuses' => ['processing']],
        'shipped'    => ['label' => 'Dikirim',       'statuses' => ['shipped']],
        'completed'  => ['label' => 'Selesai',       'statuses' => ['confirmed','completed','delivered']],
        'omset'      => ['label' => 'Riwayat Omset & Keuangan', 'statuses' => null],
    ];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        if (!array_key_exists($tab, $this->tabs)) $tab = 'all';

        $period    = $request->get('period', '30d');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
        $q         = trim($request->get('q', ''));
        $txType    = $request->get('tx_type', 'all');
        if (!in_array($txType, ['all', 'buyer', 'creator', 'domain', 'deposit'])) {
            $txType = 'all';
        }

        // Helper untuk filter tanggal konsisten
        $applyDate = function($qBuilder) use ($period, $startDate, $endDate) {
            if ($startDate && $endDate) {
                $qBuilder->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            } else {
                if ($period === '7d') {
                    $qBuilder->where('created_at', '>=', now()->subDays(7));
                } elseif ($period === '30d') {
                    $qBuilder->where('created_at', '>=', now()->subDays(30));
                } elseif ($period === '1y') {
                    $qBuilder->where('created_at', '>=', now()->subYears(1));
                }
            }
        };

        // Query dasar pesanan produk pembeli
        $buyerQuery = Order::with(['user', 'items.product.seller', 'payment', 'shipment'])
            ->where('status', '!=', OrderStatus::Pending)
            ->orderByDesc('created_at');
        $applyDate($buyerQuery);

        if ($q) {
            $buyerQuery->where(function($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%"))
                    ->orWhereHas('items.product', fn($p) => $p->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%"))
                    ->orWhereHas('items.product.seller', fn($cu) => $cu->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('store_name', 'like', "%{$q}%")
                        ->orWhere('store_slug', 'like', "%{$q}%"));
            });
        }

        // Stats standard untuk 4 tab pesanan produk biasa
        $statsBase = Order::query()->where('status', '!=', OrderStatus::Pending);
        $applyDate($statsBase);

        $stats = [
            'total'      => (clone $statsBase)->count(),
            'processing' => (clone $statsBase)->whereIn('status', ['processing'])->count(),
            'shipped'    => (clone $statsBase)->whereIn('status', ['shipped'])->count(),
            'completed'  => (clone $statsBase)->whereIn('status', ['confirmed', 'completed', 'delivered'])->count(),
            'revenue'    => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->sum('total'),
        ];

        // Tab counts
        $counts = [];
        foreach ($this->tabs as $key => $cfg) {
            $cQuery = clone $statsBase;
            if ($cfg['statuses'] !== null) {
                $cQuery->whereIn('status', $cfg['statuses']);
            }
            $counts[$key] = $cQuery->count();
        }

        // ═════════════════════════════════════════════════════════════════════
        // KONDISIONAL KHUSUS TAB OMSET (SATU PINTU FINANCIAL LEDGER)
        // ═════════════════════════════════════════════════════════════════════
        $omsetItems = null;
        $revenueStats = [];
        $txTypeCounts = [];

        if ($tab === 'omset') {
            // 1. Query Domain Orders (Creator)
            $domainQuery = DomainOrder::with('user.creatorProfile')->orderByDesc('created_at');
            $applyDate($domainQuery);
            if ($q) {
                $domainQuery->where(function($sub) use ($q) {
                    $sub->where('domain_name', 'like', "%{$q}%")
                        ->orWhere('extension', 'like', "%{$q}%")
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('username', 'like', "%{$q}%"));
                });
            }

            // 2. Query Pindah Saldo Toko ke Deposit Kasir POS (Creator)
            $payoutDepositQuery = PayoutRequest::with('seller.creatorProfile')
                ->where('bank_name', 'like', '%Deposit Kasir POS%')
                ->where('status', 'processed')
                ->orderByDesc('created_at');
            $applyDate($payoutDepositQuery);
            if ($q) {
                $payoutDepositQuery->where(function($sub) use ($q) {
                    $sub->where('notes', 'like', "%{$q}%")
                        ->orWhere('bank_account_name', 'like', "%{$q}%")
                        ->orWhereHas('seller', fn($u) => $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%"));
                });
            }

            // 3. Query Top-up Deposit POS via QRIS / Midtrans (Creator)
            $directPosDepositQuery = PosDeposit::with('creator.user')
                ->whereIn('status', ['paid', 'success'])
                ->orderByDesc('created_at');
            $applyDate($directPosDepositQuery);
            if ($q) {
                $directPosDepositQuery->where(function($sub) use ($q) {
                    $sub->where('deposit_number', 'like', "%{$q}%")
                        ->orWhereHas('creator.user', fn($u) => $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%"));
                });
            }

            // ── Perhitungan Statistik Finansial Keseluruhan ──
            $buyerPaidBase = (clone $buyerQuery)->whereNotIn('status', ['cancelled', 'refunded']);
            $buyerGross    = (float) $buyerPaidBase->sum('total');
            $buyerSubtotal = (float) $buyerPaidBase->sum('subtotal');
            $buyerAdminFee = (float) $buyerPaidBase->sum('admin_fee');
            $buyerPlatFee  = (float) $buyerPaidBase->sum('platform_fee');
            $buyerProfit   = $buyerAdminFee + $buyerPlatFee;
            $buyerCount    = (clone $buyerQuery)->count();

            $domainPaidBase = (clone $domainQuery)->whereIn('status', ['paid', 'completed']);
            $domainGross    = (float) $domainPaidBase->sum('amount');
            $domainAdminFee = (float) $domainPaidBase->sum('admin_fee');
            $domainPlatFee  = (float) $domainPaidBase->sum('platform_fee');
            $domainTax      = (float) $domainPaidBase->sum('tax_amount');

            $buyPrices = \App\Http\Controllers\Creator\CreatorDomainController::getBuyPrices();

            // Total Modal Beli Registrar dan Keuntungan Bersih Sebenarnya
            $domainBaseCost = 0;
            $domainProfit   = 0;
            foreach ($domainPaidBase->get() as $dpb) {
                $ext = strtolower($dpb->extension ?? '');
                $buyCost = (float)($buyPrices[$ext] ?? 0);
                $domainBaseCost += $buyCost;
                // Rumus Keuntungan: (Harga Jual - Harga Beli Modal) + Platform Fee + Admin Fee + Pajak 11% = Gross - BuyCost
                $domainProfit += max(0, (float)$dpb->amount - $buyCost);
            }

            $domainCount    = (clone $domainQuery)->count();
            $domainPaidCount= $domainPaidBase->count();

            $depositTransferGross = (float) (clone $payoutDepositQuery)->sum('amount');
            $depositDirectGross   = (float) (clone $directPosDepositQuery)->sum('amount');
            $depositTotalGross    = $depositTransferGross + $depositDirectGross;
            $depositCount         = (clone $payoutDepositQuery)->count() + (clone $directPosDepositQuery)->count();

            $revenueStats = [
                'gross'         => $buyerGross + $domainGross + $depositTotalGross,
                'subtotal'      => $buyerSubtotal + $domainBaseCost,
                'admin_fee'     => $buyerAdminFee + $domainAdminFee,
                'platform_fee'  => $buyerPlatFee + $domainPlatFee,
                'tax'           => $domainTax,
                'profit'        => $buyerProfit + $domainProfit,
                'buyer_gross'   => $buyerGross,
                'buyer_profit'  => $buyerProfit,
                'buyer_count'   => $buyerCount,
                'domain_gross'  => $domainGross,
                'domain_base'   => $domainBaseCost,
                'domain_profit' => $domainProfit,
                'domain_tax'    => $domainTax,
                'domain_count'  => $domainCount,
                'domain_paid'   => $domainPaidCount,
                'deposit_gross' => $depositTotalGross,
                'deposit_count' => $depositCount,
            ];

            // Update stats tab total omset card
            $stats['revenue'] = $revenueStats['gross'];

            // Counts untuk pill filter transaksi
            $txTypeCounts = [
                'all'     => $buyerCount + $domainCount + $depositCount,
                'buyer'   => $buyerCount,
                'creator' => $domainCount + $depositCount,
                'domain'  => $domainCount,
                'deposit' => $depositCount,
            ];

            // ── Kumpulkan dan seragamkan baris transaksi ──
            $rows = collect();

            // 1. Pesanan Pembeli
            if (in_array($txType, ['all', 'buyer'])) {
                foreach ($buyerQuery->get() as $o) {
                    $types = $o->items->map(function($i) {
                        $pt = $i->product?->product_type ?? $i->product?->type ?? '';
                        if ($pt === 'ticket') return 'Tiket';
                        if (in_array($pt, ['external_link', 'digital', 'file'])) return 'Digital';
                        return 'Fisik';
                    })->unique()->implode(', ');
                    $txTypeLabel = $types ?: 'Produk';
                    $channel = $o->payment?->method ? strtoupper($o->payment->method) : ($o->payment?->gateway ? strtoupper($o->payment->gateway) : 'MIDTRANS');
                    $profit = (float)($o->admin_fee ?? 0) + (float)($o->platform_fee ?? 0);

                    $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
                    $stBadge = match($statusVal) {
                        'confirmed', 'processing' => ['#059669', '#E6F4EA', 'Perlu Dikirim'],
                        'shipped'                 => ['#047857', '#D1E7DD', 'Dikirim'],
                        'completed', 'delivered'  => ['#166534', '#DCFCE7', 'Selesai'],
                        'cancelled'               => ['#DC2626', '#FEE2E2', 'Dibatalkan'],
                        default                   => ['#475569', '#F1F5F9', ucfirst($statusVal)]
                    };

                    $creatorName = $o->items->first()?->product?->seller?->name ?? 'Creator';

                    $rows->push((object)[
                        'id'           => 'ORD-' . $o->id,
                        'source_type'  => 'buyer_order',
                        'created_at'   => $o->created_at,
                        'ref_number'   => '#' . $o->order_number,
                        'url'          => route('admin.orders.show', $o),
                        'party_name'   => $o->user->name ?? 'Pembeli',
                        'party_email'  => $o->user->email ?? '-',
                        'party_role'   => 'Pembeli',
                        'category'     => 'Pembeli (' . $txTypeLabel . ')',
                        'creator_name' => $creatorName,
                        'channel'      => $channel,
                        'base_amount'  => (float)$o->subtotal,
                        'buy_cost'     => 0,
                        'admin_fee'    => (float)($o->admin_fee ?? 0),
                        'platform_fee' => (float)($o->platform_fee ?? 0),
                        'tax_amount'   => 0,
                        'gross'        => (float)$o->total,
                        'profit'       => $profit,
                        'status'       => $statusVal,
                        'status_label' => $stBadge[2],
                        'status_color' => $stBadge[0],
                        'status_bg'    => $stBadge[1],
                        'can_update'   => false,
                        'raw'          => $o,
                    ]);
                }
            }

            // 2. Transaksi Domain Creator
            if (in_array($txType, ['all', 'domain', 'creator'])) {
                foreach ($domainQuery->get() as $d) {
                    $ext = strtolower($d->extension ?? '');
                    $buyCost = (float)($buyPrices[$ext] ?? 0);
                    $isPaid = in_array($d->status, ['paid', 'completed']);
                    // Keuntungan = (Harga Jual - Harga Beli) + Platform Fee + Admin Fee + Pajak 11%
                    $profit = $isPaid ? max(0, (float)$d->amount - $buyCost) : 0;

                    $stBadge = match($d->status) {
                        'completed' => ['#166534', '#DCFCE7', 'Sudah Berhasil'],
                        'paid'      => ['#0284C7', '#E0F2FE', 'Lunas (Paid)'],
                        'pending'   => ['#D97706', '#FEF3C7', 'Pending'],
                        'rejected'  => ['#DC2626', '#FEE2E2', 'Ditolak'],
                        'cancelled' => ['#64748B', '#F1F5F9', 'Dibatalkan'],
                        default     => ['#475569', '#F1F5F9', ucfirst($d->status)]
                    };

                    $creatorName = $d->user->name ?? 'Creator';

                    $rows->push((object)[
                        'id'           => 'DOM-' . $d->id,
                        'source_type'  => 'domain_order',
                        'created_at'   => $d->created_at,
                        'ref_number'   => '#DOM-' . str_pad($d->id, 5, '0', STR_PAD_LEFT),
                        'url'          => $d->user ? route('admin.creator-resources.show', $d->user->id) : null,
                        'party_name'   => $creatorName,
                        'party_email'  => $d->user->email ?? '-',
                        'party_role'   => 'Creator',
                        'category'     => 'Domain: ' . $d->domain_name,
                        'creator_name' => $creatorName,
                        'channel'      => 'MIDTRANS',
                        'base_amount'  => (float)$d->base_amount,
                        'buy_cost'     => $buyCost,
                        'admin_fee'    => (float)($d->admin_fee ?? 0),
                        'platform_fee' => (float)($d->platform_fee ?? 0),
                        'tax_amount'   => (float)($d->tax_amount ?? 0),
                        'gross'        => (float)$d->amount,
                        'profit'       => $profit,
                        'status'       => $d->status,
                        'status_label' => $stBadge[2],
                        'status_color' => $stBadge[0],
                        'status_bg'    => $stBadge[1],
                        'can_update'   => true,
                        'raw'          => $d,
                    ]);
                }
            }

            // 3. Pindah Saldo Kasir POS (Creator)
            if (in_array($txType, ['all', 'deposit', 'creator'])) {
                foreach ($payoutDepositQuery->get() as $p) {
                    $creatorName = $p->seller->name ?? 'Creator';
                    $rows->push((object)[
                        'id'           => 'TRF-' . $p->id,
                        'source_type'  => 'pos_deposit',
                        'created_at'   => $p->created_at,
                        'ref_number'   => '#TRF-POS-' . $p->id,
                        'url'          => null,
                        'party_name'   => $creatorName,
                        'party_email'  => $p->seller->email ?? '-',
                        'party_role'   => 'Creator',
                        'category'     => 'Deposit POS (Pindah Saldo)',
                        'creator_name' => $creatorName,
                        'channel'      => 'SALDO CREATOR',
                        'base_amount'  => (float)$p->amount,
                        'buy_cost'     => (float)$p->amount,
                        'admin_fee'    => 0,
                        'platform_fee' => 0,
                        'tax_amount'   => 0,
                        'gross'        => (float)$p->amount,
                        'profit'       => 0,
                        'status'       => 'processed',
                        'status_label' => 'Berhasil',
                        'status_color' => '#166534',
                        'status_bg'    => '#DCFCE7',
                        'can_update'   => false,
                        'raw'          => $p,
                    ]);
                }

                foreach ($directPosDepositQuery->get() as $dp) {
                    $user = $dp->creator?->user;
                    $creatorName = $user->name ?? 'Creator';
                    $rows->push((object)[
                        'id'           => 'DEP-' . $dp->id,
                        'source_type'  => 'pos_deposit',
                        'created_at'   => $dp->created_at,
                        'ref_number'   => '#' . $dp->deposit_number,
                        'url'          => null,
                        'party_name'   => $creatorName,
                        'party_email'  => $user->email ?? '-',
                        'party_role'   => 'Creator',
                        'category'     => 'Deposit POS (Top-Up)',
                        'creator_name' => $creatorName,
                        'channel'      => strtoupper($dp->payment_method ?: 'QRIS'),
                        'base_amount'  => (float)$dp->amount,
                        'buy_cost'     => (float)$dp->amount,
                        'admin_fee'    => 0,
                        'platform_fee' => 0,
                        'tax_amount'   => 0,
                        'gross'        => (float)$dp->amount,
                        'profit'       => 0,
                        'status'       => $dp->status,
                        'status_label' => $dp->status === 'paid' ? 'Lunas' : ucfirst($dp->status),
                        'status_color' => $dp->status === 'paid' ? '#166534' : '#D97706',
                        'status_bg'    => $dp->status === 'paid' ? '#DCFCE7' : '#FEF3C7',
                        'can_update'   => false,
                        'raw'          => $dp,
                    ]);
                }
            }

            // Urutkan berdasarkan waktu transaksi terbaru
            $rows = $rows->sortByDesc('created_at')->values();

            // Paginate
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $pagedRows = $rows->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $omsetItems = new LengthAwarePaginator(
                $pagedRows,
                $rows->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );

            $orders = $omsetItems;
        } else {
            // Tab pesanan biasa: filter status tab & paginate
            $statuses = $this->tabs[$tab]['statuses'];
            if ($statuses) {
                $buyerQuery->whereIn('status', $statuses);
            }
            $orders = $buyerQuery->paginate(20)->withQueryString();

            $revenueStats = [
                'gross'        => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->sum('total'),
                'subtotal'     => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->sum('subtotal'),
                'admin_fee'    => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->sum('admin_fee'),
                'platform_fee' => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->sum('platform_fee'),
                'profit'       => (clone $statsBase)->whereNotIn('status', ['pending', 'cancelled', 'refunded'])->selectRaw('SUM(COALESCE(admin_fee,0) + COALESCE(platform_fee,0)) as profit')->value('profit') ?? 0,
            ];
        }

        return view('admin.orders.index', [
            'orders'       => $orders,
            'omsetItems'   => $omsetItems,
            'tab'          => $tab,
            'tabs'         => $this->tabs,
            'counts'       => $counts,
            'stats'        => $stats,
            'revenueStats' => $revenueStats,
            'txType'       => $txType,
            'txTypeCounts' => $txTypeCounts,
            'q'            => $q,
            'period'       => $period,
            'start_date'   => $startDate ?? '',
            'end_date'     => $endDate ?? '',
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment', 'shipment', 'couponUsage']);
        $nextStatuses = $order->status->nextStatuses();
        $couriers = Courier::where('is_active', true)->orderBy('order')->get();
        return view('admin.orders.show', compact('order', 'nextStatuses', 'couriers'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);
        $newStatus = OrderStatus::from($request->status);
        $allowed = $order->status->nextStatuses();
        if (!in_array($newStatus, $allowed)) {
            return back()->with('error', 'Perubahan status tidak diizinkan.');
        }
        $order->update(['status' => $newStatus->value]);
        return back()->with('success', 'Status pesanan berhasil diperbarui ke: ' . $newStatus->label());
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $trackingNumber = trim($request->tracking_number);

        if ($order->shipment) {
            // Only update tracking_number — keep courier info from checkout
            // Use updateOrIgnore to avoid unique constraint if same value
            \App\Models\Shipment::where('id', $order->shipment->id)
                ->update(['tracking_number' => $trackingNumber ?: null]);
        } else {
            // No shipment yet — create one using courier info from order notes / fallback
            $order->shipment()->create([
                'courier_name'    => $order->shipment->courier_name ?? '—',
                'courier_service' => $order->shipment->courier_service ?? null,
                'tracking_number' => $trackingNumber ?: null,
                'status'          => 'shipped',
            ]);
        }
        return back()->with('success', 'Nomor resi berhasil disimpan. Buyer bisa tracking real-time sekarang!');
    }

    public function updateShippingCost(Request $request, Order $order)
    {
        $request->validate([
            'shipping_cost' => 'required|numeric|min:0',
            'courier_name'  => 'nullable|string|max:100',
        ]);

        $newShippingCost = (float) $request->shipping_cost;
        $newTotal = ($order->subtotal - $order->discount) + $newShippingCost;

        $order->update([
            'shipping_cost' => $newShippingCost,
            'total'         => max(0, $newTotal),
        ]);

        // Update Midtrans payment amount if still pending
        if ($order->payment && $order->payment->status->value === 'pending') {
            $order->payment->update(['amount' => max(0, $newTotal)]);
        }

        return back()->with('success', 'Ongkos kirim berhasil diperbarui. Total pesanan: Rp ' . number_format($newTotal, 0, ',', '.'));
    }

    public function export(Request $request)
    {
        $start = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $end   = $request->get('end_date', now()->format('Y-m-d'));
        $format = $request->get('format', 'xlsx');

        if ($format === 'pdf') {
            $orders = Order::with(['user', 'items', 'payment', 'shipment'])
                ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Hitung total untuk PDF laporan pesanan
            $pdfTotals = [
                'subtotal' => $orders->sum('subtotal'),
                'shipping' => $orders->sum('shipping_cost'),
                'discount' => $orders->sum('discount'),
                'total'    => $orders->sum('total'),
            ];

            $pdf = \PDF::loadView('admin.orders.pdf', compact('orders', 'start', 'end', 'pdfTotals'))->setPaper('a4', 'landscape');
            return $pdf->download("Laporan_Pesanan_{$start}_to_{$end}.pdf");
        }

        $export = new \App\Exports\OrdersExport($start, $end);

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, "Laporan_Pesanan_{$start}_to_{$end}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        return \Maatwebsite\Excel\Facades\Excel::download($export, "Laporan_Pesanan_{$start}_to_{$end}.xlsx");
    }

    /**
     * Export Laporan Omset & Keuangan (Buyer + Domain Creator + Deposit POS)
     * Menghasilkan laporan lengkap dengan total di baris paling bawah.
     */
    public function exportOmset(Request $request)
    {
        $start   = $request->get('start_date', now()->subDays(30)->format('Y-m-d'));
        $end     = $request->get('end_date', now()->format('Y-m-d'));
        $format  = $request->get('format', 'xlsx');
        $txType  = $request->get('tx_type', 'all');
        if (!in_array($txType, ['all', 'buyer', 'creator', 'domain', 'deposit'])) {
            $txType = 'all';
        }

        $buyPrices = \App\Http\Controllers\Creator\CreatorDomainController::getBuyPrices();

        // ── Query Buyer Orders ──
        $buyerQuery = Order::with(['user', 'items.product.seller', 'payment'])
            ->where('status', '!=', OrderStatus::Pending)
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        // ── Query Domain Orders ──
        $domainQuery = DomainOrder::with('user')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at');

        // ── Query Pindah Saldo POS ──
        $payoutDepositQuery = PayoutRequest::with('seller')
            ->where('bank_name', 'like', '%Deposit Kasir POS%')
            ->where('status', 'processed')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at');

        // ── Query Top-up Deposit POS ──
        $directPosDepositQuery = PosDeposit::with('creator.user')
            ->whereIn('status', ['paid', 'success'])
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->orderByDesc('created_at');

        // ── Kumpulkan rows ──
        $rows = collect();

        if (in_array($txType, ['all', 'buyer'])) {
            foreach ($buyerQuery->get() as $o) {
                $types = $o->items->map(function($i) {
                    $pt = $i->product?->product_type ?? $i->product?->type ?? '';
                    if ($pt === 'ticket') return 'Tiket';
                    if (in_array($pt, ['external_link', 'digital', 'file'])) return 'Digital';
                    return 'Fisik';
                })->unique()->implode(', ');
                $txTypeLabel = $types ?: 'Produk';
                $channel = $o->payment?->method ? strtoupper($o->payment->method) : ($o->payment?->gateway ? strtoupper($o->payment->gateway) : 'MIDTRANS');
                $profit = (float)($o->admin_fee ?? 0) + (float)($o->platform_fee ?? 0);
                $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
                $stBadge = match($statusVal) {
                    'confirmed', 'processing' => ['Perlu Dikirim'],
                    'shipped'                 => ['Dikirim'],
                    'completed', 'delivered'  => ['Selesai'],
                    'cancelled'               => ['Dibatalkan'],
                    default                   => [ucfirst($statusVal)]
                };

                $rows->push((object)[
                    'created_at'   => $o->created_at,
                    'ref_number'   => '#' . $o->order_number,
                    'party_name'   => $o->user->name ?? 'Pembeli',
                    'party_email'  => $o->user->email ?? '-',
                    'category'     => 'Pembeli (' . $txTypeLabel . ')',
                    'channel'      => $channel,
                    'base_amount'  => (float)$o->subtotal,
                    'admin_fee'    => (float)($o->admin_fee ?? 0),
                    'platform_fee' => (float)($o->platform_fee ?? 0),
                    'tax_amount'   => 0,
                    'gross'        => (float)$o->total,
                    'profit'       => $profit,
                    'status_label' => $stBadge[0],
                ]);
            }
        }

        if (in_array($txType, ['all', 'domain', 'creator'])) {
            foreach ($domainQuery->get() as $d) {
                $ext     = strtolower($d->extension ?? '');
                $buyCost = (float)($buyPrices[$ext] ?? 0);
                $isPaid  = in_array($d->status, ['paid', 'completed']);
                $profit  = $isPaid ? max(0, (float)$d->amount - $buyCost) : 0;
                $stLabel = match($d->status) {
                    'completed' => 'Sudah Berhasil',
                    'paid'      => 'Lunas (Paid)',
                    'pending'   => 'Pending',
                    'rejected'  => 'Ditolak',
                    'cancelled' => 'Dibatalkan',
                    default     => ucfirst($d->status)
                };

                $rows->push((object)[
                    'created_at'   => $d->created_at,
                    'ref_number'   => '#DOM-' . str_pad($d->id, 5, '0', STR_PAD_LEFT),
                    'party_name'   => $d->user->name ?? 'Creator',
                    'party_email'  => $d->user->email ?? '-',
                    'category'     => 'Domain: ' . $d->domain_name,
                    'channel'      => 'MIDTRANS',
                    'base_amount'  => (float)$d->base_amount,
                    'admin_fee'    => (float)($d->admin_fee ?? 0),
                    'platform_fee' => (float)($d->platform_fee ?? 0),
                    'tax_amount'   => (float)($d->tax_amount ?? 0),
                    'gross'        => (float)$d->amount,
                    'profit'       => $profit,
                    'status_label' => $stLabel,
                ]);
            }
        }

        if (in_array($txType, ['all', 'deposit', 'creator'])) {
            foreach ($payoutDepositQuery->get() as $p) {
                $rows->push((object)[
                    'created_at'   => $p->created_at,
                    'ref_number'   => '#TRF-POS-' . $p->id,
                    'party_name'   => $p->seller->name ?? 'Creator',
                    'party_email'  => $p->seller->email ?? '-',
                    'category'     => 'Deposit POS (Pindah Saldo)',
                    'channel'      => 'SALDO CREATOR',
                    'base_amount'  => (float)$p->amount,
                    'admin_fee'    => 0,
                    'platform_fee' => 0,
                    'tax_amount'   => 0,
                    'gross'        => (float)$p->amount,
                    'profit'       => 0,
                    'status_label' => 'Berhasil',
                ]);
            }

            foreach ($directPosDepositQuery->get() as $dp) {
                $user = $dp->creator?->user;
                $rows->push((object)[
                    'created_at'   => $dp->created_at,
                    'ref_number'   => '#' . $dp->deposit_number,
                    'party_name'   => $user->name ?? 'Creator',
                    'party_email'  => $user->email ?? '-',
                    'category'     => 'Deposit POS (Top-Up)',
                    'channel'      => strtoupper($dp->payment_method ?: 'QRIS'),
                    'base_amount'  => (float)$dp->amount,
                    'admin_fee'    => 0,
                    'platform_fee' => 0,
                    'tax_amount'   => 0,
                    'gross'        => (float)$dp->amount,
                    'profit'       => 0,
                    'status_label' => $dp->status === 'paid' ? 'Lunas' : ucfirst($dp->status),
                ]);
            }
        }

        $rows = $rows->sortByDesc('created_at')->values();

        // ── Hitung Grand Total ──
        $totals = [
            'base_amount'  => $rows->sum('base_amount'),
            'admin_fee'    => $rows->sum('admin_fee'),
            'platform_fee' => $rows->sum('platform_fee'),
            'tax_amount'   => $rows->sum('tax_amount'),
            'gross'        => $rows->sum('gross'),
            'profit'       => $rows->sum('profit'),
        ];

        $filterLabel = match($txType) {
            'buyer'   => 'Pesanan Pembeli',
            'domain'  => 'Domain Creator',
            'deposit' => 'Deposit POS Creator',
            'creator' => 'Semua Creator',
            default   => 'Semua Transaksi',
        };
        $filename = "Laporan_Omset_" . str_replace(' ', '_', $filterLabel) . "_{$start}_sd_{$end}";

        if ($format === 'pdf') {
            $pdf = \PDF::loadView('admin.orders.omset_pdf', [
                'rows'    => $rows,
                'totals'  => $totals,
                'start'   => $start,
                'end'     => $end,
                'txType'  => $filterLabel,
                'period'  => "$start s/d $end",
            ])->setPaper('a4', 'landscape');
            return $pdf->download("{$filename}.pdf");
        }

        $export = new \App\Exports\OmsetExport($rows, $totals, $start, $end);

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, "{$filename}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        return \Maatwebsite\Excel\Facades\Excel::download($export, "{$filename}.xlsx");
    }
}

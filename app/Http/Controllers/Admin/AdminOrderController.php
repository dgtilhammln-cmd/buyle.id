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
    // 4 Pilar Utama Bisnis Buyle.id + 1 Rekap Keuangan
    private array $tabs = [
        'marketplace' => ['label' => 'Marketplace',       'code' => 'MKT', 'desc' => 'Produk Fisik & Digital'],
        'ticketing'   => ['label' => 'Tiket Event',       'code' => 'TIX', 'desc' => 'E-Tiket & Registrasi'],
        'pos'         => ['label' => 'Kasir POS',         'code' => 'POS', 'desc' => 'Kasir Outlet & Dine-In'],
        'wedding'     => ['label' => 'Undangan & Amplop', 'code' => 'WED', 'desc' => 'Paket Undangan & Kado Amplop'],
        'finance'     => ['label' => 'Rekap Arus Kas',    'code' => 'FIN', 'desc' => 'Rekonsiliasi Profit & Payout'],
    ];

    public function index(Request $request)
    {
        $rawTab = $request->get('tab', 'marketplace');
        $tabMapping = [
            'all'        => 'marketplace',
            'processing' => 'marketplace',
            'shipped'    => 'marketplace',
            'completed'  => 'marketplace',
            'omset'      => 'finance',
        ];
        $tab = $tabMapping[$rawTab] ?? $rawTab;
        if (!array_key_exists($tab, $this->tabs)) {
            $tab = 'marketplace';
        }

        $subStatus = $request->get('status', in_array($rawTab, ['processing', 'shipped', 'completed']) ? $rawTab : 'all');
        $period    = $request->get('period', '30d');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');
        $q         = trim($request->get('q', ''));

        // Helper filter rentang tanggal
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

        // Helper filter pencarian order
        $applySearch = function($qBuilder) use ($q) {
            if (!$q) return;
            $qBuilder->where(function($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('user_phone', 'like', "%{$q}%")
                    ->orWhere('receiver_name', 'like', "%{$q}%")
                    ->orWhere('shipping_address->customer_name', 'like', "%{$q}%")
                    ->orWhere('shipping_address->customer_phone', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%"))
                    ->orWhereHas('items.product', fn($p) => $p->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%"))
                    ->orWhereHas('items.product.seller', fn($cu) => $cu->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('store_name', 'like', "%{$q}%"));
            });
        };

        // -------------------------------------------------------------
        // HITUNG COUNTS SETIAP TAB UTAMA
        // -------------------------------------------------------------
        $mktCountQuery = Order::query()->where('status', '!=', OrderStatus::Pending)
            ->where(function($sq) {
                $sq->whereNull('source')->orWhere('source', '!=', 'pos');
            })->where(function($sq) {
                $sq->whereNull('shipping_address->is_pos')->orWhere('shipping_address->is_pos', false);
            })->whereDoesntHave('items.product', fn($p) => $p->where('product_type', 'ticket'));
        $applyDate($mktCountQuery);

        $tixCountQuery = Order::query()->where('status', '!=', OrderStatus::Pending)
            ->where(function($sq) {
                $sq->whereHas('items.product', fn($p) => $p->where('product_type', 'ticket'))
                   ->orWhere('order_number', 'like', 'TIX-%');
            });
        $applyDate($tixCountQuery);

        $posCountQuery = Order::query()->where('status', '!=', OrderStatus::Pending)
            ->where(function($sq) {
                $sq->where('source', 'pos')
                   ->orWhere('shipping_address->is_pos', true)
                   ->orWhere('order_number', 'like', 'POS-%');
            });
        $applyDate($posCountQuery);

        $wedCountQuery = \App\Models\WeddingGift::query();
        $applyDate($wedCountQuery);

        $payoutCountQuery = PayoutRequest::query();
        $applyDate($payoutCountQuery);

        $counts = [
            'marketplace' => $mktCountQuery->count(),
            'ticketing'   => $tixCountQuery->count(),
            'pos'         => $posCountQuery->count(),
            'wedding'     => $wedCountQuery->count(),
            'finance'     => $payoutCountQuery->count(),
        ];

        // -------------------------------------------------------------
        // EKSEKUSI DATA PER TAB AKTIF
        // -------------------------------------------------------------
        $kpi = [
            'gross'       => 0,
            'partner_net' => 0,
            'profit'      => 0,
            'total_items' => 0,
        ];
        $subStatusCounts = ['all' => 0, 'processing' => 0, 'shipped' => 0, 'completed' => 0];
        $financeStats = [];

        if ($tab === 'marketplace') {
            $baseQuery = Order::with(['user', 'items.product.seller', 'payment', 'shipment'])
                ->where('status', '!=', OrderStatus::Pending)
                ->where(function($sq) {
                    $sq->whereNull('source')->orWhere('source', '!=', 'pos');
                })->where(function($sq) {
                    $sq->whereNull('shipping_address->is_pos')->orWhere('shipping_address->is_pos', false);
                })->whereDoesntHave('items.product', fn($p) => $p->where('product_type', 'ticket'))
                ->orderByDesc('created_at');
            $applyDate($baseQuery);
            $applySearch($baseQuery);

            // Substatus counts
            $subStatusCounts['all']        = (clone $baseQuery)->count();
            $subStatusCounts['processing'] = (clone $baseQuery)->whereIn('status', ['processing'])->count();
            $subStatusCounts['shipped']    = (clone $baseQuery)->whereIn('status', ['shipped'])->count();
            $subStatusCounts['completed']  = (clone $baseQuery)->whereIn('status', ['confirmed', 'completed', 'delivered'])->count();

            // KPI
            $paidMkt = (clone $baseQuery)->whereNotIn('status', ['cancelled', 'refunded']);
            $gross      = (float)$paidMkt->sum('total');
            $subtotal   = (float)$paidMkt->sum('subtotal');
            $platFee    = (float)$paidMkt->sum('platform_fee');
            $adminFee   = (float)$paidMkt->sum('admin_fee');
            $kpi['gross']       = $gross;
            $kpi['partner_net'] = max(0, $subtotal - $platFee);
            $kpi['profit']      = $platFee + $adminFee;
            $kpi['total_items'] = $subStatusCounts['all'];

            // Filter substatus
            if ($subStatus === 'processing') {
                $baseQuery->whereIn('status', ['processing']);
            } elseif ($subStatus === 'shipped') {
                $baseQuery->whereIn('status', ['shipped']);
            } elseif ($subStatus === 'completed') {
                $baseQuery->whereIn('status', ['confirmed', 'completed', 'delivered']);
            }

            $orders = $baseQuery->paginate(20)->withQueryString();

        } elseif ($tab === 'ticketing') {
            $baseQuery = Order::with(['user', 'items.product.seller', 'payment', 'ticketPasses'])
                ->where('status', '!=', OrderStatus::Pending)
                ->where(function($sq) {
                    $sq->whereHas('items.product', fn($p) => $p->where('product_type', 'ticket'))
                       ->orWhere('order_number', 'like', 'TIX-%');
                })
                ->orderByDesc('created_at');
            $applyDate($baseQuery);
            $applySearch($baseQuery);

            $paidTix = (clone $baseQuery)->whereNotIn('status', ['cancelled', 'refunded']);
            $gross      = (float)$paidTix->sum('total');
            $subtotal   = (float)$paidTix->sum('subtotal');
            $platFee    = (float)$paidTix->sum('platform_fee');
            $adminFee   = (float)$paidTix->sum('admin_fee');
            $kpi['gross']       = $gross;
            $kpi['partner_net'] = max(0, $subtotal - $platFee);
            $kpi['profit']      = $platFee + $adminFee;
            $kpi['total_items'] = (clone $baseQuery)->count();

            $orders = $baseQuery->paginate(20)->withQueryString();

        } elseif ($tab === 'pos') {
            $baseQuery = Order::with(['user', 'items.product', 'payment'])
                ->where('status', '!=', OrderStatus::Pending)
                ->where(function($sq) {
                    $sq->where('source', 'pos')
                       ->orWhere('shipping_address->is_pos', true)
                       ->orWhere('order_number', 'like', 'POS-%');
                })
                ->orderByDesc('created_at');
            $applyDate($baseQuery);
            $applySearch($baseQuery);

            $paidPos = (clone $baseQuery)->whereNotIn('status', ['cancelled', 'refunded']);
            $gross      = (float)$paidPos->sum('total');
            $subtotal   = (float)$paidPos->sum('subtotal');
            $platFee    = (float)$paidPos->sum('platform_fee');
            $adminFee   = (float)$paidPos->sum('admin_fee');
            $kpi['gross']       = $gross;
            $kpi['partner_net'] = max(0, $gross - $platFee - $adminFee);
            $kpi['profit']      = $platFee + $adminFee;
            $kpi['total_items'] = (clone $baseQuery)->count();

            $orders = $baseQuery->paginate(20)->withQueryString();

        } elseif ($tab === 'wedding') {
            $giftQuery = \App\Models\WeddingGift::with(['wedding.user', 'guest'])->orderByDesc('created_at');
            $applyDate($giftQuery);
            if ($q) {
                $giftQuery->where(function($sub) use ($q) {
                    $sub->where('sender_name', 'like', "%{$q}%")
                        ->orWhere('midtrans_order_id', 'like', "%{$q}%")
                        ->orWhereHas('wedding', fn($w) => $w->where('slug', 'like', "%{$q}%")
                            ->orWhere('groom_name', 'like', "%{$q}%")
                            ->orWhere('bride_name', 'like', "%{$q}%"));
                });
            }

            $settledGifts = (clone $giftQuery)->where('payment_status', 'settlement');
            $settledGross = 0;
            $coupleNet    = 0;
            $feeBuyle     = 0;

            foreach ($settledGifts->get() as $sg) {
                $sgFee = 5000;
                $settledGross += ((float)$sg->amount + $sgFee);
                $coupleNet    += (float)$sg->amount;
                $feeBuyle     += $sgFee;
            }

            $kpi['gross']       = $settledGross;
            $kpi['partner_net'] = $coupleNet;
            $kpi['profit']      = $feeBuyle;
            $kpi['total_items'] = (clone $giftQuery)->count();

            // Transform ke objek standar
            $rawGifts = $giftQuery->get()->map(function($g) {
                $sgFee = 5000;
                $isPaid = $g->payment_status === 'settlement';
                $gross = (float)$g->amount + ($isPaid ? $sgFee : 0);
                $coupleNet = (float)$g->amount;
                $profit = $isPaid ? $sgFee : 0;
                $groom = $g->wedding->groom_nickname ?? $g->wedding->groom_name ?? 'Pengantin';
                $bride = $g->wedding->bride_nickname ?? $g->wedding->bride_name ?? '';
                $coupleStr = $bride ? "{$groom} & {$bride}" : $groom;

                return (object)[
                    'id'            => 'AMP-' . $g->id,
                    'pillar'        => 'AMP',
                    'order_number'  => $g->midtrans_order_id ?: ('AMP-' . $g->id),
                    'created_at'    => $g->created_at,
                    'customer_name' => $g->sender_name ?: 'Tamu Undangan',
                    'customer_info' => 'Kado untuk: ' . $coupleStr,
                    'partner_name'  => $g->wedding->user->name ?? 'Pengantin',
                    'channel'       => strtoupper($g->payment_method ?? 'QRIS'),
                    'gross'         => $gross,
                    'partner_net'   => $coupleNet,
                    'profit'        => $profit,
                    'status'        => $isPaid ? 'Lunas' : ($g->payment_status === 'expire' ? 'Expired' : ucfirst($g->payment_status)),
                    'status_color'  => $isPaid ? '#15803D' : ($g->payment_status === 'expire' ? '#DC2626' : '#D97706'),
                    'status_bg'     => $isPaid ? '#DCFCE7' : ($g->payment_status === 'expire' ? '#FEE2E2' : '#FEF3C7'),
                    'raw'           => $g,
                ];
            });

            // Paginate
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $pagedRows = $rawGifts->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $orders = new LengthAwarePaginator(
                $pagedRows,
                $rawGifts->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );

        } else {
            // Tab Finance (Rekap Arus Kas & Payout)
            $payoutWithdrawalQuery = PayoutRequest::with('seller.creatorProfile')->orderByDesc('created_at');
            $applyDate($payoutWithdrawalQuery);
            if ($q) {
                $payoutWithdrawalQuery->where(function($sub) use ($q) {
                    $sub->where('bank_name', 'like', "%{$q}%")
                        ->orWhere('bank_account_number', 'like', "%{$q}%")
                        ->orWhere('notes', 'like', "%{$q}%")
                        ->orWhereHas('seller', fn($u) => $u->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%"));
                });
            }

            // Kumpulkan seluruh data payout
            $payoutProcessed = (float) (clone $payoutWithdrawalQuery)->whereIn('status', ['processed', 'approved'])->sum('amount');
            $payoutPending   = (float) (clone $payoutWithdrawalQuery)->where('status', 'pending')->sum('amount');
            $payoutPendingCount = (clone $payoutWithdrawalQuery)->where('status', 'pending')->count();

            // Hitung akumulasi gross & profit dari seluruh pilar
            $allOrders = Order::query()->whereNotIn('status', ['cancelled', 'refunded', 'pending']);
            $applyDate($allOrders);
            $totalOrderGross  = (float)$allOrders->sum('total');
            $totalOrderPlat   = (float)$allOrders->sum('platform_fee');
            $totalOrderAdmin  = (float)$allOrders->sum('admin_fee');
            $totalOrderProfit = $totalOrderPlat + $totalOrderAdmin;

            // Amplop nikah
            $allGifts = \App\Models\WeddingGift::query()->where('payment_status', 'settlement');
            $applyDate($allGifts);
            $totalGiftAmount = (float)$allGifts->sum('amount');
            $totalGiftFee    = (float)$allGifts->count() * 5000;
            $totalGiftGross  = $totalGiftAmount + $totalGiftFee;

            $totalGrossAll   = $totalOrderGross + $totalGiftGross;
            $totalProfitAll  = $totalOrderProfit + $totalGiftFee;
            $totalPartnerNet = max(0, $totalGrossAll - $totalProfitAll);
            $outstandingNet  = max(0, $totalPartnerNet - $payoutProcessed - $payoutPending);

            $kpi['gross']       = $totalGrossAll;
            $kpi['partner_net'] = $totalPartnerNet;
            $kpi['profit']      = $totalProfitAll;
            $kpi['total_items'] = (clone $payoutWithdrawalQuery)->count();

            $financeStats = [
                'total_gross'          => $totalGrossAll,
                'total_partner_net'    => $totalPartnerNet,
                'total_profit'         => $totalProfitAll,
                'payout_processed'     => $payoutProcessed,
                'payout_pending'       => $payoutPending,
                'payout_pending_count' => $payoutPendingCount,
                'outstanding'          => $outstandingNet,
            ];

            // Tampilkan daftar mutasi payout sebagai tabel utama di tab finance
            $payoutRows = $payoutWithdrawalQuery->get()->map(function($p) {
                $sellerName = $p->seller->name ?? 'Creator';
                $isSuccess = in_array($p->status, ['processed', 'approved']);
                return (object)[
                    'id'            => 'WD-' . $p->id,
                    'pillar'        => 'WD',
                    'order_number'  => $p->notes ? $p->notes : ('WD-' . str_pad($p->id, 5, '0', STR_PAD_LEFT)),
                    'created_at'    => $p->created_at,
                    'customer_name' => $sellerName,
                    'customer_info' => 'Pencairan Dana ke: ' . $p->bank_name . ' (' . $p->bank_account_number . ')',
                    'partner_name'  => $sellerName,
                    'channel'       => strtoupper($p->bank_name),
                    'gross'         => -(float)$p->amount,
                    'partner_net'   => (float)$p->amount,
                    'profit'        => 0,
                    'status'        => $isSuccess ? 'Success' : ($p->status === 'rejected' ? 'Ditolak' : 'Menunggu'),
                    'status_color'  => $isSuccess ? '#15803D' : ($p->status === 'rejected' ? '#DC2626' : '#D97706'),
                    'status_bg'     => $isSuccess ? '#DCFCE7' : ($p->status === 'rejected' ? '#FEE2E2' : '#FEF3C7'),
                    'raw'           => $p,
                ];
            });

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 20;
            $pagedRows = $payoutRows->slice(($currentPage - 1) * $perPage, $perPage)->values();
            $orders = new LengthAwarePaginator(
                $pagedRows,
                $payoutRows->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
            );
        }

        return view('admin.orders.index', [
            'orders'          => $orders,
            'tab'             => $tab,
            'tabs'            => $this->tabs,
            'counts'          => $counts,
            'kpi'             => $kpi,
            'subStatus'       => $subStatus,
            'subStatusCounts' => $subStatusCounts,
            'financeStats'    => $financeStats,
            'q'               => $q,
            'period'          => $period,
            'start_date'      => $startDate ?? '',
            'end_date'        => $endDate ?? '',
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
        if (!in_array($txType, ['all', 'buyer', 'creator', 'domain', 'deposit', 'pos'])) {
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

        foreach ($buyerQuery->get() as $o) {
            $isPos = ($o->source === 'pos') || (!empty($o->shipping_address['is_pos']));

            if ($txType === 'buyer' && $isPos) continue;
            if (in_array($txType, ['pos', 'deposit']) && !$isPos) continue;
            if ($txType === 'domain') continue;

            $types = $o->items->map(function($i) {
                $pt = $i->product?->product_type ?? $i->product?->type ?? '';
                if ($pt === 'ticket') return 'Tiket';
                if (in_array($pt, ['external_link', 'digital', 'file'])) return 'Digital';
                return 'Fisik';
            })->unique()->implode(', ');
            $txTypeLabel = $types ?: 'Produk';
            $shipAddr = is_array($o->shipping_address) ? $o->shipping_address : [];
            $posMethod = $shipAddr['payment_method'] ?? null;
            $channel = $o->payment?->method ? strtoupper($o->payment->method) : ($posMethod ? strtoupper($posMethod) : ($o->payment?->gateway ? strtoupper($o->payment->gateway) : ($isPos ? 'KASIR POS' : 'MIDTRANS')));
            $profit = (float)($o->admin_fee ?? 0) + (float)($o->platform_fee ?? 0);
            $statusVal = is_object($o->status) ? $o->status->value : (string)$o->status;
            $stBadge = match($statusVal) {
                'confirmed', 'processing' => ['Berhasil'],
                'shipped'                 => ['Dikirim'],
                'completed', 'delivered'  => ['Selesai'],
                'cancelled'               => ['Dibatalkan'],
                default                   => [ucfirst($statusVal)]
            };

            $partyName = $isPos ? ($shipAddr['name'] ?? $shipAddr['receiver_name'] ?? 'Pelanggan Kasir') : ($o->user->name ?? ($shipAddr['receiver_name'] ?? 'Pembeli'));
            $partyEmail = $isPos ? ($shipAddr['email'] ?? ($shipAddr['phone'] ?? '-')) : ($o->user->email ?? '-');
            $categoryLabel = $isPos ? ('POS (Kasir - ' . $txTypeLabel . ')') : ('Pembeli (' . $txTypeLabel . ')');

            $rows->push((object)[
                'created_at'   => $o->created_at,
                'ref_number'   => '#' . $o->order_number,
                'party_name'   => $partyName,
                'party_email'  => $partyEmail,
                'category'     => $categoryLabel,
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

        if (in_array($txType, ['all', 'deposit', 'pos', 'creator'])) {
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
            'buyer'   => 'Pesanan Pembeli Online',
            'pos'     => 'POS (Kasir & Deposit)',
            'deposit' => 'POS (Kasir & Deposit)',
            'domain'  => 'Domain Creator',
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

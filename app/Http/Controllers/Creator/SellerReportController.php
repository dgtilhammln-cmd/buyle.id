<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Enums\PaymentStatus;
use App\Models\AnalyticsEvent;
use App\Models\CreatorProfile;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf;

class SellerReportController extends Controller
{
    /**
     * Hitung date range berdasarkan filter param.
     */
    private function getDateRange(Request $request): array
    {
        $filter  = $request->query('filter', '30');
        $endDate = Carbon::now()->endOfDay();

        $startDate = match ($filter) {
            'today'      => Carbon::now()->startOfDay(),
            'yesterday'  => Carbon::yesterday()->startOfDay(),
            '7'          => Carbon::now()->subDays(7)->startOfDay(),
            '30'         => Carbon::now()->subDays(30)->startOfDay(),
            '90'         => Carbon::now()->subDays(90)->startOfDay(),
            'this_month' => Carbon::now()->startOfMonth(),
            'last_month' => Carbon::now()->subMonth()->startOfMonth(),
            'this_year'  => Carbon::now()->startOfYear(),
            'all'        => Carbon::create(2020, 1, 1)->startOfDay(),
            'custom'     => $request->filled('start_date')
                            ? Carbon::parse($request->query('start_date'))->startOfDay()
                            : Carbon::now()->subDays(30)->startOfDay(),
            default      => Carbon::now()->subDays(30)->startOfDay(),
        };

        if ($filter === 'custom' && $request->filled('end_date')) {
            $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
        } elseif ($filter === 'yesterday') {
            $endDate = Carbon::yesterday()->endOfDay();
        } elseif ($filter === 'last_month') {
            $endDate = Carbon::now()->subMonth()->endOfMonth();
        }

        return [$filter, $startDate, $endDate];
    }

    /**
     * Base query untuk orders seller atau reseller yang sudah bayar (Khusus Toko Online / Non-POS).
     */
    private function paidOrdersBaseQuery(int $sellerId, Carbon $startDate, Carbon $endDate)
    {
        return Order::whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success->value))
            ->where(function($q) {
                $q->where('source', '!=', 'pos')->orWhereNull('source');
            })
            ->where(function($orQ) use ($sellerId) {
                $orQ->whereHas('items.product', fn($q) => $q->where('seller_id', $sellerId))
                    ->orWhereHas('items', fn($q) => $q->where('reseller_id', $sellerId));
            })
            ->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Menampilkan Laporan Penjualan komprehensif.
     */
    public function index(Request $request)
    {
        $seller = auth()->user();
        [$filter, $startDate, $endDate] = $this->getDateRange($request);

        // ── 1. Orders (Paid) ───────────────────────────────────────────────────
        $allOrders = $this->paidOrdersBaseQuery($seller->id, $startDate, $endDate)
            ->with([
                'user',
                'payment',
                'shipment',
                'items' => fn($q) => $q->where(function($iq) use ($seller) {
                    $iq->where('seller_id', $seller->id)
                       ->orWhere('reseller_id', $seller->id)
                       ->orWhereHas('product', fn($p) => $p->where('seller_id', $seller->id));
                }),
                'items.product',
                'items.seller',
                'items.reseller',
            ])
            ->orderByDesc('created_at')
            ->get();

        $totalOrders = $allOrders->count();

        // Calculate sales & net earnings (Original creator gets creator_earnings, Reseller gets reseller_margin)
        $totalSales = $allOrders->sum(function($o) use ($seller) {
            return $o->items->sum(function($item) use ($seller) {
                if ((int)$item->reseller_id === (int)$seller->id) {
                    return (float) ($item->reseller_margin > 0 ? $item->reseller_margin : $item->subtotal);
                }
                return (float) ($item->creator_earnings > 0 ? $item->creator_earnings : $item->subtotal);
            });
        });

        $salesByDate = $allOrders
            ->groupBy(fn($o) => $o->created_at->format('Y-m-d'))
            ->map(function($group) use ($seller) {
                return (float)$group->sum(function($o) use ($seller) {
                    return $o->items->sum(function($item) use ($seller) {
                        if ((int)$item->reseller_id === (int)$seller->id) {
                            return (float) ($item->reseller_margin > 0 ? $item->reseller_margin : $item->subtotal);
                        }
                        return (float) ($item->creator_earnings > 0 ? $item->creator_earnings : $item->subtotal);
                    });
                });
            })
            ->toArray();

        // ── 2. Visitor Stats ────────────────────────────────────────────────────
        // Guard: tabel product_visits mungkin belum ada di server lama
        $totalVisitors  = 0;
        $uniqueVisitors = 0;
        $visitorsByDate = [];
        if (Schema::hasTable('product_visits')) {
            $visitRows      = ProductVisit::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get(['session_id', 'created_at']);
            $totalVisitors  = $visitRows->count();
            $uniqueVisitors = $visitRows->pluck('session_id')->unique()->filter()->count();
            // Group by date for Traffic Wave chart
            $visitorsByDate = $visitRows
                ->groupBy(fn($v) => $v->created_at->format('Y-m-d'))
                ->map(fn($g) => $g->count())
                ->toArray();
        }

        // ── 3. Top Products (by visits count) ──────────────────────────────────
        $topProducts = Product::where('seller_id', $seller->id)
            ->withCount([
                'visits as visits_count' => fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]),
            ])
            ->withSum([
                'orderItems as sold_amount' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('order.payment', fn($p) => $p->where('status', PaymentStatus::Success->value))
                      ->whereBetween('order_items.created_at', [$startDate, $endDate]);
                },
            ], 'subtotal')
            ->orderByDesc('visits_count')
            ->limit(10)
            ->get();

        // ── 4. UTM Sources ──────────────────────────────────────────────────────
        // Guard: kolom utm_source mungkin belum ada, juga GROUP BY pakai kolom raw
        // agar aman di MySQL strict mode (no COALESCE in GROUP BY)
        $utmSources = collect();
        if (Schema::hasColumn('orders', 'utm_source')) {
            $rawRows = $this->paidOrdersBaseQuery($seller->id, $startDate, $endDate)
                ->select('utm_source', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
                ->groupBy('utm_source')
                ->orderByDesc('count')
                ->get();

            // Map null/empty ke label yang mudah dibaca, lalu merge sama-sama "Direct"
            $utmSources = $rawRows->map(function ($row) {
                $row->utm_source = ($row->utm_source && $row->utm_source !== '')
                    ? $row->utm_source
                    : 'Organic / Direct';
                return $row;
            })->groupBy('utm_source')->map(function ($group, $key) {
                return (object) [
                    'utm_source' => $key,
                    'count'      => $group->sum('count'),
                    'revenue'    => $group->sum('revenue'),
                ];
            })->sortByDesc('count')->values();
        }

        // ── 5. Buyers list ─────────────────────────────────────────────────────
        $buyers = $allOrders;

        // ── 6. Bio Link Click Stats ────────────────────────────────────────────
        $totalBioClicks = 0;
        $topBioLinks    = collect();
        $bioUtmSources  = collect();
        if (Schema::hasColumn('analytics_events', 'bio_creator_id')) {
            // Get creator profile to find ID
            $creatorProfile = CreatorProfile::where('user_id', $seller->id)->first();
            if ($creatorProfile) {
                $bioBase = AnalyticsEvent::where('event_type', 'bio_link_click')
                    ->where('bio_creator_id', $creatorProfile->id)
                    ->whereBetween('created_at', [$startDate, $endDate]);

                $totalBioClicks = (clone $bioBase)->count();

                // Top clicked blocks
                $topBioLinks = (clone $bioBase)
                    ->select('bio_block_id', 'page_title', DB::raw('COUNT(*) as click_count'))
                    ->groupBy('bio_block_id', 'page_title')
                    ->orderByDesc('click_count')
                    ->limit(10)
                    ->get();

                // UTM sources for bio clicks
                $bioUtmSources = (clone $bioBase)
                    ->select('utm_source', DB::raw('COUNT(*) as count'))
                    ->groupBy('utm_source')
                    ->orderByDesc('count')
                    ->get()
                    ->map(function ($row) {
                        $row->utm_source = ($row->utm_source && $row->utm_source !== '')
                            ? $row->utm_source
                            : 'Organic / Direct';
                        return $row;
                    });
            }
        }

        // ── 7. Leads Tracker ───────────────────────────────────────────────────
        $leads = \App\Models\Lead::where(function($q) use ($seller) {
                $q->where('user_id', $seller->id)
                  ->orWhere('seller_id', $seller->id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        // ── 8. Saldo Siap Ditarik & Total GMV Kumulatif (Single Source of Truth) ─
        $balanceData      = \App\Services\SellerBalanceService::calculateBalance($seller);
        $availableBalance = $balanceData['available_balance'];
        $allTimeGmv       = $balanceData['gmv'];
        $holdBalance      = $balanceData['total_hold_balance'];

        // ── 9. Transaksi Kasir Digital (POS) - Terpisah Mandiri ───────────────
        $posPaymentMethod = $request->query('pos_method', 'all');
        $posQuery = Order::where('source', 'pos')
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereIn('status', [
                \App\Enums\OrderStatus::Confirmed,
                \App\Enums\OrderStatus::Processing,
                \App\Enums\OrderStatus::Shipped,
                \App\Enums\OrderStatus::Delivered,
            ])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($posPaymentMethod && $posPaymentMethod !== 'all') {
            if ($posPaymentMethod === 'cash') {
                $posQuery->where(function($q) {
                    $q->where('shipping_address->payment_method', 'cash')
                      ->orWhereHas('payment', fn($pq) => $pq->where('method', 'cash'));
                });
            } elseif ($posPaymentMethod === 'qris') {
                $posQuery->where(function($q) {
                    $q->where('shipping_address->payment_method', 'qris')
                      ->orWhereHas('payment', fn($pq) => $pq->where('method', 'qris'));
                });
            } elseif ($posPaymentMethod === 'transfer') {
                $posQuery->where(function($q) {
                    $q->whereIn('shipping_address->payment_method', ['transfer', 'bank_transfer'])
                      ->orWhereHas('payment', fn($pq) => $pq->whereIn('method', ['transfer', 'bank_transfer']));
                });
            }
        }

        $posOrders = $posQuery->with(['items.product', 'payment', 'user'])
            ->orderByDesc('created_at')
            ->get();

        $posTotalSales = (float) $posOrders->sum('total');
        $posCashTotal = (float) $posOrders->filter(function($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $m === 'cash';
        })->sum('total');

        $posDigitalTotal = (float) $posOrders->filter(function($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $m !== 'cash';
        })->sum('total');

        $posFeesTotal = (float) $posOrders->filter(function($o) {
            $m = strtolower($o->shipping_address['payment_method'] ?? ($o->payment->method ?? ''));
            return $m === 'cash';
        })->sum(function($o) {
            return (float) ($o->platform_fee ?? 0) + (float) ($o->admin_fee ?? 0);
        });

        $posTotalTransactions = $posOrders->count();

        // Saldo Deposit POS & Mutasi Ledger
        $creatorProfile = CreatorProfile::where('user_id', $seller->id)->first();
        $posBalance = (float) ($creatorProfile->pos_balance ?? 0);

        $posMutations = $creatorProfile
            ? \App\Models\PosBalanceMutation::where('creator_id', $creatorProfile->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderByDesc('created_at')
                ->get()
            : collect();

        $posTotalTopup = (float) $posMutations->where('type', 'topup')->sum('amount');
        $posTotalFeeMutations = (float) abs($posMutations->where('type', 'fee_deduction')->sum('amount'));

        return view('creator.reports.index', compact(
            'filter', 'startDate', 'endDate',
            'totalSales', 'totalOrders', 'totalVisitors', 'uniqueVisitors', 'availableBalance', 'allTimeGmv', 'holdBalance',
            'topProducts', 'utmSources', 'buyers', 'visitorsByDate', 'salesByDate',
            'totalBioClicks', 'topBioLinks', 'bioUtmSources', 'leads',
            'posOrders', 'posTotalSales', 'posCashTotal', 'posDigitalTotal', 'posFeesTotal',
            'posTotalTransactions', 'posBalance', 'posMutations', 'posTotalTopup', 'posTotalFeeMutations',
            'posPaymentMethod'
        ));
    }

    /**
     * Hapus lead dari tracker.
     */
    public function destroyLead(\App\Models\Lead $lead)
    {
        $seller = auth()->user();
        if ((int)$lead->seller_id === (int)$seller->id || (int)$lead->user_id === (int)$seller->id) {
            $lead->delete();
            return back()->with('success', 'Lead berhasil dihapus.');
        }
        return back()->with('error', 'Anda tidak memiliki akses untuk menghapus lead ini.');
    }

    /**
     * Export data pembeli ke CSV atau PDF.
     */
    public function export(Request $request)
    {
        $seller = auth()->user();
        [$filter, $startDate, $endDate] = $this->getDateRange($request);

        $type = $request->query('type') ?? $request->query('tab');
        $storeName = $seller->creatorProfile?->store_name ?? ('seller-' . $seller->id);
        $format    = $request->query('format', 'csv');

        // ── 1. Export Data Leads ──────────────────────────────────────────────
        if ($type === 'leads') {
            $leads = \App\Models\Lead::where(function($q) use ($seller) {
                    $q->where('user_id', $seller->id)
                      ->orWhere('seller_id', $seller->id);
                })
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderByDesc('created_at')
                ->get();

            $filename = 'Data_Leads_' . str_replace(' ', '_', $storeName) . '_' . date('Ymd');

            if ($format === 'pdf') {
                $pdf = Pdf::loadHTML($this->generateLeadsPdfHtml($leads, $storeName));
                return $pdf->download($filename . '.pdf');
            }

            // Default CSV / XLS
            $csvData  = "\xEF\xBB\xBF"; // UTF-8 BOM agar Excel dapat membaca dengan benar
            $csvData .= "Tanggal & Waktu,Nama Lead,No WhatsApp,Kota / Perusahaan,Kebutuhan / Pesan,Sumber Form\n";

            foreach ($leads as $lead) {
                $date    = $lead->created_at ? $lead->created_at->format('Y-m-d H:i') : '-';
                $name    = str_replace([",", "\n", "\r"], ' ', $lead->name ?? '-');
                $phone   = str_replace([",", "\n", "\r"], ' ', $lead->phone ?? '-');
                $city    = str_replace([",", "\n", "\r"], ' ', $lead->city ?? $lead->company ?? '-');
                $message = str_replace([",", "\n", "\r"], ' ', $lead->message ?? $lead->product ?? '-');
                $source  = str_replace([",", "\n", "\r"], ' ', $lead->source === 'theme5_footer' ? 'Footer5' : ($lead->source ?? 'Website'));

                $csvData .= "{$date},{$name},{$phone},{$city},{$message},{$source}\n";
            }

            $csvData .= "\n";
            $csvData .= "RINGKASAN LEADS TRACKER\n";
            $csvData .= "Total Leads Masuk," . $leads->count() . " Leads\n";

            return response($csvData)
                ->header('Content-Type', 'text/csv; charset=utf-8')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
        }

        // ── 2. Export Data Pembeli / Keuangan (Default) ─────────────────────
        $orders = $this->paidOrdersBaseQuery($seller->id, $startDate, $endDate)
            ->with([
                'user',
                'items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)),
            ])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'Laporan_Keuangan_Pembeli_' . str_replace(' ', '_', $storeName) . '_' . date('Ymd');

        if ($format === 'pdf') {
            $pdf = Pdf::loadHTML($this->generatePdfHtml($orders, $storeName));
            return $pdf->download($filename . '.pdf');
        }

        // Default: CSV (buka dengan Excel / XLS)
        $csvData  = "\xEF\xBB\xBF"; // UTF-8 BOM agar Excel bisa baca
        $csvData .= "Tanggal,ID Transaksi,Nama Pembeli,Email,No WA,Produk,Total Nominal (Rp),UTM Source\n";

        $grandTotalNominal = 0;
        $totalOrderCount   = $orders->count();

        foreach ($orders as $order) {
            $date     = $order->created_at->format('Y-m-d H:i');
            $rawOid   = $order->order_number ?: ('BYL-' . $order->id);
            $oid      = str_starts_with($rawOid, '#') ? $rawOid : '#' . $rawOid;
            $name     = str_replace(',', ' ', $order->user?->name ?? '-');
            $email    = $order->user?->email ?? '-';
            $phone    = $order->user?->phone ?? '-';
            $products = str_replace(',', ' ', $order->items->pluck('product_name')->implode(' | '));
            $subtotal = (float) $order->items->sum('subtotal');
            $grandTotalNominal += $subtotal;
            $total    = number_format($subtotal, 0, '.', '');
            $source   = isset($order->utm_source) ? ($order->utm_source ?: 'Organic') : 'Organic';

            $csvData .= "{$date},{$oid},{$name},{$email},{$phone},{$products},{$total},{$source}\n";
        }

        // Ringkasan Laporan Keuangan di bagian bawah CSV/XLS
        $csvData .= "\n";
        $csvData .= "LAPORAN KEUANGAN SAN PENJUALAN\n";
        $csvData .= "Total Transaksi,{$totalOrderCount} Pesanan\n";
        $csvData .= "Total Nominal Pendapatan,Rp " . number_format($grandTotalNominal, 0, ',', '.') . "\n";

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
    }

    private function generatePdfHtml($orders, string $storeName): string
    {
        $totalOrderCount   = $orders->count();
        $grandTotalNominal = 0;

        $html = "<!DOCTYPE html><html><head><meta charset='utf-8'>";
        $html .= "<style>
            body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; font-size: 11px; padding: 15px; }
            .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 14px; }
            .header h2 { margin: 0 0 6px 0; font-size: 18px; color: #0f172a; letter-spacing: -0.5px; }
            .header p { margin: 0; color: #64748b; font-size: 11.5px; }
            table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            table.report-table th { background: #f8fafc; color: #475569; font-weight: 700; text-align: left; padding: 9px 10px; border: 1px solid #cbd5e1; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
            table.report-table td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10.5px; }
            table.report-table tr:nth-child(even) { background-color: #f9fafb; }
            .total-row td { background: #f1f5f9 !important; font-weight: 800; font-size: 11px; }
            .summary-box { float: right; width: 280px; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 14px; background: #f8fafc; margin-top: 10px; }
            .summary-title { margin: 0 0 10px 0; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #334155; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; }
            .summary-table { width: 100%; border-collapse: collapse; }
            .summary-table td { border: none; padding: 4px 0; font-size: 11px; }
        </style></head><body>";

        $html .= "<div class='header'>";
        $html .= "<h2>LAPORAN KEUANGAN & DATA PEMBELI</h2>";
        $html .= "<p>Toko / Creator: <strong>" . htmlspecialchars($storeName) . "</strong> | Tanggal Cetak: " . date('d/m/Y H:i') . "</p>";
        $html .= "</div>";

        $html .= "<table class='report-table'>";
        $html .= "<thead><tr>
            <th style='width: 12%;'>Tanggal</th>
            <th style='width: 17%;'>ID Transaksi</th>
            <th style='width: 15%;'>Nama Pembeli</th>
            <th style='width: 16%;'>Email / Kontak</th>
            <th style='width: 23%;'>Produk Dipesan</th>
            <th style='width: 17%; text-align: right;'>Nominal (Rp)</th>
        </tr></thead><tbody>";

        foreach ($orders as $order) {
            $date     = $order->created_at->format('d/m/Y H:i');
            $rawOid   = $order->order_number ?: ('BYL-' . $order->id);
            $oid      = htmlspecialchars(str_starts_with($rawOid, '#') ? $rawOid : '#' . $rawOid);
            $name     = htmlspecialchars($order->user?->name ?? '-');
            $email    = htmlspecialchars($order->user?->email ?? ($order->user?->phone ?? '-'));
            $products = htmlspecialchars($order->items->pluck('product_name')->implode(', '));
            $subtotal = (float) $order->items->sum('subtotal');
            $grandTotalNominal += $subtotal;
            $totalFormatted = 'Rp ' . number_format($subtotal, 0, ',', '.');

            $html .= "<tr>
                <td>{$date}</td>
                <td style='font-family: monospace; font-weight: bold; color: #0f172a;'>{$oid}</td>
                <td>{$name}</td>
                <td>{$email}</td>
                <td>{$products}</td>
                <td style='text-align: right; font-weight: 700; color: #0f172a;'>{$totalFormatted}</td>
            </tr>";
        }

        $grandTotalFormatted = 'Rp ' . number_format($grandTotalNominal, 0, ',', '.');

        $html .= "</tbody>";
        $html .= "<tfoot>
            <tr class='total-row'>
                <td colspan='5' style='text-align: right; font-weight: 800; color: #0f172a;'>TOTAL KESELURUHAN ({$totalOrderCount} Transaksi):</td>
                <td style='text-align: right; font-weight: 800; color: #1eb349; font-size: 11.5px;'>{$grandTotalFormatted}</td>
            </tr>
        </tfoot>";
        $html .= "</table>";

        $html .= "<div class='summary-box'>";
        $html .= "<div class='summary-title'>Ringkasan Laporan Keuangan</div>";
        $html .= "<table class='summary-table'>";
        $html .= "<tr><td>Total Jumlah Pesanan:</td><td style='text-align: right; font-weight: 800; color: #0f172a;'>{$totalOrderCount} Pesanan</td></tr>";
        $html .= "<tr><td>Total Nominal Omset:</td><td style='text-align: right; font-weight: 800; color: #1eb349; font-size: 12px;'>{$grandTotalFormatted}</td></tr>";
        $html .= "</table>";
        $html .= "</div>";

        $html .= "</body></html>";
        return $html;
    }

    private function generateLeadsPdfHtml($leads, string $storeName): string
    {
        $totalLeads = $leads->count();

        $html = "<!DOCTYPE html><html><head><meta charset='utf-8'>";
        $html .= "<style>
            body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; font-size: 11px; padding: 15px; }
            .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 14px; }
            .header h2 { margin: 0 0 6px 0; font-size: 18px; color: #0f172a; letter-spacing: -0.5px; }
            .header p { margin: 0; color: #64748b; font-size: 11.5px; }
            table.report-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            table.report-table th { background: #f8fafc; color: #475569; font-weight: 700; text-align: left; padding: 9px 10px; border: 1px solid #cbd5e1; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
            table.report-table td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10.5px; }
            table.report-table tr:nth-child(even) { background-color: #f9fafb; }
            .summary-box { float: right; width: 250px; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 12px; background: #f8fafc; margin-top: 10px; }
            .summary-title { margin: 0 0 8px 0; font-size: 11px; font-weight: 800; text-transform: uppercase; color: #334155; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; }
            .summary-table { width: 100%; border-collapse: collapse; }
            .summary-table td { border: none; padding: 3px 0; font-size: 11px; }
        </style></head><body>";

        $html .= "<div class='header'>";
        $html .= "<h2>LAPORAN LEADS TRACKER & PESAN MASUK</h2>";
        $html .= "<p>Toko / Creator: <strong>" . htmlspecialchars($storeName) . "</strong> | Tanggal Cetak: " . date('d/m/Y H:i') . "</p>";
        $html .= "</div>";

        $html .= "<table class='report-table'>";
        $html .= "<thead><tr>
            <th style='width: 15%;'>Waktu & Tanggal</th>
            <th style='width: 20%;'>Nama Lead</th>
            <th style='width: 18%;'>WhatsApp / Kontak</th>
            <th style='width: 17%;'>Kota / Perusahaan</th>
            <th style='width: 20%;'>Kebutuhan / Pesan</th>
            <th style='width: 10%;'>Sumber</th>
        </tr></thead><tbody>";

        foreach ($leads as $lead) {
            $date    = $lead->created_at ? $lead->created_at->format('d/m/Y H:i') : '-';
            $name    = htmlspecialchars($lead->name ?? '-');
            $phone   = htmlspecialchars($lead->phone ?? '-');
            $city    = htmlspecialchars($lead->city ?? $lead->company ?? '-');
            $message = htmlspecialchars($lead->message ?? $lead->product ?? '-');
            $source  = htmlspecialchars($lead->source === 'theme5_footer' ? 'Footer5' : ($lead->source ?? 'Website'));

            $html .= "<tr>
                <td>{$date}</td>
                <td><strong>{$name}</strong></td>
                <td>{$phone}</td>
                <td>{$city}</td>
                <td>{$message}</td>
                <td>{$source}</td>
            </tr>";
        }

        if ($totalLeads === 0) {
            $html .= "<tr><td colspan='6' style='text-align:center; padding:20px; color:#64748b;'>Belum ada data leads pada periode ini.</td></tr>";
        }

        $html .= "</tbody></table>";

        $html .= "<div class='summary-box'>";
        $html .= "<div class='summary-title'>RINGKASAN LEADS</div>";
        $html .= "<table class='summary-table'>";
        $html .= "<tr><td>Total Leads Masuk:</td><td style='text-align:right;'><strong>{$totalLeads} Leads</strong></td></tr>";
        $html .= "</table>";
        $html .= "</div>";

        $html .= "</body></html>";

        return $html;
    }

    /**
     * Update status pesanan dan nomor resi pengiriman oleh Creator.
     */
    public function updateOrder(Request $request, Order $order)
    {
        $seller = auth()->user();

        // Pastikan order memiliki produk milik creator ini
        $hasProduct = $order->items()->whereHas('product', fn($q) => $q->where('seller_id', $seller->id))->exists();
        if (!$hasProduct && $seller->role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke pesanan ini.'], 403);
        }

        $request->validate([
            'order_status'    => 'nullable|string|in:pending,processing,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'courier_name'    => 'nullable|string|max:100',
        ]);

        if ($request->filled('order_status')) {
            $order->status = $request->order_status;
            $order->save();
        }

        if ($request->has('tracking_number') || $request->has('courier_name')) {
            $shipment = $order->shipment ?: new \App\Models\Shipment(['order_id' => $order->id]);
            if ($request->filled('tracking_number')) {
                $shipment->tracking_number = trim($request->tracking_number);
                $shipment->shipped_at = $shipment->shipped_at ?: now();
            }
            if ($request->filled('courier_name')) {
                $shipment->courier_name = trim($request->courier_name);
            }
            if ($request->filled('order_status')) {
                if ($request->order_status === 'shipped') {
                    $shipment->status = \App\Enums\ShipmentStatus::InTransit;
                } elseif ($request->order_status === 'completed') {
                    $shipment->status = \App\Enums\ShipmentStatus::Delivered;
                } elseif ($request->order_status === 'cancelled') {
                    $shipment->status = \App\Enums\ShipmentStatus::Cancelled;
                }
            }
            $shipment->save();
        }

        $statusStr = is_object($order->status) ? $order->status->value : (string)$order->status;
        $statusLabel = is_object($order->status) && method_exists($order->status, 'label') ? $order->status->label() : ucfirst($statusStr);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'         => true,
                'message'         => 'Status pesanan & resi berhasil diperbarui!',
                'status'          => $statusStr,
                'status_label'    => $statusLabel,
                'tracking_number' => $order->shipment?->tracking_number ?? '',
                'courier_name'    => $order->shipment?->courier_name ?? '',
            ]);
        }

        return back()->with('success', 'Status pesanan & resi berhasil diperbarui.');
    }

    /**
     * Export data transaksi kasir POS ke file CSV (Excel compatible).
     */
    public function exportPos(Request $request)
    {
        $authUser = auth()->user();
        $seller = ($authUser->isPosCashier() && $authUser->parent_seller_id)
            ? (\App\Models\User::find($authUser->parent_seller_id) ?? $authUser)
            : $authUser;

        [$filter, $startDate, $endDate] = $this->getDateRange($request);
        $posPaymentMethod = $request->query('pos_method', 'all');
        $format           = strtolower($request->query('format', 'csv'));

        $profile   = $seller->creatorProfile;
        $storeName = $profile?->store_name ?? ('Toko ' . $seller->name);

        $posQuery = Order::where('source', 'pos')
            ->where(function($q) use ($seller) {
                $q->where('seller_id', $seller->id)
                  ->orWhereHas('items.product', fn($pq) => $pq->where('seller_id', $seller->id));
            })
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($posPaymentMethod && $posPaymentMethod !== 'all') {
            if ($posPaymentMethod === 'cash') {
                $posQuery->where(function($q) {
                    $q->where('shipping_address->payment_method', 'cash')
                      ->orWhereHas('payment', fn($pq) => $pq->where('method', 'cash'));
                });
            } elseif ($posPaymentMethod === 'qris') {
                $posQuery->where(function($q) {
                    $q->where('shipping_address->payment_method', 'qris')
                      ->orWhereHas('payment', fn($pq) => $pq->where('method', 'qris'));
                });
            } elseif ($posPaymentMethod === 'transfer') {
                $posQuery->where(function($q) {
                    $q->whereIn('shipping_address->payment_method', ['transfer', 'bank_transfer'])
                      ->orWhereHas('payment', fn($pq) => $pq->whereIn('method', ['transfer', 'bank_transfer']));
                });
            }
        }

        $orders = $posQuery->with(['items.product', 'payment', 'user'])
            ->orderByDesc('created_at')
            ->get();

        $totalOmset   = 0;
        $totalCash    = 0;
        $totalDigital = 0;
        $totalFees    = 0;

        foreach ($orders as $order) {
            $addr   = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
            $method = strtolower($addr['payment_method'] ?? ($order->payment->method ?? 'cash'));
            $platformFee = (float) ($order->platform_fee ?? 0);
            $adminFee    = (float) ($order->admin_fee ?? 0);
            $total       = (float) $order->total;

            $totalOmset += $total;
            if ($method === 'cash') {
                $totalCash += $total;
                $totalFees += ($platformFee + $adminFee);
            } else {
                $totalDigital += $total;
            }
        }

        $cleanStore = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $storeName)) ?: 'POS';
        $filename   = 'Laporan_POS_' . $cleanStore . '_' . date('Ymd_His');

        if ($format === 'pdf') {
            $pdfHtml = $this->generatePosPdfHtml($orders, $storeName, $startDate, $endDate, $posPaymentMethod, $totalOmset, $totalCash, $totalDigital, $totalFees, $profile);
            $pdf = Pdf::loadHTML($pdfHtml)->setPaper('a4', 'portrait');
            return $pdf->download($filename . '.pdf');
        }

        // CSV export (UTF-8 BOM)
        $csvData  = "\xEF\xBB\xBF";
        $csvData .= "Tanggal & Waktu,No Invoice POS,Pelanggan,Meja / Catatan,Kasir,Metode Pembayaran,Item Produk,Subtotal (Rp),Diskon (Rp),Platform Fee (Rp),Admin Fee (Rp),Total Bayar (Rp),Status Pembayaran\n";

        foreach ($orders as $order) {
            $date     = $order->created_at->format('Y-m-d H:i');
            $invoice  = $order->order_number;
            $addr     = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
            $customer = str_replace([",", "\n", "\r"], ' ', $addr['name'] ?? $order->user?->name ?? 'Pelanggan Umum');
            $table    = str_replace([",", "\n", "\r"], ' ', $addr['table_number'] ?? $addr['table'] ?? ($order->notes ?? '-'));
            $cashier  = str_replace([",", "\n", "\r"], ' ', $addr['cashier_name'] ?? '-');
            $method   = strtoupper($addr['payment_method'] ?? ($order->payment->method ?? 'CASH'));

            $itemNames = $order->items->map(function($i) {
                return $i->product_name . ' (' . $i->qty . 'x)';
            })->implode(' | ');
            $itemNames = str_replace([",", "\n", "\r"], ' ', $itemNames);

            $subtotal    = (float) $order->subtotal;
            $discount    = (float) ($order->discount ?? 0);
            $platformFee = (float) ($order->platform_fee ?? 0);
            $adminFee    = (float) ($order->admin_fee ?? 0);
            $total       = (float) $order->total;
            $status      = ($order->payment && $order->payment->status->value === 'success') ? 'LUNAS' : 'PENDING';

            $csvData .= "{$date},{$invoice},{$customer},{$table},{$cashier},{$method},{$itemNames},{$subtotal},{$discount},{$platformFee},{$adminFee},{$total},{$status}\n";
        }

        $csvData .= "\n";
        $csvData .= "RINGKASAN LAPORAN KASIR POS (" . $startDate->format('d/m/Y') . " - " . $endDate->format('d/m/Y') . ")\n";
        $csvData .= "Total Transaksi POS," . $orders->count() . " Transaksi\n";
        $csvData .= "Total Omset Kotor POS,Rp " . number_format($totalOmset, 0, ',', '.') . "\n";
        $csvData .= "Total Uang Tunai di Laci Kasir (Cash),Rp " . number_format($totalCash, 0, ',', '.') . "\n";
        $csvData .= "Total Pembayaran Digital (QRIS / Transfer),Rp " . number_format($totalDigital, 0, ',', '.') . "\n";
        $csvData .= "Total Fee Terpotong dari Saldo Deposit,Rp " . number_format($totalFees, 0, ',', '.') . "\n";

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
    }

    private function generatePosPdfHtml($orders, string $storeName, Carbon $startDate, Carbon $endDate, string $posMethod, float $totalOmset, float $totalCash, float $totalDigital, float $totalFees, $profile): string
    {
        $orderCount = $orders->count();
        $methodLabel = match($posMethod) {
            'cash'     => 'Tunai (Cash)',
            'qris'     => 'QRIS / Digital',
            'transfer' => 'Transfer Bank',
            default    => 'Semua Metode'
        };

        $periodLabel = $startDate->format('d/m/Y') . ' s/d ' . $endDate->format('d/m/Y');
        $printedAt   = Carbon::now()->format('d/m/Y H:i');

        $html = "<!DOCTYPE html><html><head><meta charset='utf-8'>";
        $html .= "<style>
            body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #0f172a; font-size: 10px; margin: 0; padding: 15px; }
            .header-table { width: 100%; border-collapse: collapse; margin-bottom: 18px; border-bottom: 2px solid #16a34a; padding-bottom: 10px; }
            .header-title { font-size: 18px; font-weight: 800; color: #0f172a; margin: 0 0 4px; text-transform: uppercase; letter-spacing: -0.3px; }
            .header-sub { font-size: 10.5px; color: #64748b; margin: 0; }
            .header-meta { text-align: right; font-size: 9.5px; color: #475569; }
            
            /* KPI Grid in PDF */
            .kpi-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 16px; }
            .kpi-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; text-align: left; }
            .kpi-label { font-size: 8.5px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.3px; }
            .kpi-val { font-size: 14px; font-weight: 800; color: #0f172a; }
            .kpi-sub { font-size: 8px; color: #94a3b8; margin-top: 2px; }

            /* Table */
            table.rep-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
            table.rep-table th { background: #f1f5f9; color: #334155; font-weight: 700; text-align: left; padding: 7px 8px; border: 1px solid #cbd5e1; font-size: 9px; text-transform: uppercase; letter-spacing: 0.3px; }
            table.rep-table td { padding: 6px 8px; border: 1px solid #e2e8f0; font-size: 9px; vertical-align: middle; }
            table.rep-table tr:nth-child(even) { background-color: #fbfcfd; }
            
            .badge-cash { background: #ecfdf5; color: #059669; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 8.5px; }
            .badge-qris { background: #eff6ff; color: #2563eb; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 8.5px; }
            .badge-tf   { background: #f5f3ff; color: #7c3aed; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 8.5px; }

            .tfoot-row td { background: #f1f5f9 !important; font-weight: 800; font-size: 10px; }
            .footer-note { text-align: center; margin-top: 25px; font-size: 8.5px; color: #94a3b8; border-top: 1px dashed #cbd5e1; padding-top: 10px; }
        </style></head><body>";

        $html .= "<table class='header-table'><tr>";
        $html .= "<td style='vertical-align:top;'>";
        $html .= "<div class='header-title'>" . htmlspecialchars($storeName) . "</div>";
        $html .= "<div class='header-sub'>Laporan Rekap Penjualan Kasir POS</div>";
        $html .= "</td>";
        $html .= "<td class='header-meta' style='vertical-align:top;'>";
        $html .= "<div><strong>Periode:</strong> " . $periodLabel . "</div>";
        $html .= "<div><strong>Metode Bayar:</strong> " . $methodLabel . "</div>";
        $html .= "<div><strong>Waktu Cetak:</strong> " . $printedAt . " WIB</div>";
        $html .= "</td>";
        $html .= "</tr></table>";

        // KPI Table
        $html .= "<table class='kpi-table'><tr>";
        $html .= "<td class='kpi-card' style='background:#0f172a; border-color:#334155;'>
            <div class='kpi-label' style='color:#94a3b8;'>Total Omset Kotor</div>
            <div class='kpi-val' style='color:#ffffff;'>Rp " . number_format($totalOmset, 0, ',', '.') . "</div>
            <div class='kpi-sub' style='color:#64748b;'>" . number_format($orderCount) . " Struk Selesai</div>
        </td>";
        $html .= "<td class='kpi-card'>
            <div class='kpi-label' style='color:#059669;'>Tunai di Laci (Cash)</div>
            <div class='kpi-val' style='color:#059669;'>Rp " . number_format($totalCash, 0, ',', '.') . "</div>
            <div class='kpi-sub'>Fisik uang kasir</div>
        </td>";
        $html .= "<td class='kpi-card'>
            <div class='kpi-label' style='color:#2563eb;'>QRIS & Digital</div>
            <div class='kpi-val' style='color:#2563eb;'>Rp " . number_format($totalDigital, 0, ',', '.') . "</div>
            <div class='kpi-sub'>Non-tunai / Cashless</div>
        </td>";
        $html .= "<td class='kpi-card'>
            <div class='kpi-label' style='color:#d97706;'>Fee Terpotong</div>
            <div class='kpi-val' style='color:#d97706;'>Rp " . number_format($totalFees, 0, ',', '.') . "</div>
            <div class='kpi-sub'>Biaya deposit & admin</div>
        </td>";
        $html .= "</tr></table>";

        // Table Data
        $html .= "<table class='rep-table'>";
        $html .= "<thead><tr>
            <th style='width: 14%;'>No Struk</th>
            <th style='width: 13%;'>Waktu</th>
            <th style='width: 17%;'>Pelanggan / Meja</th>
            <th style='width: 12%;'>Metode</th>
            <th style='width: 29%;'>Item Pesanan</th>
            <th style='width: 15%; text-align: right;'>Total Bayar</th>
        </tr></thead><tbody>";

        if ($orders->isEmpty()) {
            $html .= "<tr><td colspan='6' style='text-align:center; padding: 25px; color:#94a3b8;'>Tidak ada transaksi POS pada rentang waktu ini.</td></tr>";
        } else {
            foreach ($orders as $order) {
                $addr     = is_array($order->shipping_address) ? $order->shipping_address : (json_decode($order->shipping_address ?? '', true) ?? []);
                $invoice  = htmlspecialchars($order->order_number);
                $time     = $order->created_at->format('d/m/y H:i');
                $customer = htmlspecialchars($addr['name'] ?? $order->user?->name ?? 'Pelanggan Umum');
                $table    = !empty($addr['table_number']) ? ' (M#' . htmlspecialchars($addr['table_number']) . ')' : '';
                $method   = strtolower($addr['payment_method'] ?? ($order->payment->method ?? 'cash'));
                
                $badge = match($method) {
                    'cash'     => "<span class='badge-cash'>Tunai</span>",
                    'qris'     => "<span class='badge-qris'>QRIS</span>",
                    'transfer' => "<span class='badge-tf'>Transfer</span>",
                    default    => "<span class='badge-tf'>" . strtoupper(htmlspecialchars($method)) . "</span>",
                };

                $items = htmlspecialchars($order->items->map(fn($i) => $i->product_name . ' (' . $i->qty . 'x)')->implode(', '));
                $totalFormatted = 'Rp ' . number_format($order->total, 0, ',', '.');

                $html .= "<tr>
                    <td style='font-family: monospace; font-weight: bold;'>{$invoice}</td>
                    <td>{$time}</td>
                    <td>{$customer}{$table}</td>
                    <td>{$badge}</td>
                    <td>{$items}</td>
                    <td style='text-align: right; font-weight: 700;'>{$totalFormatted}</td>
                </tr>";
            }
        }

        $html .= "</tbody>";
        $html .= "<tfoot><tr class='tfoot-row'>
            <td colspan='5' style='text-align: right; font-weight: 800;'>TOTAL OMSET KOTOR (" . number_format($orderCount) . " Transaksi):</td>
            <td style='text-align: right; font-weight: 800; color: #16a34a; font-size: 11px;'>Rp " . number_format($totalOmset, 0, ',', '.') . "</td>
        </tr></tfoot>";
        $html .= "</table>";

        $html .= "<div class='footer-note'>
            Dokumen ini di-generate otomatis oleh Sistem Kasir Digital buyle.id &copy; " . date('Y') . " " . htmlspecialchars($storeName) . ".
        </div>";

        $html .= "</body></html>";
        return $html;
    }
}

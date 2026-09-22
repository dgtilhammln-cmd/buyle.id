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
            '7'      => Carbon::now()->subDays(7)->startOfDay(),
            '90'     => Carbon::now()->subDays(90)->startOfDay(),
            'custom' => $request->filled('start_date')
                            ? Carbon::parse($request->query('start_date'))->startOfDay()
                            : Carbon::now()->subDays(30)->startOfDay(),
            default  => Carbon::now()->subDays(30)->startOfDay(),
        };

        if ($filter === 'custom' && $request->filled('end_date')) {
            $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
        }

        return [$filter, $startDate, $endDate];
    }

    /**
     * Base query untuk orders seller atau reseller yang sudah bayar.
     */
    private function paidOrdersBaseQuery(int $sellerId, Carbon $startDate, Carbon $endDate)
    {
        return Order::whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success->value))
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
        $leads = \App\Models\Lead::where('user_id', $seller->id)
            ->orWhere('seller_id', $seller->id)
            ->orderByDesc('created_at')
            ->get();

        return view('creator.reports.index', compact(
            'filter', 'startDate', 'endDate',
            'totalSales', 'totalOrders', 'totalVisitors', 'uniqueVisitors',
            'topProducts', 'utmSources', 'buyers', 'visitorsByDate', 'salesByDate',
            'totalBioClicks', 'topBioLinks', 'bioUtmSources', 'leads'
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

        $orders = $this->paidOrdersBaseQuery($seller->id, $startDate, $endDate)
            ->with([
                'user',
                'items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)),
            ])
            ->orderByDesc('created_at')
            ->get();

        $storeName = $seller->creatorProfile?->store_name ?? ('seller-' . $seller->id);
        $filename  = 'Laporan_Keuangan_Pembeli_' . str_replace(' ', '_', $storeName) . '_' . date('Ymd');
        $format    = $request->query('format', 'csv');

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
}

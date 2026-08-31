<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            default  => Carbon::now()->subDays(30)->startOfDay(), // '30' dan fallback
        };

        if ($filter === 'custom' && $request->filled('end_date')) {
            $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
        }

        return [$filter, $startDate, $endDate];
    }

    /**
     * Base query untuk orders seller yang sudah bayar.
     */
    private function paidOrdersBaseQuery(int $sellerId, Carbon $startDate, Carbon $endDate)
    {
        return Order::whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success->value))
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $sellerId))
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
                'items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)),
            ])
            ->orderByDesc('created_at')
            ->get();

        $totalOrders = $allOrders->count();
        $totalSales  = $allOrders->sum(fn($o) => $o->items->sum('subtotal'));

        // ── 2. Visitor Stats ────────────────────────────────────────────────────
        $visitRows    = ProductVisit::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['session_id']);

        $totalVisitors  = $visitRows->count();
        $uniqueVisitors = $visitRows->pluck('session_id')->unique()->filter()->count();

        // ── 3. Top Products (by visits count) ──────────────────────────────────
        $topProducts = Product::where('seller_id', $seller->id)
            ->withCount([
                'visits as visits_count' => fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]),
            ])
            ->withSum([
                'orderItems as sold_amount' => function ($q) use ($startDate, $endDate, $seller) {
                    $q->whereHas('order.payment', fn($p) => $p->where('status', PaymentStatus::Success->value))
                      ->whereBetween('order_items.created_at', [$startDate, $endDate]);
                },
            ], 'subtotal')
            ->orderByDesc('visits_count')
            ->limit(10)
            ->get();

        // ── 4. UTM Sources (dari orders yang sudah paid) ────────────────────────
        $utmSources = $this->paidOrdersBaseQuery($seller->id, $startDate, $endDate)
            ->select(
                DB::raw("COALESCE(NULLIF(utm_source,''), 'Organic / Direct') as utm_source"),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy(DB::raw("COALESCE(NULLIF(utm_source,''), 'Organic / Direct')"))
            ->orderByDesc('count')
            ->get();

        // ── 5. Buyers list ─────────────────────────────────────────────────────
        $buyers = $allOrders; // already sorted by created_at desc

        return view('creator.reports.index', compact(
            'filter', 'startDate', 'endDate',
            'totalSales', 'totalOrders', 'totalVisitors', 'uniqueVisitors',
            'topProducts', 'utmSources', 'buyers'
        ));
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
        $filename  = 'Data_Pembeli_' . str_replace(' ', '_', $storeName) . '_' . date('Ymd');
        $format    = $request->query('format', 'csv');

        if ($format === 'pdf') {
            $pdf = Pdf::loadHTML($this->generatePdfHtml($orders, $storeName));
            return $pdf->download($filename . '.pdf');
        }

        // Default: CSV / XLS
        $csvData = "\xEF\xBB\xBF"; // BOM for Excel
        $csvData .= "Tanggal,Nama,Email,No WA,Order ID,Produk,Total (Rp),UTM Source\n";

        foreach ($orders as $order) {
            $date     = $order->created_at->format('Y-m-d H:i');
            $name     = str_replace(',', ' ', $order->user?->name ?? '-');
            $email    = $order->user?->email ?? '-';
            $phone    = $order->user?->phone ?? '-';
            $oid      = $order->order_number;
            $products = str_replace(',', ' ', $order->items->pluck('product_name')->implode(' | '));
            $total    = $order->items->sum('subtotal');
            $source   = $order->utm_source ?: 'Organic';

            $csvData .= "{$date},{$name},{$email},{$phone},{$oid},{$products},{$total},{$source}\n";
        }

        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.csv\"");
    }

    private function generatePdfHtml($orders, string $storeName): string
    {
        $html  = "<h2 style='font-family:sans-serif;'>Data Pembeli - {$storeName}</h2>";
        $html .= "<table border='1' cellpadding='5' cellspacing='0' style='width:100%;font-family:sans-serif;font-size:11px;border-collapse:collapse;'>";
        $html .= "<tr style='background:#f3f4f6;'><th>Tanggal</th><th>Nama</th><th>Email</th><th>No WA</th><th>Total Order</th><th>Produk</th><th>Sumber UTM</th></tr>";

        foreach ($orders as $order) {
            $date     = $order->created_at->format('d/m/Y H:i');
            $name     = htmlspecialchars($order->user?->name ?? '-');
            $email    = htmlspecialchars($order->user?->email ?? '-');
            $phone    = htmlspecialchars($order->user?->phone ?? '-');
            $total    = 'Rp ' . number_format($order->items->sum('subtotal'), 0, ',', '.');
            $products = htmlspecialchars($order->items->pluck('product_name')->implode(', '));
            $source   = htmlspecialchars($order->utm_source ?: 'Organic');

            $html .= "<tr><td>{$date}</td><td>{$name}</td><td>{$email}</td><td>{$phone}</td><td>{$total}</td><td>{$products}</td><td>{$source}</td></tr>";
        }

        $html .= '</table>';
        return $html;
    }
}

<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductVisit;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SellerReportController extends Controller
{
    /**
     * Menampilkan Laporan Penjualan komprehensif.
     */
    public function index(Request $request)
    {
        $seller = auth()->user();
        
        // 1. Setup Date Range Filter
        $filter = $request->query('filter', '30'); // default 30 days
        $startDate = null;
        $endDate = Carbon::now();
        
        if ($filter === '7') {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
        } elseif ($filter === '30') {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        } elseif ($filter === '90') {
            $startDate = Carbon::now()->subDays(90)->startOfDay();
        } elseif ($filter === 'custom') {
            $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        } else {
            $startDate = Carbon::now()->subDays(30)->startOfDay(); // fallback
        }

        // 2. Query Orders (Paid)
        $ordersQuery = Order::whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Total GMV / Sales
        $allOrders = $ordersQuery->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)), 'user'])->get();
        $totalSales = $allOrders->sum(fn($order) => $order->items->sum('subtotal'));
        $totalOrders = $allOrders->count();

        // 3. Query Product Visits
        $visitsQuery = ProductVisit::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate]);
            
        $totalVisitors = $visitsQuery->count();
        $uniqueVisitors = $visitsQuery->distinct('session_id')->count('session_id');

        // 4. Produk Terbanyak Diklik & Terjual
        $topProducts = Product::where('seller_id', $seller->id)
            ->withCount(['visits' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['orderItems as sold_amount' => function($q) use ($startDate, $endDate) {
                $q->whereHas('order.payment', fn($p) => $p->where('status', \App\Enums\PaymentStatus::Success))
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'subtotal')
            ->orderByDesc('visits_count')
            ->limit(10)
            ->get();

        // 5. Distribusi Sumber UTM (dari Product Visits atau Orders, mari ambil dari Orders agar relevan ke penjualan)
        $utmSources = Order::whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('utm_source', DB::raw('count(*) as count'), DB::raw('sum(total) as revenue'))
            ->groupBy('utm_source')
            ->orderByDesc('count')
            ->get()
            ->map(function($item) {
                $item->utm_source = $item->utm_source ?: 'Organic / Direct';
                return $item;
            });

        // 6. Data Pembeli Lengkap
        // Urutkan dari yang terbaru
        $buyers = $allOrders->sortByDesc('created_at')->values();

        return view('creator.reports.index', compact(
            'filter', 'startDate', 'endDate',
            'totalSales', 'totalOrders', 'totalVisitors', 'uniqueVisitors',
            'topProducts', 'utmSources', 'buyers'
        ));
    }
    
    /**
     * Export data pembeli ke XLS (CSV) atau PDF.
     */
    public function export(Request $request)
    {
        $seller = auth()->user();
        
        $filter = $request->query('filter', '30');
        $startDate = null;
        $endDate = Carbon::now();
        
        if ($filter === '7') {
            $startDate = Carbon::now()->subDays(7)->startOfDay();
        } elseif ($filter === '30') {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        } elseif ($filter === '90') {
            $startDate = Carbon::now()->subDays(90)->startOfDay();
        } elseif ($filter === 'custom') {
            $startDate = $request->query('start_date') ? Carbon::parse($request->query('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
            $endDate = $request->query('end_date') ? Carbon::parse($request->query('end_date'))->endOfDay() : Carbon::now()->endOfDay();
        } else {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        }

        $orders = Order::whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
            ->whereHas('items.product', fn($q) => $q->where('seller_id', $seller->id))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $seller->id)), 'user'])
            ->orderByDesc('created_at')
            ->get();
            
        $format = $request->query('format', 'xls');
        $filename = 'Data_Pembeli_' . $seller->creatorProfile->store_name . '_' . date('Ymd');
        
        if ($format === 'xls' || $format === 'csv') {
            $csvData = "Tanggal,Nama,Email,No WA,Order ID,Produk,Total(Rp),UTM Source\n";
            foreach ($orders as $order) {
                $date = $order->created_at->format('Y-m-d H:i');
                $name = $order->user->name ?? '-';
                $email = $order->user->email ?? '-';
                $phone = $order->user->phone ?? '-';
                $oid = $order->order_number;
                
                $productNames = $order->items->pluck('product_name')->implode(' | ');
                $productNames = str_replace(',', ' ', $productNames); // escape comma for csv
                
                $total = $order->items->sum('subtotal');
                $source = $order->utm_source ?: 'Organic';
                
                $csvData .= "{$date},{$name},{$email},{$phone},{$oid},{$productNames},{$total},{$source}\n";
            }
            
            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
        } 
        
        if ($format === 'pdf') {
            // Karena tidak yakin dompdf sudah dikonfigurasi, kita gunakan HTML simple ke dompdf
            $pdf = Pdf::loadHTML($this->generatePdfHtml($orders, $seller));
            return $pdf->download($filename . '.pdf');
        }
        
        return back();
    }
    
    private function generatePdfHtml($orders, $seller)
    {
        $html = '<h2 style="font-family:sans-serif;">Data Pembeli - ' . $seller->creatorProfile->store_name . '</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; font-family:sans-serif; font-size:12px; border-collapse:collapse;">';
        $html .= '<tr><th>Tanggal</th><th>Nama</th><th>Email</th><th>Total Order</th><th>Produk</th><th>Sumber UTM</th></tr>';
        
        foreach ($orders as $order) {
            $date = $order->created_at->format('d/m/Y');
            $name = $order->user->name ?? '-';
            $email = $order->user->email ?? '-';
            $total = 'Rp ' . number_format($order->items->sum('subtotal'), 0, ',', '.');
            $products = $order->items->pluck('product_name')->implode(', ');
            $source = $order->utm_source ?: 'Organic';
            
            $html .= "<tr><td>{$date}</td><td>{$name}</td><td>{$email}</td><td>{$total}</td><td>{$products}</td><td>{$source}</td></tr>";
        }
        
        $html .= '</table>';
        return $html;
    }
}

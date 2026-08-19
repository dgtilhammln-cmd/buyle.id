<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        return view('admin.analytics.index');
    }

    public function realtime()
    {
        $since = now()->subMinutes(60);
        $active = AnalyticsEvent::ofType('pageview')
            ->where('created_at', '>=', $since)
            ->count();

        $locations = AnalyticsEvent::ofType('pageview')
            ->where('created_at', '>=', $since)
            ->whereNotNull('country')
            ->distinct('country')
            ->orderByDesc('created_at')
            ->limit(5)
            ->pluck('country')
            ->unique()
            ->values();

        return response()->json([
            'active'    => $active,
            'locations' => $locations,
        ]);
    }

    public function data(Request $request)
    {
        $period = $request->input('period', '365');
        $from   = match($period) {
            '7'      => now()->subDays(6)->startOfDay(),
            '30'     => now()->subDays(29)->startOfDay(),
            '365'    => now()->subDays(364)->startOfDay(),
            'custom' => \Carbon\Carbon::parse($request->input('from'))->startOfDay(),
            default  => now()->subDays(364)->startOfDay(),
        };
        $to = $period === 'custom'
            ? \Carbon\Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfDay();

        // Pageview summary
        $summary = [];
        $summary['pageview'] = AnalyticsEvent::ofType('pageview')->whereBetween('created_at', [$from, $to])->count();

        // Registered users in period
        $summary['registered_users'] = User::whereBetween('created_at', [$from, $to])->where('role', 'buyer')->count();

        // Products sold (sum of qty from order items for completed/shipped orders)
        $soldQuery = \App\Models\OrderItem::whereHas('order', function($q) use ($from, $to) {
            $q->whereIn('status', ['completed', 'shipped', 'delivered'])
              ->whereBetween('created_at', [$from, $to]);
        });
        $summary['products_sold'] = $soldQuery->sum('qty');

        // Revenue from paid/completed orders
        $ordersInPeriod = Order::whereIn('status', ['completed', 'shipped', 'delivered', 'processing'])
            ->whereBetween('created_at', [$from, $to])
            ->get();
        $grossRevenue = $ordersInPeriod->sum('total_amount');
        $txCount      = $ordersInPeriod->count();
        // Midtrans estimate: 2.9% + Rp2000 per transaction
        $gatewayFee   = ($grossRevenue * 0.029) + ($txCount * 2000);
        $netRevenue   = max(0, $grossRevenue - $gatewayFee);
        $summary['gross_revenue'] = (int) $grossRevenue;
        $summary['net_revenue']   = (int) $netRevenue;
        $summary['tx_count']      = $txCount;

        // Daily chart pageviews
        $daily = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count', 'date');

        // Daily revenue (gross)
        $dailyRevenue = Order::whereIn('status', ['completed', 'shipped', 'delivered', 'processing'])
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')->orderBy('date')
            ->pluck('total', 'date');

        // Daily new users
        $dailyUsers = User::where('role', 'buyer')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count', 'date');

        $days   = (int) $from->diffInDays($to) + 1;
        $labels = [];
        $visitorValues = [];
        $revenueValues = [];
        $userValues    = [];
        for ($i = 0; $i < $days; $i++) {
            $date     = $from->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $from->copy()->addDays($i)->format('d/m');
            $visitorValues[] = $daily[$date] ?? 0;
            $revenueValues[] = (int) ($dailyRevenue[$date] ?? 0);
            $userValues[]    = $dailyUsers[$date] ?? 0;
        }

        // Device breakdown
        $devices = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type');

        // Top pages
        $topPages = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('page_url, COUNT(*) as views')
            ->groupBy('page_url')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        // Top Locations
        $locations = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at', [$from, $to])
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(10)
            ->pluck('count', 'country');

        return response()->json([
            'summary'        => $summary,
            'labels'         => $labels,
            'visitorValues'  => $visitorValues,
            'revenueValues'  => $revenueValues,
            'userValues'     => $userValues,
            'devices'        => $devices,
            'top_pages'      => $topPages,
            'locations'      => $locations,
        ]);
    }

    public function exportXls(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return back()->with('error', 'Silakan filter periode tanggal terlebih dahulu sebelum men-download laporan.');
        }

        $from = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $to   = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        $days = (int) $from->diffInDays($to) + 1;

        $pageviews = AnalyticsEvent::ofType('pageview')->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        $dailyUsers = User::where('role', 'buyer')->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        $dailyOrders = Order::whereIn('status', ['completed','shipped','delivered','processing'])
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->groupBy('date')->get()->keyBy('date');

        $filename = "Laporan_Analytics_{$from->format('Ymd')}_{$to->format('Ymd')}.csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($days, $from, $pageviews, $dailyUsers, $dailyOrders) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['Generated By:', 'buyle.id | hvmdigital.id']);
            fputcsv($file, ['Periode:', $from->format('d/M/Y') . ' - ' . $from->copy()->addDays($days-1)->format('d/M/Y')]);
            fputcsv($file, ['Catatan:', 'Est. Profit Bersih = Kotor - (2.9% + Rp2000/transaksi) [Estimasi Midtrans]']);
            fputcsv($file, []);

            fputcsv($file, ['Tanggal', 'Bulan', 'Tahun', 'Visitor', 'Akun Terdaftar', 'Jumlah Transaksi', 'Pemasukan Kotor (Rp)', 'Est. Biaya Gateway (Rp)', 'Est. Profit Bersih (Rp)']);

            for ($i = 0; $i < $days; $i++) {
                $dateObj  = $from->copy()->addDays($i);
                $dateStr  = $dateObj->format('Y-m-d');
                $v        = $pageviews[$dateStr]   ?? 0;
                $u        = $dailyUsers[$dateStr]   ?? 0;
                $row      = $dailyOrders[$dateStr]  ?? null;
                $txCount  = $row ? $row->orders  : 0;
                $gross    = $row ? $row->revenue : 0;
                $fee      = ($gross * 0.029) + ($txCount * 2000);
                $net      = max(0, $gross - $fee);

                fputcsv($file, [
                    $dateObj->format('d'),
                    $dateObj->format('M'),
                    $dateObj->format('Y'),
                    $v,
                    $u,
                    $txCount,
                    number_format($gross, 0, ',', '.'),
                    number_format($fee,   0, ',', '.'),
                    number_format($net,   0, ',', '.'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        if (!$request->start_date || !$request->end_date) {
            return back()->with('error', 'Silakan filter periode tanggal terlebih dahulu sebelum men-download laporan.');
        }

        $from = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $to   = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        $days = (int) $from->diffInDays($to) + 1;

        $pageviews = AnalyticsEvent::ofType('pageview')->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        $dailyUsers = User::where('role', 'buyer')->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->pluck('count', 'date');

        $dailyOrders = Order::whereIn('status', ['completed','shipped','delivered','processing'])
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_amount) as revenue')
            ->groupBy('date')->get()->keyBy('date');

        $data   = [];
        $totals = ['v' => 0, 'u' => 0, 'tx' => 0, 'gross' => 0, 'fee' => 0, 'net' => 0];

        for ($i = 0; $i < $days; $i++) {
            $dateObj = $from->copy()->addDays($i);
            $dateStr = $dateObj->format('Y-m-d');

            $v       = $pageviews[$dateStr]  ?? 0;
            $u       = $dailyUsers[$dateStr] ?? 0;
            $row     = $dailyOrders[$dateStr] ?? null;
            $txCount = $row ? $row->orders  : 0;
            $gross   = $row ? $row->revenue : 0;
            $fee     = ($gross * 0.029) + ($txCount * 2000);
            $net     = max(0, $gross - $fee);

            $totals['v']     += $v;
            $totals['u']     += $u;
            $totals['tx']    += $txCount;
            $totals['gross'] += $gross;
            $totals['fee']   += $fee;
            $totals['net']   += $net;

            $data[] = [
                'date'  => $dateObj->format('d/m/Y'),
                'tgl'   => $dateObj->format('d'),
                'bln'   => $dateObj->format('M'),
                'thn'   => $dateObj->format('Y'),
                'v'     => $v,
                'u'     => $u,
                'tx'    => $txCount,
                'gross' => $gross,
                'fee'   => $fee,
                'net'   => $net,
            ];
        }

        return view('admin.exports.analytics_pdf', compact('data', 'totals', 'from', 'to'));
    }
}

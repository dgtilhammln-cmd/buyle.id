<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();

        $period     = $request->input('period', '30d');
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');

        if ($start_date && $end_date) {
            $period = 'custom';
            $from   = Carbon::parse($start_date)->startOfDay();
            $to     = Carbon::parse($end_date)->endOfDay();
        } elseif ($period === '7d') {
            $from = $now->copy()->subDays(6)->startOfDay();
            $to   = $now->copy()->endOfDay();
        } elseif ($period === '1y') {
            $from = $now->copy()->subDays(364)->startOfDay();
            $to   = $now->copy()->endOfDay();
        } else {
            $period = '30d';
            $from   = $now->copy()->subDays(29)->startOfDay();
            $to     = $now->copy()->endOfDay();
        }

        $daysDiff = $from->diffInDays($to);
        if ($daysDiff > 60) $daysDiff = 60;

        // Cache key berdasarkan period yang dipilih (bukan custom)
        $cacheKey = 'admin_dashboard_' . $period . '_' . $from->format('Ymd') . '_' . $to->format('Ymd');
        $cacheTtl = 300; // 5 menit

        [
            $stats,
            $labels,
            $visitorValues,
            $creatorValues,
            $transactionValues,
            $productValues,
            $recentOrders,
            $creatorsLeaderboard,
        ] = Cache::remember($cacheKey, $cacheTtl, function () use ($from, $to, $now, $daysDiff) {

            // 1. STAT CARDS
            $visitorCount = AnalyticsEvent::ofType('pageview')
                ->whereBetween('created_at', [$from, $to])
                ->count();
            if ($visitorCount === 0) {
                $visitorCount = AnalyticsEvent::ofType('pageview')->count();
            }

            $creatorsCount = User::whereIn('role', ['seller', 'creator'])->count();
            if ($creatorsCount === 0) {
                $creatorsCount = User::whereHas('creatorProfile')->count();
            }

            $transactionsCount   = Order::whereBetween('created_at', [$from, $to])->count();
            $allOrdersCount      = Order::count();
            $displayTransactions = $transactionsCount > 0 ? $transactionsCount : $allOrdersCount;
            $totalProductsCount  = Product::count();

            $stats = [
                'visitor'      => $visitorCount,
                'creators'     => $creatorsCount,
                'transactions' => $displayTransactions,
                'products'     => $totalProductsCount,
            ];

            // 2. DAILY CHART DATA
            $visitorChart = AnalyticsEvent::ofType('pageview')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')
                ->pluck('count', 'date');

            $creatorChart = User::whereIn('role', ['seller', 'creator'])
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')
                ->pluck('count', 'date');

            $transactionChart = Order::whereBetween('created_at', [$from, $to])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')
                ->pluck('count', 'date');

            $productChart = Product::whereBetween('created_at', [$from, $to])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')->orderBy('date')
                ->pluck('count', 'date');

            $labels = $visitorValues = $creatorValues = $transactionValues = $productValues = [];
            for ($i = $daysDiff; $i >= 0; $i--) {
                $date = $to->copy()->subDays($i)->format('Y-m-d');
                $labels[]            = $to->copy()->subDays($i)->format('d/m');
                $visitorValues[]     = $visitorChart[$date] ?? 0;
                $creatorValues[]     = $creatorChart[$date] ?? 0;
                $transactionValues[] = $transactionChart[$date] ?? 0;
                $productValues[]     = $productChart[$date] ?? 0;
            }

            // 3. RIWAYAT TRANSAKSI TERBARU
            $recentOrders = Order::with(['user:id,name,email', 'items.product:id,name,slug'])
                ->latestFirst()
                ->limit(25)
                ->get();

            // 4. LEADERBOARD — 1 query aggregate, TIDAK ada N+1 per creator
            // Hitung total sold & views per user via JOIN ke products + order_items
            $leaderRaw = DB::table('users')
                ->select([
                    'users.id',
                    'users.name',
                    'users.username',
                    'users.avatar',
                    DB::raw('COUNT(DISTINCT p.id) as products_count'),
                    DB::raw('COALESCE(SUM(p.views_count), 0) as total_views'),
                    DB::raw('COALESCE(SUM(p.sold_count), 0) as total_sold'),
                    DB::raw('COALESCE(SUM(oi.qty), 0) as total_orders_qty'),
                ])
                ->leftJoin('products as p', function ($j) {
                    $j->on('p.seller_id', '=', 'users.id')
                      ->where('p.is_active', true);
                })
                ->leftJoin('order_items as oi', 'oi.product_id', '=', 'p.id')
                ->leftJoin('creator_profiles as cp', 'cp.user_id', '=', 'users.id')
                ->whereNotNull('cp.user_id')
                ->whereNotIn('users.role', ['admin', 'super_admin', 'admin_super'])
                ->groupBy('users.id', 'users.name', 'users.username', 'users.avatar')
                ->orderByDesc(DB::raw('COALESCE(SUM(p.sold_count), 0) + COALESCE(SUM(oi.qty), 0)'))
                ->limit(10)
                ->get();

            // Ambil store_name & store_slug dari creator_profiles sekali (bulk)
            $creatorProfiles = DB::table('creator_profiles')
                ->whereIn('user_id', $leaderRaw->pluck('id'))
                ->pluck('store_name', 'user_id')
                ->toArray();
            $creatorSlugs = DB::table('creator_profiles')
                ->whereIn('user_id', $leaderRaw->pluck('id'))
                ->pluck('store_slug', 'user_id')
                ->toArray();

            $creatorsLeaderboard = $leaderRaw->map(function ($row) use ($creatorProfiles, $creatorSlugs) {
                $storeName = $creatorProfiles[$row->id] ?? $row->name ?? 'Creator #' . $row->id;
                $storeSlug = $creatorSlugs[$row->id] ?? $row->username ?? $row->id;
                return (object) [
                    'id'             => $row->id,
                    'name'           => $storeName,
                    'slug'           => $storeSlug,
                    'avatar'         => $row->avatar ? asset('storage/' . $row->avatar) : null,
                    'total_views'    => (int) $row->total_views,
                    'total_orders'   => (int) $row->total_orders_qty + (int) $row->total_sold,
                    'products_count' => (int) $row->products_count,
                ];
            });

            return [
                $stats, $labels, $visitorValues, $creatorValues,
                $transactionValues, $productValues, $recentOrders, $creatorsLeaderboard,
            ];
        });

        return view('admin.dashboard.index', compact(
            'stats',
            'labels',
            'visitorValues',
            'creatorValues',
            'transactionValues',
            'productValues',
            'recentOrders',
            'creatorsLeaderboard',
            'period',
            'start_date',
            'end_date'
        ));
    }
}

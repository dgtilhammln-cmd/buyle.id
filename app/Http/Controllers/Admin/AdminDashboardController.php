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

        // 1. STAT CARDS
        // Total Visitor
        $visitorCount = AnalyticsEvent::ofType('pageview')->whereBetween('created_at', [$from, $to])->count();
        if ($visitorCount === 0) {
            $visitorCount = AnalyticsEvent::ofType('pageview')->count();
        }

        // Total Creators
        $creatorsCount = User::whereIn('role', ['seller', 'creator'])->count();
        if ($creatorsCount === 0) {
            $creatorsCount = User::whereHas('creatorProfile')->count();
        }

        // Total Transaksi (Count of checkout orders, NOT money)
        $transactionsCount = Order::whereBetween('created_at', [$from, $to])->count();
        $allOrdersCount    = Order::count();
        $displayTransactions = $transactionsCount > 0 ? $transactionsCount : $allOrdersCount;

        // Total Produk Digital (mencakup semua kategori)
        $totalProductsCount = Product::count();

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

        $labels = [];
        $visitorValues = [];
        $creatorValues = [];
        $transactionValues = [];
        $productValues = [];

        for ($i = $daysDiff; $i >= 0; $i--) {
            $date = $to->copy()->subDays($i)->format('Y-m-d');
            $labels[] = $to->copy()->subDays($i)->format('d/m');
            $visitorValues[] = $visitorChart[$date] ?? 0;
            $creatorValues[] = $creatorChart[$date] ?? 0;
            $transactionValues[] = $transactionChart[$date] ?? 0;
            $productValues[] = $productChart[$date] ?? 0;
        }

        // 3. RIWAYAT TRANSAKSI TERBARU (ORDER)
        $recentOrders = Order::with(['user', 'items.product'])->latestFirst()->limit(25)->get();

        // 4. KLASEMEN CREATOR (Leaderboard - Top Traffic & Orders)
        $creatorsLeaderboard = User::whereIn('role', ['seller', 'creator'])
            ->orWhereHas('creatorProfile')
            ->with(['creatorProfile', 'products'])
            ->withCount('products')
            ->get()
            ->map(function ($user) {
                $products = $user->products ?? collect();
                $totalViews = $products->sum('views_count');
                $totalSoldCount = $products->sum('sold_count');
                $productIds = $products->pluck('id')->filter()->all();
                $orderItemsCount = !empty($productIds) ? OrderItem::whereIn('product_id', $productIds)->count() : 0;

                $storeName = $user->creatorProfile?->store_name ?: ($user->name ?: 'Creator #' . $user->id);
                $storeSlug = $user->creatorProfile?->store_slug ?: ($user->username ?: $user->id);

                return (object) [
                    'id'             => $user->id,
                    'name'           => $storeName,
                    'slug'           => $storeSlug,
                    'avatar'         => $user->avatar ? asset('storage/' . $user->avatar) : null,
                    'total_views'    => $totalViews,
                    'total_orders'   => max($totalSoldCount, $orderItemsCount),
                    'products_count' => $user->products_count ?? $products->count(),
                ];
            })
            ->sortByDesc(fn($c) => ($c->total_orders * 1000) + $c->total_views)
            ->values()
            ->take(10);

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

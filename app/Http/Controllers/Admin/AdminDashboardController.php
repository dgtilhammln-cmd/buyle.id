<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\Service;
use App\Models\Article;
use App\Models\GalleryProject;
use App\Models\Client;
use App\Models\Lead;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $now  = now();
        
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        
        if ($start_date && $end_date) {
            $from = Carbon::parse($start_date)->startOfDay();
            $to   = Carbon::parse($end_date)->endOfDay();
        } else {
            $from = $now->copy()->subDays(29)->startOfDay();
            $to   = $now->copy()->endOfDay();
        }

        // Calculate days diff for chart
        $daysDiff = $from->diffInDays($to);
        if ($daysDiff > 60) $daysDiff = 60; // Limit chart labels

        $visitorCount = AnalyticsEvent::ofType('pageview')->whereBetween('created_at',[$from,$to])->count();
        $waClicks     = AnalyticsEvent::ofType('wa_click')->whereBetween('created_at',[$from,$to])->count();
        $leadsCount   = Lead::whereBetween('created_at',[$from,$to])->count();
        $totalBuyers  = User::where('role','buyer')->count();
        $newBuyers    = User::where('role','buyer')->whereBetween('created_at',[$from,$to])->count();

        $stats = [
            'visitor'     => $visitorCount,
            'wa_click'    => $waClicks,
            'leads'       => $leadsCount,
            'total_buyers'=> $totalBuyers,
            'new_buyers'  => $newBuyers,
        ];

        // Leads daily chart
        $leadsChart = Lead::whereBetween('created_at',[$from,$to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count','date');

        // Visitor daily chart
        $visitorChart = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at',[$from,$to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count','date');

        // WA click daily chart
        $waChart = AnalyticsEvent::ofType('wa_click')
            ->whereBetween('created_at',[$from,$to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count','date');

        // Buyer daily chart
        $buyerChart = User::where('role','buyer')
            ->whereBetween('created_at',[$from,$to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')->orderBy('date')
            ->pluck('count','date');

        $labels = [];
        $values = [];
        $visitorValues = [];
        $waValues = [];
        $buyerValues = [];
        for ($i = $daysDiff; $i >= 0; $i--) {
            $date     = $to->copy()->subDays($i)->format('Y-m-d');
            $labels[] = $to->copy()->subDays($i)->format('d/m');
            $values[] = $leadsChart[$date] ?? 0;
            $visitorValues[] = $visitorChart[$date] ?? 0;
            $waValues[] = $waChart[$date] ?? 0;
            $buyerValues[] = $buyerChart[$date] ?? 0;
        }

        // Top pages
        $topPages = AnalyticsEvent::ofType('pageview')
            ->whereBetween('created_at',[$from,$to])
            ->selectRaw('page_url, COUNT(*) as views')
            ->groupBy('page_url')->orderByDesc('views')->limit(8)->get();

        // Content counts
        $counts = [
            'services' => Service::count(),
            'articles' => Article::count(),
            'gallery'  => GalleryProject::count(),
            'clients'  => Client::count(),
        ];

        // Recent leads
        $recentLeads = Lead::orderByDesc('created_at')->limit(8)->get();

        return view('admin.dashboard.index', compact('stats','labels','values','visitorValues','waValues','buyerValues','topPages','counts','recentLeads','start_date','end_date'));
    }
}

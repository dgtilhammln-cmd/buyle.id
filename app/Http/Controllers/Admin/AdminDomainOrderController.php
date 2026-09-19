<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DomainOrder;
use Illuminate\Http\Request;

class AdminDomainOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $q      = trim($request->get('q', ''));
        $period = $request->get('period', 'all');

        $query = DomainOrder::with('user')->orderByDesc('created_at');

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('domain_name', 'like', "%{$q}%")
                    ->orWhere('extension', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%"));
            });
        }

        if ($period === '7d') {
            $query->where('created_at', '>=', now()->subDays(7));
        } elseif ($period === '30d') {
            $query->where('created_at', '>=', now()->subDays(30));
        } elseif ($period === '1y') {
            $query->where('created_at', '>=', now()->subYears(1));
        }

        $orders = $query->paginate(20)->withQueryString();

        // Calculate summary stats
        $allOrdersQuery = DomainOrder::query();
        $stats = [
            'total'     => (clone $allOrdersQuery)->count(),
            'paid'      => (clone $allOrdersQuery)->where('status', 'paid')->count(),
            'pending'   => (clone $allOrdersQuery)->where('status', 'pending')->count(),
            'cancelled' => (clone $allOrdersQuery)->where('status', 'cancelled')->count(),
            'revenue'   => (clone $allOrdersQuery)->where('status', 'paid')->sum('amount'),
        ];

        return view('admin.domain_orders.index', compact('orders', 'stats', 'status', 'q', 'period'));
    }

    public function updateStatus(Request $request, DomainOrder $domainOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);

        $oldStatus = $domainOrder->status;
        $domainOrder->status = $request->status;
        $domainOrder->save();

        // If status changed to paid, sync user custom_domain if empty
        if ($request->status === 'paid' && $oldStatus !== 'paid' && $domainOrder->user) {
            $user = $domainOrder->user;
            if ($user->creatorProfile && empty($user->creatorProfile->custom_domain)) {
                $user->creatorProfile->custom_domain = $domainOrder->domain_name;
                $user->creatorProfile->save();
            }
        }

        return back()->with('success', "Status order domain '{$domainOrder->domain_name}' berhasil diperbarui ke " . strtoupper($request->status) . "!");
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Courier;
use App\Models\Shipment;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    // Tab config: [label, status values or null for all, key]
    private array $tabs = [
        'all'        => ['label' => 'Semua',         'statuses' => null],
        'processing' => ['label' => 'Perlu Dikirim', 'statuses' => ['confirmed','processing']],
        'shipped'    => ['label' => 'Dikirim',       'statuses' => ['shipped']],
        'completed'  => ['label' => 'Selesai',       'statuses' => ['completed','delivered']],
    ];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        if (!array_key_exists($tab, $this->tabs)) $tab = 'all';

        $period    = $request->get('period', '30d');
        $startDate = $request->get('start_date');
        $endDate   = $request->get('end_date');

        $query = Order::with(['user', 'items.product.seller', 'payment', 'shipment'])
            ->orderByDesc('created_at');

        // Date / Period Filter
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $period = 'custom';
        } else {
            if ($period === '7d') {
                $query->where('created_at', '>=', now()->subDays(7));
            } elseif ($period === '30d') {
                $query->where('created_at', '>=', now()->subDays(30));
            } elseif ($period === '1y') {
                $query->where('created_at', '>=', now()->subYears(1));
            }
        }

        // Filter by tab statuses
        $statuses = $this->tabs[$tab]['statuses'];
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        // Search (by order_number, user name/email/username, store/creator name/email, product title)
        if ($q = trim($request->get('q'))) {
            $query->where(function($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%"))
                    ->orWhereHas('items.product', fn($p) => $p->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%"))
                    ->orWhereHas('items.product.seller', fn($cu) => $cu->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('store_name', 'like', "%{$q}%")
                        ->orWhere('store_slug', 'like', "%{$q}%"));
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Stats calculation for 5 top cards (filtered by period/dates)
        $statsBase = Order::query();
        if ($startDate && $endDate) {
            $statsBase->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        } else {
            if ($period === '7d') {
                $statsBase->where('created_at', '>=', now()->subDays(7));
            } elseif ($period === '30d') {
                $statsBase->where('created_at', '>=', now()->subDays(30));
            } elseif ($period === '1y') {
                $statsBase->where('created_at', '>=', now()->subYears(1));
            }
        }

        $stats = [
            'total'      => (clone $statsBase)->count(),
            'processing' => (clone $statsBase)->whereIn('status', ['confirmed', 'processing'])->count(),
            'shipped'    => (clone $statsBase)->whereIn('status', ['shipped'])->count(),
            'completed'  => (clone $statsBase)->whereIn('status', ['completed', 'delivered'])->count(),
            'revenue'    => (clone $statsBase)->whereIn('status', ['completed', 'delivered', 'shipped', 'processing'])->sum('total'),
        ];

        // Tab counts
        $counts = [];
        foreach ($this->tabs as $key => $cfg) {
            $cQuery = clone $statsBase;
            if ($cfg['statuses'] !== null) {
                $cQuery->whereIn('status', $cfg['statuses']);
            }
            $counts[$key] = $cQuery->count();
        }

        return view('admin.orders.index', [
            'orders'     => $orders,
            'tab'        => $tab,
            'tabs'       => $this->tabs,
            'counts'     => $counts,
            'stats'      => $stats,
            'q'          => $q ?? '',
            'period'     => $period,
            'start_date' => $startDate ?? '',
            'end_date'   => $endDate ?? '',
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
            $pdf = \PDF::loadView('admin.orders.pdf', compact('orders', 'start', 'end'))->setPaper('a4', 'landscape');
            return $pdf->download("Laporan_Pesanan_{$start}_to_{$end}.pdf");
        }

        $export = new \App\Exports\OrdersExport($start, $end);

        if ($format === 'csv') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, "Laporan_Pesanan_{$start}_to_{$end}.csv", \Maatwebsite\Excel\Excel::CSV);
        }

        return \Maatwebsite\Excel\Facades\Excel::download($export, "Laporan_Pesanan_{$start}_to_{$end}.xlsx");
    }
}

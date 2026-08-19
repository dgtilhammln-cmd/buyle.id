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
        'all'        => ['label' => 'Semua',                    'statuses' => null],
        'pending'    => ['label' => 'Belum Bayar',              'statuses' => ['pending']],
        'processing' => ['label' => 'Perlu Dikirim',            'statuses' => ['confirmed','processing']],
        'shipped'    => ['label' => 'Dikirim',                  'statuses' => ['shipped']],
        'delivered'  => ['label' => 'Selesai',                  'statuses' => ['delivered']],
        'cancelled'  => ['label' => 'Pengembalian/Pembatalan',  'statuses' => ['cancelled','refunded']],
    ];

    public function index(Request $request)
    {
        $tab = $request->get('tab', 'all');
        if (!array_key_exists($tab, $this->tabs)) $tab = 'all';

        $query = Order::with(['user', 'items', 'payment', 'shipment'])
            ->orderByDesc('created_at');

        // Filter by tab statuses
        $statuses = $this->tabs[$tab]['statuses'];
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        // Search
        if ($q = $request->get('q')) {
            $query->where(function($sub) use ($q) {
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhereHas('user', fn($u) => $u->where('name','like',"%{$q}%")
                        ->orWhere('email','like',"%{$q}%"));
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        // Abandoned Carts (for pending & all tab)
        $abandonedCarts = collect();
        if (in_array($tab, ['pending', 'all'])) {
            $abandonedCartsQuery = \App\Models\User::whereHas('carts')->with(['carts.product', 'carts.variantValue']);
            if ($q) {
                $abandonedCartsQuery->where(function($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%");
                });
            }
            $abandonedCarts = $abandonedCartsQuery->get();
        }

        // Counts per tab
        $counts = [];
        $abandonedCount = \App\Models\User::whereHas('carts')->count();
        foreach ($this->tabs as $key => $cfg) {
            if ($cfg['statuses'] === null) {
                $counts[$key] = Order::count();
            } else {
                $counts[$key] = Order::whereIn('status', $cfg['statuses'])->count();
                if ($key === 'pending') {
                    $counts[$key] += $abandonedCount;
                }
            }
        }

        return view('admin.orders.index', [
            'orders'         => $orders,
            'tab'            => $tab,
            'tabs'           => $this->tabs,
            'counts'         => $counts,
            'q'              => $q ?? '',
            'abandonedCarts' => $abandonedCarts ?? collect(),
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

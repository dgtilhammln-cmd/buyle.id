<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Order;
use App\Models\User;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PayoutRequest::with(['seller'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $requests = $query->paginate(20)->withQueryString();

        $pendingCount = PayoutRequest::where('status', 'pending')->count();

        // Total GMV (semua order sukses)
        $totalGmv = Order::whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->sum('order_items.subtotal');

        // Platform fee (5% dari GMV)
        $platformFee = $totalGmv * 0.05;

        // Total fee penarikan dari request yang sudah approved
        $withdrawalFeeTotal = PayoutRequest::whereIn('status', ['approved', 'processed'])
            ->sum('admin_fee');

        // Total laba bersih buyle.id
        $netProfit = $platformFee + $withdrawalFeeTotal;

        // Kewajiban saldo creator (saldo mengendap yang belum dicairkan)
        $totalApproved = PayoutRequest::whereIn('status', ['approved', 'processed'])->sum('amount');
        $totalPending  = PayoutRequest::where('status', 'pending')->sum('amount');
        $creatorLiability = max(0, $totalGmv - $totalApproved - $totalPending);

        return view('admin.payouts.index', compact(
            'requests',
            'status',
            'pendingCount',
            'totalGmv',
            'platformFee',
            'withdrawalFeeTotal',
            'netProfit',
            'creatorLiability'
        ));
    }

    public function approve(Request $request, PayoutRequest $payout)
    {
        $request->validate([
            'reference_number' => 'nullable|string|max:100',
            'admin_notes'      => 'nullable|string|max:500',
        ]);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $payout->update([
            'status'           => 'processed',
            'processed_at'     => now(),
            'reference_number' => $request->reference_number,
            'admin_notes'      => $request->admin_notes,
        ]);

        // Kirim notifikasi email ke creator
        try {
            $seller = $payout->seller;
            if ($seller && $seller->email) {
                $seller->notify(new \App\Notifications\PayoutApprovedNotification($payout));
            }
        } catch (\Throwable $e) {
            \Log::warning('Gagal kirim notifikasi PayoutApproved: ' . $e->getMessage());
        }

        return back()->with('success', 'Pencairan dana berhasil dikonfirmasi. Email notifikasi terkirim ke creator.');
    }

    public function reject(Request $request, PayoutRequest $payout)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $payout->update([
            'status'       => 'rejected',
            'processed_at' => now(),
            'admin_notes'  => $request->admin_notes,
        ]);

        // Kirim notifikasi email ke creator
        try {
            $seller = $payout->seller;
            if ($seller && $seller->email) {
                $seller->notify(new \App\Notifications\PayoutRejectedNotification($payout));
            }
        } catch (\Throwable $e) {
            \Log::warning('Gagal kirim notifikasi PayoutRejected: ' . $e->getMessage());
        }

        return back()->with('success', 'Pengajuan ditolak. Saldo creator otomatis dikembalikan. Email notifikasi terkirim.');
    }
}

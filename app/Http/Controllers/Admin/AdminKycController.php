<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreatorKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminKycController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');
        $search = $request->input('search', '');

        $query = CreatorKyc::with(['user.creatorProfile'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('user', fn($uq) =>
                        $uq->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                    )->orWhere('nik', 'like', "%{$search}%")
                     ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('submitted_at');

        $kycs = $query->paginate(20)->withQueryString();

        $counts = [
            'all'      => CreatorKyc::count(),
            'pending'  => CreatorKyc::where('status', 'pending')->count(),
            'verified' => CreatorKyc::where('status', 'verified')->count(),
            'rejected' => CreatorKyc::where('status', 'rejected')->count(),
            'none'     => CreatorKyc::where('status', 'none')->count(),
        ];

        return view('admin.kyc.index', compact('kycs', 'status', 'search', 'counts'));
    }

    public function show(CreatorKyc $kyc)
    {
        $kyc->load(['user.creatorProfile', 'user.products']);
        $user = $kyc->user;

        $ktpUrl    = $kyc->ktp_photo_path    ? route('admin.kyc.photo', ['kyc' => $kyc->id, 'type' => 'ktp'])    : null;
        $selfieUrl = $kyc->selfie_photo_path ? route('admin.kyc.photo', ['kyc' => $kyc->id, 'type' => 'selfie']) : null;

        // Finansial & Transaksi
        $totalRevenue = 0;
        $totalOrdersCount = 0;
        $recentOrders = collect();
        $payouts = collect();
        $totalWithdrawn = 0;
        $pendingPayout = 0;

        if ($user) {
            try {
                $totalRevenue = \App\Models\OrderItem::whereHas('product', function ($q) use ($user) {
                    $q->where('seller_id', $user->id);
                })->whereHas('order', function ($q) {
                    $q->whereNotIn('status', ['pending', 'cancelled', 'refunded', 'failed', 'expired']);
                })->sum('subtotal');

                $totalOrdersCount = \App\Models\OrderItem::whereHas('product', function ($q) use ($user) {
                    $q->where('seller_id', $user->id);
                })->whereHas('order', function ($q) {
                    $q->whereNotIn('status', ['pending', 'cancelled', 'refunded', 'failed', 'expired']);
                })->count();

                $recentOrders = \App\Models\OrderItem::with(['order', 'product'])
                    ->whereHas('product', function ($q) use ($user) {
                        $q->where('seller_id', $user->id);
                    })->latest()->take(5)->get();

                $payouts = \App\Models\PayoutRequest::where('seller_id', $user->id)->latest()->get();
                $totalWithdrawn = $payouts->whereIn('status', ['approved', 'processed'])->sum('net_amount');
                $pendingPayout  = $payouts->where('status', 'pending')->sum('amount');
            } catch (\Exception $e) {
                // Ignore query failures gracefully
            }
        }

        // Rekening Bank
        $bankName    = $user->bank_name ?? ($payouts->first()?->bank_name ?? null);
        $bankAccount = $user->bank_account_number ?? ($payouts->first()?->bank_account_number ?? null);
        $bankHolder  = $user->bank_account_name ?? ($payouts->first()?->bank_account_name ?? null);

        return view('admin.kyc.show', compact(
            'kyc', 
            'ktpUrl', 
            'selfieUrl', 
            'totalRevenue', 
            'totalOrdersCount', 
            'recentOrders', 
            'payouts', 
            'totalWithdrawn', 
            'pendingPayout',
            'bankName',
            'bankAccount',
            'bankHolder'
        ));
    }

    public function photo(CreatorKyc $kyc, string $type)
    {
        $path = $type === 'ktp' ? $kyc->ktp_photo_path : $kyc->selfie_photo_path;

        if (!$path || !Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return response(Storage::disk('private')->get($path))
            ->header('Content-Type', 'image/webp')
            ->header('Cache-Control', 'private, max-age=3600');
    }

    public function approve(Request $request, CreatorKyc $kyc)
    {
        $kyc->update([
            'status'           => 'verified',
            'reviewed_at'      => now(),
            'reviewed_by'      => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Sinkronisasi status terverifikasi di Profil Creator
        if ($kyc->user && $kyc->user->creatorProfile) {
            $kyc->user->creatorProfile->update(['is_verified' => true]);
        }

        return response()->json([
            'success' => true, 
            'message' => 'e-KYC Creator berhasil disetujui! Status profil creator telah disinkronkan menjadi Terverifikasi.'
        ]);
    }

    public function reject(Request $request, CreatorKyc $kyc)
    {
        $request->validate(['reason' => 'required|string|min:10|max:1000']);

        $kyc->update([
            'status'           => 'rejected',
            'reviewed_at'      => now(),
            'reviewed_by'      => auth()->id(),
            'rejection_reason' => $request->reason,
        ]);

        return response()->json(['success' => true, 'message' => 'Pengajuan ditolak.']);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use App\Models\Order;
use App\Models\User;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = PayoutRequest::with(['seller', 'seller.creatorProfile'])
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

        // Total admin fee pencairan dari request yang sudah diproses
        $withdrawalFeeTotal = PayoutRequest::whereIn('status', ['approved', 'processed'])
            ->sum('admin_fee');

        // Total Admin Fee (dari Order + Payout)
        $orderAdminFee = Order::whereHas('payment', fn($q) => $q->where('status', PaymentStatus::Success))->sum('admin_fee');
        $totalAdminFee = $orderAdminFee + $withdrawalFeeTotal;

        // Total laba bersih buyle.id
        $netProfit = $platformFee + $totalAdminFee;

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
            'totalAdminFee',
            'withdrawalFeeTotal',
            'netProfit',
            'creatorLiability'
        ));
    }

    public function approve(Request $request, PayoutRequest $payout)
    {
        $request->validate([
            'reference_number'  => 'nullable|string|max:100',
            'admin_notes'       => 'nullable|string|max:500',
            'proof_of_transfer' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        // Upload & compress bukti transfer
        $proofPath = $payout->proof_of_transfer;
        if ($request->hasFile('proof_of_transfer')) {
            $proofPath = $this->uploadAndCompressProof($request->file('proof_of_transfer'));
        }

        $payout->update([
            'status'            => 'processed',
            'processed_at'      => now(),
            'approved_at'       => now(),
            'reference_number'  => $request->reference_number,
            'admin_notes'       => $request->admin_notes,
            'proof_of_transfer' => $proofPath,
            'processed_by'      => auth()->id(),
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
            'processed_by' => auth()->id(),
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

    public function uploadProof(Request $request, PayoutRequest $payout)
    {
        $request->validate([
            'proof_of_transfer' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'reference_number'  => 'nullable|string|max:100',
        ]);

        $proofPath = $this->uploadAndCompressProof($request->file('proof_of_transfer'));

        $updateData = ['proof_of_transfer' => $proofPath];
        if ($request->filled('reference_number')) {
            $updateData['reference_number'] = $request->reference_number;
        }

        $payout->update($updateData);

        return back()->with('success', 'Bukti transfer internal berhasil diunggah dan dikompresi.');
    }

    // ── Upload + GD compress bukti transfer ──────────────────────────────
    private function uploadAndCompressProof($file): string
    {
        $ext     = strtolower($file->getClientOriginalExtension());
        $ext     = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? $ext : 'jpg';
        $name    = 'proof_' . now()->format('YmdHis') . '_' . uniqid() . '.jpg';
        $dir     = 'payouts/proofs/' . now()->format('Y/m');
        $tmpPath = $file->getRealPath();

        // Load dengan GD
        $mime = mime_content_type($tmpPath);
        $src  = match (true) {
            str_contains($mime, 'png')  => imagecreatefrompng($tmpPath),
            str_contains($mime, 'webp') => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($tmpPath) : imagecreatefromjpeg($tmpPath),
            default                     => imagecreatefromjpeg($tmpPath),
        };

        if ($src === false) {
            // Fallback: simpan original tanpa compress
            $path = $dir . '/' . $name;
            Storage::disk('public')->put($path, file_get_contents($tmpPath));
            return $path;
        }

        // Resize jika lebar > 1200px
        $origW = imagesx($src);
        $origH = imagesy($src);
        $maxW  = 1200;

        if ($origW > $maxW) {
            $ratio  = $maxW / $origW;
            $newW   = $maxW;
            $newH   = (int) round($origH * $ratio);
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $resized;
        }

        // Output ke buffer
        ob_start();
        imagejpeg($src, null, 78); // 78% quality
        $compressed = ob_get_clean();
        imagedestroy($src);

        $path = $dir . '/' . $name;
        Storage::disk('public')->put($path, $compressed);

        return $path;
    }
}

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

        $query = CreatorKyc::with('user')
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
        $kyc->load('user');
        $ktpUrl    = $kyc->ktp_photo_path    ? route('admin.kyc.photo', ['kyc' => $kyc->id, 'type' => 'ktp'])    : null;
        $selfieUrl = $kyc->selfie_photo_path ? route('admin.kyc.photo', ['kyc' => $kyc->id, 'type' => 'selfie']) : null;

        return view('admin.kyc.show', compact('kyc', 'ktpUrl', 'selfieUrl'));
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

        return response()->json(['success' => true, 'message' => 'Creator berhasil diverifikasi.']);
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

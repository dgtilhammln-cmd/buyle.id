<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminWhiteLabelController extends Controller
{
    /**
     * Tampilkan daftar pengajuan produk White Label.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = Product::where('is_whitelabel', true)
            ->with(['seller:id,name,email', 'category:id,name']);

        if ($status !== 'all') {
            $query->where('whitelabel_approval_status', $status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('seller', fn($sq) => $sq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending'  => Product::where('is_whitelabel', true)->where('whitelabel_approval_status', 'pending')->count(),
            'approved' => Product::where('is_whitelabel', true)->where('whitelabel_approval_status', 'approved')->count(),
            'rejected' => Product::where('is_whitelabel', true)->where('whitelabel_approval_status', 'rejected')->count(),
        ];

        return view('admin.whitelabel.index', compact('products', 'status', 'counts'));
    }

    /**
     * Setujui produk White Label.
     */
    public function approve(Product $product)
    {
        if (!$product->is_whitelabel) {
            return back()->with('error', 'Produk bukan produk White Label.');
        }

        $product->update([
            'whitelabel_approval_status' => 'approved',
            'whitelabel_rejection_reason' => null,
        ]);

        Cache::forget('catalog_main');
        Cache::forget('whitelabel_catalog');
        Cache::forget("seller_products_{$product->seller_id}");

        return back()->with('success', "Produk \"{$product->name}\" berhasil disetujui (Approved) sebagai White Label.");
    }

    /**
     * Tolak pengajuan White Label.
     */
    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        if (!$product->is_whitelabel) {
            return back()->with('error', 'Produk bukan produk White Label.');
        }

        $product->update([
            'whitelabel_approval_status' => 'rejected',
            'whitelabel_rejection_reason' => $request->reason ?: 'Tidak memenuhi syarat produk White Label (misal: terdapat watermark atau branding pribadi).',
        ]);

        Cache::forget('catalog_main');
        Cache::forget('whitelabel_catalog');
        Cache::forget("seller_products_{$product->seller_id}");

        return back()->with('success', "Produk \"{$product->name}\" telah ditolak (Rejected).");
    }
}

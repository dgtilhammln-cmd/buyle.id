<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponApiController extends Controller
{
    /**
     * List semua voucher aktif yang eligible untuk subtotal user saat ini.
     * Digunakan untuk Voucher Picker di checkout.
     */
    public function index(Request $request)
    {
        $subtotal = (float) $request->query('subtotal', 0);
        $userId   = Auth::id();

        $coupons = Coupon::active()
            ->orderByRaw("CASE category
                WHEN 'product'  THEN 1
                WHEN 'shipping' THEN 2
                WHEN 'event'    THEN 3
                WHEN 'member'   THEN 4
                WHEN 'referral' THEN 5
                ELSE 6 END")
            ->get()
            ->map(function (Coupon $c) use ($subtotal, $userId) {
                $eligible = $subtotal >= (float)$c->min_purchase;

                // Sudah pernah dipakai oleh user ini?
                $alreadyUsed = $userId
                    ? CouponUsage::where('coupon_id', $c->id)->where('user_id', $userId)->exists()
                    : false;

                // Hitung estimasi diskon
                $estimatedDiscount = $eligible ? $c->calculateDiscount($subtotal) : 0;

                // Sisa kuota
                $remaining = $c->usage_limit !== null
                    ? max(0, $c->usage_limit - $c->used_count)
                    : null;

                // Badge colors
                $badgeColor = match ($c->category) {
                    'shipping' => '#10B981', // green
                    'event'    => '#F59E0B', // amber
                    'member'   => '#8B5CF6', // purple
                    'referral' => '#EC4899', // pink
                    default    => '#0EA5E9', // blue (product)
                };

                // Format value
                if ($c->type->value === 'percentage') {
                    $formattedValue = (int)$c->value . '%';
                } else {
                    $formattedValue = 'Rp ' . number_format($c->value, 0, ',', '.');
                }

                $minPurchaseFormatted = $c->min_purchase > 0
                    ? 'Min. belanja Rp ' . number_format($c->min_purchase, 0, ',', '.')
                    : 'Tanpa min. belanja';

                $maxDiscountFormatted = $c->max_discount
                    ? 'Maks. Rp ' . number_format($c->max_discount, 0, ',', '.')
                    : null;

                $expiredLabel = $c->expired_at ? 'Berlaku s/d ' . $c->expired_at->format('d M Y') : null;

                return [
                    'code'                  => $c->code,
                    'category'              => $c->category,
                    'description'           => $c->description,
                    'badge'                 => $c->badge,
                    'badge_color'           => $badgeColor,
                    'type'                  => $c->type->value,
                    'value'                 => $formattedValue,
                    'min_purchase'          => $minPurchaseFormatted,
                    'max_discount'          => $maxDiscountFormatted,
                    'expired_label'         => $expiredLabel,
                    'remaining'             => $remaining,
                    'eligible'              => $eligible && !$alreadyUsed,
                    'already_used'          => $alreadyUsed,
                    'estimated_discount'    => $estimatedDiscount,
                    'estimated_discount_fmt'=> $estimatedDiscount > 0 ? 'Hemat Rp ' . number_format($estimatedDiscount, 0, ',', '.') : null,
                ];
            });

        return response()->json($coupons);
    }

    /**
     * Validate & calculate discount for a specific coupon code.
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $code     = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;
        $userId   = Auth::id();

        $coupon = Coupon::active()->byCode($code)->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak ditemukan atau sudah tidak aktif.'], 422);
        }

        try {
            $coupon->validate($subtotal);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['valid' => false, 'message' => $e->getMessage()], 422);
        }

        // Check per-user uniqueness if limit is 1
        if ($coupon->usage_limit === 1 && $userId) {
            $alreadyUsed = CouponUsage::where('coupon_id', $coupon->id)->where('user_id', $userId)->exists();
            if ($alreadyUsed) {
                return response()->json(['valid' => false, 'message' => 'Voucher ini sudah pernah Anda gunakan sebelumnya.'], 422);
            }
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return response()->json([
            'valid'          => true,
            'code'           => $coupon->code,
            'discount'       => $discount,
            'discount_fmt'   => 'Rp ' . number_format($discount, 0, ',', '.'),
            'message'        => 'Voucher berhasil diterapkan! Hemat Rp ' . number_format($discount, 0, ',', '.'),
        ]);
    }
}

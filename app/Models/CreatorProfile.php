<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'custom_domain',
        'custom_domain_status',
        'site_verification_code',
        'store_description',
        'creator_type',
        'social_links',
        'address',
        'province_id',
        'city_id',
        'raja_city_id',
        'subdistrict_id',
        'raja_district_id',
        'province_name',
        'city_name',
        'subdistrict_name',
        'village_name',
        'postal_code',
        'latitude',
        'longitude',
        'detected_ip',
        'meta_title',
        'meta_desc',
        'meta_keywords',
        'store_banner_1',
        'store_banner_2',
        // Bio Link fields
        'bio_role',
        'bio_theme',
        'bio_config',
        // Exclusive & Verification
        'is_exclusive',
        'is_verified',
        // AI Scan Metadata
        'last_menu_scan_at',
        'monthly_scan_count',
        'scan_count_reset_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'bio_config'   => 'array',
        'is_exclusive' => 'boolean',
        'is_verified'  => 'boolean',
        'last_menu_scan_at' => 'datetime',
        'scan_count_reset_at' => 'datetime',
    ];

    public function isStoreActive(): bool
    {
        $config = $this->bio_config;
        if (is_array($config) && isset($config['is_store_active'])) {
            $val = $config['is_store_active'];
            if ($val === false || $val === 0 || $val === '0' || $val === 'false' || $val === null) {
                return false;
            }
            return true;
        }
        return true;
    }

    public static function getOrCreateForUser($user)
    {
        if (!$user) return null;
        
        $profile = static::where('user_id', $user->id)->first();
        if (!$profile) {
            $baseSlug = \Illuminate\Support\Str::slug($user->name ?: 'creator');
            if (empty($baseSlug)) {
                $baseSlug = 'creator-' . $user->id;
            }
            $slug = $baseSlug;
            $i = 1;
            while (static::where('store_slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }
            $profile = static::create([
                'user_id'    => $user->id,
                'store_name' => $user->name ?: 'Creator Store',
                'store_slug' => $slug,
            ]);
        }
        return $profile;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bioBlocks()
    {
        return $this->hasMany(CreatorBioBlock::class, 'creator_id')->orderBy('order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Hitung Tingkatan Creator berdasarkan total GMV / Penjualan.
     */
    public function getTierInfo(?float $gmv = null): array
    {
        if ($gmv === null) {
            $sellerId = $this->user_id;
            try {
                $gmv = \App\Models\Order::whereHas('items.product', fn($q) => $q->where('seller_id', $sellerId))
                    ->whereHas('payment', fn($q) => $q->where('status', \App\Enums\PaymentStatus::Success))
                    ->with(['items' => fn($q) => $q->whereHas('product', fn($p) => $p->where('seller_id', $sellerId))])
                    ->get()
                    ->sum(fn($order) => $order->items->sum('subtotal'));
            } catch (\Exception $e) {
                $gmv = 0;
            }
        }

        if ($gmv >= 500000000) {
            return [
                'name'            => 'FINANCIAL FREEDOM',
                'level'           => 6,
                'subtitle'        => 'Solid Carbon Fiber Ultra-Card',
                'badge'           => 'Financial Freedom',
                'header_bg'       => 'linear-gradient(135deg, #09090b 0%, #27272a 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#eab308" stroke="#facc15" stroke-width="1.5"><path d="M4.5 16.5c-1.5 1.26-2 5.5-2 5.5s4.24-.5 5.5-2c2.43.16 4.67-.76 6.5-2.5l5.5-5.5c2.34-2.34 2.34-6.14 0-8.48-2.34-2.34-6.14-2.34-8.48 0l-5.5 5.5c-1.74 1.83-2.66 4.07-2.5 6.5z"/></svg>',
            ];
        } elseif ($gmv >= 200000000) {
            return [
                'name'            => 'EKSEKUTIF SENIOR',
                'level'           => 5,
                'subtitle'        => 'Obsidian Matte Black Silver',
                'badge'           => 'Eksekutif Senior',
                'header_bg'       => 'linear-gradient(135deg, #0f172a 0%, #312e81 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#c084fc" stroke="#e879f9" stroke-width="1.5"><path d="M6 3h12l4 6-10 12L2 9l4-6z"/></svg>',
            ];
        } elseif ($gmv >= 100000000) {
            return [
                'name'            => 'EKSEKUTIF MUDA',
                'level'           => 4,
                'subtitle'        => 'Deep Emerald Platinum',
                'badge'           => 'Enterprise',
                'header_bg'       => 'linear-gradient(135deg, #047857 0%, #10b981 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15" stroke-width="1"><path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z"/><path d="M12 15.5l-4 6 4-2 4 2z"/></svg>',
            ];
        } elseif ($gmv >= 50000000) {
            return [
                'name'            => 'PENGUSAHA MUDA',
                'level'           => 3,
                'subtitle'        => 'Rose Gold / Champagne Gold',
                'badge'           => 'Pengusaha Muda',
                'header_bg'       => 'linear-gradient(135deg, #c2410c 0%, #f97316 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#fbbf24" stroke="#d97706" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>',
            ];
        } elseif ($gmv >= 10000000) {
            return [
                'name'            => 'PEJUANG / HUSTLER',
                'level'           => 2,
                'subtitle'        => 'Titanium Gray Metallic',
                'badge'           => 'Pejuang / Hustler',
                'header_bg'       => 'linear-gradient(135deg, #1e293b 0%, #475569 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#38bdf8" stroke="#0284c7" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
            ];
        } else {
            return [
                'name'            => 'PERINTIS',
                'level'           => 1,
                'subtitle'        => 'Matte Silver / Brushed Steel',
                'badge'           => 'Perintis',
                'header_bg'       => 'linear-gradient(135deg, #1eb349 0%, #a5cf37 100%)',
                'icon_svg'        => '<svg width="14" height="14" viewBox="0 0 24 24" fill="#facc15" stroke="#facc15" stroke-width="1"><path d="M12 2l2.4 5.3 5.8.5-4.4 3.9 1.3 5.6-5.1-3-5.1 3 1.3-5.6-4.4-3.9 5.8-.5z"/><path d="M12 15.5l-4 6 4-2 4 2z"/></svg>',
            ];
        }
    }

    /**
     * Hitung Statistik Rating & Total Ulasan secara realtime dari DB.
     */
    public function getRatingStats(): array
    {
        $sellerId = $this->user_id;
        
        try {
            $ratingCount = \App\Models\ProductRating::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->count();
            $dbAvg = \App\Models\ProductRating::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))->avg('rating');

            if ($ratingCount > 0 && $dbAvg) {
                return [
                    'rating' => number_format((float)$dbAvg, 1, '.', ''),
                    'count'  => $ratingCount,
                ];
            }

            $products = \App\Models\Product::where('seller_id', $sellerId)->get();
            $validRatings = $products->where('rating', '>', 0);
            $prodAvg = $validRatings->isNotEmpty() ? $validRatings->avg('rating') : 5.0;
            $totalSold = $products->sum('sold_count');

            return [
                'rating' => number_format((float)($prodAvg ?: 5.0), 1, '.', ''),
                'count'  => (int)$totalSold,
            ];
        } catch (\Exception $e) {
            return [
                'rating' => '5.0',
                'count'  => 0,
            ];
        }
    }
}

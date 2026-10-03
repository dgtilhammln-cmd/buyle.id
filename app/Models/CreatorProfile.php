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
                'name'     => 'FINANCIAL FREEDOM',
                'level'    => 6,
                'subtitle' => 'Solid Carbon Fiber Ultra-Card',
                'badge'    => 'Financial Freedom',
            ];
        } elseif ($gmv >= 200000000) {
            return [
                'name'     => 'EKSEKUTIF SENIOR',
                'level'    => 5,
                'subtitle' => 'Obsidian Matte Black Silver',
                'badge'    => 'Eksekutif Senior',
            ];
        } elseif ($gmv >= 100000000) {
            return [
                'name'     => 'EKSEKUTIF MUDA',
                'level'    => 4,
                'subtitle' => 'Deep Emerald Platinum',
                'badge'    => 'Eksekutif Muda',
            ];
        } elseif ($gmv >= 50000000) {
            return [
                'name'     => 'PENGUSAHA MUDA',
                'level'    => 3,
                'subtitle' => 'Rose Gold / Champagne Gold',
                'badge'    => 'Pengusaha Muda',
            ];
        } elseif ($gmv >= 10000000) {
            return [
                'name'     => 'PEJUANG / HUSTLER',
                'level'    => 2,
                'subtitle' => 'Titanium Gray Metallic',
                'badge'    => 'Pejuang / Hustler',
            ];
        } else {
            return [
                'name'     => 'PERINTIS',
                'level'    => 1,
                'subtitle' => 'Matte Silver / Brushed Steel',
                'badge'    => 'Perintis',
            ];
        }
    }
}

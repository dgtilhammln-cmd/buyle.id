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
        // AI Scan Metadata
        'last_menu_scan_at',
        'monthly_scan_count',
        'scan_count_reset_at',
    ];

    protected $casts = [
        'social_links' => 'array',
        'bio_config'   => 'array',
        'last_menu_scan_at' => 'datetime',
        'scan_count_reset_at' => 'datetime',
    ];

    public function isStoreActive(): bool
    {
        $config = $this->bio_config;
        if (is_array($config) && isset($config['is_store_active'])) {
            return (bool) $config['is_store_active'];
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
}

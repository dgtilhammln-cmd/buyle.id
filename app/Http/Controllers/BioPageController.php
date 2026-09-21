<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\Product;

class BioPageController extends Controller
{
    // Reserved slugs that cannot be used as bio usernames
    const RESERVED_SLUGS = [
        'login', 'register', 'logout', 'admin', 'creator', 'c', 'api',
        'shop', 'catalog', 'account', 'checkout', 'cart', 'about',
        'contact', 'terms', 'privacy', 'sitemap', 'robots.txt',
        'password', 'verify', 'email', 'dashboard', 'home',
    ];

    public function show(string $username)
    {
        if (in_array(strtolower($username), self::RESERVED_SLUGS)) {
            abort(404);
        }

        $profile = CreatorProfile::with(['user', 'bioBlocks' => function ($q) {
            $q->where('is_active', true)->orderBy('order', 'asc')->orderBy('id', 'asc');
        }])->where('store_slug', $username)->firstOrFail();

        // Auto-redirect to /c/{slug} if no bio setup yet
        if (!$profile->bio_role) {
            if (!$profile->isStoreActive()) {
                abort(404);
            }
            return redirect()->route('store.show', $profile->store_slug);
        }

        $config = $profile->bio_config ?? [];
        $blocks = $profile->bioBlocks;

        // Auto-populate bio avatar from user's Google/profile avatar if not set
        if (empty($config['avatar']) && $profile->user) {
            $userAvatar = $profile->user->avatar ?? null;
            if ($userAvatar) {
                // Google avatars are full URLs, local ones are relative paths
                $config['_user_avatar'] = $userAvatar;
            }
        }

        // Resolve seller products from DB
        $sellerIds = array_values(array_unique(array_filter([
            $profile->user_id ?? null,
            isset($profile->user) ? $profile->user->id : null,
            $profile->id ?? null,
        ])));
        $sellerProducts = !empty($sellerIds) ? Product::whereIn('seller_id', $sellerIds)->where('is_active', true)->latest()->get() : collect();

        // Resolve Buyle products linked in blocks
        $productIds = [];
        foreach ($blocks->where('type', 'buyle_product') as $b) {
            if (!empty($b->data_json['product_id'])) $productIds[] = $b->data_json['product_id'];
        }
        $blockProducts = !empty($productIds) ? Product::whereIn('id', $productIds)->get() : collect();

        $products = $sellerProducts->concat($blockProducts)->unique('id')->keyBy('id');

        $theme = $profile->bio_theme ?? 'theme1';

        // SEO meta
        $roleTitleMap = [
            'content_creator' => 'Content Creator',
            'affiliator'      => 'Affiliator',
            'business'        => 'Business',
        ];
        $roleTitle = $roleTitleMap[$profile->bio_role ?? ''] ?? 'Creator';
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
        $seoDesc   = !empty($config['bio']) ? $config['bio'] : (!empty($profile->store_description) ? $profile->store_description : 'Temukan berbagai produk digital, rekomendasi affiliate, dan informasi resmi dari ' . $bioName . ' di buyle.id.');

        // OG image: bio avatar > user avatar > default
        $ogImage = asset('images/buyle-og.png');
        if (!empty($config['avatar'])) {
            $ogImage = asset('storage/' . $config['avatar']);
        } elseif (!empty($config['_user_avatar'])) {
            $userAv = $config['_user_avatar'];
            $ogImage = \Illuminate\Support\Str::startsWith($userAv, ['http://', 'https://']) ? $userAv : asset('storage/' . $userAv);
        }

        if (!empty($profile->custom_domain)) {
            $canonical  = 'https://' . rtrim($profile->custom_domain, '/');
            $seoTitle   = $bioName . ' - ' . $roleTitle;
            $ogSiteName = $bioName;
        } else {
            $canonical  = url('/' . $username);
            $seoTitle   = $bioName . ' - ' . $roleTitle . ' | buyle.id';
            $ogSiteName = 'buyle.id';
        }

        return view("bio.{$theme}", compact(
            'profile', 'config', 'blocks', 'products',
            'seoTitle', 'seoDesc', 'ogImage', 'canonical', 'username', 'ogSiteName'
        ));
    }
}

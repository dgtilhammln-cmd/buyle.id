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

        // Resolve Buyle products linked in blocks
        $productIds = $blocks->where('type', 'buyle_product')
            ->pluck('data_json')->flatten()->filter(fn($v) => is_array($v) && isset($v['product_id']))
            ->map(fn($v) => $v['product_id'])->unique()->toArray();

        // Simpler: get product_id from data_json
        $productIds = [];
        foreach ($blocks->where('type', 'buyle_product') as $b) {
            if (!empty($b->data_json['product_id'])) $productIds[] = $b->data_json['product_id'];
        }
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $theme = $profile->bio_theme ?? 'theme1';

        // SEO meta
        $roleTitleMap = [
            'content_creator' => 'Content Creator',
            'affiliator'      => 'Affiliator',
            'business'        => 'Business',
        ];
        $roleTitle = $roleTitleMap[$profile->bio_role ?? ''] ?? 'Creator';
        $bioName   = $config['name'] ?? $profile->store_name ?? $username;
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

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
            $q->where('is_active', true)->orderBy('order');
        }])->where('store_slug', $username)->firstOrFail();

        // Auto-redirect to /c/{slug} if no bio setup yet
        if (!$profile->bio_role) {
            return redirect()->route('store.show', $profile->store_slug);
        }

        $config = $profile->bio_config ?? [];
        $blocks = $profile->bioBlocks;

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
        $seoTitle = ($config['name'] ?? $profile->store_name ?? $username) . ' | Link in Bio · buyle.id';
        $seoDesc  = $config['bio'] ?? 'Temukan semua link, produk, dan konten dari ' . ($config['name'] ?? $username) . ' di sini.';
        $ogImage  = !empty($config['avatar']) ? asset('storage/' . $config['avatar']) : asset('images/buyle-og.png');
        $canonical = url('/' . $username);

        return view("bio.{$theme}", compact(
            'profile', 'config', 'blocks', 'products',
            'seoTitle', 'seoDesc', 'ogImage', 'canonical', 'username'
        ));
    }
}

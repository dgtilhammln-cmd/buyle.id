<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CreatorBioBlock;
use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CreatorBioController extends Controller
{
    private function getProfile(): CreatorProfile
    {
        return CreatorProfile::firstOrCreate(
            ['user_id' => auth()->id()],
            ['store_name' => auth()->user()->name, 'store_slug' => '']
        );
    }

    /**
     * Main dashboard - shows role picker if not set, else tabs.
     */
    public function index()
    {
        $profile = $this->getProfile();

        if (!$profile->bio_role) {
            return view('creator.bio.role_picker', compact('profile'));
        }

        $blocks = $profile->bioBlocks()->get();
        $myProducts = Product::where('seller_id', auth()->id())->where('is_active', true)->orderBy('name')->get(['id', 'name', 'price', 'image', 'slug']);

        return view('creator.bio.index', compact('profile', 'blocks', 'myProducts'));
    }

    /**
     * Set role and redirect to main dashboard.
     */
    public function setRole(Request $request)
    {
        $request->validate(['bio_role' => 'required|in:content_creator,affiliator,business']);
        $profile = $this->getProfile();
        $profile->update([
            'bio_role'  => $request->bio_role,
            'bio_theme' => $profile->bio_theme ?? 'theme1',
        ]);

        return redirect()->route('creator.bio.index')->with('success', 'Selamat! Profil Link in Bio Anda siap dibuat. 🎉');
    }

    /**
     * Save theme selection.
     */
    public function saveTheme(Request $request)
    {
        $request->validate(['bio_theme' => 'required|in:theme1,theme2,theme3,theme4']);
        $this->getProfile()->update(['bio_theme' => $request->bio_theme]);
        return back()->with('success', 'Tema berhasil diperbarui!');
    }

    /**
     * Save profile settings (Tab 2).
     */
    public function saveProfile(Request $request)
    {
        $request->validate([
            'bio_name'      => 'nullable|string|max:100',
            'bio_username'  => 'nullable|string|max:50|alpha_dash',
            'bio_bio'       => 'nullable|string|max:300',
            'bio_location'  => 'nullable|string|max:100',
            'bio_avatar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio_cover'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio_wa'        => 'nullable|string|max:30',
            'bio_ig'        => 'nullable|string|max:80',
            'bio_tiktok'    => 'nullable|string|max:80',
            'bio_youtube'   => 'nullable|url|max:200',
            'color_bg'      => 'nullable|string|max:20',
            'color_text'    => 'nullable|string|max:20',
            'color_btn'     => 'nullable|string|max:20',
            'color_btn_text'=> 'nullable|string|max:20',
            'color_accent'  => 'nullable|string|max:20',
            'color_card'    => 'nullable|string|max:20',
            'hero_size'     => 'nullable|integer',
            'bio_hero'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'embed_location'=> 'nullable|string|max:1000',
        ]);

        $profile = $this->getProfile();
        $config  = $profile->bio_config ?? [];

        // Handle avatar upload
        if ($request->hasFile('bio_avatar')) {
            if (!empty($config['avatar'])) Storage::disk('public')->delete($config['avatar']);
            $config['avatar'] = $request->file('bio_avatar')->store('bio/avatars', 'public');
        }
        // Handle cover upload
        if ($request->hasFile('bio_cover')) {
            if (!empty($config['cover'])) Storage::disk('public')->delete($config['cover']);
            $config['cover'] = $request->file('bio_cover')->store('bio/covers', 'public');
        }

        // Handle hero upload
        if ($request->hasFile('bio_hero')) {
            if (!empty($config['hero'])) Storage::disk('public')->delete($config['hero']);
            $config['hero'] = $request->file('bio_hero')->store('bio/heroes', 'public');
        }

        $config['name']     = $request->bio_name     ?? $config['name'] ?? '';
        $config['bio']      = $request->bio_bio       ?? $config['bio'] ?? '';
        $config['location'] = $request->bio_location  ?? $config['location'] ?? '';
        $config['wa']       = $request->bio_wa        ?? $config['wa'] ?? '';
        $config['ig']       = $request->bio_ig        ?? $config['ig'] ?? '';
        $config['tiktok']   = $request->bio_tiktok    ?? $config['tiktok'] ?? '';
        $config['youtube']  = $request->bio_youtube   ?? $config['youtube'] ?? '';

        if ($request->filled('color_bg')) $config['color_bg'] = $request->color_bg;
        if ($request->filled('color_text')) $config['color_text'] = $request->color_text;
        if ($request->filled('color_btn')) $config['color_btn'] = $request->color_btn;
        if ($request->filled('color_btn_text')) $config['color_btn_text'] = $request->color_btn_text;
        if ($request->filled('color_accent')) $config['color_accent'] = $request->color_accent;
        if ($request->filled('color_card')) $config['color_card'] = $request->color_card;
        if ($request->filled('hero_size')) $config['hero_size'] = $request->hero_size;
        
        if ($request->has('embed_location')) {
            // allow empty to clear
            $config['embed_location'] = $request->embed_location;
        }

        // Handle username (store_slug used as bio URL slug)
        if ($request->filled('bio_username')) {
            $slug = $request->bio_username;
            $exists = CreatorProfile::where('store_slug', $slug)->where('id', '!=', $profile->id)->exists();
            if ($exists) {
                return back()->withErrors(['bio_username' => 'Username sudah dipakai orang lain.'])->withInput();
            }
            $profile->store_slug = $slug;
        }

        $profile->bio_config = $config;
        $profile->save();

        return back()->with('success', 'Profil berhasil disimpan!');
    }

    /**
     * Store a new block (Tab 3 & 4).
     */
    public function storeBlock(Request $request)
    {
        $request->validate([
            'type'  => 'required|in:link,pdf,tiktok,affiliate,shopee,buyle_product',
            'title' => 'required|string|max:150',
            'url'   => 'nullable|string|max:2000',
            'block_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'icon_class'  => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
            'product_id'  => 'nullable|exists:products,id',
        ]);

        $profile = $this->getProfile();
        $data    = [];

        // Handle custom image upload
        if ($request->hasFile('block_image')) {
            $data['image'] = $request->file('block_image')->store('bio/blocks', 'public');
        } elseif ($request->filled('scraped_image')) {
            $data['image'] = $request->scraped_image;
        }

        // If shopee: try to scrape OG image if still empty
        if (in_array($request->type, ['shopee', 'affiliate']) && $request->filled('url') && empty($data['image'])) {
            $scraped = $this->scrapeOgImage($request->url);
            if ($scraped) $data['image'] = $scraped;
        }

        if ($request->filled('description')) $data['description'] = $request->description;
        if ($request->filled('product_id'))  $data['product_id']  = $request->product_id;
        if ($request->filled('icon_class'))  $data['icon_class']  = $request->icon_class;

        $lastOrder = $profile->bioBlocks()->max('order') ?? 0;

        CreatorBioBlock::create([
            'creator_id' => $profile->id,
            'type'       => $request->type,
            'title'      => $request->title,
            'url'        => $request->url,
            'data_json'  => $data ?: null,
            'order'      => $lastOrder + 1,
            'is_active'  => true,
        ]);

        return back()->with('success', 'Block berhasil ditambahkan!');
    }

    /**
     * Delete a block.
     */
    public function destroyBlock(CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);

        // Delete image if stored locally
        if (!empty($block->data_json['image']) && !Str::startsWith($block->data_json['image'], 'http')) {
            Storage::disk('public')->delete($block->data_json['image']);
        }
        $block->delete();
        return back()->with('success', 'Block dihapus.');
    }

    /**
     * Update a block.
     */
    public function updateBlock(Request $request, CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);

        $request->validate([
            'title' => 'required|string|max:150',
            'url'   => 'nullable|string|max:2000',
            'block_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'scraped_image' => 'nullable|string',
            'icon_class'  => 'nullable|string|max:100',
        ]);

        $data = $block->data_json ?? [];

        if ($request->hasFile('block_image')) {
            if (!empty($data['image']) && !Str::startsWith($data['image'], 'http')) {
                Storage::disk('public')->delete($data['image']);
            }
            $data['image'] = $request->file('block_image')->store('bio/blocks', 'public');
        } elseif ($request->filled('scraped_image')) {
            $data['image'] = $request->scraped_image;
        }

        if ($request->filled('icon_class')) {
            $data['icon_class'] = $request->icon_class;
        } else {
            unset($data['icon_class']); // Remove if cleared
        }

        $block->update([
            'title'     => $request->title,
            'url'       => $request->url,
            'data_json' => $data,
        ]);

        return back()->with('success', 'Block berhasil diupdate!');
    }

    /**
     * Toggle block active status.
     */
    public function toggleBlock(CreatorBioBlock $block)
    {
        $profile = $this->getProfile();
        if ($block->creator_id !== $profile->id) abort(403);
        $block->update(['is_active' => !$block->is_active]);
        return back();
    }

    /**
     * Reorder blocks via AJAX (JSON drag-drop).
     */
    public function reorderBlocks(Request $request)
    {
        $profile = $this->getProfile();
        $ids = $request->input('ids', []);
        foreach ($ids as $i => $id) {
            CreatorBioBlock::where('id', $id)->where('creator_id', $profile->id)->update(['order' => $i + 1]);
        }
        return response()->json(['ok' => true]);
    }

    /**
     * Scrape OG image using multiple strategies.
     * Strategy 1: Direct HTML fetch (works for Tokopedia, most sites)
     * Strategy 2: Microlink.io API (works for Shopee JS-rendered pages)
     */
    private function scrapeOgImage(string $url): ?string
    {
        // Strategy 1: Direct HTML scrape
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9,en-US;q=0.8',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Cache-Control'   => 'no-cache',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();
                // og:image
                if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $m) ||
                    preg_match('/<meta[^>]+content=["\'](.*?)["\'\s][^>]+property=["\']og:image["\']/i', $html, $m)) {
                    if (!empty($m[1]) && filter_var($m[1], FILTER_VALIDATE_URL)) return $m[1];
                }
                // twitter:image
                if (preg_match('/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $m)) {
                    if (!empty($m[1]) && filter_var($m[1], FILTER_VALIDATE_URL)) return $m[1];
                }
            }
        } catch (\Throwable $e) {}

        // Strategy 2: Microlink.io (handles JS-rendered pages like Shopee)
        try {
            $mlResponse = Http::timeout(15)
                ->get('https://api.microlink.io', [
                    'url'        => $url,
                    'meta'       => 'true',
                    'screenshot' => 'false',
                ]);
            if ($mlResponse->successful()) {
                $data = $mlResponse->json();
                $img = $data['data']['image']['url']
                    ?? $data['data']['logo']['url']
                    ?? null;
                if ($img && filter_var($img, FILTER_VALIDATE_URL)) return $img;
            }
        } catch (\Throwable $e) {}

        return null;
    }

    /**
     * AJAX endpoint: scrape Shopee/any URL for OG image + title.
     */
    public function scrapeUrl(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $url   = $request->url;

        $image = null;
        $title = null;

        // Strategy 1: Direct HTML
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Mobile Safari/537.36',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'id-ID,id;q=0.9',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();

                // og:image
                if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\'](.*?)["\']/i', $html, $m) ||
                    preg_match('/<meta[^>]+content=["\'](.*?)["\'\s][^>]+property=["\']og:image["\']/i', $html, $m)) {
                    if (!empty($m[1]) && filter_var($m[1], FILTER_VALIDATE_URL)) $image = $m[1];
                }
                // og:title
                if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\'](.*?)["\']/i', $html, $m)) {
                    $title = html_entity_decode($m[1], ENT_QUOTES);
                } elseif (preg_match('/<title>(.*?)<\/title>/is', $html, $m)) {
                    $title = html_entity_decode(strip_tags($m[1]), ENT_QUOTES);
                }
            }
        } catch (\Throwable $e) {}

        // Strategy 2: Microlink.io fallback (especially for Shopee)
        if (!$image || !$title) {
            try {
                $mlResponse = Http::timeout(15)
                    ->get('https://api.microlink.io', [
                        'url'        => $url,
                        'meta'       => 'true',
                        'screenshot' => 'false',
                    ]);
                if ($mlResponse->successful()) {
                    $data = $mlResponse->json();
                    if (!$image) {
                        $img = $data['data']['image']['url']
                            ?? $data['data']['logo']['url']
                            ?? null;
                        if ($img && filter_var($img, FILTER_VALIDATE_URL)) $image = $img;
                    }
                    if (!$title) {
                        $title = $data['data']['title'] ?? $data['data']['description'] ?? null;
                    }
                }
            } catch (\Throwable $e) {}
        }

        return response()->json(['image' => $image, 'title' => $title]);
    }
}

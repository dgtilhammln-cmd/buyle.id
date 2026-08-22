<?php

namespace App\Http\Controllers;

use App\Models\CreatorProfile;
use App\Models\Product;
use Illuminate\Http\Request;

class CreatorStoreController extends Controller
{
    public function show(Request $request, $slug)
    {
        // 1. Cari profil creator berdasarkan slug
        $profile = CreatorProfile::where('store_slug', $slug)
            ->with(['user'])
            ->firstOrFail();

        $seller = $profile->user;
        if (!$seller || $seller->role !== 'seller') {
            abort(404);
        }

        // 2. Ambil grup produk yang aktif untuk filter kategori
        $groups = \App\Models\CreatorProductGroup::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // 3. Ambil produk berdasarkan grup (atau semua)
        $query = Product::where('seller_id', $seller->id)
            ->where('is_active', true);

        if ($request->filled('group')) {
            $group = $groups->firstWhere('slug', $request->group);
            if ($group) $query->where('creator_group_id', $group->id);
        }

        // 4. Smart Search
        $suggestion       = null; // keyword saran "Mungkin Maksud Anda"
        $suggestionApplied = false; // apakah hasil sudah pakai fuzzy fallback

        if ($request->filled('q')) {
            $rawQ = trim($request->q);

            // --- PASS 1: Exact LIKE (toleran partial) ---
            $exactQuery = (clone $query)->where(function ($q) use ($rawQ) {
                $q->where('name',       'like', '%' . $rawQ . '%')
                  ->orWhere('short_desc', 'like', '%' . $rawQ . '%')
                  ->orWhere('description', 'like', '%' . $rawQ . '%');
            });

            $exactCount = (clone $exactQuery)->count();

            if ($exactCount > 0) {
                // Normal hit — gunakan hasil exact
                $query = $exactQuery;
            } else {
                // --- PASS 2: Fuzzy fallback ---
                // Pecah query jadi token, coba soundex/similar_text
                $allNames = Product::where('seller_id', $seller->id)
                    ->where('is_active', true)
                    ->pluck('name');

                [$bestKeyword, $bestScore] = $this->findBestMatch($rawQ, $allNames->toArray());

                if ($bestScore >= 40 && $bestKeyword) {
                    // Simpan saran untuk ditampilkan di view
                    $suggestion = $bestKeyword;

                    // Buat query fuzzy: pecah kata-kata dari bestKeyword lalu OR-kan
                    $tokens = $this->tokenize($bestKeyword);
                    $query->where(function ($q) use ($tokens, $rawQ) {
                        // Coba tiap token dari suggestion
                        foreach ($tokens as $t) {
                            $q->orWhere('name',        'like', '%' . $t . '%')
                              ->orWhere('short_desc',  'like', '%' . $t . '%');
                        }
                        // Juga coba token dari query asli (sudah stripped)
                        foreach ($this->tokenize($rawQ) as $t) {
                            $q->orWhere('name',        'like', '%' . $t . '%')
                              ->orWhere('short_desc',  'like', '%' . $t . '%');
                        }
                    });
                    $suggestionApplied = true;
                } else {
                    // Tidak ada kemiripan sama sekali — kosongkan hasil
                    $query->whereRaw('0 = 1');
                }
            }
        }

        // 5. Sort
        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'terlaris') {
            $query->orderByDesc('sold_count');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $seo = [
            'title'       => $profile->meta_title ?: ($profile->store_name . ' | buyle.id'),
            'description' => $profile->meta_desc ?: $profile->store_description,
            'keywords'    => $profile->meta_keywords,
            'og_image'    => $seller->avatar ? asset('storage/' . $seller->avatar) : asset('images/og-default.jpg'),
            'canonical'   => route('store.show', ['slug' => $slug]),
        ];

        return view('storefront.show', compact(
            'profile', 'seller', 'groups', 'products', 'seo', 'sort',
            'suggestion', 'suggestionApplied'
        ));
    }

    /**
     * Temukan kata yang paling mirip dari daftar nama produk
     * Mengembalikan [nama_terbaik, skor_kemiripan (0-100)]
     */
    private function findBestMatch(string $query, array $names): array
    {
        $queryLower  = mb_strtolower($query);
        $bestName    = null;
        $bestScore   = 0;

        foreach ($names as $name) {
            $nameLower = mb_strtolower($name);

            // Metode 1: similar_text (kecocokan karakter)
            similar_text($queryLower, $nameLower, $pct1);

            // Metode 2: token overlap (kata per kata)
            $pct2 = $this->tokenOverlapScore($queryLower, $nameLower);

            // Metode 3: soundex untuk tiap token
            $pct3 = $this->soundexScore($queryLower, $nameLower);

            $score = max($pct1, $pct2 * 100, $pct3 * 100);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestName  = $name;
            }
        }

        return [$bestName, $bestScore];
    }

    /**
     * Hitung skor overlap token (0.0 - 1.0)
     */
    private function tokenOverlapScore(string $a, string $b): float
    {
        $tokA = $this->tokenize($a);
        $tokB = $this->tokenize($b);

        if (empty($tokA) || empty($tokB)) return 0.0;

        $matched = 0;
        foreach ($tokA as $ta) {
            foreach ($tokB as $tb) {
                similar_text($ta, $tb, $pct);
                if ($pct >= 65) { // toleransi typo per token
                    $matched++;
                    break;
                }
            }
        }

        return $matched / max(count($tokA), count($tokB));
    }

    /**
     * Soundex matching per token (0.0 - 1.0)
     */
    private function soundexScore(string $a, string $b): float
    {
        $tokA = $this->tokenize($a);
        $tokB = $this->tokenize($b);

        if (empty($tokA) || empty($tokB)) return 0.0;

        $soundexB = array_map('soundex', $tokB);
        $matched   = 0;

        foreach ($tokA as $ta) {
            if (in_array(soundex($ta), $soundexB, true)) {
                $matched++;
            }
        }

        return $matched / max(count($tokA), count($tokB));
    }

    /**
     * Pecah string menjadi token kata (min 2 karakter)
     */
    private function tokenize(string $str): array
    {
        $str    = mb_strtolower(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $str));
        $tokens = preg_split('/\s+/', trim($str), -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_filter($tokens, fn($t) => mb_strlen($t) >= 2));
    }
}

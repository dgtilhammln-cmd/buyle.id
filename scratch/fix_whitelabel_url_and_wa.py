import glob, re

# 1. Update BioProductController.php to match product slug or block id or data_json->slug
bio_ctrl_path = 'app/Http/Controllers/BioProductController.php'
with open(bio_ctrl_path, 'r', encoding='utf-8') as f:
    code = f.read()

old_query = """        // Search by block ID or slug inside data_json
        $block = CreatorBioBlock::where('creator_id', $profile->id)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                if (is_numeric($identifier)) {
                    $query->where('id', $identifier);
                } else {
                    $query->where('data_json->slug', $identifier)
                          ->orWhere('id', $identifier);
                }
            })
            ->firstOrFail();"""

new_query = """        // Search by block ID, slug in data_json, or matching product slug
        $block = CreatorBioBlock::where('creator_id', $profile->id)
            ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
            ->where('is_active', true)
            ->where(function ($query) use ($identifier) {
                if (is_numeric($identifier)) {
                    $query->where('id', $identifier);
                } else {
                    $query->where('data_json->slug', $identifier)
                          ->orWhere('id', $identifier);
                }
            })
            ->first();

        if (!$block) {
            // Fallback: search by product slug or title slug
            $blocks = CreatorBioBlock::where('creator_id', $profile->id)
                ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
                ->where('is_active', true)
                ->get();

            foreach ($blocks as $b) {
                $bSlug = $b->data_json['slug'] ?? null;
                if (!$bSlug && !empty($b->data_json['product_id'])) {
                    $p = Product::find($b->data_json['product_id']);
                    if ($p && $p->slug === $identifier) {
                        $block = $b;
                        break;
                    }
                }
                if (!$bSlug) {
                    $bSlug = \Illuminate\Support\Str::slug($b->title);
                }
                if ($bSlug === $identifier) {
                    $block = $b;
                    break;
                }
            }
        }

        if (!$block) {
            abort(404);
        }"""

if old_query in code:
    code = code.replace(old_query, new_query)
    with open(bio_ctrl_path, 'w', encoding='utf-8') as f:
        f.write(code)
    print("1. Updated BioProductController.php query fallback.")

# 2. Update all 5 bio themes to generate $blockSlug = $block->data_json['slug'] ?? $prod->slug ?? Str::slug($displayTitle)
for theme_path in glob.glob('resources/views/bio/theme*.blade.php'):
    with open(theme_path, 'r', encoding='utf-8') as f:
        t_code = f.read()

    # Replace $prodUrl=route('bio.product.show',[$username,$block->data_json['slug']??$block->id]);
    t_code = t_code.replace(
        "$prodUrl=route('bio.product.show',[$username,$block->data_json['slug']??$block->id]);",
        "$blockSlug=!empty($block->data_json['slug'])?$block->data_json['slug']:(!empty($prod->slug)?$prod->slug:\\Illuminate\\Support\\Str::slug($displayTitle?:'produk-'.$block->id)); $prodUrl=route('bio.product.show',[$username,$blockSlug]);"
    )
    with open(theme_path, 'w', encoding='utf-8') as f:
        f.write(t_code)

print("2. Updated theme files to use product slug instead of block ID 31.")

# 3. Update product_show.blade.php so buyle_product defaults paymentMethod to 'web' (Payment Gateway)
ps_path = 'resources/views/bio/product_show.blade.php'
with open(ps_path, 'r', encoding='utf-8') as f:
    ps_code = f.read()

ps_code = ps_code.replace(
    "$paymentMethod = $block->data_json['payment_method'] ?? 'wa';",
    "$paymentMethod = $block->data_json['payment_method'] ?? ($block->type === 'buyle_product' ? 'web' : 'wa');"
)

old_cta = """        @if($paymentMethod === 'wa' && $waNumber)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}"
                target="_blank" class="btn-buy">
                <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Beli via WhatsApp
            </a>
        @elseif($product || !empty($block->data_json['product_id']))"""

new_cta = """        @if($paymentMethod === 'wa' && $block->type !== 'buyle_product' && $waNumber)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waMessage) }}"
                target="_blank" class="btn-buy">
                <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Beli via WhatsApp
            </a>
        @elseif($product || !empty($block->data_json['product_id']))"""

ps_code = ps_code.replace(old_cta, new_cta)

with open(ps_path, 'w', encoding='utf-8') as f:
    f.write(ps_code)

print("3. Updated product_show.blade.php so Whitelabel products use Payment Gateway button.")

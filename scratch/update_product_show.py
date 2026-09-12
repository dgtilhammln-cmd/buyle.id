ps_path = 'resources/views/bio/product_show.blade.php'
with open(ps_path, 'r', encoding='utf-8') as f:
    content = f.read()

old_header = """    @php
        $images = $block->data_json['images'] ?? [];
        $price = $block->data_json['price'] ?? 0;
        $origPrice = $block->data_json['original_price'] ?? null;
        $paymentMethod = $block->data_json['payment_method'] ?? 'wa';
        $waText = $block->data_json['wa_text'] ?? '';
        $waNumber = $config['wa'] ?? '';
        $waMessage = 'Halo, saya mendapatkan nomor dari buyle.id. ' . ($waText ?: 'Saya tertarik dengan produk *' . $block->title . '* (Rp ' . number_format($price, 0, ',', '.') . ' IDR). Apakah masih tersedia?');
        $firstImage = !empty($images[0]) ? asset('storage/' . $images[0]) : asset('images/buyle-og.png');
        $pageTitle = $block->title . ' - ' . ($config['name'] ?? $username) . ' | buyle.id';
        $rawDesc  = $block->data_json['description'] ?? '';
        $pageDesc = !empty($rawDesc) ? Str::limit(strip_tags($rawDesc), 160) : 'Beli ' . $block->title . ' berkualitas dengan harga terbaik dari ' . ($config['name'] ?? $username) . ' di buyle.id.';
    @endphp"""

new_header = """    @php
        $product = $product ?? null;
        $images = $block->data_json['images'] ?? [];
        if (empty($images) && !empty($block->data_json['image'])) {
            $images = [$block->data_json['image']];
        }
        if (empty($images) && $product && $product->image) {
            $images = [$product->image];
        }
        $prodTitle = !empty($block->title) ? $block->title : ($product->name ?? 'Produk');
        $price = $block->data_json['price'] ?? $block->data_json['custom_price'] ?? ($product ? ($product->is_on_sale ? $product->sale_price : $product->effective_price) : 0);
        $origPrice = $block->data_json['original_price'] ?? ($product && $product->is_on_sale ? $product->price : null);
        $paymentMethod = $block->data_json['payment_method'] ?? 'wa';
        $waText = $block->data_json['wa_text'] ?? '';
        $waNumber = $config['wa'] ?? '';
        $waMessage = 'Halo, saya mendapatkan nomor dari buyle.id. ' . ($waText ?: 'Saya tertarik dengan produk *' . $prodTitle . '* (Rp ' . number_format($price, 0, ',', '.') . ' IDR). Apakah masih tersedia?');
        $firstImage = !empty($images[0]) ? (Str::startsWith($images[0], 'http') ? $images[0] : asset('storage/' . $images[0])) : asset('images/buyle-og.png');
        $pageTitle = $prodTitle . ' - ' . ($config['name'] ?? $username) . ' | buyle.id';
        $rawDesc  = $block->data_json['description'] ?? ($product ? $product->description : '');
        $pageDesc = !empty($rawDesc) ? Str::limit(strip_tags($rawDesc), 160) : 'Beli ' . $prodTitle . ' berkualitas dengan harga terbaik dari ' . ($config['name'] ?? $username) . ' di buyle.id.';
    @endphp"""

content = content.replace(old_header, new_header)

old_title_tag = '<h1 class="prod-name">{{ $block->title }}</h1>'
new_title_tag = '<h1 class="prod-name">{{ $prodTitle }}</h1>'
content = content.replace(old_title_tag, new_title_tag)

old_desc = '@if(!empty($block->data_json[\'description\']))\n                <div class="prod-desc">{{ $block->data_json[\'description\'] }}</div>\n            @endif'
new_desc = '@if(!empty($rawDesc))\n                <div class="prod-desc">{!! nl2br(e($rawDesc)) !!}</div>\n            @endif'
content = content.replace(old_desc, new_desc)

with open(ps_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated product_show.blade.php")

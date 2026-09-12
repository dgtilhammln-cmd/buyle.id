import glob

for t_path in glob.glob('resources/views/bio/theme*.blade.php'):
    with open(t_path, 'r', encoding='utf-8') as f:
        code = f.read()

    # For buyleBlocks
    code = code.replace(
        "$blockSlug=!empty($block->data_json['slug'])?$block->data_json['slug']:(!empty($prod->slug)?$prod->slug:\\Illuminate\\Support\\Str::slug($displayTitle?:'produk-'.$block->id)); $prodUrl=route('bio.product.show',[$username,$blockSlug]); $displayTitle=!empty($block->title)?$block->title:($prod->name??'');",
        "$displayTitle=!empty($block->title)?$block->title:($prod->name??''); $blockSlug=!empty($block->data_json['slug'])?$block->data_json['slug']:(!empty($prod->slug)?$prod->slug:\\Illuminate\\Support\\Str::slug($displayTitle?:'produk-'.$block->id)); $prodUrl=route('bio.product.show',[$username,$blockSlug]);"
    )

    # For customProdBlocks
    code = code.replace(
        "$blockSlug=!empty($block->data_json['slug'])?$block->data_json['slug']:(!empty($prod->slug)?$prod->slug:\\Illuminate\\Support\\Str::slug($displayTitle?:'produk-'.$block->id)); $prodUrl=route('bio.product.show',[$username,$blockSlug]);",
        "$displayTitle=$block->title??''; $blockSlug=!empty($block->data_json['slug'])?$block->data_json['slug']:\\Illuminate\\Support\\Str::slug($displayTitle?:'produk-'.$block->id); $prodUrl=route('bio.product.show',[$username,$blockSlug]);"
    )

    with open(t_path, 'w', encoding='utf-8') as f:
        f.write(code)

print("Fixed displayTitle order in themes 1-5.")

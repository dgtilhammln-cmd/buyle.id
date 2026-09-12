import glob, re

for theme_path in glob.glob('resources/views/bio/theme*.blade.php'):
    with open(theme_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Replace <a href="{{ $block->url }}" target="_blank" with <a href="{{ route('bio.product.show', [$username, $block->data_json['slug'] ?? $block->id]) }}" inside buyleBlocks loops
    # Let's handle buyleBlocks loops in theme1-5
    
    # 1. Update php vars in buyleBlocks foreach
    # Pattern for buyleBlocks php logic
    content = content.replace(
        "@php $prod=$products[$block->data_json['product_id']??0]??null; @endphp",
        "@php $prod=$products[$block->data_json['product_id']??0]??null; $prodUrl=route('bio.product.show',[$username,$block->data_json['slug']??$block->id]); $displayTitle=!empty($block->title)?$block->title:($prod->name??''); $price=$block->data_json['price']??$block->data_json['custom_price']??($prod?($prod->is_on_sale?$prod->sale_price:$prod->effective_price):0); $origPrice=$block->data_json['original_price']??($prod&&$prod->is_on_sale?$prod->price:null); @endphp"
    )
    content = content.replace(
        "@php $allNum++; $prod=$products[$block->data_json['product_id']??0]??null; @endphp",
        "@php $allNum++; $prod=$products[$block->data_json['product_id']??0]??null; $prodUrl=route('bio.product.show',[$username,$block->data_json['slug']??$block->id]); $displayTitle=!empty($block->title)?$block->title:($prod->name??''); $price=$block->data_json['price']??$block->data_json['custom_price']??($prod?($prod->is_on_sale?$prod->sale_price:$prod->effective_price):0); $origPrice=$block->data_json['original_price']??($prod&&$prod->is_on_sale?$prod->price:null); @endphp"
    )
    content = content.replace(
        "@php $num5++; $prod=$products[$block->data_json['product_id']??0]??null; @endphp",
        "@php $num5++; $prod=$products[$block->data_json['product_id']??0]??null; $prodUrl=route('bio.product.show',[$username,$block->data_json['slug']??$block->id]); $displayTitle=!empty($block->title)?$block->title:($prod->name??''); $price=$block->data_json['price']??$block->data_json['custom_price']??($prod?($prod->is_on_sale?$prod->sale_price:$prod->effective_price):0); $origPrice=$block->data_json['original_price']??($prod&&$prod->is_on_sale?$prod->price:null); @endphp"
    )
    
    # 2. Replace href="{{ $block->url }}" target="_blank" in buyle_product blocks with href="{{ $prodUrl }}"
    # Replace href="{{ $block->url }}" target="_blank" with href="{{ $prodUrl }}"
    content = content.replace(
        '<a href="{{ $block->url }}" target="_blank" class="prod-card search-item bio-track-link" data-title="{{ $prod->name }}"',
        '<a href="{{ $prodUrl }}" class="prod-card search-item bio-track-link" data-title="{{ $displayTitle }}"'
    )
    content = content.replace(
        '<a href="{{ $block->url }}" target="_blank" class="landing-prod-card search-item bio-track-link" data-title="{{ $prod->name }}"',
        '<a href="{{ $prodUrl }}" class="landing-prod-card search-item bio-track-link" data-title="{{ $displayTitle }}"'
    )
    
    # 3. Use $displayTitle & $price & $origPrice in titles/prices inside buyleBlocks
    content = content.replace(
        '<h3 class="prod-title">{{ $prod->name }}</h3>',
        '<h3 class="prod-title">{{ $displayTitle }}</h3>'
    )
    content = content.replace(
        '<h3 class="landing-prod-title">{{ $prod->name }}</h3>',
        '<h3 class="landing-prod-title">{{ $displayTitle }}</h3>'
    )
    
    # Replace price rendering in buyleBlocks
    content = content.replace(
        '<div class="prod-price">@if($prod->is_on_sale)<span style="text-decoration:line-through;opacity:.55;font-size:.72rem;">Rp {{ number_format($prod->price,0,\',\',\'.\') }}</span> Rp {{ number_format($prod->sale_price,0,\',\',\'.\') }}@else Rp {{ number_format($prod->effective_price,0,\',\',\'.\') }}@endif</div>',
        '<div class="prod-price">@if(!empty($origPrice)&&$origPrice>$price)<span style="text-decoration:line-through;opacity:.55;font-size:.72rem;">Rp {{ number_format($origPrice,0,\',\',\'.\') }}</span> @endif Rp {{ number_format($price,0,\',\',\'.\') }}</div>'
    )
    content = content.replace(
        '<span class="landing-prod-price">@if($prod->is_on_sale)<s style="opacity:.55;font-size:.72rem;">Rp {{ number_format($prod->price,0,\',\',\'.\') }}</s> Rp {{ number_format($prod->sale_price,0,\',\',\'.\') }}@else Rp {{ number_format($prod->effective_price,0,\',\',\'.\') }}@endif</span>',
        '<span class="landing-prod-price">@if(!empty($origPrice)&&$origPrice>$price)<s style="opacity:.55;font-size:.72rem;">Rp {{ number_format($origPrice,0,\',\',\'.\') }}</s> @endif Rp {{ number_format($price,0,\',\',\'.\') }}</span>'
    )

    with open(theme_path, 'w', encoding='utf-8') as f:
        f.write(content)

print("Successfully updated theme files for Whitelabel product routing.")

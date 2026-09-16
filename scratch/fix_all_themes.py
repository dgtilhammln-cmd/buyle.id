import re
import os

theme_files = [
    r"c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\resources\views\bio\theme1.blade.php",
    r"c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\resources\views\bio\theme2.blade.php",
    r"c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\resources\views\bio\theme3.blade.php",
    r"c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\resources\views\bio\theme4.blade.php",
    r"c:\Users\dgtil\Downloads\PENTING\HVM Digital\buyle.id ex alaatrumah\resources\views\bio\theme5.blade.php",
]

old_is_physical = """$isBlockPhysical = function($b) use ($products) {
                if ($b->type === 'custom_product') return true;
                $cat = strtolower(trim($b->data_json['category'] ?? ''));
                if (in_array($cat, ['makanan', 'barang', 'jasa', 'lainnya', 'kuliner', 'fisik', 'umkm'])) return true;
                $pid = $b->data_json['product_id'] ?? null;
                if ($pid && isset($products[$pid])) {
                    $pType = strtolower($products[$pid]->product_type ?? $products[$pid]->type ?? '');
                    if (in_array($pType, ['physical', 'makanan', 'service', 'product', 'umkm', 'barang', 'jasa', 'food'])) return true;
                }
                $title = strtolower($b->title ?? '');
                if (preg_match('/(es|nasi|teh|kopi|jus|sirup|air|soto|bakso|mie|ayam|bebek|daging|ikan|kerupuk|lumpia|kasur|samsung|promo|sepatu|baju|celana)/i', $title)) {
                    return true;
                }
                return false;
            };"""

new_is_physical = """$isBlockPhysical = function($b) use ($products) {
                if ($b->type === 'buyle_product') return false;
                if ($b->type === 'custom_product') {
                    $cat = strtolower(trim($b->data_json['category'] ?? ''));
                    if (in_array($cat, ['digital', 'ebook', 'link', 'tiket', 'event', 'external_link'])) return false;
                    return true;
                }
                $pid = $b->data_json['product_id'] ?? null;
                if ($pid && isset($products[$pid])) {
                    $pType = strtolower($products[$pid]->product_type ?? $products[$pid]->type ?? '');
                    if (in_array($pType, ['external_link', 'digital', 'ticket'])) return false;
                    if (in_array($pType, ['physical', 'makanan', 'service', 'product', 'umkm', 'barang', 'jasa', 'food'])) return true;
                }
                $cat = strtolower(trim($b->data_json['category'] ?? ''));
                if (in_array($cat, ['makanan', 'barang', 'jasa', 'lainnya', 'kuliner', 'fisik', 'umkm'])) return true;
                return false;
            };"""

old_custom_php = "$price=$block->data_json['price']??0;"
new_custom_php_replacement = "$prod=$products[$block->data_json['product_id']??0]??null; $displayTitle=!empty($block->title)?$block->title:($prod->name??''); $price=$block->data_json['price']??$block->data_json['custom_price']??($prod?($prod->is_on_sale?$prod->sale_price:$prod->effective_price):0); $origPrice=$block->data_json['original_price']??($prod&&$prod->is_on_sale?$prod->price:null);"

for fpath in theme_files:
    if not os.path.exists(fpath):
        print("Not found:", fpath)
        continue
    with open(fpath, "r", encoding="utf-8") as f:
        content = f.read()

    # Replace $isBlockPhysical
    if old_is_physical in content:
        content = content.replace(old_is_physical, new_is_physical)
        print(f"Replaced $isBlockPhysical in {os.path.basename(fpath)}")
    else:
        print(f"$isBlockPhysical pattern not exact in {os.path.basename(fpath)}")

    # Replace image & price extraction for custom product blocks so it falls back to $prod
    old_php_pattern = r"\$imgs=\$block->data_json\['images'\]\?\?\[\];\s*\$rawPath=\!empty\(\$imgs\[0\]\)\?\$imgs\[0\]:\(\$block->data_json\['image'\]\?\?null\);"
    new_php_pattern = r"$pid=$block->data_json['product_id']??0; $prod=$products[$pid]??null; $imgs=$block->data_json['images']??[]; $rawPath=!empty($imgs[0])?$imgs[0]:($block->data_json['image']??($prod->image??null));"
    
    if re.search(old_php_pattern, content):
        content = re.sub(old_php_pattern, new_php_pattern, content)
        print(f"Replaced $rawPath fallback in {os.path.basename(fpath)}")

    old_price_pattern = r"\$price=\$block->data_json\['price'\]\?\?0;\s*\$origPrice=\$block->data_json\['original_price'\]\?\?null;"
    new_price_pattern = r"$price=$block->data_json['price']??$block->data_json['custom_price']??($prod?($prod->is_on_sale?$prod->sale_price:$prod->effective_price):0); $origPrice=$block->data_json['original_price']??($prod&&$prod->is_on_sale?$prod->price:null);"

    if re.search(old_price_pattern, content):
        content = re.sub(old_price_pattern, new_price_pattern, content)
        print(f"Replaced $price fallback in {os.path.basename(fpath)}")

    with open(fpath, "w", encoding="utf-8") as f:
        f.write(content)

print("Done python script processing.")

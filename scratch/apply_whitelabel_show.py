import glob, re

# 1. Update BioProductController.php
bio_controller_path = 'app/Http/Controllers/BioProductController.php'
with open(bio_controller_path, 'r', encoding='utf-8') as f:
    code = f.read()

code = code.replace(
    "use App\\Models\\CreatorProfile;",
    "use App\\Models\\CreatorProfile;\nuse App\\Models\\Product;"
)
code = code.replace(
    "->where('type', 'custom_product')",
    "->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])"
)
if "$product = null;" not in code:
    code = code.replace(
        "$config = $profile->bio_config ?? [];",
        "$product = null;\n        if (!empty($block->data_json['product_id'])) {\n            $product = Product::find($block->data_json['product_id']);\n        }\n\n        $config = $profile->bio_config ?? [];"
    )
    code = code.replace(
        "compact('profile', 'block', 'config', 'theme', 'username')",
        "compact('profile', 'block', 'config', 'theme', 'username', 'product')"
    )

with open(bio_controller_path, 'w', encoding='utf-8') as f:
    f.write(code)

print("Updated BioProductController.php")

# 2. Update CreatorBioController.php
creator_bio_path = 'app/Http/Controllers/Creator/CreatorBioController.php'
with open(creator_bio_path, 'r', encoding='utf-8') as f:
    cb_code = f.read()

cb_code = cb_code.replace(
    "if ($request->type === 'custom_product') {",
    "if (in_array($request->type, ['custom_product', 'buyle_product'])) {"
)
cb_code = cb_code.replace(
    "if ($block->type === 'custom_product') {",
    "if (in_array($block->type, ['custom_product', 'buyle_product'])) {"
)

with open(creator_bio_path, 'w', encoding='utf-8') as f:
    f.write(cb_code)

print("Updated CreatorBioController.php")

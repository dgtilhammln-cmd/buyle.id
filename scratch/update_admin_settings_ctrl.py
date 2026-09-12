with open('app/Http/Controllers/Admin/AdminSettingsController.php', 'r', encoding='utf-8') as f:
    code = f.read()

old_keys = "$imageKeys = ['hero_bg_image', 'hero_main_image', 'hero_secondary_image', 'about_image', 'about_c3_image', 'og_image_default', 'logo', 'favicon', 'coverage_map', 'ad_product_sidebar_1_image', 'ad_product_sidebar_2_image'];"
new_keys = "$imageKeys = ['hero_bg_image', 'hero_main_image', 'hero_secondary_image', 'about_image', 'about_c3_image', 'og_image_default', 'logo', 'favicon', 'coverage_map', 'ad_product_sidebar_1_image', 'ad_product_sidebar_2_image', 'adsense_custom_image'];"

if old_keys in code:
    code = code.replace(old_keys, new_keys)
    with open('app/Http/Controllers/Admin/AdminSettingsController.php', 'w', encoding='utf-8') as f:
        f.write(code)
    print("Updated AdminSettingsController.php")

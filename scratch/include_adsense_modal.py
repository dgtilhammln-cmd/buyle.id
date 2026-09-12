import glob

files = glob.glob('resources/views/bio/theme*.blade.php') + ['resources/views/bio/product_show.blade.php']

for f_path in files:
    with open(f_path, 'r', encoding='utf-8') as f:
        code = f.read()

    if "partials.adsense_modal" not in code:
        code = code.replace(
            "@include('partials.report_modal'",
            "@include('partials.adsense_modal')\n    @include('partials.report_modal'"
        )
        with open(f_path, 'w', encoding='utf-8') as f:
            f.write(code)

print("Added adsense_modal partial to all bio views.")

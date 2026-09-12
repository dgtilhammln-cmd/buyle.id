import glob

for path in glob.glob('resources/views/bio/theme*.blade.php'):
    with open(path, 'rb') as f:
        content = f.read()
    
    content = content.replace(
        b"$buyleBlocks      = $blocks->where('type', 'buyle_product');",
        b"$buyleBlocks      = $blocks->where('type', 'buyle_product')->values();"
    )
    content = content.replace(
        b"$buyleBlocks = $blocks->where('type', 'buyle_product');",
        b"$buyleBlocks = $blocks->where('type', 'buyle_product')->values();"
    )
    content = content.replace(
        b"$customProdBlocks = $blocks->where('type', 'custom_product');",
        b"$customProdBlocks = $blocks->where('type', 'custom_product')->values();"
    )
    
    with open(path, 'wb') as f:
        f.write(content)

print("Updated theme files successfully.")

<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CreatorProfile;
use App\Models\CreatorBioBlock;
use App\Models\Product;

$profile = CreatorProfile::where('store_slug', 'hvmdigital')->first();
if (!$profile) { echo "Profile hvmdigital not found\n"; exit(1); }
echo "Profile ID: " . $profile->id . "\n";

$blocks = CreatorBioBlock::where('creator_id', $profile->id)
    ->whereIn('type', ['custom_product', 'buyle_product', 'buyle_affiliate'])
    ->where('is_active', true)
    ->get();

foreach ($blocks as $b) {
    $data  = $b->data_json ?? [];
    $pid   = $data['product_id'] ?? null;
    $prod  = $pid ? Product::find($pid) : null;
    $bslug = $data['slug'] ?? 'none';
    $pslug = $prod ? $prod->slug : 'N/A';
    $ptype = $prod ? $prod->product_type : 'N/A';
    $titleSlug = \Illuminate\Support\Str::slug($b->title);
    echo "---\n";
    echo "Block ID   : {$b->id}\n";
    echo "Title      : {$b->title}\n";
    echo "Title Slug : {$titleSlug}\n";
    echo "data_slug  : {$bslug}\n";
    echo "product_id : " . ($pid ?? 'null') . "\n";
    echo "Prod slug  : {$pslug}\n";
    echo "Prod type  : {$ptype}\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- All Creator Profiles ---\n";
foreach (App\Models\CreatorProfile::all() as $p) {
    echo "Profile ID: {$p->id} | User ID: {$p->user_id} | Store Name: '{$p->store_name}' | Slug: '{$p->store_slug}'\n";
}

echo "\n--- Creator Bio Blocks Breakdown by Type ---\n";
$blocksGrouped = App\Models\CreatorBioBlock::select('type', \DB::raw('count(*) as total'))->groupBy('type')->get();
foreach ($blocksGrouped as $bg) {
    echo "Type: '{$bg->type}' => Total: {$bg->total}\n";
}

echo "\n--- Sample buyle_product Bio Blocks ---\n";
foreach (App\Models\CreatorBioBlock::where('type', 'buyle_product')->take(10)->get() as $b) {
    $cat = $b->data_json['category'] ?? 'N/A';
    $pid = $b->data_json['product_id'] ?? 'N/A';
    echo "ID: {$b->id} | CreatorID: {$b->creator_id} | Title: '{$b->title}' | Cat: '{$cat}' | ProductID: {$pid}\n";
}

echo "\n--- Sample Products product_type in DB ---\n";
foreach (App\Models\Product::select('product_type', \DB::raw('count(*) as total'))->groupBy('product_type')->get() as $pt) {
    echo "product_type: '{$pt->product_type}' => Total: {$pt->total}\n";
}

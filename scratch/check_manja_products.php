<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\CreatorProfile;
use App\Models\CreatorBioBlock;
use App\Models\Product;

$user = User::where('name', 'like', '%manja%')
    ->orWhere('email', 'like', '%manja%')
    ->orWhereHas('creatorProfile', fn($q) => $q->where('store_name', 'like', '%manja%')->orWhere('store_slug', 'like', '%manja%'))
    ->first();

if (!$user) {
    echo "User Manja not found!\n";
    $profiles = CreatorProfile::get(['id', 'user_id', 'store_name', 'store_slug']);
    echo "Available profiles:\n";
    print_r($profiles->toArray());
    exit;
}

echo "Found User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
$profile = CreatorProfile::where('user_id', $user->id)->first();

if ($profile) {
    echo "Profile ID: {$profile->id}, Store: {$profile->store_name}, Slug: {$profile->store_slug}\n";
    
    $blocks = CreatorBioBlock::where('creator_id', $profile->id)->get();
    echo "Total Bio Blocks: " . $blocks->count() . "\n";
    foreach ($blocks as $b) {
        $cat = $b->data_json['category'] ?? 'NO_CAT';
        $pId = $b->data_json['product_id'] ?? 'NO_PID';
        echo "- Block ID {$b->id} | Type: {$b->type} | Title: {$b->title} | Active: {$b->is_active} | Cat: {$cat} | ProductID: {$pId}\n";
    }
}

$products = Product::where('seller_id', $user->id)->get();
echo "\nTotal Products in DB for seller_id {$user->id}: " . $products->count() . "\n";
foreach ($products as $p) {
    echo "- Product ID {$p->id} | Name: {$p->name} | Price: {$p->price} | Type: {$p->product_type} | Active: {$p->is_active}\n";
}

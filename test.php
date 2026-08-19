<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $seller = App\Models\User::first();
    Auth::login($seller);
    $groups = App\Models\CreatorProductGroup::where('seller_id', $seller->id)->orderBy('order')->get();
    view('creator.groups.index', compact('groups'))->render();
    echo 'SUCCESS';
} catch (\Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
}

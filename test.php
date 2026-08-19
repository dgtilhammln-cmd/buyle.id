<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DB Tables ===\n";
$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
foreach ($tables as $t) echo " - " . $t->name . "\n";

echo "\n=== USERS & ROLES ===\n";
$users = App\Models\User::select('id','name','email','role')->get();
foreach ($users as $u) {
    echo $u->id . ' | ' . $u->name . ' | ' . $u->email . ' | ' . ($u->role ?? 'NULL') . "\n";
}

echo "\n=== TEST creator/dashboard VIEW (requires seller user) ===\n";
try {
    // Make a fake seller
    $seller = $users->first();
    $seller->role = 'seller'; // Don't save, just test
    Auth::login($seller);

    $view = view('creator.dashboard', [
        'seller' => $seller,
        'gmv' => 0,
        'platformFee' => 0,
        'platformFeeRate' => 10,
        'totalPayout' => 0,
        'availableBalance' => 0,
        'totalProducts' => 0,
        'activeProducts' => 0,
        'totalTransactions' => 0,
        'recentSales' => collect(),
        'recentProducts' => collect(),
    ])->render();
    echo "SUCCESS\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\nIn: " . basename($e->getFile()) . ':' . $e->getLine() . "\n";
}

echo "\n=== TEST account/overview VIEW ===\n";
try {
    $user = $users->first();
    Auth::login($user);
    $totalOrders    = $user->orders()->count();
    $activeOrders   = $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count();
    $totalSpent     = $user->orders()->where('status', '!=', 'cancelled')->sum('grand_total');
    $totalAddresses = $user->addresses()->count();
    $recentOrders   = $user->orders()->latest()->limit(5)->get();
    view('account.overview', compact('user', 'totalOrders', 'activeOrders', 'totalSpent', 'totalAddresses', 'recentOrders'))->render();
    echo "SUCCESS\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\nIn: " . basename($e->getFile()) . ':' . $e->getLine() . "\n";
}

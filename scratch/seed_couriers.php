<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

(new Database\Seeders\CourierSeeder())->run();

echo "Active couriers:\n";
foreach (\App\Models\Courier::where('is_active', true)->get() as $c) {
    echo "- {$c->name} ({$c->code})\n";
}

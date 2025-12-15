<?php

use Illuminate\Support\Facades\Artisan;
use App\Models\Order;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$minDate = Order::min('order_date');
if (!$minDate) {
    echo "No orders found.\n";
    exit;
}

$startDate = Carbon::parse($minDate);
$endDate = Carbon::now();

echo "Backfilling ALL analytics from {$startDate->toDateString()} to {$endDate->toDateString()}...\n";

for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
    $dateStr = $date->toDateString();
    echo "Processing {$dateStr}...\n";
    Artisan::call('analytics:update', ['date' => $dateStr]);
}

echo "Full backfill complete.\n";

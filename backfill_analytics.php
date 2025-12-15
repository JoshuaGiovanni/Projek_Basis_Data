<?php

use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$startDate = Carbon::now()->subDays(90);
$endDate = Carbon::now();

echo "Backfilling analytics from {$startDate->toDateString()} to {$endDate->toDateString()}...\n";

for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
    $dateStr = $date->toDateString();
    echo "Processing {$dateStr}...\n";
    Artisan::call('analytics:update', ['date' => $dateStr]);
}

echo "Backfill complete.\n";

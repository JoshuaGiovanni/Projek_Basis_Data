<?php

use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

if (Schema::hasColumn('analytics_summaries', 'record_count')) {
    echo "Column 'record_count' EXISTS in analytics_summaries.\n";
} else {
    echo "Column 'record_count' MISSING in analytics_summaries.\n";
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('summary_date');
            $table->enum('metric_type', ['SERVICE_PROFIT', 'SEGMENTATION', 'ANALYST_PERF']);
            $table->string('group_key'); // Holds dynamic keys like 'Service Name', '17-22:INDIVIDUAL', 'Junior (0-1)'
            $table->decimal('value_main', 15, 2)->default(0); // Primary metric (e.g. Profit)
            $table->decimal('value_secondary', 15, 2)->nullable(); // Secondary (e.g. Rating, Count)
            $table->decimal('value_tertiary', 15, 2)->nullable(); // Tertiary (e.g. Duration)
            
            $table->timestamps();
            
            $table->index(['summary_date', 'metric_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_summaries');
    }
};

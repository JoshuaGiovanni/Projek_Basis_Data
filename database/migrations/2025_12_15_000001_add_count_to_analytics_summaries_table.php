<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analytics_summaries', function (Blueprint $table) {
            $table->integer('record_count')->default(1)->after('value_tertiary');
        });
    }

    public function down(): void
    {
        Schema::table('analytics_summaries', function (Blueprint $table) {
            $table->dropColumn('record_count');
        });
    }
};

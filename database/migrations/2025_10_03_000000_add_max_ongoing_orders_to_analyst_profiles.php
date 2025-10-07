<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analyst_profile', function (Blueprint $table) {
            $table->unsignedInteger('max_ongoing_orders')->default(5)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('analyst_profile', function (Blueprint $table) {
            $table->dropColumn('max_ongoing_orders');
        });
    }
};






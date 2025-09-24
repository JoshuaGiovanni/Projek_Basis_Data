<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliverables', function (Blueprint $table) {
            $table->id('deliverable_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->onDelete('cascade');
            $table->string('file_url', 255);
            $table->timestamp('submitted_at')->useCurrent();
            $table->boolean('approved_by_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliverables');
    }
};






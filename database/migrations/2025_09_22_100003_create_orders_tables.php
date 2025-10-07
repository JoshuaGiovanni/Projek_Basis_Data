<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Orders Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('service_id')->constrained('services', 'service_id')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('client_profile', 'client_id')->onDelete('cascade');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('due_date')->nullable();
            $table->decimal('final_amount', 12, 2);
            $table->enum('status', ['PENDING', 'IN_PROGRESS', 'SUBMITTED', 'COMPLETED', 'CANCELLED'])->default('PENDING');
        });

        // Order Briefs Table
        Schema::create('order_briefs', function (Blueprint $table) {
            $table->id('brief_id');
            $table->foreignId('order_id')->unique()->constrained('orders', 'order_id')->onDelete('cascade');
            $table->longText('project_description')->nullable();
            $table->text('attachments_url')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
        });

        // Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->timestamp('payment_date')->useCurrent();
            $table->string('payment_method', 50)->default('QR_CODE');
            $table->string('proof_image', 255)->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED'])->default('PENDING');
        });

        // Reviews Table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('order_id')->unique()->constrained('orders', 'order_id')->onDelete('cascade');
            $table->unsignedBigInteger('reviewer_id');
            $table->unsignedBigInteger('analyst_id');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('reviewer_id')->references('client_id')->on('client_profile')->onDelete('cascade');
            $table->foreign('analyst_id')->references('analyst_id')->on('analyst_profile')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_briefs');
        Schema::dropIfExists('orders');
    }
};

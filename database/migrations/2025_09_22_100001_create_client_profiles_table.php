<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_profile', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->primary();
            $table->enum('type', ['INDIVIDUAL', 'COMPANY'])->default('INDIVIDUAL');
            $table->string('company_name', 150)->nullable();
            $table->string('industry', 150)->nullable();
            $table->foreign('client_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_profile');
    }
};

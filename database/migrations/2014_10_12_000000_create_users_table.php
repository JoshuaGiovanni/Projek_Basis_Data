<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id'); // Matches schema: user_id
            $table->string('username', 100); // Matches schema
            $table->string('email', 191)->unique(); // Matches schema
            $table->string('password_hash', 255); // Matches schema
            $table->string('phone', 30)->nullable(); // Matches schema
            $table->enum('role', ['ANALYST', 'CLIENT', 'ADMIN']); // Matches schema
            $table->timestamps(); // Matches schema: created_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};


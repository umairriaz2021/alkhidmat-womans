<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Stripe, EasyPaisa, etc.
        $table->string('slug')->unique();
        $table->string('logo')->nullable();
        $table->boolean('is_active')->default(true);
        
        // JSON column jo default null ho
        $table->json('general')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

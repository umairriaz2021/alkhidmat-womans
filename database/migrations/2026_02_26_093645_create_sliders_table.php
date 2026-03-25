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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('main_heading');
            $table->string('tagline')->nullable();
            $table->text('content')->nullable();
            $table->string('image_id'); // Media table ki ID ke liye
            $table->string('cta_text')->nullable(); // Button text
            $table->string('cta_url')->nullable();  // Button link
            $table->boolean('status')->default(true); // Active/Inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};

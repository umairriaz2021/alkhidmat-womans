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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slider_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content'); // TextEditor ka data yahan jayega
            $table->string('category')->nullable();
            $table->enum('status', ['published', 'draft', 'archived'])->default('draft');
            $table->string('meta_title')->nullable(); // SEO ke liye
            $table->text('meta_description')->nullable(); // SEO ke liye
            $table->foreign('slider_id')->references('id')->on('sliders')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};

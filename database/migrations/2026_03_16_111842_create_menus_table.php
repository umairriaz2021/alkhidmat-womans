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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Link Name
            $table->string('url')->nullable(); // Link URL
            
            // Submenu Logic: Apne hi table 'menus' ki ID ko reference karein
            $table->unsignedBigInteger('parent_id')->nullable();
            
            // Mega Menu Fields
            $table->boolean('mega_menu')->default(false)->nullable();
            $table->string('mega_menu_heading')->nullable();
            
            // Status Reference
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
                  $table->foreign('status_id')
                  ->references('id')
                  ->on('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

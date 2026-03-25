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
        Schema::create('page_extras', function (Blueprint $table) {
           $table->id();
        // Page ID reference (cascade on delete matlab page delete toh extra data bhi delete)
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            
            $table->string('key'); // Field ka naam (e.g., 'sidebar_title', 'icon_class')
            $table->text('value')->nullable(); // Field ka data
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_extras');
    }
};

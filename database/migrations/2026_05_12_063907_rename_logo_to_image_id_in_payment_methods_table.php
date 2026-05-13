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
        Schema::table('payment_methods', function (Blueprint $table) {
           $table->renameColumn('logo', 'image_id');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
        // Ab yahan image_id mil jayega
        $table->string('image_id')->nullable()->change();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
        // Reverse order mein karein
        $table->renameColumn('image_id', 'logo');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
        $table->string('logo')->nullable()->change();
    });
   
    }
};

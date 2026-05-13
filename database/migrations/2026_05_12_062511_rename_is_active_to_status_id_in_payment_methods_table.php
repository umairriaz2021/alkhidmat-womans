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
            $table->renameColumn('is_active', 'status_id');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
            // 2. Column type ko unsignedBigInteger mein change karein (foreign key ke liye zaroori hai)
            // Aur statuses table se connect karein
            $table->unsignedBigInteger('status_id')->change();
            
            $table->foreign('status_id')
                  ->references('id')
                  ->on('status')
                  ->onDelete('cascade'); // Ya apni marzi ka constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_methods', function (Blueprint $table) {
            // Reverse process: Foreign key drop karein aur column wapis rename karein
            $table->dropForeign(['status_id']);
            $table->renameColumn('status_id', 'is_active');
        });
        Schema::table('payment_methods', function (Blueprint $table) {
             $table->boolean('is_active')->change();
        });
    }
};
